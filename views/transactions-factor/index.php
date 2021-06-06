<?php

use system\modules\filemanager\widget\MediaViewWidget;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use \system\modules\transactions\models\TransactionsFactor;

/* @var $this yii\web\View */
/* @var $searchModel system\modules\transactions\models\SearchTransactionsFactor */
/* @var $dataProvider yii\data\ActiveDataProvider */

\system\widgets\topMenu\TopMenuWidget::addBtb(
    'add',
    Yii::t('transactions', 'ثبت فاکتور ها'),
    'success',
    null,
    Yii::$app->Options->BackendUrl . '/transactions-factor-head/default/create'
);
\system\widgets\backLang\backLangWidget::languages();

$this->title = Yii::t('transactions', 'فاکتور ها') . ' ';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="transactions-factor-head-index">
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
//                            ['class' => '\system\lib\i18n\LanguageColumn'],
                            [
                                'attribute' => 'created_at',
                                'value' => function ($model) {
                                    /**
                                     * @var $model
                                     */
                                    return Yii::$app->functions->convertdate($model->created_at);
                                },
                                'filter' => \yii\widgets\MaskedInput::widget(
                                    [
                                        'model' => $searchModel,
                                        'attribute' => 'created_at',
                                        'options' => ['class' => 'form-control'],
                                        'mask' => '9999/99/99'
                                    ]
                                )
                            ],

                            [
                                'attribute' => 'status',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    /**
                                     * @var $model TransactionsFactor
                                     */
                                    switch ($model->status) {
                                        case $model::STATUS_EXPIRED:
                                            return 'منقضی شده';
                                            break;
                                        case $model::STATUS_PAYED:
                                            return 'پرداخت شده';
                                            break;
                                        case $model::STATUS_WAITING_FOR_PAY:
                                            return 'در انتظار پرداخت';
                                            break;
                                    }
                                },
                                'filter' => Html::activeDropDownList(
                                    $searchModel,
                                    'status',
                                    [
                                        '' => 'همه',
                                        TransactionsFactor::STATUS_EXPIRED => 'منقضی شده',
                                        TransactionsFactor::STATUS_PAYED => 'پرداخت شده',
                                        TransactionsFactor::STATUS_WAITING_FOR_PAY => 'در انتظار پرداخت'
                                    ],
                                    [
                                        'class' => 'form-control'
                                    ]
                                )
                            ],
                            [
                                'attribute' => 'uid',
                                'value' => function ($model) {
                                    /**
                                     * @var $model TransactionsFactor
                                     */
                                    return \system\modules\factorina\models\User::findOne($model->uid)->fullname;
                                },
                                'filter' => \kartik\select2\Select2::widget(
                                    [
                                        'model' => $searchModel,
                                        'attribute' => 'uid',
                                        'data' =>
                                            array_merge_recursive(
                                                ['' => 'همه'],
                                                \yii\helpers\ArrayHelper::map(\system\modules\factorina\models\User::find()->all(), 'id', 'fullname')
                                            ),

                                    ]
                                )
                                ,],
                            [
                                'attribute' => 'total_price',
                                'value' => function ($model) {
                                    return number_format($model->total_price);
                                },
                                'filter' => Html::activeTextInput($searchModel, 'total_price', ['class' => 'form-control'])
                            ],
                            //'payed_at',
                            //'price',
                            //'tax_price',
                            //'tax_percent',
                            //'discount_price',
                            //'discount_percent',
                            //'user_credit',


//                            ['class' => 'system\lib\ActionColumn'],
                        ],
                    ]); ?>
                    <?php Pjax::end(); ?>
                </div>
            </div>


        </div>


    </div>
</div>
