<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ServerConnection */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Server Connections', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="server-connection-view">

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
            'notes:ntext',
            'type',
            'connection_string',
            'username',
            'password',
            'json_data',
        ],
    ]) ?>

</div>
