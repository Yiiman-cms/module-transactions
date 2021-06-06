<?php

namespace system\modules\transactions\models;

use system\lib\ActiveRecord;
use system\modules\user\models\User;
use Yii;

/**
 * This is the model class for table "{{%module_transactions}}".
 *
 * @property int $id
 * @property int $uid کاربری که پرداخت برای وی ایجاد شده است
 * @property string $terminal درگاه پرداخت
 * @property string $description توضیحات و هیستوری
 * @property string $created_at تاریخ ایجاد
 * @property string $payed_at تاریخ پرداخت
 * @property int $status وضعیت پرداخت
 * @property string $terminal_pre_pay_serial شماره تراکنش پیش از پرداخت
 * @property string $terminal_after_pay_serial شماره تراکنش پس از پرداخت
 * @property string $terminal_final_transaction_serial شماره تراکنش نهایی
 * @property int $created_user_mode نوع کاربری که این تراکنش را ایجاد کرده است(ادمین یا کاربر عادی)
 * @property int $created_from_uid در صورتی که مد کاربر عادی باشد، از جدول کاربران عادی و در صورتی که از کاربران ادمین باشد، شناسه ی کابر ادمین را بازگردانی میکند
 * @property double $price مبلغ پرداختی
 * @property int $factor فاکتور
 * @property TransactionsFactor $factor0
 * @property User|\system\modules\useradmin\models\User|null $createdFromUid0
 * @property User $uid0
 * @property string $hash
 */
class Transactions extends \system\lib\ActiveRecord
{

    public static $modules = [];


    private static $terminals = [];

    const STATUS_WAIT_FOR_PAY = 1;//در انتظار پرداخت
    const STATUS_FAILED = 2;// پرداخت لغو شده یا شکست خورده
    const STATUS_PAYED = 3;// پرداخت انجام شده

    const CREATED_USER_MODE_ADMIN = 1;
    const CREATED_USER_MODE_USER = 2;


    /**
     * درصد مالیات بر درآمد
     */
    const TAX_PERCENT = 9;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%module_transactions}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uid', 'terminal', 'description', 'created_at', 'status', 'created_user_mode', 'price', 'factor'], 'required'],
            [['uid', 'status',  'created_user_mode', 'created_from_uid', 'factor'], 'integer'],
            [['created_at', 'payed_at'], 'safe'],
            [['price'], 'number'],
            [['terminal', 'terminal_pre_pay_serial', 'terminal_after_pay_serial', 'terminal_final_transaction_serial','hash'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 1000],
            [['uid'], 'exist', 'skipOnError' => true, 'targetClass' => \system\modules\user\models\User::className(), 'targetAttribute' => ['uid' => 'id']],
            [['factor'], 'exist', 'skipOnError' => true, 'targetClass' => \system\modules\transactions\models\TransactionsFactor::className(), 'targetAttribute' => ['factor' => 'id']],
            [['created_from_uid'], 'exist', 'skipOnError' => true, 'targetClass' => \system\modules\user\models\User::className(), 'targetAttribute' => ['created_from_uid' => 'id']],
            [['created_from_uid'], 'exist', 'skipOnError' => true, 'targetClass' => \system\modules\user\models\UserAdmin::className(), 'targetAttribute' => ['created_from_uid' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('transactions', 'ID'),
            'uid' => Yii::t('transactions', 'کاربری که پرداخت برای وی ایجاد شده است'),
            'terminal' => Yii::t('transactions', 'درگاه پرداخت'),
            'description' => Yii::t('transactions', 'توضیحات و هیستوری'),
            'created_at' => Yii::t('transactions', 'تاریخ ایجاد'),
            'payed_at' => Yii::t('transactions', 'تاریخ پرداخت'),
            'status' => Yii::t('transactions', 'وضعیت پرداخت'),
            'terminal_pre_pay_serial' => Yii::t('transactions', 'شماره تراکنش پیش از پرداخت'),
            'terminal_after_pay_serial' => Yii::t('transactions', 'شماره تراکنش پس از پرداخت'),
            'terminal_final_transaction_serial' => Yii::t('transactions', 'شماره تراکنش نهایی'),
            'created_user_mode' => Yii::t('transactions', 'نوع کاربری که این تراکنش را ایجاد کرده است(ادمین یا کاربر عادی)'),
            'created_from_uid' => Yii::t('transactions', 'در صورتی که مد کاربر عادی باشد، از جدول کاربران عادی و در صورتی که از کاربران ادمین باشد، شناسه ی کابر ادمین را بازگردانی میکند'),
            'price' => Yii::t('transactions', 'مبلغ'),
            'factor' => Yii::t('transactions', 'فاکتور'),
        ];
    }

    /**
     * دریافت مدل کاربری که برای وی تراکنش ایجاد شده است
     * @return \yii\db\ActiveQuery|User
     */
    public function getUid0()
    {
        return $this->hasOne(User::className(), ['id' => 'uid']);
    }

    /**
     * دریافت مدل فاکتور
     * @return \yii\db\ActiveQuery
     */
    public function getFactor0()
    {
        return $this->hasOne(TransactionsFactor::className(), ['id' => 'factor']);
    }

    /**
     * دریافت کاربر سیستمی که در حال تغییر وضعیت تراکنش است
     * @return \yii\db\ActiveQuery|User|\system\modules\useradmin\models\User
     */
    public function getCreatedFromUid0()
    {
        // < User Mode >
        {
            switch (true) {
                case Yii::$app->user->identity instanceof User:
                    return $this->hasOne(User::className(), ['id' => 'created_from_uid']);
                    break;
                case Yii::$app->user->identity instanceof \system\modules\useradmin\models\User:
                    $this->created_user_mode = self::CREATED_USER_MODE_ADMIN;
                    return $this->hasOne(\system\modules\useradmin\models\User::className(), ['id' => 'created_from_uid']);
                    break;
            }
        }
        // </ User Mode >

    }


    /**
     * @inheritDoc
     */
    public function save($runValidation = true, $attributeNames = null)
    {
        $this->hash=uniqid(true);
        $this->created_at = date('Y-m-d H:i:s');
        $this->created_from_uid = Yii::$app->user->id;

        // < User Mode >
        {
            switch (true) {
                case Yii::$app->user->identity instanceof User:
                    $this->created_user_mode = self::CREATED_USER_MODE_USER;
                    break;
                case Yii::$app->user->identity instanceof \system\modules\useradmin\models\User:
                    $this->created_user_mode = self::CREATED_USER_MODE_ADMIN;
                    break;
            }
        }
        // </ User Mode >


        return parent::save($runValidation, $attributeNames); // TODO: Change the autogenerated stub
    }


    /**
     * یک تراکنش جدید در بانک داده ایجاد میکند
     *
     *
     * چنانچه قیمت 0 باشد وضعیت تراکنش به طور خودکار به انجام شده تغییر میابد
     * @param string $terminal_before_pay_id شماره پیگیری درگاه برای رهگیری عملیات قبل از پرداخت
     * @param string $terminal نام ترمینال پرداختی
     * @param int $uid شناسه کاربری که برای وی پرداخت انجام میشود
     * @param int $status وضعیت پرداخت
     * @param float $price قیمت به تومان
     * @param int $factorId شناسه ی فاکتوز
     * @param string $description توضیحات در مورد پرداخت
     * @return self
     */
    public static function addTransaction
    (
        string $terminal_before_pay_id,
        string $terminal,
        int $uid,
        int $status,
        float $price,
        int $factorId,
        string $description = 'ثبت اولیه '
    )
    {
        $model = new self();
        $model->uid = $uid;
        $model->terminal = $terminal;
        $model->description = $description;
        $model->status = $status;
        $model->factor=$factorId;

        $model->terminal_pre_pay_serial = $terminal_before_pay_id;
        $model->price = $price;
        if ($model->price == 0) {
            $model->status = self::STATUS_PAYED;
        }
        if ($model->save()) {
            $model->addHistory($model->status, 'ثبت اولیه ');
        }else{
            echo '<pre>';
            var_dump($model->errors);
            die();
        }
        return $model;
    }

    public function addHistory(int $status, string $description)
    {
        $model = new TransactionsHistory();
        $model->status = $status;
        $model->transaction = $this->id;
        $model->created_at = date('Y-m-d H:i:s');
        $model->created_by = Yii::$app->user->id;
        $model->description = $description;
        $model->save();
    }


    public static function addTerminal($name, $class)
    {
        array_merge_recursive(self::$terminals, [$name => $class]);
    }

    /**
     * یافتن تراکنش با شماره فاکتور
     * @param $factorId
     * @return Transactions|null
     */
    public static function findBy_factorId($factorId)
    {
        return self::findOne(['factor' => $factorId]);
    }

    /**
     * یافتن یک تراکنش از روی شماره تراکنش قبل از پرداخت
     * @param $prePaySerial
     * @return Transactions|null
     */
    public static function findBy_prePaySerial($prePaySerial)
    {
        return self::findOne(['terminal_pre_pay_serial' => $prePaySerial]);
    }

    /**
     * یافتن یک تراکنش با شماره سریال پس از پرداخت
     * @param $afterPaySerial
     * @return Transactions|null
     */
    public static function findBy_afterPaySerial($afterPaySerial)
    {
        return self::findOne(['terminal_after_pay_serial' => $afterPaySerial]);
    }

    /**
     * یافتن یک تراکنش با سریال نهایی پس از پرداخت
     * @param $finalPaySerial
     * @return Transactions|null
     */
    public static function findBy_finalPayserial($finalPaySerial)
    {
        return self::findOne(['terminal_final_transaction_serial' => $finalPaySerial]);
    }

    /**
     * تغییر وضعیت تراکنش
     * @param $status
     * @param string $description
     */
    public function changeStatus($status, $description = '')
    {
        $this->status = $status;
        if ($this->save()) {
            $this->addHistory($status, $description);
        }
    }


    /**
     * تغییر وضعیت فاکتور و تراکنش به پرداخت شده
     */
    public function payed(){
        $this->factor0->changeStatus(TransactionsFactor::STATUS_PAYED);
        $this->changeStatus($this::STATUS_PAYED,'فاکتور با موفیت پرداخت شد');
    }

    /**
     * تغییر وضعیت فاکتور و تراکنش به پرداخت ناموفق
     */
    public function PaymentCanceled($description=''){
        $this->factor0->changeStatus(TransactionsFactor::STATUS_EXPIRED);
        $this->changeStatus($this::STATUS_FAILED,$description);
    }



}
