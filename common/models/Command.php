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
            [['id'], 'unique']
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
            'server_connection_id' => 'Server Connection ID',
            'execute_on' => 'Execute On',
            'author' => 'Author',
            'type' => 'Type',
            'created_at' => 'Created At',
            'external_issue_id' => 'External Issue ID',
            'chained_task_id' => 'Chained Task ID',
        ];
    }

}
