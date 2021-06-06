<?php

namespace system\modules\transactions\models;

use phpDocumentor\Reflection\Types\This;
use system\lib\ActiveRecord;
use system\modules\transactions\models\TransactionsFactorItems;
use system\modules\transactions\models\TransactionsUserCredits;
use system\modules\transactions\Terminals\BaseTerminal;
use system\modules\user\models\User;
use Yii;

/**
 * This is the model class for table "{{%module_transactions_factor_head}}".
 *
 * @property int $id
 * @property string $created_at تاریخ ایجاد
 * @property int $created_by ایجاد شده توسط
 * @property int $status وضعیت پرداخت
 * @property int $uid کاربر مربوطه
 * @property string $payed_at زمان پرداخت
 * @property double $price جمع خالص
 * @property double $tax_price مبلغ مالیات
 * @property int $tax_percent درصد مالیات
 * @property double $discount_price مبلغ تخفیف
 * @property int $discount_percent درصد تخفیف
 * @property double $user_credit اعتبار کاربر
 * @property double $total_price جمع پرداختی
 * @property string $extra_data اطلاعات اضافی
 *
 * @property Transactions[] $transactions
 * @property User $uid0
 * @property \system\modules\useradmin\models\User $created_by0
 * @property TransactionsFactorItems[] $transactionsFactorItems
 * @property TransactionsUserCredits[] $transactionsUserCredits
 */
class TransactionsFactor extends \system\lib\ActiveRecord
{
    const STATUS_PAYED = 1;
    const STATUS_WAITING_FOR_PAY = 0;
    const STATUS_EXPIRED = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%module_transactions_factor_head}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'status', 'total_price', 'uid', 'price', 'tax_price', 'tax_percent', 'discount_price', 'discount_percent', 'user_credit'], 'required'],
            [['created_at', 'payed_at', 'extra_data'], 'safe'],
            [['created_by', 'status', 'uid', 'tax_percent', 'discount_percent'], 'integer'],
            [['price', 'tax_price', 'discount_price', 'user_credit', 'total_price'], 'number'],
            [['uid'], 'exist', 'skipOnError' => true, 'targetClass' => \system\modules\user\models\User::className(), 'targetAttribute' => ['uid' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => \system\modules\useradmin\models\User::className(), 'targetAttribute' => ['created_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('transactions', 'ID'),
            'created_at' => Yii::t('transactions', 'تاریخ ایجاد'),
            'created_by' => Yii::t('transactions', 'ایجاد شده توسط'),
            'status' => Yii::t('transactions', 'وضعیت پرداخت'),
            'uid' => Yii::t('transactions', 'کاربر مربوطه'),
            'payed_at' => Yii::t('transactions', 'زمان پرداخت'),
            'price' => Yii::t('transactions', 'مبلغ پرداختی'),
            'tax_price' => Yii::t('transactions', 'مبلغ مالیات'),
            'tax_percent' => Yii::t('transactions', 'درصد مالیات'),
            'discount_price' => Yii::t('transactions', 'مبلغ تخفیف'),
            'discount_percent' => Yii::t('transactions', 'درصد تخفیف'),
            'user_credit' => Yii::t('transactions', 'اعتبار کاربر'),
            'total_price' => Yii::t('transactions', 'مبلغ نهایی'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactions()
    {
        return $this->hasMany(Transactions::className(), ['factor' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUid0()
    {
        return $this->hasOne(User::className(), ['id' => 'uid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreated_by0()
    {
        return $this->hasOne(\system\modules\useradmin\models\User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery|TransactionsFactorItems[]
     */
    public function getTransactionsFactorItems()
    {
        return $this->hasMany(TransactionsFactorItems::className(), ['factor' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery|TransactionsUserCredits[]
     */
    public function getTransactionsUserCredits()
    {
        return $this->hasMany(TransactionsUserCredits::className(), ['factor' => 'id']);
    }

    /**
     *
     * یک فاکتور را به صورت سیستمی ثبت به همراه پارامتر های آن ثبت میکند
     *
     * @param int $uid
     * @param string $status
     * @param float $price
     * @param float $tax_price
     * @param float $discount_price
     * @param array $extra_data
     * @param TransactionsFactorItems[] $items
     * @return self
     */
    public static function addFactor
    (
        int $uid,
        string $status,

        array $items,
        array $extra_data = []
    )
    {
        $model = new self();
        $model->uid = $uid;
        $model->status = $status;
        $model->price = 0;
        $model->tax_price = 0;
        $model->discount_price = 0;
        $model->tax_percent = empty($model->tax_price) ? 0 : Yii::$app->functions->number2percentCalculator($model->price, $model->tax_price);
        $model->discount_percent = empty($model->discount_price) ? 0 : Yii::$app->functions->number2percentCalculator($model->price, $model->discount_price);
        $model->created_at = date('Y-m-d H:i:s');
        $model->extra_data = json_encode($extra_data);
        if (Yii::$app->user->identity instanceof \system\modules\useradmin\models\User) {
            $model->created_by = Yii::$app->user->id;
        }

        $model->total_price = 0;

        $credit = User::findOne($uid)->correctCredit();
        $model->user_credit = !empty($credit) ? $credit : 0;

        // < factor items prices >
        {
            $items_total_prices = 0;
            $items_total_tax_price = 0;
            $items_total_discount_price = 0;
            $items_total_no_calculated_price = 0;
        }
        // </ factor items prices >

        if ($model->save()) {
            foreach ($items as $item) {
                $item->factor = $model->id;
                $item->price = (float)$item->price;
                // < generate counts >
                {
                    if ($item->count <= 0) {
                        $item->count = 1;
                    }
                    $priceInCount = $item->price * $item->count;
                }
                // </ generate counts >

                // < generate tax and discount >
                {
                    switch (true) {
                        case empty($item->discount_price) && empty($item->discount_percent):
                            $item->discount_price = 0;
                            $item->discount_percent = 0;
                            break;
                        case empty($item->discount_price) && !empty($item->discount_percent):

                            $item->discount_price = Yii::$app->functions->percent2NumberCalculator(($priceInCount), $item->discount_percent);
                            break;
                        case !empty($item->discount_price) && empty($item->discount_percent):
                            $item->discount_percent = Yii::$app->functions->number2percentCalculator($priceInCount, ($priceInCount - $item->discount_price));
                            break;
                    }

                    switch (true) {
                        case empty($item->tax_price) && empty($item->tax_percent):
                            $item->tax_price = 0;
                            $item->tax_percent = 0;
                            break;
                        case empty($item->tax_price) && !empty($item->tax_percent):
                            $item->tax_price = Yii::$app->functions->percent2NumberCalculator($priceInCount, $item->tax_percent);
                            break;
                        case !empty($item->tax_price) && empty($item->tax_percent):
                            $item->tax_percent = Yii::$app->functions->number2percentCalculator($priceInCount, ($priceInCount - $item->tax_price));
                            break;
                    }
                }
                // </ generate tax and discount >


                $item->total_price = $priceInCount - $item->discount_price + $item->tax_price;

                if (!$item->save()) {



                    $model->addErrors($item->errors);
                }else{
                    // < serve total prices >
                    {
                        $items_total_discount_price += $item->discount_price;
                        $items_total_prices += $item->total_price;
                        $items_total_tax_price += $item->tax_price;
                        $items_total_no_calculated_price += $item->price;
                    }
                    // </ serve total prices >
                }
            }


            // < generate factor Prices >
            {
                $model->total_price = $items_total_prices;
                $model->price = $items_total_no_calculated_price;
                $model->tax_price = $items_total_tax_price;
                if ($model->tax_price > 0) {
                    $model->tax_percent = Yii::$app->functions->number2percentCalculator($items_total_no_calculated_price, ($items_total_no_calculated_price + $model->tax_price));
                } else {
                    $model->tax_percent = 0;
                }
                $model->discount_price = $items_total_discount_price;
                if ($model->discount_price > 0) {
                    $model->discount_percent = Yii::$app->functions->number2percentCalculator($items_total_no_calculated_price, ($items_total_no_calculated_price + $model->discount_price));
                } else {
                    $model->discount_percent = 0;
                }

                $model->save();
            }
            // </ generate factor Prices >
        }

        return $model;
    }

    /**
     * آغاز عملیات پرداخت فاکتور
     * @param BaseTerminal $terminalClass
     */
    public function pay(\system\modules\transactions\base\BaseTerminal $terminalClass)
    {
        return $terminalClass->pay($this);
    }

    /**
     * وضعیت فاکتور را تغییر میدهد
     *
     * در صورتی که فاکتور به حالت پرداخت شده تغییر وضعیت بدهد، توابع کال بک محصولات درون فاکتور اجرا میشود
     *
     * @param $status
     */
    public function changeStatus($status)
    {

        $this->status = $status;
        $this->save();
        switch ($status) {
            case self::STATUS_PAYED:
                foreach ($this->transactionsFactorItems as $item) {
                    /**
                     * @var $module_class ActiveRecord
                     */
                    $module_class = $item->module_class;
                    $model = $module_class::findOne($item->module_id);
                    try {
                        $model->{$item->module_after_pay_function}($item);
                    } catch (\Exception $e) {
                        Yii::error('factor_item_callback', $e->getMessage());
                    }
                }
                break;
        }

    }
}
