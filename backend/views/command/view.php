<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

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
                'label' => 'Execute now',
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
            //'id',
            //'name',
            'description:ntext',
            'save_as_template:boolean',
            'command:ntext',
            'server_connection_id',
            'execute_on',
            'authorUser.name',
            'type',
            'created_at:datetime',
            'external_issue_id',
        ],
    ])
    ?>

</div>
<div class="command-results">
    <h2>Results</h2>
    <?php echo GridView::widget([
        'dataProvider' => $results,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'execution_start:datetime',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function($data) {
    switch ($data->result_status) {
        case \common\models\TaskExecution::STATUS_WAITING:
            return '<span class="label label-inverse">Scheduled on ' . Yii::$app->formatter->asDate($data->scheduled_on) . '</span>';
        case \common\models\TaskExecution::STATUS_UNKNOWN:
            return '<span class="label">Unknown</span>';
        case \common\models\TaskExecution::STATUS_SUCCESS:
            return '<span class="label label-success">Success</span>';
        case \common\models\TaskExecution::STATUS_ERROR:
            return '<span class="label label-errr">Error</span>';
        case \common\models\TaskExecution::STATUS_RUNNING:
            return '<span class="label label-warning">Error</span>';
    }
    
    return '<span class="label">n/a</span>';
                }
            ]
        ],
    ]); ?>
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
