<?php

namespace common\models;

use Yii;
use common\behaviors\GuidBehavior;

/**
 * This is the model class for table "command_review".
 *
 * @property string $id
 * @property string $reviewer_id
 * @property string $command_id
 * @property boolean $approved
 */
class CommandReview extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'command_review';
    }

    public function behaviors() {
        return [
            GuidBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['reviewer_id', 'command_id'], 'required'],
            [['approved'], 'boolean'],
            [['id', 'reviewer_id', 'command_id'], 'string', 'max' => 255],
            [['id'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'reviewer_id' => 'Reviewer ID',
            'command_id' => 'Command ID',
            'approved' => 'Approved',
        ];
    }

    public function afterSave($insert, $changedAttributes) {
        d("after save");
        AuditRecord::create('approved', 'command', $this->command_id, ['reviewer_id' => $this->reviewer_id, 'approved_on' => time(), 'approved' => $this->approved]);
        return parent::afterSave($insert, $changedAttributes);
    }

}
