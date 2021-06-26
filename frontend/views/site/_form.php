<?php

/* @var $this yii\web\View */
/* @var $model \backend\models\Trip */
/* @var $creation boolean */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use corpsepk\DaData\SuggestionsWidget;

?>
<script src="/frontend/web/js/create.js"></script>
<h1><?= Html::encode($this->title) ?></h1>

<p>
    Заполните все поля. Цена указывается за промежуток пути между населенными пунктами.
</p>
<div class="row">
    <div class="col-lg-12">
        <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

        <?= $form->field($model, 'date')->widget(\kartik\date\DatePicker::class, [
            'language' => 'ru',
            'options' => [
                'placeholder' => "Выберите дату",
                'class'=> 'form-control',
                'autocomplete' => 'off',
            ],
            'pluginOptions' => [
                'format' => 'yyyy-mm-dd',
                'todayHighlight' => true,
                'defaultDate' => date('Y-m-d'),
                'startDate' => date('Y-m-d')
            ],
        ]) ?>

        <?= $form->field($model, 'time')->widget(\kartik\time\TimePicker::className(),
            [
                'options'=>[
                    'class'=>'form-control',
                    'placeholder' => "Выберите время",
                ],
                'pluginOptions' => [
                    'minuteStep' => 15,
                    'secondStep' => 1,
                    'hourStep' => 1,
                    'showMeridian' => false,
                    'defaultTime' => date('H:i') . ":00",
                    'showSeconds' => true,
                ],
            ]); ?>

        <?= $form->field($model, 'car_number') ?>

        <?= $form->field($model, 'car_model') ?>

        <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

        <p><label for="routs">Маршрут:</label></p>
        <ul id="routs">
            <li class="rout" number="0">
                <label>Адрес</label>
                <?= SuggestionsWidget::widget(['options' => ['type' => SuggestionsWidget::TYPE_ADDRESS], 'name' => 'Rout[0][place]']); ?>
            </li><li class="rout" number="1">
                <label>Адрес</label><label>Цена</label><label>Количество мест</label><?= SuggestionsWidget::widget(['options' => ['type' => SuggestionsWidget::TYPE_ADDRESS], 'name' => 'Rout[1][place]']); ?><input type="number" pattern="^\d+(.\d{1,2})?$" class="form-control" name="Rout[1][price]"><input type="number" pattern="^\d+$" class="form-control" name="Rout[1][number_of_seats]">
            </li><li class="rout" number="2" style="display: none;">
                <label>Адрес</label><label>Цена</label><label>Количество мест</label><?= SuggestionsWidget::widget(['options' => ['type' => SuggestionsWidget::TYPE_ADDRESS], 'name' => 'Rout[2][place]']); ?><input type="number" pattern="^\d+(.\d{1,2})?$" class="form-control" name="Rout[2][price]"><input type="number" pattern="^\d+$" class="form-control" name="Rout[2][number_of_seats]">
            </li><li class="rout" number="3" style="display: none;">
                <label>Адрес</label><label>Цена</label><label>Количество мест</label><?= SuggestionsWidget::widget(['options' => ['type' => SuggestionsWidget::TYPE_ADDRESS], 'name' => 'Rout[3][place]']); ?><input type="number" pattern="^\d+(.\d{1,2})?$" class="form-control" name="Rout[3][price]"><input type="number" pattern="^\d+$" class="form-control" name="Rout[3][number_of_seats]">
            </li><li class="rout" number="4" style="display: none;">
                <label>Адрес</label><label>Цена</label><label>Количество мест</label><?= SuggestionsWidget::widget(['options' => ['type' => SuggestionsWidget::TYPE_ADDRESS], 'name' => 'Rout[4][place]']); ?><input type="number" pattern="^\d+(.\d{1,2})?$" class="form-control" name="Rout[4][price]"><input type="number" pattern="^\d+$" class="form-control" name="Rout[4][number_of_seats]">
            </li><li class="rout" number="5" style="display: none;">
                <label>Адрес</label><label>Цена</label><label>Количество мест</label><?= SuggestionsWidget::widget(['options' => ['type' => SuggestionsWidget::TYPE_ADDRESS], 'name' => 'Rout[5][place]']); ?><input type="number" pattern="^\d+(.\d{1,2})?$" class="form-control" name="Rout[5][price]"><input type="number" pattern="^\d+$" class="form-control" name="Rout[5][number_of_seats]">
            </li><li class="rout" number="6" style="display: none;">
                <label>Адрес</label><label>Цена</label><label>Количество мест</label><?= SuggestionsWidget::widget(['options' => ['type' => SuggestionsWidget::TYPE_ADDRESS], 'name' => 'Rout[6][place]']); ?><input type="number" pattern="^\d+(.\d{1,2})?$" class="form-control" name="Rout[6][price]"><input type="number" pattern="^\d+$" class="form-control" name="Rout[6][number_of_seats]">
            </li><li class="rout" number="7" style="display: none;">
                <label>Адрес</label><label>Цена</label><label>Количество мест</label><?= SuggestionsWidget::widget(['options' => ['type' => SuggestionsWidget::TYPE_ADDRESS], 'name' => 'Rout[7][place]']); ?><input type="number" pattern="^\d+(.\d{1,2})?$" class="form-control" name="Rout[7][price]"><input type="number" pattern="^\d+$" class="form-control" name="Rout[7][number_of_seats]">
            </li><li class="rout" number="8" style="display: none;">
                <label>Адрес</label><label>Цена</label><label>Количество мест</label><?= SuggestionsWidget::widget(['options' => ['type' => SuggestionsWidget::TYPE_ADDRESS], 'name' => 'Rout[8][place]']); ?><input type="number" pattern="^\d+(.\d{1,2})?$" class="form-control" name="Rout[8][price]"><input type="number" pattern="^\d+$" class="form-control" name="Rout[8][number_of_seats]">
            </li><li class="rout" number="9" style="display: none;">
                <label>Адрес</label><label>Цена</label><label>Количество мест</label><?= SuggestionsWidget::widget(['options' => ['type' => SuggestionsWidget::TYPE_ADDRESS], 'name' => 'Rout[9][place]']); ?><input type="number" pattern="^\d+(.\d{1,2})?$" class="form-control" name="Rout[9][price]"><input type="number" pattern="^\d+$" class="form-control" name="Rout[9][number_of_seats]">
            </li><li class="rout" number="10" style="display: none;">
                <label>Адрес</label><label>Цена</label><label>Количество мест</label><?= SuggestionsWidget::widget(['options' => ['type' => SuggestionsWidget::TYPE_ADDRESS], 'name' => 'Rout[10][place]']); ?><input type="number" pattern="^\d+(.\d{1,2})?$" class="form-control" name="Rout[10][price]"><input type="number" pattern="^\d+$" class="form-control" name="Rout[10][number_of_seats]">
            </li></ul>
        <input type="button" class="btn btn-default" id="add-rout" value="Добавить">

        <div class="form-group">
            <?= Html::submitButton($creation ? 'Создать' : 'Обновить', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
