<?php

/* @var $this yii\web\View */
/* @var $model \backend\models\Trip */


$this->title = 'Создание поездки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
    <?= $this->render('_form', [
        'model' => $model,
        "creation" => true,
    ]) ?>
</div>
