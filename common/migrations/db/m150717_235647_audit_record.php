<?php

use yii\db\Schema;
use yii\db\Migration;

class m150717_235647_audit_record extends Migration {

    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%audit_record}}', [
            'id' => Schema::TYPE_STRING . ' PRIMARY KEY',
            'user_id' => Schema::TYPE_STRING . '(32)',
            'event' => Schema::TYPE_STRING . '(64) NOT NULL',
            'template' => Schema::TYPE_STRING . '(64)', // to be displayed on timeline
            'data' => Schema::TYPE_TEXT,
            'note' => Schema::TYPE_TEXT,
            'connected_type' => Schema::TYPE_STRING . '(64) NOT NULL',
            'connected_id' => Schema::TYPE_STRING . '(64)',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'ip_address' => Schema::TYPE_STRING . '(64)',
            'source' => Schema::TYPE_STRING . 'NULL',
                ], $tableOptions);
        
    }

    public function down() {
        $this->dropTable('{{%audit_record}}');
        return true;
    }

}
