<?php

namespace Test;

use Faker\Factory;
use Jetfuel\Gaotongpay\BankPayment;
use Jetfuel\Gaotongpay\Constants\Bank;
use Jetfuel\Gaotongpay\Constants\Channel;
use Jetfuel\Gaotongpay\DigitalPayment;
use Jetfuel\Gaotongpay\TradeQuery;
use Jetfuel\Gaotongpay\Traits\NotifyWebhook;
use PHPUnit\Framework\TestCase;

class UnitTest extends TestCase
{
    private $merchantId;
    private $secretKey;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->merchantId = getenv('MERCHANT_ID');
        $this->secretKey = getenv('SECRET_KEY');
    }

    public function testDigitalPaymentOrder()
    {
        $faker = Factory::create();
        $tradeNo = $faker->uuid;
         $channel = Channel::QQ;
        $amount = 50;
        $clientIp = $faker->ipv4;
        $notifyUrl = $faker->url;
        $returnUrl = $faker->url;

        $payment = new DigitalPayment($this->merchantId, $this->secretKey);
        $result = $payment->order($tradeNo, $channel, $amount, $clientIp, $notifyUrl, $returnUrl);

        var_dump($result);
        $this->assertContains('getqrcode', $result, '', true);
        return $tradeNo;
    }

    /**
     * @depends testDigitalPaymentOrder
     *
     * @param $tradeNo
     */
    public function testDigitalPaymentOrderFind($tradeNo)
    {
        $tradeQuery = new TradeQuery($this->merchantId, $this->secretKey, 'https://cx.gaotongpay.com/zfapi/order/singlequery');
        $result = $tradeQuery->find($tradeNo);
        var_dump($result);
        $this->assertEquals('1', $result['rspCode']);
    }

    /**
     * @depends testDigitalPaymentOrder
     *
     * @param $tradeNo
     */
    public function testDigitalPaymentOrderIsPaid($tradeNo)
    {
        $tradeQuery = new TradeQuery($this->merchantId, $this->secretKey, 'https://cx.gaotongpay.com/zfapi/order/singlequery');
        $result = $tradeQuery->isPaid($tradeNo);

        $this->assertFalse($result);
    }

    // public function testBankPaymentOrder()
    // {
    //     $faker = Factory::create();
    //     $tradeNo = $faker->uuid;
    //     $bank = Bank::CCB;
    //     $amount = 1;
    //     $returnUrl = $faker->url;
    //     $notifyUrl = $faker->url;

    //     $payment = new BankPayment($this->merchantId, $this->secretKey);
    //     $result = $payment->order($tradeNo, $bank, $amount, $returnUrl, $notifyUrl);

    //     $this->assertContains('<form', $result, '', true);

    //     return $tradeNo;
    // }

    // public function testBankPaymentOrderFind($tradeNo)
    // {
    //     $tradeQuery = new TradeQuery($this->merchantId, $this->secretKey);
    //     $result = $tradeQuery->find($tradeNo);

    //     $this->assertEquals('00', $result['code']);
    // }

    // public function testBankPaymentOrderIsPaid($tradeNo)
    // {
    //     $tradeQuery = new TradeQuery($this->merchantId, $this->secretKey);
    //     $result = $tradeQuery->isPaid($tradeNo);

    //     $this->assertFalse($result);
    // }

    public function testTradeQueryFindOrderNotExist()
    {
        $faker = Factory::create();
        $tradeNo = $faker->uuid;

        $tradeQuery = new TradeQuery($this->merchantId, $this->secretKey, 'https://cx.gaotongpay.com/zfapi/order/singlequery');
        $result = $tradeQuery->find($tradeNo);

        $this->assertNull($result);
    }

    public function testTradeQueryIsPaidOrderNotExist()
    {
        $faker = Factory::create();
        $tradeNo = $faker->uuid;

        $tradeQuery = new TradeQuery($this->merchantId, $this->secretKey, 'https://cx.gaotongpay.com/zfapi/order/singlequery');
        $result = $tradeQuery->isPaid($tradeNo);

        $this->assertFalse($result);
    }

    public function testNotifyWebhookVerifyNotifyPayload()
    {
        $mock = $this->getMockForTrait(NotifyWebhook::class);

        $payload = [
            'partner'         => '10080',
            'ordernumber'     => '150211000000000018',
            'orderstatus'     => '1',
            'paymoney'        => '50',
            'sysnumber'       => 'aa123456789',
            'attach'          => 'abc',
            'sign'            => 'a3fc7ee52cd803a35296647d6440f10f',
        ];

        $this->assertTrue($mock->verifyNotifyPayload($payload, $this->secretKey));
    }

    public function testNotifyWebhookParseNotifyPayload()
    {
        $mock = $this->getMockForTrait(NotifyWebhook::class);

        $payload = [
            'partner'         => '10080',
            'ordernumber'     => '150211000000000018',
            'orderstatus'     => '1',
            'paymoney'        => '50',
            'sysnumber'       => 'aa123456789',
            'attach'          => 'abc',
            'sign'            => 'a3fc7ee52cd803a35296647d6440f10f',
        ];

        $this->assertEquals([
            'partner'         => '10080',
            'ordernumber'     => '150211000000000018',
            'orderstatus'     => '1',
            'paymoney'        => '50',
            'sysnumber'       => 'aa123456789',
            'attach'          => 'abc',
            'sign'            => 'a3fc7ee52cd803a35296647d6440f10f',
        ], $mock->parseNotifyPayload($payload, $this->secretKey));
    }

    public function testNotifyWebhookSuccessNotifyResponse()
    {
        $mock = $this->getMockForTrait(NotifyWebhook::class);

        $this->assertEquals('ok', $mock->successNotifyResponse());
    }
}
