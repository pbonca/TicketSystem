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
$this->registerJsFile('/assets/closeTicket.js', [View::POS_BEGIN]);

$this->title = 'My tickets';
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
                'class' => ActionColumn::class,
                'template' => '{view}{close}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a('View', Url::to(['view-comments', 'id' => $model->id]));
                    },
                    'close' => function ($url, $model, $key) {
                        if ($model->status == 'ACTIVE') {
                            return Html::button('<span class="glyphicon glyphicon-off"></span>', ['id' => 'closeButton', 'data-ticketId' => $model->id]);
                        }
                    },
                ],
            ],
        ],
    ]) ?>

</div>
