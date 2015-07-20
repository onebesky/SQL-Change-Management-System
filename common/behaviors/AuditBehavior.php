<?php
namespace common\behaviors;

use common\models\AuditRecord;
use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;

class AuditBehavior extends Behavior {
    
    public $attribute = 'id';
    public $events = [];
    public $dataFunction = null;
    
    public function events() {
        return[
            ActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterUpdate',
            ActiveRecord::EVENT_AFTER_DELETE => 'afterDelete',
            
        ];
    }
    
    public function afterSave() {
        \d("audit after save");
        AuditRecord::create($this->getEventName('created'), $this->owner->tableSchema->name, $this->owner->primaryKey, $this->getData());
    }
    
    public function afterUpdate() {
        \d('audit update');
        \d($this->getData());
        AuditRecord::create($this->getEventName('updated'), $this->owner->tableSchema->name, $this->owner->primaryKey, $this->getData());
    }
    
    public function afterDelete() {
        \d("after delete");
        AuditRecord::create($this->getEventName('deleted'), $this->owner->tableSchema->name, $this->owner->primaryKey, $this->owner->attributes);
    }
    
    protected function getEventName($type){
        return strtolower($this->owner->tableSchema->name . '-' . $type);
    }
    
    protected function getData(){
        if ($this->dataFunction){
            $f = $this->dataFunction;
            return $f($this->owner);
        }
        return null;
    }
    
    protected function getUserId(){
        if (php_sapi_name() == "cli" || Yii::$app->user == null || Yii::$app->user->isGuest) {
            return null;
        } else {
            return Yii::$app->user->id;
        }
    }
    
    protected function getIpAddress(){
        $clientIp = null;
        if (isset($_SERVER) && isset($_SERVER['REMOTE_ADDR'])) {
            $clientIp = $_SERVER['REMOTE_ADDR'];
        }
        return $clientIp;
    }
}