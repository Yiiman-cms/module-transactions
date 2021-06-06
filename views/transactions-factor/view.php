<?php
use system\modules\filemanager\widget\MediaViewWidget;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model system\modules\transactions\models\TransactionsFactor */


\system\widgets\topMenu\TopMenuWidget::addBtb(
'add',
Yii::t('transactions', 'ثبت فاکتور ها'),
'success' ,
null ,
Yii::$app->Options->BackendUrl . '/transactions-factor-head/default/create'
);


\system\widgets\topMenu\TopMenuWidget::addBtb(
'edit',
Yii::t('transactions', 'ویرایش این مورد'),
'info' ,
null ,
Yii::$app->Options->BackendUrl . '/transactions-factor-head/default/update?id='.$model->id);


\system\widgets\topMenu\TopMenuWidget::addBtb(
'delete',
Yii::t('transactions', 'حذف این مورد'),
'danger' ,
null ,
Yii::$app->Options->BackendUrl . '/transactions-factor-head/default/delete?id='.$model->id);


$this->title = Yii::t('transactions','فاکتور ها:  '.$model->id);
$this->params['breadcrumbs'][] = ['label' => Yii::t('transactions', 'مالی'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

\system\widgets\backLang\backLangWidget::languages($model);

?>
<div class="transactions-factor-head-view">
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
                                        'created_at',
            'created_by',
                                            [
                                            'attribute' => 'status' ,
                                            'value'   => function ( $model ) {
                                            /**
                                            * @var $model \common\models\Neighbourhoods
                                            */
                                            switch ( $model->status ) {
                                            case 1:
                                            return 'فعال';
                                            break;
                                            case 0:
                                            return 'غیرفعال';
                                            break;
                                            }
                                            },
                                            ],
                                                        'uid',
            'payed_at',
            'price',
            'tax_price',
            'tax_percent',
            'discount_price',
            'discount_percent',
            'user_credit',
                            ],
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
