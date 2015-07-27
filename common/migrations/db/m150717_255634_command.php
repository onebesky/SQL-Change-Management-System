<?php

use yii\db\Schema;
use yii\db\Migration;

class m150717_255634_command extends Migration {

    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%command}}', [
            'id' => Schema::TYPE_STRING . ' PRIMARY KEY',
            'name' => Schema::TYPE_STRING . ' NOT NULL',
            'description' => Schema::TYPE_TEXT,
            'save_as_template' => Schema::TYPE_BOOLEAN . ' DEFAULT "0"',
            'command' => Schema::TYPE_TEXT . ' NOT NULL',
            'server_connection_id' => Schema::TYPE_STRING . ' NOT NULL',
            'execute_on' => Schema::TYPE_INTEGER,
            'author' => Schema::TYPE_STRING . ' NOT NULL',
            'type' => Schema::TYPE_INTEGER . ' NOT NULL', //sql, php migration, bash
            'created_at' => Schema::TYPE_INTEGER,
            'external_issue_id' => Schema::TYPE_STRING, // url or id of issue stored in bug tracker
            'chained_task_id' => Schema::TYPE_STRING, // execute chained task after self execution
                ], $tableOptions);
        
        $this->createTable('{{%task_execution}}', [
            'id' => Schema::TYPE_STRING . ' PRIMARY KEY',
            'execution_start' => Schema::TYPE_INTEGER,
            'execution_end' => Schema::TYPE_INTEGER,
            'result_status' => Schema::TYPE_INTEGER, // scheduled, success, fail
            'result_data' => Schema::TYPE_INTEGER, // text output
            'input_command' => Schema::TYPE_TEXT, // copy of the command executed
        ]);
        
    }

    public function down() {
        $this->dropTable('{{%command}}');
        $this->dropTable('{{%task_execution}}');
        return true;
    }

}