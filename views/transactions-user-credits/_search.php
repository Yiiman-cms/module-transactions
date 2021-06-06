<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model system\modules\transactions\models\SearchTransactionsUserCredits */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="transactions-user-credits-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'credit') ?>

    <?= $form->field($model, 'uid') ?>

    <?= $form->field($model, 'created_at') ?>

    <?= $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'created_user_mode') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'factor') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('transactions', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('transactions', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
