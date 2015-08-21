<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\SystemLog */

$this->title = Yii::t('backend', 'Event #{id}', ['id'=>$model->id]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Events'), 'url' => ['/timeline-event']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="system-log-view">

    <?php 
   
    echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'user_id',
            ['label' => 'User', 'value' => $model->user ? $model->user->name : ''],
            'event',
            [
                'label' => 'Related Model',
                'value' => $model->connected_type . ' ' . $model->connected_id
            ],
            'created_at:datetime',
            'ip_address',
            [
                'label' => 'data',
                'value' => print_r($model->dataArray, 1),
                'format' => 'ntext'
            ]
        ],
    ]) ?>

</div>
