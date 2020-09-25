<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \backend\models\ChangeUserData */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = "Change user's data";
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="site-signup">
        <h1><?= Html::encode($this->title) ?></h1>
        <div>Please fill out the following fields to change the user's personal data.<br>
            If you leave a field blank that data will remain the same:
        </div>
        <div class="row">
            <div class="col-lg-5">
                <?php $form = ActiveForm::begin(['id' => 'form-changeUserData', 'enableClientValidation' => false, 'enableAjaxValidation' => false]); ?>

                <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <?= $form->field($model, 'isAdmin')->checkbox() ?>

                <div class="form-group">
                    <?= Html::submitButton('Change it!', ['class' => 'btn btn-primary', 'name' => 'change-button']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
<?php
