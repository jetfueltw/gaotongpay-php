<?php

namespace Jetfuel\Gaotongpay;

class Signature
{
    /**
     * Generate signature.
     *
     * @param array $payload
     * @param string $secretKey
     * @return string
     */
    public static function generate(array $payload, $secretKey)
    {
        $baseString = self::buildBaseString($payload).$secretKey;

        return self::md5Hash($baseString);
    }

    /**
     * @param array $payload
     * @param string $secretKey
     * @param string $signature
     * @return bool
     */
    public static function validate(array $payload, $secretKey, $signature)
    {
        return self::generate($payload, $secretKey) === $signature;
    }

    private static function buildBaseString(array $payload)
    {
        $baseString = "partner={$payload['partner']}&banktype={$payload['banktype']}&paymoney={$payload['paymoney']}&ordernumber={$payload['ordernumber']}&callbackurl={$payload['callbackurl']}";

        return $baseString;
    }

    private static function md5Hash($data)
    {
        return md5($data);
    }
}
