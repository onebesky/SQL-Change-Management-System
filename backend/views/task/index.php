<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Task Executions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-execution-index">


    <p>
        <?php echo Html::a('Create Task Execution', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'execution_start',
            'execution_end',
            'result_status',
            'result_data',
            // 'input_command:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
