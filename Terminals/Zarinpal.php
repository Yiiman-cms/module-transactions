<?php


namespace system\modules\transactions\Terminals;


use mysql_xdevapi\Result;
use phpDocumentor\Reflection\Types\True_;
use SoapClient;
use system\modules\transactions\models\Transactions;
use system\modules\transactions\models\TransactionsFactor;
use yii\web\BadRequestHttpException;

class Zarinpal extends \system\modules\transactions\base\BaseTerminal
{
    public $tokens =
        [
            'zarinMerchant' =>
                [
                    'label' => 'مرچنت کد',
                    'hint' => 'لطفا مرچنت کد درگاه زرین پال را در این ورودی ثبت کنید'
                ]
        ];
    public $token;
    public $sandbox = false;

    public function initTokens()
    {
        $this->token = \Yii::$app->Options->zarinMerchant;
        $this->sandbox = $this->isDebugMode();
    }

    /**
     * @param TransactionsFactor $factor
     * @param Transactions $transactions
     * @param string $callbackUrl
     * @return mixed|void
     */
    public function start(TransactionsFactor $factor, Transactions $transactions, string $callbackUrl)
    {
        if ($factor->price == 0) {
            return;
        }
        if ($this->sandbox) {
            $url = 'https://sandbox.zarinpal.com/pg/StartPay/' . $transactions->terminal_pre_pay_serial;
        } else {
            $url = 'https://www.zarinpal.com/pg/StartPay/' . $transactions->terminal_pre_pay_serial;
        }
        $html = <<<HTML
<!DOCTYPE html>
<html direction="rtl" dir="rtl" style="direction: rtl" >
    <body>
        <form action="{$url}" method="post">
            <h4 style="text-align: center">در حال انتقال به درگاه بانکی</h4>
        </form>
        <script >
            document.forms[0].submit()
        </script>
    </body>
</html>
HTML;

        \print_r($html);
        die();
    }

    public function verify(Transactions $transactions)
    {
        $get = \Yii::$app->request->get();

        switch ($get['Status']) {
            case 'NOK':
                $transactions->PaymentCanceled('کاربر انصراف داده است');
                return false;
                break;
            case 'OK':

                // < Verify Online >
                {

                    if ($this->sandbox) {
                        $url = 'https://sandbox.zarinpal.com/pg/v4/payment/verify.json';
                    } else {
                        $url = 'https://api.zarinpal.com/pg/v4/payment/verify.json';
                    }


                    \Yii::$app->Curl->setHeader('content-type', 'application/json');
                    \Yii::$app->Curl->setDefaultJsonDecoder();
                    $post = \Yii::$app->Curl->post($url,
                        [
                            'merchant_id' => $this->token,
                            'amount' => $transactions->factor0->price * 10,
                            'authority' => $transactions->terminal_pre_pay_serial,
                        ]
                    );

                    $result = json_decode(\Yii::$app->Curl->rawResponse);

                    if ((int)$result->data->code == 100) {
                        $transactions->terminal_after_pay_serial = $result->data->ref_id;
                        $transactions->save();
                        $transactions->payed();
                        return true;
                    } elseif ((int)$result->data->code == 101) {
                        if ($transactions->factor0->status == TransactionsFactor::STATUS_PAYED) {
                            return true;
                        } else {
                            $transactions->payed();
                            return true;
                        }
                    } else {
                        $transactions->PaymentCanceled('');
                    }
                }
                // </ Verify Online >
                break;
        }
    }

    /**
     * @param float $price
     * @param TransactionsFactor $factor
     * @param Transactions $transaction
     * @param string $callbackUrl
     * @return mixed
     * @throws BadRequestHttpException
     */
    function get_before_pay_serial(float $price, TransactionsFactor $factor, Transactions $transaction, $callbackUrl)
    {
        if ($this->sandbox) {
            $url = 'https://sandbox.zarinpal.com/pg/v2/payment/request.json';
        } else {
            $url = 'https://api.zarinpal.com/pg/v4/payment/request.json';
        }

        $curl = \Yii::$app->Curl;
        $curl->setHeader('content-type', 'application/json');
        \Yii::$app->Curl->setDefaultJsonDecoder();
        $data =
            [
                'merchant_id' => $this->token,
                'amount' => (int)$price,
                'description' => $transaction->description,
                'callback_url' => $callbackUrl,
//            'metadata' =>
//                [
//                    'mobile' => $factor->uid0->username
//                ]
        ];
        $post = $curl->post($url, $data);
        $resultOrg = $curl->rawResponse;
        $result = json_decode($resultOrg);

        // < returned html >
        {
            if (empty($result) && !empty($resultOrg)) {
                echo $resultOrg;
                die();
            }
        }
        // </ returned html >


        if (empty($result->data) && !empty($result->errors)) {
            \Yii::$app->session->addFlash('danger', 'Code: ' . $result->errors->code . ' : ' . $result->errors->message);
            \Yii::$app->response->redirect(['/panel'])->send();
            die();
        }
        if ((int)$result->data->code == 100) {
            return $result->data->authority;
        } else {
            throw new BadRequestHttpException($result->errorDesc);
        }

    }

    function title()
    {
        return 'زرین پال';
    }


    public function renderJS()
    {
        return <<<JS
function checkzsandbox(){
        var zsandbox=parseInt( $('#dynamicmodel-paymentdebug option:checked').val());
        if (zsandbox===1){
            if ($('.make-test-zmerchant').length ===0){
                $('.zmerchant-note').before(`<div class="make-test-zmerchant btn btn-success">
                    تولید مرچنت کد تست برای حالت سندباکس
                </div>`);
                trigger_test_btn();
            }
            $('.make-test-zmerchant').css('display','block');
        }else{
            $('.make-test-zmerchant').css('display','none');
        }
        if ($('#dynamicmodel-zarinmerchant').val()==='1344b5d4-0048-11e8-94db-005056a205be'){
            $('.zmerchant-note').css('display','block');
            var html=`
                <ul>
                    <li style="color:orange">شما در حال استفاده از کد تست به عنوان درگاه پرداخت هستید.</li>
                    <li>کد مرچنت تست فقط در حالت سندباکس و برای آزمایش درگاه استفاده میشود.</li>
                    <li>اگر از کد تست استفاده میکنید باید حتما وضعیت درگاه روی (تست و برنامه نویسی) تنظیم شده باشد, در غیر اینصورت با خطا در حین پرداخت مواجه خواهید شد</li>
                    <li>برای بازگردانی درگاه به حالت عادی لطفا وضعیت درگاه را روی (عادی) قرار داده و کد مرچنت خود را اینجا وارد کنید</li>
                </ul>
            `;
            $('.zmerchant-note').html(html);
        }else{
            $('.zmerchant-note').css('display','none');
        }
}



$('#dynamicmodel-paymentdebug').off();
$('#dynamicmodel-paymentdebug').change(function (){
    checkzsandbox();
});
var zhtml=`
<br>
                <div class="make-test-zmerchant btn btn-success">
                    تولید مرچنت کد تست برای حالت سندباکس
                </div>
                <p class="zmerchant-note"></p>
`;
$('#dynamicmodel-zarinmerchant').off();
$('#dynamicmodel-zarinmerchant').closest('.form-group').after(zhtml);
$('#dynamicmodel-zarinmerchant').keyup(function (){
    checkzsandbox();
});



function trigger_test_btn(){
    $('.make-test-zmerchant').off();
    $('.make-test-zmerchant').click(function (e){
    e.preventDefault();
    $('#dynamicmodel-zarinmerchant').val('1344b5d4-0048-11e8-94db-005056a205be');
    checkzsandbox();
});
}

checkzsandbox();
trigger_test_btn();
JS;

    }
}
