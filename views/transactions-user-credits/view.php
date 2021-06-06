<?php
use system\modules\filemanager\widget\MediaViewWidget;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model system\modules\transactions\models\TransactionsUserCredits */


\system\widgets\topMenu\TopMenuWidget::addBtb(
'add',
Yii::t('transactions', 'ثبت تاریخچه ی کیف پول کاربران'),
'success' ,
null ,
Yii::$app->Options->BackendUrl . '/transactions-user-credits/default/create'
);


\system\widgets\topMenu\TopMenuWidget::addBtb(
'edit',
Yii::t('transactions', 'ویرایش این مورد'),
'info' ,
null ,
Yii::$app->Options->BackendUrl . '/transactions-user-credits/default/update?id='.$model->id);


\system\widgets\topMenu\TopMenuWidget::addBtb(
'delete',
Yii::t('transactions', 'حذف این مورد'),
'danger' ,
null ,
Yii::$app->Options->BackendUrl . '/transactions-user-credits/default/delete?id='.$model->id);


$this->title = Yii::t('transactions','تاریخچه ی کیف پول کاربران:  '.$model->id);
$this->params['breadcrumbs'][] = ['label' => Yii::t('transactions', 'مالی'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

\system\widgets\backLang\backLangWidget::languages($model);

?>
<div class="transactions-user-credits-view">
    <div class="container">
        <div class="jumbotron">
            <div class="viewLanguagebox">
                زبان های ست شده:
                <?= (new \system\lib\i18n\LanguageColumn())->renderDataCell($model,0,0) ?>
            </div>
            <div class="card card-nav-tabs">
                <div class="card-body ">
                    <h3 class="text-center"><?= Html::encode($this->title) ?></h3>
                    <div class="row">
                        <div class="col-md-12 pull-right">
                            <?= DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                        'credit',
            'uid',
            'created_at',
            'created_by',
            'created_user_mode',
            'description',
            'factor',
                            ],
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
