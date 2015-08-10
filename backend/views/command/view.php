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
        <?php
        echo Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
        if ($model->canExecute(\Yii::$app->user->identity)) {
           /* echo Html::a('Execute', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-warning',
                'data' => [
                    'method' => 'post',
                ],
            ]);*/
            echo yii\bootstrap\Button::widget([
                'label' => 'Execute',
                'options' => ['class' => 'btn-warning', 'id' => 'execute-button']
            ]);
        }
        if ($model->canDelete()) {
            echo Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this command?',
                    'method' => 'post',
                ],
            ]);
        }
        ?>
    </p>

    <?php
    echo DetailView::widget([
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
    ])
    ?>

</div>
<?php
$this->registerJs('
$("#execute-button").on("click", function(){
    var $btn = $(this);
    var origText = $btn.html;
    $.ajax({
        url: "execute?id=' . $model->id . '",
        beforeSend: function(){
            $btn.prop("disabled", true);
            $btn.html("Executing...");
        },
        success: function($data) {
            console.log("done", $data);
            $btn.html(origText);
            $btn.prop("disabled", false);
        }
        error: function() {
            console.log("error");
            $btn.html(origText);
            $btn.prop("disabled", false);
        }
    });
});
    ');
