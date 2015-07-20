<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "server_connection".
 *
 * @property string $id
 * @property string $name
 * @property string $notes
 * @property string $type
 * @property string $connection_string
 * @property string $username
 * @property string $password
 * @property string $json_data
 */
class ServerConnection extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'server_connection';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'type', 'connection_string'], 'required'],
            [['notes'], 'string'],
            [['id', 'name', 'connection_string', 'username', 'password', 'json_data'], 'string', 'max' => 255],
            [['type'], 'string', 'max' => 64],
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
            'name' => 'Name',
            'notes' => 'Notes',
            'type' => 'Type',
            'connection_string' => 'Connection String',
            'username' => 'Username',
            'password' => 'Password',
            'json_data' => 'Json Data',
        ];
    }

    /**
     * @inheritdoc
     * @return ServerConnectionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ServerConnectionQuery(get_called_class());
    }
}
