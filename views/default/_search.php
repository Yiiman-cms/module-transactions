<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model system\modules\transactions\models\SearchTransactions */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="transactions-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'uid') ?>

    <?= $form->field($model, 'terminal') ?>

    <?= $form->field($model, 'description') ?>

    <?= $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'payed_at') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'terminal_pre_pay_serial') ?>

    <?php // echo $form->field($model, 'terminal_after_pay_serial') ?>

    <?php // echo $form->field($model, 'terminal_final_transaction_serial') ?>

    <?php // echo $form->field($model, 'pay_module') ?>

    <?php // echo $form->field($model, 'pay_module_id') ?>

    <?php // echo $form->field($model, 'created_user_mode') ?>

    <?php // echo $form->field($model, 'created_from_uid') ?>

    <?php // echo $form->field($model, 'price') ?>

    <?php // echo $form->field($model, 'factor') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('transactions', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('transactions', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
