<?php

namespace Jetfuel\Gaotongpay;

use Jetfuel\Gaotongpay\Traits\ResultParser;

class DigitalPayment extends Payment
{
    use ResultParser;

    const BASE_API_URL = 'https://wgtj.gaotongpay.com/';
    const QRCODE_IMG_PREFIX = 'IMG|';

    /**
     * DigitalPayment constructor.
     *
     * @param string $merchantId
     * @param string $secretKey
     * @param null|string $baseApiUrl
     */
    public function __construct($merchantId, $secretKey, $baseApiUrl = null)
    {
        $this->baseApiUrl = $baseApiUrl === null ? self::BASE_API_URL : $baseApiUrl;
        parent::__construct($merchantId, $secretKey, $baseApiUrl);
    }

    /**
     * Create digital payment order.
     *
     * @param string $tradeNo
     * @param int $channel
     * @param float $amount
     * @param string $notifyUrl
     * @return array|null
     */
    public function order($tradeNo, $channel, $amount, $notifyUrl)
    {
        $payload = $this->signPayload([
            'banktype'    => $channel,
            'paymoney'    => $amount,
            'ordernumber' => $tradeNo,
            'callbackurl' => $notifyUrl,
        ]);

        $imgSrc = $this->parseResponse($this->httpClient->get('PayBank.aspx', $payload));
        if (isset($imgSrc)) 
        {
            $result['qrcodeUrl'] = self::QRCODE_IMG_PREFIX . $this->baseApiUrl . $imgSrc;
            return $result;
        }
        return null;
    }
}
