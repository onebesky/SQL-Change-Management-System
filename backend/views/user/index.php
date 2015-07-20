<?php

use backend\models\search\UserSearch;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel UserSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('backend', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php echo Html::a(Yii::t('backend', 'Create {modelClass}', [
    'modelClass' => 'User',
]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'username',
            'email:email',
            /*[
                'class' => \common\grid\EnumColumn::className(),
                'attribute' => 'status',
                'enum' => User::getStatuses(),
                'filter' => User::getStatuses()
            ],*/
            'created_at:datetime',
            'logged_at:datetime',
            // 'updated_at',
            [
                'header' => 'roles',
                'value' => function($data) {
                    return implode(" ", ArrayHelper::map(Yii::$app->authManager->getRolesByUser($data->id), 'name', 'name'));
                }
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
