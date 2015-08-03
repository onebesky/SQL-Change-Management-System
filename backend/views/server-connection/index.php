<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ServerConnectionQuery */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Server Connections';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="server-connection-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php echo Html::a('Create Server Connection', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'name',
            //'notes:ntext',
            'type',
            'connection_string',
            // 'username',
            // 'password',
            // 'json_data',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
