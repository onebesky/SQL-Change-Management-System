<?php

namespace common\models\connectionTester;

class MysqlConnectionTester implements ConnectionTesterInterface {

    private $dbConnection;

    /**
     * 
     * @param /common/models/ServerConnection $serverConnection
     */
    public function __construct($serverConnection) {
        $this->dbConnection = $serverConnection;
    }

    public function run() {
        \d("run mysql tester");
        try {
            $db = $this->dbConnection->getDbConnection();
            if ($db == null) {
                return false;
            }
        } catch (\Exception $exc) {
            return false;
        }


        return true;
    }

}
