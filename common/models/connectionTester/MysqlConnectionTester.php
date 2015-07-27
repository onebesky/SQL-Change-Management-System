<?php

namespace common\models\connectionTester;

class MysqlConnectionTester implements ConnectionTesterInterface {
    
    private $dbConnection;
    
    public function __construct($serverConnection) {
        $this->dbConnection = $serverConnection;
    }

    public function run() {
        \d("run mysql tester");
        return true;
    }

}
