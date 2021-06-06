<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model system\modules\transactions\models\TransactionsUserCredits */

$this->title = Yii::t('transactions', 'ثبت تاریخچه ی کیف پول کاربران');
$this->params['breadcrumbs'][] = ['label' => Yii::t('transactions', 'مالی'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transactions-user-credits-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
