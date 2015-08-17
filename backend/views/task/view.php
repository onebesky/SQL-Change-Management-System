<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\TaskExecution */

$this->title = $model->command->name . ' - results';
$this->params['breadcrumbs'][] = ['label' => 'Task Executions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
d($model->serverConnection);
?>
<div class="task-execution-view">

    <p>
        <?php echo Html::a('View All Results', ['/command/view', 'id' => $model->command_id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?php
    $statusValue = '<span class="label">n/a</span>';
    switch ($model->result_status) {
        case \common\models\TaskExecution::STATUS_WAITING:
            $statusValue = '<span class="label label-inverse">Scheduled on ' . Yii::$app->formatter->asDate($model->scheduled_on) . '</span>';
            break;
        case \common\models\TaskExecution::STATUS_UNKNOWN:
            $statusValue = '<span class="label">Unknown</span>';
            break;
        case \common\models\TaskExecution::STATUS_SUCCESS:
            $statusValue = '<span class="label label-success">Success</span>';
            break;
        case \common\models\TaskExecution::STATUS_ERROR:
            $statusValue = '<span class="label label-errr">Error</span>';
            break;
        case \common\models\TaskExecution::STATUS_RUNNING:
            $statusValue = '<span class="label label-warning">Error</span>';
            break;
    }
    echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            //'execution_start',
            //'execution_end',
            [
                'label' => 'Command',
                'value' => Html::a($model->command->name, ['/command/view', 'id' => $model->command_id]),
                'format' => 'raw',
            ],
            'result_status',
            [
                'label' => 'Status',
                'format' => 'raw',
                'value' => $statusValue
            ],
            'result_data',
            'input_command:ntext',
            [
                'label' => 'Server Connection',
                'value' => $model->serverConnection->name . ' (' . $model->serverConnection->type . ')'
            ],
            'execution_start:datetime',
            [
                'label' => 'Duration',
                'value' => $model->execution_end ? ($model->execution_end - $model->execution_start) . ' sec' : 'n/a',
            ]
        ],
    ])
    ?>

</div>
