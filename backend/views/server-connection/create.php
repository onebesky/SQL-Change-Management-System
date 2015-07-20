<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ServerConnection */

$this->title = 'Create Server Connection';
$this->params['breadcrumbs'][] = ['label' => 'Server Connections', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="server-connection-create">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
