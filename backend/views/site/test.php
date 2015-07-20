
<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

echo \Yii::$app->guid->basic();
echo "<br>";
echo \Yii::$app->guid->short();
echo "<br>";
echo \Yii::$app->guid->byTimeAndIp();
echo "<br>";
echo \Yii::$app->guid->byLength(64);
echo "<br>";
echo \Yii::$app->guid->guid();


echo "Role<br>";
echo \Yii::$app->user->can('administrator') ? 'yes' : 'no';