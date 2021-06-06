<?php
use system\modules\filemanager\widget\FileSelectorWidget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $model system\modules\transactions\models\Transactions */
/* @var $form yii\widgets\ActiveForm */


?>

<div class="transactions-form">

    <?php $form = ActiveForm::begin(); ?>

    
        <div class="row">
            <div class="col-md-3">
                <div class="row">
                    <div class="card card-nav-tabs">
                        <div class="card-body ">
                            <h4 class="text-center">وضعیت
                                انتشار تراکنش</h4>

                            <div class="row">
                                <div class="col-md-12 pull-right">
                                    <?= $form->field( $model , 'status' )->widget(
											\kartik\select2\Select2::className() ,
											[
												'data'          =>
													[
														$model::STATUS_DE_ACTIVE  => 'منتشر شده' ,
														$model::STATUS_ACTIVE   => 'در حال بازبینی' ,
														
													] ,
												'options'       => [ 'dir' => 'rtl' ] ,
												'pluginOptions' => [ 'dir' => 'rtl' ] ,
												'pluginEvents'  => [
													"change"              => "function() {  }" ,
													"select2:opening"     => "function() {  }" ,
													"select2:open"        => "function() {  }" ,
													"select2:closing"     => "function() {  }" ,
													"select2:close"       => "function() {  }" ,
													"select2:selecting"   => "function() {  }" ,
													"select2:select"      => "function() {  }" ,
													"select2:unselecting" => "function() {  }" ,
													"select2:unselect"    => "function() {  }"
												]
											]
										) ?>
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
                                            <?= $form->field( $model , 'uid' )->widget(
								\kartik\select2\Select2::className() ,
								[
									'data'          => \yii\helpers\ArrayHelper::map(
										\system\modules\user\models\User::find()->where(
											[ 'status' => 1,'language_parent'=>null ]
										)->all() ,
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
                                            <?= $form->field( $model , 'factor' )->widget(
								\kartik\select2\Select2::className() ,
								[
									'data'          => \yii\helpers\ArrayHelper::map(
										\system\modules\transactions\models\TransactionsFactor::find()->where(
											[ 'status' => 1,'language_parent'=>null ]
										)->all() ,
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
                                            <?= $form->field( $model , 'created_from_uid' )->widget(
								\kartik\select2\Select2::className() ,
								[
									'data'          => \yii\helpers\ArrayHelper::map(
										\system\modules\user\models\User::find()->where(
											[ 'status' => 1,'language_parent'=>null ]
										)->all() ,
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
                                            <?= $form->field( $model , 'created_from_uid' )->widget(
								\kartik\select2\Select2::className() ,
								[
									'data'          => \yii\helpers\ArrayHelper::map(
										\system\modules\user\models\UserAdmin::find()->where(
											[ 'status' => 1,'language_parent'=>null ]
										)->all() ,
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
                <div class="card card-nav-tabs card-rtl">
                    <div class="card-body ">
                        <div class="col-md-12">
                            <h4 class="text-center">مشخصات</h4>
                            <div class="row">
                                    <div class="col-md-6"><?= $form->field($model, 'terminal')->textInput(['maxlength' => true]) ?></div>

    <div class="col-md-6"><?= $form->field($model, 'created_at')->textInput() ?></div>

    <div class="col-md-6"><?= $form->field($model, 'payed_at')->textInput() ?></div>

    <div class="col-md-6"><?= $form->field($model, 'terminal_pre_pay_serial')->textInput(['maxlength' => true]) ?></div>

    <div class="col-md-6"><?= $form->field($model, 'terminal_after_pay_serial')->textInput(['maxlength' => true]) ?></div>

    <div class="col-md-6"><?= $form->field($model, 'terminal_final_transaction_serial')->textInput(['maxlength' => true]) ?></div>

    <div class="col-md-6"><?= $form->field($model, 'pay_module')->textInput(['maxlength' => true]) ?></div>

    <div class="col-md-6"><?= $form->field($model, 'pay_module_id')->textInput() ?></div>

    <div class="col-md-6"><?= $form->field($model, 'created_user_mode')->textInput() ?></div>

    <div class="col-md-6"><?= $form->field($model, 'price')->textInput() ?></div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        

    <?php ActiveForm::end(); ?>

</div>
