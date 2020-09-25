<?php

use common\models\CommonUser;
use yii\grid\ActionColumn;
use yii\grid\DataColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $provider \yii\data\ActiveDataProvider */
/* @var $model \common\models\Ticket */

$this->registerJsFile('/assets/closeTicket.js', [View::POS_BEGIN]);

$this->title = "User's tickets";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title); ?></h1>
    <?= GridView::widget([
        'dataProvider' => $provider,
        'columns' => [
            [
                'class' => DataColumn::className(), // this line is optional
                'attribute' => 'user',
                'content' => function($model) {
                        return CommonUser::getName($model->author_id);
                }
            ],
            [
                'class' => DataColumn::className(), // this line is optional
                'attribute' => 'title',
                'content' => function ($model, $key, $index, $column) {
                    if (strlen($model->title) > 20) {
                        return substr($model->title, 0, 20) . '...';
                    } else {
                        return $model->title;
                    }
                }
            ],
            [
                'class' => DataColumn::className(), // this line is optional
                'attribute' => 'description',
                'content' => function ($model, $key, $index, $column) {
                    if (strlen($model->description) > 20) {
                        return substr($model->description, 0, 20) . '...';
                    } else {
                        return $model->description;
                    }
                }
            ],
            'status',
            'modification_date',
            [
                'class' => DataColumn::className(), // this line is optional
                'attribute' => 'Assigned admin',
                'content' => function ($model, $key, $index, $column) {
                    if ($model->admin_id != null) {
                        return CommonUser::getName($model->admin_id);
                    } else {
                        return 'No admin assigned yet...';
                    }
                }
            ],
            [
                'class' => ActionColumn::class,
                'template' => '{view}{assign}{close}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-zoom-in"></span>', Url::to(['view-comments', 'id' => $model->id]));
                    },
                    'assign' => function ($url, $model, $key) {
                        if ($model->admin_id === null) {
                            return Html::a('<span class="glyphicon glyphicon-user"></span>', Url::to(['assign-to-admin', 'id' => $model->id]));
                        }
                    },
                    'close' => function ($url, $model, $key) {
                        if ($model->status == 'ACTIVE') {
                            return Html::button('<span class="glyphicon glyphicon-off"></span>', ['id' => 'closeButton', 'data-ticketId' =>$model->id]);
                        }
                    },
                ],
            ],
        ],
    ])
    ?>
</div>
