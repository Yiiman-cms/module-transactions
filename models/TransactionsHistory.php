<?php

namespace system\modules\transactions\models;

use Yii;
/**
* This is the model class for table "{{%module_transactions_history}}".
*
    * @property int $id
    * @property int $transaction تراکنش
    * @property int $status وضعیت
    * @property string $created_at زمان ثبت
    * @property int $created_by ثبت شده توسط
    * @property string $description توضیحات
*/
class TransactionsHistory extends \system\lib\ActiveRecord
{
const STATUS_ACTIVE=1;
const STATUS_DE_ACTIVE=0;

/**
* {@inheritdoc}
*/
public static function tableName()
{
return '{{%module_transactions_history}}';
}

/**
* {@inheritdoc}
*/
public function rules()
{
return [
            [['transaction', 'status', 'created_at', 'created_by'], 'required'],
            [['transaction', 'status', 'created_by'], 'integer'],
            [['created_at'], 'safe'],
            [['description'], 'string', 'max' => 255],
        ];
}

/**
* {@inheritdoc}
*/
public function attributeLabels()
{
return [
    'id' => Yii::t('transactions', 'ID'),
    'transaction' => Yii::t('transactions', 'تراکنش'),
    'status' => Yii::t('transactions', 'وضعیت'),
    'created_at' => Yii::t('transactions', 'زمان ثبت'),
    'created_by' => Yii::t('transactions', 'ثبت شده توسط'),
    'description' => Yii::t('transactions', 'توضیحات'),
];
}
}
