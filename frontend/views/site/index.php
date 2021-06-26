<?php

/* @var $this yii\web\View */
/* @var $model backend\models\ReservationSearch */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use kartik\date\DatePicker;

$this->title = 'Бронирование маршрутных такси';
?>

<div class="site-index">
    <?php $form = ActiveForm::begin(['id' => 'contact-form', "action" => "/frontend/web/site/search", "method" => "get"]); ?>

    <div class="jumbotron">
        <h1><?= $this->title ?></h1>

        <p class="lead">Для поиска заполните поля ниже.</p>

        <p>
        <div class="form-group">
            <?= Html::submitButton('<span class="glyphicon glyphicon-search"></span> Поиск', ['class' => 'btn btn-lg btn-success', 'name' => 'contact-button']) ?>
        </div>
        </p>
    </div>

    <div class="body-content">
        <?php
        echo $form->field($model, 'from');
        echo $form->field($model, 'to');
        echo $form->field($model, 'date_from')->widget(DatePicker::class, [
            'language' => 'ru',
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
            'options' => [
                'placeholder' => "Выберите дату",
                'class' => 'form-control',
                'autocomplete' => 'off',
            ],
        ])->label("Дата") ;
        echo $form->field($model, 'number_of_seats') ?>

    </div>

    <?php ActiveForm::end(); ?>
</div>
