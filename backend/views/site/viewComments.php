<?php

use common\models\CommonUser;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $provider \yii\data\ActiveDataProvider */
/* @var $ticket \common\models\Ticket */
/* @var $model \common\models\ViewComment */
/* @var $comments \common\models\ViewComment[] */

$this->title = "Comments";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">

        <div  style="border-style: double; text-align: center">
            <h5>
                <?php
                echo 'Author: '. CommonUser::getName($ticket->author_id) .'. ';
                echo 'Status: '. $ticket->status .'. ';
                echo 'Last comment: '. $ticket->modification_date.' ';
                if ($ticket->admin_id != null) {
                    echo 'Assigned admin: '. CommonUser::getName($ticket->admin_id).'. ';
                }
                ?>
            </h5>

            <h2>
                <?php
                    echo '<b>Title:</b> '. $ticket->title;
                ?>
            </h2>

            <hr/>
            <p>
                <?php
                    echo '<b>Description:</b> '. $ticket->description;
                ?>
            </p>
        </div>
    <h3><?= Html::encode($this->title); ?></h3>

<?php
if (empty($comments)) {
    echo "There isn't any comment yet...";
}

foreach ($comments as $key => $comment){
    echo '<div>';

    if ($comment->author->is_admin === 1) {
        echo '<div style="background-color: #ff1636; border-style: solid">';
    } else {
        echo '<div style="background-color: #0b72b8; border-style: solid">';
    }
    echo '<div>';
    echo '<h6 style="color: white"><u>'.$comment->author->name.':</u> '.$comment->creation_date.' '. '</h6>';
    echo '</div>';
    echo '<p style="color: white; font-size: 20px">'.$comment->content.'</p>';
    echo '</div>';
    echo '</div>';
    echo '<br>';
}
    ?>
    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-newComment', 'enableClientValidation' => false, 'enableAjaxValidation' => false]); ?>

            <?= $form->field($model, 'content')->textInput(['autofocus' => true])->label('Your comment: ') ?>

            <div class="form-group">
                <?= Html::submitButton('Comment', ['class' => 'btn btn-primary', 'name' => 'change-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
