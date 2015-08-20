<?php

namespace common\models;

use Yii;
use common\behaviors\GuidBehavior;
use common\behaviors\AuditBehavior;

/**
 * This is the model class for table "task_execution".
 *
 * @property string $id
 * @property integer $execution_start
 * @property integer $execution_end
 * @property integer $result_status
 * @property integer $result_data
 * @property string $input_command
 * @property string $server_connection_id
 * @property string $executed_by user id that executed command
 * @property integer $scheduled_on timed execution
 * 
 * Relations
 * @property ServerConnection $serverConnection
 */
class TaskExecution extends \yii\db\ActiveRecord {

    const STATUS_WAITING = 1;
    const STATUS_RUNNING = 2;
    const STATUS_SUCCESS = 3;
    const STATUS_ERROR = 4;
    const STATUS_UNKNOWN = 5;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'task_execution';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['execution_start', 'execution_end', 'result_status', 'scheduled_on'], 'integer'],
            [['input_command', 'executed_by', 'result_data', 'server_connection_id'], 'string'],
            [['id'], 'string', 'max' => 255],
            [['id'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'execution_start' => 'Execution Start',
            'execution_end' => 'Execution End',
            'result_status' => 'Result Status',
            'result_data' => 'Result Data',
            'input_command' => 'Input Command',
            'server_connection_id' => 'Server Connection'
        ];
    }

    public function behaviors() {
        return [
            GuidBehavior::className(),
            // TODO: create outside event
            /*'audit' => [
                'class' => AuditBehavior::className(),
                'events' => [],
                'dataFunction' => function($model) {
            return $model->oldAttributes;
        }
            ]*/
        ];
    }

    public function getServerConnection() {
        return $this->hasOne(ServerConnection::className(), ['id' => 'server_connection_id']);
    }

    public function getCommand() {
        return $this->hasOne(Command::className(), ['id' => 'command_id']);
    }

}
