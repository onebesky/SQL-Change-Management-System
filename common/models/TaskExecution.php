<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "task_execution".
 *
 * @property string $id
 * @property integer $execution_start
 * @property integer $execution_end
 * @property integer $result_status
 * @property integer $result_data
 * @property string $input_command
 */
class TaskExecution extends \yii\db\ActiveRecord
{
    
    const STATUS_WAITING = 1;
    const STATUS_RUNNING = 2;
    const STATUS_SUCCESS = 3;
    const STATUS_ERROR = 4;
    const STATUS_UNKNOWN = 5;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'task_execution';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['execution_start', 'execution_end', 'result_status', 'result_data'], 'integer'],
            [['input_command'], 'string'],
            [['id'], 'string', 'max' => 255],
            [['id'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'execution_start' => 'Execution Start',
            'execution_end' => 'Execution End',
            'result_status' => 'Result Status',
            'result_data' => 'Result Data',
            'input_command' => 'Input Command',
        ];
    }
}
