<?php
/* @var $this yii\web\View */
/* @var $searchModel backend\models\ReservationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use kartik\date\DatePicker;

$this->title = "Поиск";
$this->params['breadcrumbs'][] = $this->title;

$columns = [];
$columns[] = ['class' => 'yii\grid\SerialColumn'];
$columns[] = [
    'class' => 'yii\grid\ActionColumn',
    'header' => 'Действие',
    'template' => '{delete}',
    'buttons' => [
        'delete' => function ($url, $model) {
            $model->getDeparture($_GET["ReservationSearch"]["from"]);
            $model->getDestination($_GET["ReservationSearch"]["to"]);
            return \yii\helpers\Html::a('<span class="glyphicon glyphicon-lock"></span>', '/frontend/web/site/reservation?trip_id=' . $model->id . "&from_id=" . $model->from_id . "&to_id=" . $model->to_id . "&number_of_seats=" . $_GET["ReservationSearch"]["number_of_seats"]);
        },
    ]
];
$columns[] = [
    'attribute' => 'date',
    'filter' => DatePicker::widget([
        'model' => $searchModel,
        'attribute' => 'date_from',
        'attribute2' => 'date_to',
        'type' => DatePicker::TYPE_RANGE,
        'separator' => '-',
        'pluginOptions' => [
            'format' => 'yyyy-mm-dd',
            'todayHighlight' => true,
            'defaultDate' => date('Y-m-d'),
            'startDate' => date('Y-m-d')
        ],
    ]),
];
$columns[] = ['attribute' => 'time'];
$columns[] = ['attribute' => 'car_number'];
$columns[] = ['attribute' => 'car_model'];
$columns[] = [
    'attribute' => 'number_of_seats',
    'label' => 'Количество мест',
    'value' => function($model) {
        return $model->getNumberOfSeats($_GET["ReservationSearch"]["from"], $_GET["ReservationSearch"]["to"]);
    }
];
$columns[] = [
    'label' => "Наименование организации",
    'attribute' => 'organization',
    'value' => function($model) {
        return $model->user->organization;
    },
];
$columns[] = ['attribute' => 'description'];
$columns[] = [
    'label' => "Откуда",
    'attribute' => 'from',
    'value' => function($model) {
        $routs = $model->rout;
        return $model->getDeparture($_GET["ReservationSearch"]["from"]);
    },
];
$columns[] = [
    'label' => "Куда",
    'attribute' => 'to',
    'value' => function($model) {
        return $model->getDestination($_GET["ReservationSearch"]["to"]);
    },
];
$columns[] = [
    'label' => 'Цена',
    'attribute' => 'price',
    'format' =>'raw',
    'value' => function($model) {
        return $model->getPrice($_GET["ReservationSearch"]["from"], $_GET["ReservationSearch"]["to"]);
    },
    'contentOptions' => function ($searchModel) {
        return ['align' => 'center'];
    },
];
$columns[] = [
    'label' => 'Маршрут',
    'attribute' => 'routs',
    'format' =>'raw',
    'value' => function($model) {
        $routs = $model->rout;
        $result = "";
        $result .= "<div class='div-rout' title=\"{$routs[0]->from}\"><div class=\"dot\"></div></div><div class='div-rout'  title=\"Количество мест: {$routs[0]->number_of_seats} ({$routs[0]->price}₽)\"><div class=\"dots-link\"></div></div>";
        for ($rout_number = 0; $rout_number < count($routs) - 1; $rout_number++) {
            $result .= "<div class='div-rout' title=\"{$routs[$rout_number + 1]->from}\"><div class=\"dot\"></div></div><div class='div-rout' title=\"Количество мест: {$routs[$rout_number + 1]->number_of_seats} ({$routs[$rout_number + 1]->price}₽)\"><div class=\"dots-link\"></div></div>";
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