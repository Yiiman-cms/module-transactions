<?php

namespace system\modules\transactions\interfaces;


use system\modules\factor\models\Factors;
use system\modules\transactions\models\Transactions;
use system\modules\transactions\models\TransactionsFactor;

interface Terminal
{
    /**
     *
     * توکن های درگاه پرداخت را در این بخش ثبت و آماده سازی کنید
     * @return mixed
     */
    function initTokens();

    /**
     * شماره ی سریال رهگیری که در مرحله ی اول پرداخت از سمت درگاه برای شما ارسال میشود را در این تابع بازگردانی کنید
     * @param float $price
     * @param TransactionsFactor $factor
     * @param Transactions $transaction
     * @param string $callbackUrl آدرس بازگشت کاربر از درگاه : https://yoursite/payment/verify
     * @return mixed
     */
    function get_before_pay_serial(float $price, TransactionsFactor $factor, Transactions $transaction, $callbackUrl);

    /**
     * عملیات پرداخت تراکنش را آغاز میکند
     *
     *
     * پس از آغاز عملیات پرداخت، باید در همین متد، شماره ی تراکنش در مدل ثبت شود
     *
     * @param TransactionsFactor $factorModel مدل فاکتور
     * @param Transactions $transactionModel مدل تراکنش
     * @param string $callbackUrl ادرس کال بک پرداخت
     * @return mixed
     */
    function start
    (
        TransactionsFactor $factorModel,
        Transactions $transactionModel,
        string $callbackUrl
    );

    /**
     * عملیات پرداخت کال بک شده را بازبینی میکند
     *
     * وضعیت پرداخت باید در همین متد در تراکنش ثبت شود
     *
     * جهت ثبت وضعیت جدید پرداخت باید از متد changeStatus استفاده شود
     * @param Transactions $transactionModel شماره تراکنش که توسط بانک صادر شده است
     * @return bool در صورت صحیح بودن پرداخت موفق بوده است، در غیر اینصورت پرداخت ناموفق است
     */
    function verify(Transactions $transactionModel);

    /**
     * نام درگاه
     * @return string
     */
    function title();

    public function renderForm();

    public function renderJS();

}
