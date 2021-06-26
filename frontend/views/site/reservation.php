<?php

/* @var $this yii\web\View */
/* @var $model \backend\models\Reservation */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Бронирование места';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-lg-12">
            <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

            <?= $form->field($model, 'first_name') ?>

            <?= $form->field($model, 'last_name') ?>

            <?= $form->field($model, 'middle_name') ?>

            <?= $form->field($model, 'phone')->widget(\yii\widgets\MaskedInput::className(), ['mask' => '8 (999) 999-99-99']) ?>

            <?= $form->field($model, 'email') ?>

            <?= $form->field($model, 'agree_with_policy')->checkbox()->label("Я согласен с      ".Html::a("пользовательским соглашением", "/privacy policy.html", ["target" => "_blank" ])) ?>
            
            <div class="form-group">
                <?= Html::submitButton('Забронировать', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
