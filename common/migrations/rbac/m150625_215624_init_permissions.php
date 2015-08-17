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
        
        // Commands
        $commandIndex = $this->auth->createPermission('command.index');
        $this->auth->add($commandIndex);
        $this->auth->addChild($userRole, $commandIndex);
        
        $commandExecute = $this->auth->createPermission('command.execute');
        $this->auth->add($commandExecute);
        $this->auth->addChild($runnerRole, $commandExecute);
        
        $commandCreate = $this->auth->createPermission('command.create');
        $this->auth->add($commandCreate);
        $this->auth->addChild($runnerRole, $commandCreate);
        
        $commandDelete = $this->auth->createPermission('command.delete');
        $this->auth->add($commandDelete);
        $this->auth->addChild($runnerRole, $commandDelete);
        
        $commandUpdate = $this->auth->createPermission('command.update');
        $this->auth->add($commandUpdate);
        $this->auth->addChild($runnerRole, $commandUpdate);
        
        $commandView = $this->auth->createPermission('command.view');
        $this->auth->add($commandView);
        $this->auth->addChild($userRole, $commandView);
        
        $taskView = $this->auth->createPermission('command.task');
        $this->auth->add($taskView);
        $this->auth->addChild($userRole, $commandView);
    }

    public function down()
    {
        $this->auth->remove($this->auth->getPermission('timeline-event.index'));
    }
}
