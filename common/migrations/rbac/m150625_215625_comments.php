<?php

use yii\db\Schema;
use common\rbac\Migration;
use \rmrevin\yii\module\Comments\Permission;
use \rmrevin\yii\module\Comments\rbac\ItsMyComment;

class m150625_215625_comments extends Migration {

    public function up() {
        $auth = $this->auth;
        $adminRole = $this->auth->getRole(\common\models\User::ROLE_ADMINISTRATOR);
        $userRole = $this->auth->getRole(\common\models\User::ROLE_USER);
        $ItsMyCommentRule = new ItsMyComment();

        $auth->add($ItsMyCommentRule);

        $ownComments = new \yii\rbac\Permission([
            'name' => Permission::CREATE,
            'description' => 'Can create own comments',
        ]);
        $auth->add($ownComments);
        $auth->addChild($userRole, $ownComments);

        $updateAllComments = new \yii\rbac\Permission([
            'name' => Permission::UPDATE,
            'description' => 'Can update all comments',
        ]);
        $auth->add($updateAllComments);
        $auth->addChild($adminRole, $updateAllComments);
        
        $updateOwnComment = new \yii\rbac\Permission([
            'name' => Permission::UPDATE_OWN,
            'ruleName' => $ItsMyCommentRule->name,
            'description' => 'Can update own comments',
        ]);
        $auth->add($updateOwnComment);
        $auth->addChild($userRole, $updateOwnComment);
        
        $deleteAllComments = new \yii\rbac\Permission([
            'name' => Permission::DELETE,
            'description' => 'Can delete all comments',
        ]);
        $auth->add($deleteAllComments);
        $auth->addChild($adminRole, $deleteAllComments);
        
        $deleteOwnComments = new \yii\rbac\Permission([
            'name' => Permission::DELETE_OWN,
            'ruleName' => $ItsMyCommentRule->name,
            'description' => 'Can delete own comments',
        ]);
        $auth->add($deleteOwnComments);
        $auth->addChild($userRole, $deleteOwnComments);
    }

    public function down() {
        $this->auth->remove($this->auth->getPermission('timeline-event.index'));
    }

}
