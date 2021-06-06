<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model system\modules\transactions\models\TransactionsCoupons */

$this->title = Yii::t('transactions', 'ویرایش کوپن های تخفیف: ' . $model->id, [
    'nameAttribute' => '' . $model->id,
]);


\system\widgets\topMenu\TopMenuWidget::addBtb(
'add',
Yii::t('transactions', 'ثبت کوپن های تخفیف'),
'success' ,
null ,
Yii::$app->Options->BackendUrl . '/transactions-coupons/default/create'
);

\system\widgets\topMenu\TopMenuWidget::addBtb(
'assignment',
Yii::t('transactions', 'بازبینی کوپن های تخفیف'),
'info' ,
null ,
Yii::$app->Options->BackendUrl . '/transactions-coupons/default/view?id='.$model->id
);



$this->params['breadcrumbs'][] = ['label' => Yii::t('transactions', 'مالی'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('transactions', 'ویرایش');
\system\widgets\backLang\backLangWidget::languages($model);
?>
<div class="transactions-coupons-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
