<?php

use yii\db\Schema;
use yii\db\Migration;

class m150717_255633_database_connection extends Migration {

    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%server_connection}}', [
            'id' => Schema::TYPE_STRING . ' PRIMARY KEY',
            'name' => Schema::TYPE_STRING . '(255) NOT NULL',
            'notes' => Schema::TYPE_TEXT,
            'type' => Schema::TYPE_STRING . '(64) NOT NULL',
            'connection_string' => Schema::TYPE_STRING . ' NOT NULL',
            'username' => Schema::TYPE_STRING,
            'password' => Schema::TYPE_STRING,
            'json_data' => Schema::TYPE_STRING,

                ], $tableOptions);
        
    }

    public function down() {
        $this->dropTable('{{%server_connection}}');
        return true;
    }

}
