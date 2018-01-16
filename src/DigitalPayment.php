<?php

namespace Jetfuel\Gaotongpay;

use Jetfuel\Gaotongpay\Traits\ResultParser;

class DigitalPayment extends Payment
{
    use ResultParser;

    const MODEL          = 'QR_CODE';
    const CREDIT_SUPPORT = 1;

    /**
     * DigitalPayment constructor.
     *
     * @param string $merchantId
     * @param string $secretKey
     * @param null|string $baseApiUrl
     */
    public function __construct($merchantId, $secretKey, $baseApiUrl = null)
    {
        parent::__construct($merchantId, $secretKey, $baseApiUrl);
    }

    /**
     * Create digital payment order.
     *
     * @param string $tradeNo
     * @param int $channel
     * @param float $amount
     * @param string $clientIp
     * @param string $notifyUrl
     * @return array
     */
    public function order($tradeNo, $channel, $amount, $clientIp, $notifyUrl)
    {
        // $payload = $this->signPayload([
        //     'outOrderId'      => $tradeNo,
        //     'amount'          => $this->convertYuanToFen($amount),
        //     'noticeUrl'       => $notifyUrl,
        //     'isSupportCredit' => self::CREDIT_SUPPORT,
        // ]);

        // $payload['payChannel'] = $channel;
        // $payload['ip'] = $clientIp;
        // $payload['model'] = self::MODEL;

        $data = array();
        //$order_number = date('YmdHis');         #订单号
        $data['partner'] = 10080;      #商户号
        $data['banktype'] = 'WEIXIN';           #选择微信
        $data['paymoney'] = '100.00';                #金额 单位元
        $data['ordernumber'] = '1234567892';   #订单号
        $data['callbackurl'] = 'http://www.yahoo.com/';
        $sign = $this->signPayload($data);
        var_dump($sign);
        $data['hrefbackurl'] = 'http://www.yahoo.com/';
        $data['attach'] = 'jrapi';              #备注信息   不参与签名
        
        $data['sign'] =$sign; 
        // $pay_url = 'https://wgtj.gaotongpay.com/PayBank.aspx' . '?' .http_build_query($data);
        // var_dump($pay_url);
        //header("location:" . $pay_url);   
        return $this->parseResponse($this->httpClient->get('?' .http_build_query($data)));
    }
}
