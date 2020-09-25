<?php

/* @var $this yii\web\View */
/* @var $model \common\models\CommonUser */

use yii\helpers\Html;

$this->title = 'Profile';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title); ?></h1>

    <?php
    foreach ($model as $key => $value){
        echo '<p>' . $key . ': ' . Html::encode($value) . '</p>';
    }
    ?>
</div>
