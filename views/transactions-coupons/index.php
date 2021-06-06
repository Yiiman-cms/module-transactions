<?php
use system\modules\filemanager\widget\MediaViewWidget;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel system\modules\transactions\models\SearchTransactionsCoupons */
/* @var $dataProvider yii\data\ActiveDataProvider */

\system\widgets\topMenu\TopMenuWidget::addBtb(
'add',
Yii::t('transactions', 'ثبت کوپن های تخفیف'),
'success' ,
null ,
Yii::$app->Options->BackendUrl . '/transactions-coupons/default/create'
);
\system\widgets\backLang\backLangWidget::languages();

$this->title = Yii::t('transactions', 'کوپن های تخفیف').' ';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="transactions-coupons-index">
    <div class="card card-nav-tabs">
        <div class="card-body ">
            <h3 class="text-center"><?= Html::encode($this->title) ?></h3>

            <div class="row">
                <div class="col-md-12 pull-right">

                        <?php Pjax::begin(); ?>
                                                <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
                    


                                            <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
        'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        ['class' => '\system\lib\i18n\LanguageColumn'],
                                                            'price',
            'expire',
                                            [
                                            'attribute' => 'status' ,
                                            'format'=>'raw',
                                            'value'   => function ( $model ) {

                                            switch ( $model->status ) {
                                            case $model::STATUS_ACTIVE:
                                            return '<span style="color:green">انتشار یافته</span>';
                                                                                                 break;
                                                                                                 case $model::STATUS_DE_ACTIVE:
                                                                                                 return '<span
                                                style="color: red">بازبینی</span>';
                                            break;
                                            }
                                            },
                                            ],
                                                        'start_from',
            //'limit_count',
            //'mode',
            //'uid_limit',
            //'created_at',
            //'created_by',

                        ['class' => 'system\lib\ActionColumn'],
                        ],
                        ]); ?>
                                            <?php Pjax::end(); ?>
                </div>
            </div>


        </div>


    </div>
</div>
