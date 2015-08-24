<?php

namespace common\models;

use Yii;
use common\behaviors\GuidBehavior;
use common\behaviors\AuditBehavior;

/**
 * This is the model class for table "command".
 *
 * @property string $id
 * @property string $name
 * @property string $description
 * @property boolean $save_as_template
 * @property string $command
 * @property string $server_connection_id
 * @property integer $execute_on
 * @property string $author
 * @property integer $type
 * @property integer $created_at
 * @property string $external_issue_id
 * @property string $chained_task_id
 */
class Command extends \yii\db\ActiveRecord {

    const TYPE_SQL = 1;
    const TYPE_PHP = 2;
    const TYPE_BASH = 3;

    private $_reviewersFormInput;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'command';
    }

    public function behaviors() {
        return [
            GuidBehavior::className(),
            'audit' => [
                'class' => AuditBehavior::className(),
                'events' => [],
                'dataFunction' => function($model) {
            return $model->oldAttributes;
        }
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'command', 'server_connection_id', 'author', 'type'], 'required'],
            [['description', 'command'], 'string'],
            [['save_as_template'], 'boolean'],
            [['execute_on', 'type', 'created_at'], 'integer'],
            [['id', 'name', 'server_connection_id', 'author', 'external_issue_id', 'chained_task_id'], 'string', 'max' => 255],
            [['id'], 'unique'],
            [['reviewersFormInput'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'save_as_template' => 'Save As Template',
            'command' => 'Command',
            'server_connection_id' => 'Server Connection',
            'execute_on' => 'Execute On',
            'author' => 'Author',
            'type' => 'Type',
            'created_at' => 'Created At',
            'external_issue_id' => 'External Issue ID',
            'chained_task_id' => 'Chained Task',
            'reviewersFormInput' => 'Reviewer'
        ];
    }

    public function beforeSave($insert) {
        $this->created_at = time();
        if (is_array($this->reviewersFormInput)) {
            $newUsers = [];
            foreach ($this->reviewersFormInput as $uid) {
                $user = User::findOne(['id' => $uid]);
                if ($user) {
                    $newUsers[] = $uid;
                }
            }
            $this->reviewers_json = json_encode($newUsers);
        }
        return parent::beforeSave($insert);
    }

    public function beforeValidate() {
        if (!$this->type) {
            $this->type = self::TYPE_SQL;
        }
        return parent::beforeValidate();
    }

    public function afterSave($insert, $changedAttributes) {

        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * Command can be deleted if there are no executions
     */
    public function canDelete() {
        $task = TaskExecution::findOne(['command_id' => $this->id]);
        if ($task) {
            return false;
        }
        return true;
    }

    /**
     * Execution depends on user role and Command settings
     */
    public function canExecute() {
        // TODO: if app requires approval - check
        return true;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExecutions() {
        return $this->hasMany(TaskExecution::className(), ['command_id' => 'id']);
    }

    public function getAuthorUser() {
        return $this->hasOne(User::className(), ['id' => 'author']);
    }

    /**
     * @return User[]
     */
    public function getReviewers() {
        if (!strlen($this->reviewers_json)) {
            return [];
        }
        $data = json_decode($this->reviewers_json);
        return User::findAll()->where(['id' => $data]);
    }

    public function getServerConnection() {
        return $this->hasOne(ServerConnection::className(), ['id' => 'server_connection_id']);
    }

    public function setReviewersFormInput($input) {
        $this->_reviewersFormInput = $input;
    }

    public function getReviewersFormInput() {
        if ($this->_reviewersFormInput) {
            return $this->_reviewersFormInput;
        }
        $reviewers = json_decode($this->reviewers_json);
        $this->_reviewersFormInput = $reviewers;
        return $reviewers;
    }

    public function execute($userId = null) {
        // TODO: will be different by type - SQL is just a prototype
        // create task execution 
        $model = new TaskExecution();
        $model->command_id = $this->id;
        // save current version of executed command
        $model->input_command = $this->command;
        $model->executed_by = $userId;
        $model->server_connection_id = $this->server_connection_id;
        $model->result_status = TaskExecution::STATUS_RUNNING;
        $model->execution_start = time();
        $model->save();

        // test connection
        $connection = $this->serverConnection;
        $test = $connection->testConnection();

        if (!$test) {
            $model->result_status = TaskExecution::STATUS_ERROR;
            $model->result_data('Cannot connect to server using connection ' . $connection->name);
            $model->execution_end = time();
            $model->save();
            AuditRecord::create('executed', 'task_execution', $model->id, $model->attributes);
            return $model;
        }

        try {
            $result = $connection->dbConnection->createCommand($this->command)->execute();
            d($result);
        } catch (Exception $ex) {
            $model->result_data = $ex->getMessage() . "\n" . $ex->getStackTrace();
            $model->result_status = TaskExecution::STATUS_ERROR;
            $model->execution_end = time();
            $model->save();
            AuditRecord::create('executed', 'task_execution', $model->id, $model->attributes);
            return $model;
        }

        $model->result_status = TaskExecution::STATUS_SUCCESS;
        $model->execution_end = time();
        $model->save();
        AuditRecord::create('executed', 'task_execution', $model->id, $model->attributes);
        return $model;
    }

    /**
     * Is the command approved by any reviewer or specific one?
     * @param string $userId
     */
    public function isApproved($userId = null) {
        if ($userId) {
            $review = CommandReview::find()->where(['reviewer_id' => $userId, 'command_id' => $this->id, 'approved' => 1])->one();
        } else {
            $review = CommandReview::find()->where(['command_id' => $this->id])->one();
        }
        if (!$review){
            return false;
        }
        return $review->approved;
    }

}
