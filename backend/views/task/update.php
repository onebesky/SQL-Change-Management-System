<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\TaskExecution */

$this->title = 'Update Task Execution: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Task Executions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="task-execution-update">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
