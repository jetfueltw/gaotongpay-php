<?php

namespace Jetfuel\Gaotongpay;

use Jetfuel\Gaotongpay\HttpClient\GuzzleHttpClient;
use Jetfuel\Gaotongpay\HttpClient\CurlHttpClient;

class Payment
{
    const BASE_API_URL = 'https://wgtj.gaotongpay.com/';

    /**
     * @var string
     */
    protected $merchantId;

    /**
     * @var string
     */
    protected $secretKey;

    /**
     * @var string
     */
    protected $baseApiUrl;

    /**
     * @var \Jetfuel\Gaotongpay\HttpClient\HttpClientInterface
     */
    protected $httpClient;

    /**
     * Payment constructor.
     *
     * @param string $merchantId
     * @param string $secretKey
     * @param null|string $baseApiUrl
     */
    protected function __construct($merchantId, $secretKey, $baseApiUrl = null)
    {
        $this->merchantId = $merchantId;
        $this->secretKey = $secretKey;
        $this->baseApiUrl = $baseApiUrl === null ? self::BASE_API_URL : $baseApiUrl;

        $this->httpClient = new GuzzleHttpClient($this->baseApiUrl);
    }

    /**
     * Sign request payload.
     *
     * @param array $payload
     * @return array
     */
    protected function signPayload(array $payload)
    {
        $payload['partner'] = $this->merchantId;
        $payload['sign'] = Signature::generate($payload, $this->secretKey);

        return $payload;
    }

    /**
     * SignQuery request payload.
     *
     * @param array $payload
     * @return array
     */
    protected function signQueryPayload(array $payload)
    {
        $payload['p1_mchtid'] = $this->merchantId;
        $payload['p2_signtype'] = '1';
        $payload['p4_version'] = 'v2.8';
        $payload['sign'] = Signature::generateQuery($payload, $this->secretKey);

        return $payload;
    }
}
