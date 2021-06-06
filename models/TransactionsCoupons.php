<?php

namespace system\modules\transactions\models;

use Yii;
/**
* This is the model class for table "{{%module_transactions_coupons}}".
*
    * @property int $id
    * @property double $price قیمت(صفر= نامحدود)
    * @property int $expire زمان انقضا(روز)
    * @property int $status وضعیت انتشار
    * @property string $start_from تاریخ آغاز به کار
    * @property int $limit_count محدودیت تعداد استفاده 
    * @property int $mode نوع استفاده ی کوپن، مثلا قابل استفاده بر روی محصول، یا فاکتور
    * @property int $uid_limit محدودیت برای کاربر خاص
    * @property string $created_at تاریخ ایجاد
    * @property int $created_by کاربر ثبت کننده
    *
            * @property User $uidLimit
            * @property UserAdmin $createdBy
    */
class TransactionsCoupons extends \system\lib\ActiveRecord
{
const STATUS_ACTIVE=1;
const STATUS_DE_ACTIVE=0;

/**
* {@inheritdoc}
*/
public static function tableName()
{
return '{{%module_transactions_coupons}}';
}

/**
* {@inheritdoc}
*/
public function rules()
{
return [
            [['id', 'price', 'expire', 'status', 'start_from', 'limit_count', 'mode', 'created_at', 'created_by'], 'required'],
            [['id', 'expire', 'status', 'limit_count', 'mode', 'uid_limit', 'created_by'], 'integer'],
            [['price'], 'number'],
            [['start_from', 'created_at'], 'safe'],
            [['id'], 'unique'],
            [['uid_limit'], 'exist', 'skipOnError' => true, 'targetClass' =>\system\modules\user\models\User::className(), 'targetAttribute' => ['uid_limit' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' =>\system\modules\user\models\UserAdmin::className(), 'targetAttribute' => ['created_by' => 'id']],
        ];
}

/**
* {@inheritdoc}
*/
public function attributeLabels()
{
return [
    'id' => Yii::t('transactions', 'ID'),
    'price' => Yii::t('transactions', 'قیمت(صفر= نامحدود)'),
    'expire' => Yii::t('transactions', 'زمان انقضا(روز)'),
    'status' => Yii::t('transactions', 'وضعیت انتشار'),
    'start_from' => Yii::t('transactions', 'تاریخ آغاز به کار'),
    'limit_count' => Yii::t('transactions', 'محدودیت تعداد استفاده '),
    'mode' => Yii::t('transactions', 'نوع استفاده ی کوپن، مثلا قابل استفاده بر روی محصول، یا فاکتور'),
    'uid_limit' => Yii::t('transactions', 'محدودیت برای کاربر خاص'),
    'created_at' => Yii::t('transactions', 'تاریخ ایجاد'),
    'created_by' => Yii::t('transactions', 'کاربر ثبت کننده'),
];
}

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getUidLimit()
    {
    return $this->hasOne(User::className(), ['id' => 'uid_limit']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getCreatedBy()
    {
    return $this->hasOne(UserAdmin::className(), ['id' => 'created_by']);
    }
}
