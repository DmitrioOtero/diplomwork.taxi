<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Регистрация компании';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Пожалуйста, заполните поля для регистрации:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'email') ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <?= $form->field($model, 'inn') ?>

                <?= $form->field($model, 'address') ?>

                <?= $form->field($model, 'phone')->widget(\yii\widgets\MaskedInput::className(), ['mask' => '8 (999) 999-99-99']) ?>

                <?= $form->field($model, 'organization') ?>

                <?= $form->field($model, 'agree_with_policy')->checkbox()->label("Я согласен с      ".Html::a("пользовательским соглашением", "/privacy policy.html", ["target" => "_blank" ])) ?>

                <div class="form-group">
                    <?= Html::submitButton('Регистрация', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
