<?php

namespace backend\widgets;

use yii\grid\GridView;

class ClickGridView extends GridView {

    public $clickTarget = null;
    
    public function init() {
        parent::init();

        // onclick event should always open detail view:
        if ($this->rowOptions == NULL) {
            $this->rowOptions = function ($model, $key, $index, $grid) {
                // get the model name is necessary, if the grid is not the main grid
                // without this the routed view is the view of the main controller
                $u = $this->clickTarget ? $this->clickTarget :\yii\helpers\StringHelper::basename(get_class($model));
                $u = \yii\helpers\Url::toRoute(['/' . strtolower($u) . '/view']);
                return ['id' => $model['id'], 'onclick' => 'location.href="' . $u . '?id="+(this.id);'];
            };
        }
        
        
        $this->options['class'] .= ' click-table';
        
    }

}
