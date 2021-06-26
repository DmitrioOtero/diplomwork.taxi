<?php
/* @var $this yii\web\View */
/* @var $searchModel backend\models\TripSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "Мои поездки";
$this->params['breadcrumbs'][] = $this->title;

$columns = [];
$columns[] = ['class' => 'yii\grid\SerialColumn'];
$columns[] = [
    'class' => 'yii\grid\ActionColumn',
    'header' => 'Действие',
    'template' => '{delete} {reservations}',
    'buttons' => [
        'delete' => function ($url, $model) {
            return \yii\helpers\Html::a('<span class="glyphicon glyphicon-trash"></span>', '/frontend/web/site/delete-trip?id=' . $model->id);
        },
        'reservations' => function ($url, $model) {
            return \yii\helpers\Html::a('<span class="glyphicon glyphicon-lock"></span>', '/frontend/web/site/my-reservations?trip_id=' . $model->id);
        },
    ]
];
$columns[] = ['attribute' => 'date'];
$columns[] = ['attribute' => 'time'];
$columns[] = ['attribute' => 'car_number'];
$columns[] = ['attribute' => 'car_model'];
$columns[] = ['attribute' => 'description'];
$columns[] = [
    'label' => 'Маршрут',
    'attribute' => 'routs',
    'format' =>'raw',
    'value' => function($model) {
        $routs = $model->rout;
        $result = "";
        $result .= "<div class='div-rout' title=\"{$routs[0]->from}\"><div class=\"dot\"></div></div><div class='div-rout'  title=\"Общее количество мест: {$routs[0]->number_of_seats} ({$routs[0]->price}₽)\"><div class=\"dots-link\"></div></div>";
        for ($rout_number = 0; $rout_number < count($routs) - 1; $rout_number++) {
            $result .= "<div class='div-rout' title=\"{$routs[$rout_number + 1]->from}\"><div class=\"dot\"></div></div><div class='div-rout' title=\"Общее количество мест: {$routs[$rout_number + 1]->number_of_seats} ({$routs[$rout_number + 1]->price}₽)\"><div class=\"dots-link\"></div></div>";
        }
        $result .= "<div class='div-rout' title=\"{$routs[count($routs) - 1]->to}\"><div class=\"dot\"></div></div>";
        return $result;
    },
    'contentOptions' => function ($searchModel) {
        return ['align' => 'center'];
    },
];

echo \yii\grid\GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => $columns,
]);