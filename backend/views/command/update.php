<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Command */

$this->title = 'Update Command: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Commands', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="command-update">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
