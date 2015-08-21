<div class="timeline-item">
    <span class="time">
        <i class="fa fa-clock-o"></i>
        <?php 
        $data = $model->dataArray;
        echo Yii::$app->formatter->asRelativeTime($model->created_at) ?>
    </span>

    <h3 class="timeline-header">
        <?php echo Yii::t('backend', 'User Updated') ?>
    </h3>

    <div class="timeline-body">
        <?php echo Yii::t('backend', 'User {identity} updated at {created_at}', [
            'identity' => isset($data['username']) ? $data['username'] : $model->user_id,
            'created_at' => Yii::$app->formatter->asDatetime($model->created_at)
        ]) ?>
    </div>

    <div class="timeline-footer">
        <?php echo \yii\helpers\Html::a(
            Yii::t('backend', 'View user'),
            ['/user/view', 'id' => $model->user_id],
            ['class' => 'btn btn-success btn-sm']
        ) ?>
        <?php echo \yii\helpers\Html::a(
            Yii::t('backend', 'View event'),
            ['view', 'id' => $model->id],
            ['class' => 'btn btn-success btn-sm']
        ) ?>
    </div>
</div>