<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model system\modules\transactions\models\Transactions */

$this->title = Yii::t('transactions', 'ثبت تراکنش ها');
$this->params['breadcrumbs'][] = ['label' => Yii::t('transactions', 'مالی'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transactions-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
