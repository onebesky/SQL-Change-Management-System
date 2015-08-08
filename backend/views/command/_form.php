<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Command */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="command-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'server_connection_id')->dropDownList(\yii\helpers\ArrayHelper::map(common\models\ServerConnection::find()->all(), 'id', 'name'))->hint('Database / shell connection to execute this command.') ?>

    <?php echo $form->field($model, 'command')->textarea(['rows' => 6]) ?>
    
    <?php echo $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?php echo $form->field($model, 'save_as_template')->checkbox() ?>

    
    <?php 
    // TODO: timed execution
    //echo $form->field($model, 'execute_on')->textInput()->hint() ?>

    <?php 
    // What is the type for? Is it the same  type as server connection?
    // echo $form->field($model, 'type')->textInput() ?>

    <?php echo $form->field($model, 'external_issue_id')->textInput(['maxlength' => true])->hint('External tracker task id or link to issue.') ?>

    <?php 
    // Might create chained command runner instead
    //echo $form->field($model, 'chained_task_id')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?php echo Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
