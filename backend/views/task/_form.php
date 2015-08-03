<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\TaskExecution */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="task-execution-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->field($model, 'id')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'execution_start')->textInput() ?>

    <?php echo $form->field($model, 'execution_end')->textInput() ?>

    <?php echo $form->field($model, 'result_status')->textInput() ?>

    <?php echo $form->field($model, 'result_data')->textInput() ?>

    <?php echo $form->field($model, 'input_command')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?php echo Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
