<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model system\modules\transactions\models\SearchTransactionsCoupons */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="transactions-coupons-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'price') ?>

    <?= $form->field($model, 'expire') ?>

    <?= $form->field($model, 'status') ?>

    <?= $form->field($model, 'start_from') ?>

    <?php // echo $form->field($model, 'limit_count') ?>

    <?php // echo $form->field($model, 'mode') ?>

    <?php // echo $form->field($model, 'uid_limit') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('transactions', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('transactions', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
