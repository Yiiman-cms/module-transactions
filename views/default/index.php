<?php
use system\modules\filemanager\widget\MediaViewWidget;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel system\modules\transactions\models\SearchTransactions */
/* @var $dataProvider yii\data\ActiveDataProvider */

\system\widgets\topMenu\TopMenuWidget::addBtb(
'add',
Yii::t('transactions', 'ثبت تراکنش ها'),
'success' ,
null ,
Yii::$app->Options->BackendUrl . '/transactions/default/create'
);
\system\widgets\backLang\backLangWidget::languages();

$this->title = Yii::t('transactions', 'تراکنش ها').' ';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="transactions-index">
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
                                                            'uid',
            'terminal',
            'description',
            'created_at',
            //'payed_at',
                                            [
                                            'attribute' => 'status' ,
                                            'value'   => function ( $model ) {
                                            /**
                                            * @var $model \common\models\Neighbourhoods
                                            */
                                            switch ( $model->status ) {
                                            case 10:
                                            return 'انتشار یافته';
                                            break;
                                            case 0:
                                            return 'بازبینی';
                                            break;
                                            }
                                            },
                                            ],
                                                        //'terminal_pre_pay_serial',
            //'terminal_after_pay_serial',
            //'terminal_final_transaction_serial',
            //'pay_module',
            //'pay_module_id',
            //'created_user_mode',
            //'created_from_uid',
            //'price',
            //'factor',

                        ['class' => 'system\lib\ActionColumn'],
                        ],
                        ]); ?>
                                            <?php Pjax::end(); ?>
                </div>
            </div>


        </div>


    </div>
</div>
