<?php


namespace system\modules\transactions\base;


use phpDocumentor\Reflection\Types\This;
use system\modules\transactions\interfaces\Terminal;
use system\modules\transactions\models\Transactions;
use system\modules\transactions\models\TransactionsFactor;
use system\modules\user\models\User;

abstract class BaseTerminal extends Transactions implements Terminal
{
    /**
     * توکن هایی که نیاز دارید کاربر برای ارتباط به پنل به شما بدهد را اینجا با نام فارسی وارد کنید.
     *
     * برای مثال:
     *
     * [
     *
     *
     *  'Username'=>
     *
     *          [
     *
     *          'label'=>'نام کاربری درگاه',
     *
     *          'hint'=>'لطفا نام کاربری درگاه را وارد کنید',
     *
     *          ],
     *  'apiToken'=>'توکن واسط کاربری'
     *
     * ]
     *
     * @var array
     */
    public $tokens=[];

    /**
     * در صورتی که درگاه در حالت برنامه نویسی باشد این تابع مقدار صحیح برمیگرداند
     * @return bool
     */
    public function isDebugMode(){
        return !empty(\Yii::$app->Options->PaymentDebug);
    }

    /**
     * یک تراکنش ایجاد میکند، سپس مقدمات پرداخت را آماده سازی نموده و تابع start را فراخوانی میکند
     * @param TransactionsFactor $factor
     */
    public function pay(TransactionsFactor $factor)
    {
        $this->initTokens();
        $transaction = self::addTransaction(
            0,
            $this->title(),
            $factor->uid,
            self::STATUS_WAIT_FOR_PAY,
            $factor->total_price,
            $factor->id
        );


        // < پرداخت فاکتور با مبلغ  صفر >
        {
            if (($factor->total_price) == 0) {
                $transaction->changeStatus(Transactions::STATUS_PAYED, 'پرداخت تراکنش رایگان');
                $transaction->factor0->changeStatus(TransactionsFactor::STATUS_PAYED);
                $factor->status = TransactionsFactor::STATUS_PAYED;
                return true;
            }
        }
        // </ پرداخت فاکتور با مبلغ  صفر >


        $serial = $this->get_before_pay_serial($factor->total_price * 10, $factor, $transaction, \Yii::$app->urlManager->createAbsoluteUrl(['/payment/verify']));

        $transaction->status = self::STATUS_WAIT_FOR_PAY;
        $transaction->terminal_pre_pay_serial = $serial;
        $transaction->save();
        return $this->start($factor, $transaction, \Yii::$app->urlManager->createAbsoluteUrl(['/payment/verify']));
    }

    /**
     * این متد فایل html فیلدهای فرم تنظیمات درگاه را میسازد
     * @return string
     */
    public function renderForm()
    {
        return \Yii::$app->view->render('@system/modules/transactions/settings/autorender.php',
            [
                'tokens'=>$this->tokens
            ]
        );
    }

    /**
     * این متد, کدهای جاوا اسکریپتی که کاربر نیاز دارد را پس از ایجاد فیلد های مورد نیاز در فرم ایجاد میکند.
     * @return string
     */
    public function renderJS(){
        return '';
    }
}
