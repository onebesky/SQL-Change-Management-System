<?php

namespace common\behaviors;

use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;

/**
 * Use GUID instead of auto increment id. It helps to transfer data from staging
 * to production environment and hides information about resources.
 * ID will be random md5 hash - 32 characters long
 */
class GuidBehavior extends Behavior {
    
    public $attribute = 'id';
    
    public function events() {
        return[
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeSave',
        ];
    }
    
    public function beforeSave() {
        $this->owner->{$this->attribute} = Yii::$app->guid->short();
    }
}