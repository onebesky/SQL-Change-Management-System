<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ServerConnection */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="server-connection-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'notes')->textarea(['rows' => 6]) ?>

    <?php 

    echo $form->field($model, 'type')->dropDownList(common\models\ServerConnection::$typeNames, ['maxlength' => true]) ?>

    <?php 
    echo $form->field($model, 'connection_string')->textInput(['maxlength' => true])->hint("<ul>"
            ."<li>MySQL/MariaDB: mysql:host=localhost;dbname=testdb</li>
                <li>PostgreSQL: pgsql:host=localhost;port=5432;dbname=testdb</li>
<li>SQL Server: mssql:host=localhost;dbname=testdb</li>
<li>Oracle: oci:dbname=//localhost:1521/testdb</li>"
            . "</ul>For more information visit <a href='http://www.yiiframework.com/doc/guide/1.1/en/database.dao'>Yii Data Access Object</a>"); 
    
    
            
            ?>

    <?php echo $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>

    <div class="form-group">
        <div style="display: none" class="alert alert-success connection-test-result" id="connection-test-result-success">The provided parameters are correct.</div>
        <div style="display: none" class="alert alert-danger connection-test-result" id="connection-test-result-error">The provided parameters are incorrect.</div>
    </div>
    <div class="form-group">
        <?php echo Html::button('Test Connection', ['id' => 'test-connection-button', 'class' => 'btn btn-info']) ?>
        <?php echo Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

$this->registerJs("
    $('#test-connection-button').click(function(){
          $.ajax({
              url: 'test',
              type: 'POST',
              beforeSend: function(){
                  $('#test-connection-button').prop('disabled', true);
                  $('.connection-test-result').hide();
              },
              data: $('.server-connection-form form').serializeArray(),
              success: function (data) {
                  $('#test-connection-button').prop('disabled', false);
                  $('#connection-test-result-success').show();
              },
              error: function (XMLHttpRequest, textStatus, errorThrown) {
                  $('#test-connection-button').prop('disabled', false);
                  //alert(errorThrown);
                  console.log(textStatus, errorThrown);
                  $('#connection-test-result-error').show();
              }
          });
         return false;
    });
  ");