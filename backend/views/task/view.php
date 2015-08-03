<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\TaskExecution */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Task Executions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-execution-view">

    <p>
        <?php echo Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php echo Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?php echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'execution_start',
            'execution_end',
            'result_status',
            'result_data',
            'input_command:ntext',
        ],
    ]) ?>

</div>
