<?php

namespace system\modules\transactions\models;

use Yii;

/**
 * This is the model class for table "{{%module_transactions_factor_items}}".
 *
 * @property int $id
 * @property string $title نام محصول
 * @property double $price مبلغ هر عدد(خالص)
 * @property int $count تعداد
 * @property double $tax_price مبلغ مالیات
 * @property int $tax_percent درصد مالیت
 * @property double $discount_price مبلغ تخفیف
 * @property int $discount_percent درصد تخفیف
 * @property int $factor فاکتور
 * @property double $total_price مبلغ قابل پرداخت
 * @property string $module_class کلاس مدل محصول
 * @property int $module_id آی دی مدل محصول
 * @property string $module_after_pay_function  تابعی که پس از پرداخت فراخوانی میشود و مدل آیتم محصول در فاکتور به آن ارسال میشود
 *
 * @property TransactionsFactor $factor0
 */
class TransactionsFactorItems extends \system\lib\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_DE_ACTIVE = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%module_transactions_factor_items}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'price', 'count', 'tax_price', 'tax_percent', 'discount_price', 'discount_percent', 'factor', 'total_price', 'module_class', 'module_id', 'module_after_pay_function'], 'required'],
            [['price', 'tax_price', 'discount_price', 'total_price'], 'number'],
            [['count', 'tax_percent', 'discount_percent', 'factor', 'module_id'], 'integer'],
            [['title', 'module_class', 'module_after_pay_function'], 'string', 'max' => 255],
            [['factor'], 'exist', 'skipOnError' => true, 'targetClass' => \system\modules\transactions\models\TransactionsFactor::className(), 'targetAttribute' => ['factor' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('transactions', 'ID'),
            'title' => Yii::t('transactions', 'نام محصول'),
            'price' => Yii::t('transactions', 'مبلغ هر عدد(خالص)'),
            'count' => Yii::t('transactions', 'تعداد'),
            'tax_price' => Yii::t('transactions', 'مبلغ مالیات'),
            'tax_percent' => Yii::t('transactions', 'درصد مالیت'),
            'discount_price' => Yii::t('transactions', 'مبلغ تخفیف'),
            'discount_percent' => Yii::t('transactions', 'درصد تخفیف'),
            'factor' => Yii::t('transactions', 'فاکتور'),
            'total_price' => Yii::t('transactions', 'مبلغ قابل پرداخت'),
            'module_class' => Yii::t('transactions', 'کلاس مدل محصول'),
            'module_id' => Yii::t('transactions', 'آی دی مدل محصول'),
            'module_after_pay_function' => Yii::t('transactions', 'تابعی که پس از پرداخت فراخوانی میشود'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFactor0()
    {
        return $this->hasOne(TransactionsFactor::className(), ['id' => 'factor']);
    }
}
