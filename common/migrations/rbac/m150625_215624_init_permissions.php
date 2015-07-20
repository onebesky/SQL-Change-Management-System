<?php

use yii\db\Schema;
use common\rbac\Migration;

class m150625_215624_init_permissions extends Migration
{
    public function up()
    {
        $adminRole = $this->auth->getRole(\common\models\User::ROLE_ADMINISTRATOR);
        $makerRole = $this->auth->getRole(\common\models\User::ROLE_COMMAND_MAKER);
        $runnerRole = $this->auth->getRole(\common\models\User::ROLE_TASK_RUNNER);
        $userRole = $this->auth->getRole(\common\models\User::ROLE_USER);

        $timeline = $this->auth->createPermission('timeline-event.index');
        $this->auth->add($timeline);
        $this->auth->addChild($userRole, $timeline);
        
        
    }

    public function down()
    {
        $this->auth->remove($this->auth->getPermission('timeline-event.index'));
    }
}
