<?php


namespace system\modules\transactions\Terminals;

use system\modules\transactions\base\BaseTerminal;
use system\modules\transactions\models\Transactions;
use system\modules\transactions\models\TransactionsFactor;

/**
 * کلاس ترمینال پرداخت از کیف پول
 * Class CreditTerminal
 * @package system\modules\transactions\Terminals
 */
class CreditTerminal extends BaseTerminal
{

    function initTokens()
    {
        // TODO: Implement initTokens() method.
    }

    public function get_before_pay_serial(float $price, TransactionsFactor $factor, Transactions $transaction,$callbackUrl)
    {
        return 0;
    }

    function start
    (
        TransactionsFactor $factorModel,
        Transactions $transactionModel,
        string $callbackUrl
    )
    {
        $transactionModel->factor0->changeStatus(TransactionsFactor::STATUS_PAYED);
        $transactionModel->factor0->uid0->chargeUser(-$transactionModel->factor0->total_price, 'کسر مبلغ بابت فاکتور شماره ' . $transactionModel->factor);
        return true;
    }

    function verify(Transactions $transactionModel)
    {
        // TODO: Implement verify() method.
    }

    function title()
    {
        return 'پرداخت از کیف پول';
    }
}
