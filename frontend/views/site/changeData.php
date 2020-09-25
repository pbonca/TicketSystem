<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ChangeDataForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Change data';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <div>Please fill out the following fields to change your personal data.<br> If you leave a field blank that data will remain the same:</div>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-signup', 'enableClientValidation' => false, 'enableAjaxValidation' => false]); ?>

                <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'currentPassword')->passwordInput() ?>

                <?= $form->field($model, 'newPassword')->passwordInput() ?>

                <?= $form->field($model, 'repeat')->passwordInput() ?>

                <div class="form-group">
                    <?= Html::submitButton('Change it!', ['class' => 'btn btn-primary', 'name' => 'change-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
