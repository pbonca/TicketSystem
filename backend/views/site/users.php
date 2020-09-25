<?php

use yii\bootstrap\Button;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $provider \yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title); ?></h1>
    <?= GridView::widget([
        'dataProvider' => $provider,
        'columns' => [
                'email',
                'name',
                'registration_date',
                'is_admin:boolean:Admin',
                [
                    'class' => ActionColumn::class,
                    'template' => '{update} {delete} {view} {list}',
                    'buttons' => [
                        'Delete' => function ($url, $model, $key) {
                            return Html::a('Delete', Url::to(['site/delete-user', 'id' => $model->id]));
                        },
                        'modify' => function ($url, $model, $key) {
                            return Html::a('Update', Url::to(['site/update-user', 'id' => $model->id]));
                        },
                        'View' => function ($url, $model, $key) {
                            return Html::a('View', Url::to(['site/view-user', 'id' => $model->id]));
                        },
                        'list' => function ($url, $model, $key) {
                            return Html::a('Tickets', Url::to(['site/list-user-tickets', 'id' => $model->id]));
                        },

                    ],
                ],
        ],
    ]) ?>

</div>
