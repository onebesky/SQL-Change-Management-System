<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Command */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Commands', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="command-view">

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
            'name',
            'description:ntext',
            'save_as_template:boolean',
            'command:ntext',
            'server_connection_id',
            'execute_on',
            'author',
            'type',
            'created_at',
            'external_issue_id',
            'chained_task_id',
        ],
    ]) ?>

</div>
