<?php
/**
 * @var $model \system\modules\setting\models\DynamicModel
 * @var $form \yii\bootstrap\ActiveForm
 * @var $tokens []
 */
$form = \system\widgets\ActiveForm::begin();
$model=new \system\modules\setting\models\DynamicModel();
$options=new \system\modules\sms\base\smsOptions();
if (!empty($tokens)) {
    $count=0;
    foreach ($tokens as $name => $label) {
        $attr = $name;

        if ($count==0){
            echo '<div class="row">';
        }
        echo '<div class="col-md-6">';
        $hint = false;
        if (is_array($label) && !empty($label['label'])) {
            $l = $label['label'];
        }else{
            $l=$label;
        }

        if (is_array($label) && !empty($label['hint'])) {
            $hint = $label['hint'];
        }

        $model->addRule([$attr], 'required');
        $model->addRule([$attr], 'trim');
        $model->addRule([$attr], 'string', ['max' => 50]);
        $model->defineAttribute($attr);
        $model->{$attr}=$options->{$name};
        echo $form->field($model, $attr)->textInput()->hint($hint)->label($l);
        echo '</div>';
        if ($count==0){
            echo '</div>';
        }
        if ($count==2){
            $count=0;
        }
        $count++;
    }
}
$form::end();
?>
