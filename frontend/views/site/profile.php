<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Profile';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title); ?></h1>

    <?php
    /**
     * @var $model == CommonUser's data
     */

    foreach ($model as $key => $value){
        echo '<p>' . $key . ': ' . Html::encode($value) . '</p>';
    }
    ?>
</div>
