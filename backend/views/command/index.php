<?php

use yii\helpers\Html;
use backend\widgets\ClickGridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Commands';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="command-index">


    <p>
        <?php echo Html::a('Create Command', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php echo ClickGridView::widget([
        'dataProvider' => $dataProvider,
        'clickTarget' => 'command',
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            //'id',
            'name',
            //'description:ntext',
            //'save_as_template:boolean',
            //'command:ntext',
            // 'server_connection_id',
            // 'execute_on',
            // 'author',
            // 'type',
            // 'created_at',
            // 'external_issue_id',
            // 'chained_task_id',
            
            [
                'header' => 'Author',
                'attribute' => 'author',
                'value' => function($data){
                    return $data->authorUser->full_name;
                }
            ],
            [
                'header' => 'Approved',
                'value' => function($data) {
                    return $data->isApproved() ? 'Yes' : 'No';
                }
            ],
            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
