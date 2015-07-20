<?php

use common\rbac\Migration;
use common\models\User;

class m150625_214101_roles extends Migration
{
    public function up()
    {
        $this->auth->removeAll();

        $user = $this->auth->createRole(User::ROLE_USER);
        $this->auth->add($user);

        $runner = $this->auth->createRole(User::ROLE_TASK_RUNNER);
        $this->auth->add($runner);
        $this->auth->addChild($runner, $user);
        
        $maker = $this->auth->createRole(User::ROLE_COMMAND_MAKER);
        $this->auth->add($maker);
        $this->auth->addChild($maker, $runner);

        $admin = $this->auth->createRole(User::ROLE_ADMINISTRATOR);
        $this->auth->add($admin);
        $this->auth->addChild($admin, $maker);

        $this->auth->assign($admin, 1);
        $this->auth->assign($maker, 2);
        $this->auth->assign($runner, 3);
        $this->auth->assign($user, 4);
    }

    public function down()
    {
        $this->auth->remove($this->auth->getRole(User::ROLE_ADMINISTRATOR));
        $this->auth->remove($this->auth->getRole(User::ROLE_COMMAND_MAKER));
        $this->auth->remove($this->auth->getRole(User::ROLE_TASK_RUNNER));
        $this->auth->remove($this->auth->getRole(User::ROLE_USER));
    }
}
