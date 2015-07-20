<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ServerConnection */

$this->title = 'Update Server Connection: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Server Connections', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="server-connection-update">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
