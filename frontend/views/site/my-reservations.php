<?php
/* @var $this yii\web\View */
/* @var $searchModel backend\models\TripSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $trip \backend\models\Trip */
/* @var $routs_from array */
/* @var $routs_to array */

$this->title = "Бронирования моей поездки";
$this->params['breadcrumbs'][] = ["label" => "Мои поездки", "url" => "/frontend/web/trips"];
$this->params['breadcrumbs'][] = $this->title;

$columns = [];
$columns[] = ['class' => 'yii\grid\SerialColumn'];
$columns[] = [
    'class' => 'yii\grid\ActionColumn',
    'header' => 'Действие',
    'template' => '{delete} {reservations}',
    'buttons' => [
        'delete' => function ($url, $model) {
            return \yii\helpers\Html::a('<span class="glyphicon glyphicon-trash"></span>', '/frontend/web/site/delete-reservation?id=' . $model->id);
        },
    ]
];
$columns[] = ['attribute' => 'first_name'];
$columns[] = ['attribute' => 'last_name'];
$columns[] = ['attribute' => 'middle_name'];
$columns[] = ['attribute' => 'phone'];
$columns[] = ['attribute' => 'number_of_seats'];
$columns[] = ['attribute' => 'email'];
$columns[] = [
    'attribute' => 'rout_from_id',
    'value' => function ($model) {
        return $model->routFrom->from;
    },
    'filter' => $routs_from,
];
$columns[] = [
    'attribute' => 'rout_to_id',
    'value' => function ($model) {
        return $model->routTo->to;
    },
    'filter' => $routs_to,
];

echo \yii\grid\GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => $columns,
]);