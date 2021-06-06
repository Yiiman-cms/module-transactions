<?php
use system\modules\filemanager\widget\MediaViewWidget;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel system\modules\transactions\models\SearchTransactionsUserCredits */
/* @var $dataProvider yii\data\ActiveDataProvider */

\system\widgets\topMenu\TopMenuWidget::addBtb(
'add',
Yii::t('transactions', 'ثبت تاریخچه ی کیف پول کاربران'),
'success' ,
null ,
Yii::$app->Options->BackendUrl . '/transactions-user-credits/default/create'
);
\system\widgets\backLang\backLangWidget::languages();

$this->title = Yii::t('transactions', 'تاریخچه ی کیف پول کاربران').' ';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="transactions-user-credits-index">
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
                                                            'credit',
            'uid',
            'created_at',
            'created_by',
            //'created_user_mode',
            //'description',
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
