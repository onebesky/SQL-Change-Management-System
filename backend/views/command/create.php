<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Command */

$this->title = 'Create Command';
$this->params['breadcrumbs'][] = ['label' => 'Commands', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="command-create">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
