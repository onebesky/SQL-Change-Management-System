<?php

namespace common\models;

use Yii;
use common\behaviors\GuidBehavior;

/**
 * This is the model class for table "audit_record".
 *
 * @property integer $id
 * @property string $user_id
 * @property string $event
 * @property string $data
 * @property string $connected_type
 * @property string $connected_id
 * @property integer $created_at
 * @property string $ip_address
 * @property string $template
 */
class AuditRecord extends \yii\db\ActiveRecord {
    
    const SOURCE_BACKEND = 'backend';
    const SOURCE_API = 'api';
    const SOURCE_CRON = 'cron';
    
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'audit_record';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['event', 'connected_type', 'created_at'], 'required'],
            [['data', 'note'], 'string'],
            [['created_at'], 'integer'],
            [['user_id'], 'string', 'max' => 32],
            [['source'], 'string', 'max' => 255],
            [['event', 'connected_type', 'connected_id', 'ip_address', 'template'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'event' => 'Event',
            'data' => 'Data',
            'connected_type' => 'Connected Type',
            'connected_id' => 'Connected ID',
            'created_at' => 'Created At',
            'ip_address' => 'Ip Address',
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            GuidBehavior::className(),
        ];
    }

    public static function getUserId() {
        if (php_sapi_name() == "cli" || !isset(Yii::$app->user->isGuest) || Yii::$app->user == null || Yii::$app->user->isGuest) {
            return null;
        } else {
            return Yii::$app->user->id;
        }
    }

    public static function getIpAddress() {
        $clientIp = null;
        if (isset($_SERVER) && isset($_SERVER['REMOTE_ADDR'])) {
            $clientIp = $_SERVER['REMOTE_ADDR'];
        }
        return $clientIp;
    }

    public static function create($event, $connectedType, $connectedId, $data = null, $note = null, $source = null, $createTime = null) {
        $model = new AuditRecord();
        $model->attributes = [
            'connected_type' => $connectedType,
            'connected_id' => $connectedId,
            'event' => $event,
            'source' => $source,
            'data' => is_string($data) ? $data : \yii\helpers\Json::encode($data),
            'created_at' => $createTime > 0 ? $createTime : time(),
            'note' => $note,
            'user_id' => self::getUserId(),
            'client_ip' => self::getIpAddress()
        ];
        $model->save();
        \d($model->getErrors());
    }

}
