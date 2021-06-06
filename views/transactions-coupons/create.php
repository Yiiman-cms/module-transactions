<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model system\modules\transactions\models\TransactionsCoupons */

$this->title = Yii::t('transactions', 'ثبت کوپن های تخفیف');
$this->params['breadcrumbs'][] = ['label' => Yii::t('transactions', 'مالی'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transactions-coupons-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
