<?php
use yii\helpers\Inflector;

/**
 * @author Eugene Terentev <eugene@terentev.net>
 * @var $model common\models\TimelineEvent
 */
date_default_timezone_set('Europe/London');
?>
<div class="timeline-item">
    <span class="time">
        <i class="fa fa-clock-o"></i>
        <?php echo Yii::$app->formatter->asRelativeTime($model->created_at) ?>
    </span>
    <h3 class="timeline-header">
        <?php echo Yii::t('backend', Inflector::camel2words($model->event, true)) ?>
    </h3>

    <div class="timeline-body">
        <dl>
 
            <dt><?php echo Yii::t('backend', 'Category') ?>:</dt>
            <dd><?php echo $model->connected_type ?></dd>

            <dt><?php echo Yii::t('backend', 'Event') ?>:</dt>
            <dd><?php echo $model->event ?></dd>

            <dt><?php echo Yii::t('backend', 'Date') ?>:</dt>
            <dd><?php echo Yii::$app->formatter->asDatetime($model->created_at) ?></dd>
        </dl>
    </div>
    <div class="timeline-footer">
        <?php echo \yii\helpers\Html::a(
            Yii::t('backend', 'View event'),
            ['view', 'id' => $model->id],
            ['class' => 'btn btn-success btn-sm']
        ) ?>
    </div>
</div>