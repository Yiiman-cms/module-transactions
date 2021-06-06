<?php
use system\modules\filemanager\widget\FileSelectorWidget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $model system\modules\transactions\models\TransactionsUserCredits */
/* @var $form yii\widgets\ActiveForm */


?>

<div class="transactions-user-credits-form">

    <?php $form = ActiveForm::begin(); ?>

            <div class="row">
            <?php            if(Yii::$app->controller->action->id =='update'){
            ?>
            <div class="viewLanguagebox">
                زبان های موجود:
                <?= (new \system\lib\i18n\LanguageColumn())->renderDataCell($model,0,0) ?>
            </div>

            <?php            }
            ?>

            <div class="col-md-3">
                <div class="row">
                    <div class="card card-nav-tabs" style="margin-top: 20px ">
                        <div class="card-body ">
                            <h4 class="text-center">ذخیره و انتشار</h4>
                            <div class="row">
                                <div class="col-md-12 pull-right">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success">ذخیره</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                                        <div class="row">
                            <div class="card card-nav-tabs" style="margin-top: 20px ">
                                <div class="card-body ">
                                    <h4 class="text-center"> </h4>
                                    <div class="row">
                                        <div class="col-md-12 pull-right">
                                            <?= $form->field( $model , 'factor' )->widget(
								\kartik\select2\Select2::className() ,
								[
									'data'          => \yii\helpers\ArrayHelper::map(
										\system\modules\transactions\models\TransactionsFactor::find()->where(['language_parent'=>null])->all() ,
										'id' ,
										'title'
									) ,
									'pluginOptions' => ['dir' => 'rtl','placeholder'=>'لطفا انتخاب کنید']
								]
							) ?>                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                                                <div class="row">
                            <div class="card card-nav-tabs" style="margin-top: 20px ">
                                <div class="card-body ">
                                    <h4 class="text-center"> </h4>
                                    <div class="row">
                                        <div class="col-md-12 pull-right">
                                            <?= $form->field( $model , 'uid' )->widget(
								\kartik\select2\Select2::className() ,
								[
									'data'          => \yii\helpers\ArrayHelper::map(
										\system\modules\user\models\User::find()->where(['language_parent'=>null])->all() ,
										'id' ,
										'title'
									) ,
									'pluginOptions' => ['dir' => 'rtl','placeholder'=>'لطفا انتخاب کنید']
								]
							) ?>                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                                                <div class="row">
                            <div class="card card-nav-tabs" style="margin-top: 20px ">
                                <div class="card-body ">
                                    <h4 class="text-center"> </h4>
                                    <div class="row">
                                        <div class="col-md-12 pull-right">
                                            <?= $form->field( $model , 'created_by' )->widget(
								\kartik\select2\Select2::className() ,
								[
									'data'          => \yii\helpers\ArrayHelper::map(
										\system\modules\user\models\User::find()->where(['language_parent'=>null])->all() ,
										'id' ,
										'title'
									) ,
									'pluginOptions' => ['dir' => 'rtl','placeholder'=>'لطفا انتخاب کنید']
								]
							) ?>                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                                                <div class="row">
                            <div class="card card-nav-tabs" style="margin-top: 20px ">
                                <div class="card-body ">
                                    <h4 class="text-center"> </h4>
                                    <div class="row">
                                        <div class="col-md-12 pull-right">
                                            <?= $form->field( $model , 'created_by' )->widget(
								\kartik\select2\Select2::className() ,
								[
									'data'          => \yii\helpers\ArrayHelper::map(
										\system\modules\user\models\UserAdmin::find()->where(['language_parent'=>null])->all() ,
										'id' ,
										'title'
									) ,
									'pluginOptions' => ['dir' => 'rtl','placeholder'=>'لطفا انتخاب کنید']
								]
							) ?>                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                                        <?php                                $model->image_input_widget( $form , 'درج تصویر' );
                                ?>            </div>
            <div class="col-md-9">
                <div class="card card-nav-tabs" style="margin-top: 20px ">
                    <div class="card-body ">
                        <h4 class="text-center">مشخصات</h4>
                        <div class="row">
                            <div class="col-md-12 pull-right">
                                    <div class="col-md-6"><?= $form->field($model, 'credit')->textInput() ?></div>

    <div class="col-md-6"><?= $form->field($model, 'created_at')->textInput() ?></div>

    <div class="col-md-6"><?= $form->field($model, 'created_user_mode')->textInput() ?></div>

    <div class="col-md-6"><?= $form->field($model, 'description')->widget(\system\widgets\floara\FroalaEditorWidget::className() ,[]) ?></div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        

    <?php ActiveForm::end(); ?>

</div>
