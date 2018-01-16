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
    private $partner;
    private $secretKey;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->partner = getenv('PARTNER');
        $this->secretKey = getenv('SECRET_KEY');
    }

    public function testDigitalPaymentOrder()
    {
        $faker = Factory::create();
        $tradeNo = $faker->uuid;
        $channel = 'ICBC';
        $amount = 1;
        $clientIp = $faker->ipv4;
        $notifyUrl = $faker->url;

        $payment = new DigitalPayment($this->partner, $this->secretKey);
        $result = $payment->order($tradeNo, $channel, $amount, $clientIp, $notifyUrl);
        //var_dump($result);

        $this->assertEquals('00', $result['code']);
        //$this->assertEquals('商户没通过审核', $result);

        return $tradeNo;
    }

    /**
     * @depends testDigitalPaymentOrder
     *
     * @param $tradeNo
     */
    // public function testDigitalPaymentOrderFind($tradeNo)
    // {
    //     $tradeQuery = new TradeQuery($this->merchantId, $this->secretKey);
    //     $result = $tradeQuery->find($tradeNo);

    //     $this->assertEquals('00', $result['code']);
    // }

    /**
     * @depends testDigitalPaymentOrder
     *
     * @param $tradeNo
     */
    // public function testDigitalPaymentOrderIsPaid($tradeNo)
    // {
    //     $tradeQuery = new TradeQuery($this->merchantId, $this->secretKey);
    //     $result = $tradeQuery->isPaid($tradeNo);

    //     $this->assertFalse($result);
    // }

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

    /**
     * @depends testBankPaymentOrder
     *
     * @param $tradeNo
     */
    // public function testBankPaymentOrderFind($tradeNo)
    // {
    //     $tradeQuery = new TradeQuery($this->merchantId, $this->secretKey);
    //     $result = $tradeQuery->find($tradeNo);

    //     $this->assertEquals('00', $result['code']);
    // }

    /**
     * @depends testBankPaymentOrder
     *
     * @param $tradeNo
     */
    // public function testBankPaymentOrderIsPaid($tradeNo)
    // {
    //     $tradeQuery = new TradeQuery($this->merchantId, $this->secretKey);
    //     $result = $tradeQuery->isPaid($tradeNo);

    //     $this->assertFalse($result);
    // }

    // public function testTradeQueryFindOrderNotExist()
    // {
    //     $faker = Factory::create();
    //     $tradeNo = $faker->uuid;

    //     $tradeQuery = new TradeQuery($this->merchantId, $this->secretKey);
    //     $result = $tradeQuery->find($tradeNo);

    //     $this->assertNull($result);
    // }

    // public function testTradeQueryIsPaidOrderNotExist()
    // {
    //     $faker = Factory::create();
    //     $tradeNo = $faker->uuid;

    //     $tradeQuery = new TradeQuery($this->merchantId, $this->secretKey);
    //     $result = $tradeQuery->isPaid($tradeNo);

    //     $this->assertFalse($result);
    // }

    // public function testNotifyWebhookVerifyNotifyPayload()
    // {
    //     $mock = $this->getMockForTrait(NotifyWebhook::class);

    //     $payload = [
    //         'merchantCode' => '1000000267',
    //         'instructCode' => '150211000000000018',
    //         'transType'    => '00200',
    //         'outOrderId'   => '80904482661769148113436093416980',
    //         'transTime'    => '20150211155604',
    //         'totalAmount'  => '1',
    //         'ext'          => 'ext',
    //         'sign'         => '69C0A709C58C7E7BFA5CF5B7F8D690C0',
    //     ];

    //     $this->assertTrue($mock->verifyNotifyPayload($payload, '123456ABDDFF'));
    // }

    // public function testNotifyWebhookParseNotifyPayload()
    // {
    //     $mock = $this->getMockForTrait(NotifyWebhook::class);

    //     $payload = [
    //         'merchantCode' => '1000000267',
    //         'instructCode' => '150211000000000018',
    //         'transType'    => '00200',
    //         'outOrderId'   => '80904482661769148113436093416980',
    //         'transTime'    => '20150211155604',
    //         'totalAmount'  => '1',
    //         'ext'          => 'ext',
    //         'sign'         => '69C0A709C58C7E7BFA5CF5B7F8D690C0',
    //     ];

    //     $this->assertEquals([
    //         'merchantCode' => '1000000267',
    //         'instructCode' => '150211000000000018',
    //         'transType'    => '00200',
    //         'outOrderId'   => '80904482661769148113436093416980',
    //         'transTime'    => '20150211155604',
    //         'totalAmount'  => 0.01,
    //         'ext'          => 'ext',
    //         'sign'         => '69C0A709C58C7E7BFA5CF5B7F8D690C0',
    //     ], $mock->parseNotifyPayload($payload, '123456ABDDFF'));
    // }

    // public function testNotifyWebhookSuccessNotifyResponse()
    // {
    //     $mock = $this->getMockForTrait(NotifyWebhook::class);

    //     $this->assertEquals('{"code":"00"}', $mock->successNotifyResponse());
    // }
}
