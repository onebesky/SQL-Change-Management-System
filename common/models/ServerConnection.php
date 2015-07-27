<?php

namespace common\models;

use Yii;
use common\behaviors\GuidBehavior;
use common\behaviors\AuditBehavior;

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
class ServerConnection extends \yii\db\ActiveRecord {

    protected $dbConnection;
    public static $typeNames = [
        'mysql' => 'MySQL',
        'postgre_sql' => 'Postgre SQL',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'server_connection';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'type', 'connection_string'], 'required'],
            [['notes'], 'string'],
            [['id', 'name', 'connection_string', 'username', 'password', 'json_data'], 'string', 'max' => 255],
            [['type'], 'string', 'max' => 64],
            [['active'], 'integer', 'max' => 1],
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
            'notes' => 'Notes',
            'type' => 'Type',
            'connection_string' => 'Connection String',
            'username' => 'Username',
            'password' => 'Password',
            'json_data' => 'Json Data',
        ];
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

    public function testConnection() {
        $testerName = "common\\models\\connectionTester\\" . ucfirst($this->type) . 'ConnectionTester';
        \d($testerName);
        $tester = new $testerName($this);
        return $tester->run();
    }

    /**
     * @inheritdoc
     * @return ServerConnectionQuery the active query used by this AR class.
     */
    //public static function find()
    //{
    //    return new ServerConnectionQuery(get_called_class());
    //}

    public function getDbConnection() {
        if ($this->dbConnection == null) {
            $connection = new \yii\db\Connection([
                'dsn' => $this->connection_string,
                'username' => $this->username,
                'password' => $this->password,
            ]);
            if (!$connection->open()){
                throw new Exception("Could not open database connection. Check the connector configuration.");
            }
            $this->dbConnection = $connection;
        }
        return $this->dbConnection;
    }

}
