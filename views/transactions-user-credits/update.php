<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model system\modules\transactions\models\TransactionsUserCredits */

$this->title = Yii::t('transactions', 'ویرایش تاریخچه ی کیف پول کاربران: ' . $model->id, [
    'nameAttribute' => '' . $model->id,
]);


\system\widgets\topMenu\TopMenuWidget::addBtb(
'add',
Yii::t('transactions', 'ثبت تاریخچه ی کیف پول کاربران'),
'success' ,
null ,
Yii::$app->Options->BackendUrl . '/transactions-user-credits/default/create'
);

\system\widgets\topMenu\TopMenuWidget::addBtb(
'assignment',
Yii::t('transactions', 'بازبینی تاریخچه ی کیف پول کاربران'),
'info' ,
null ,
Yii::$app->Options->BackendUrl . '/transactions-user-credits/default/view?id='.$model->id
);



$this->params['breadcrumbs'][] = ['label' => Yii::t('transactions', 'مالی'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('transactions', 'ویرایش');
\system\widgets\backLang\backLangWidget::languages($model);
?>
<div class="transactions-user-credits-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
