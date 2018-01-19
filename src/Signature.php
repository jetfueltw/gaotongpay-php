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
     * Generate query signature.
     *
     * @param array $payload
     * @param string $secretKey
     * @return string
     */
    public static function generateQuery(array $payload, $secretKey)
    {
        $baseString = self::buildBaseQueryString($payload).$secretKey;

        return self::md5Hash($baseString);
    }

    /**
     * Generate notify signature.
     *
     * @param array $payload
     * @param string $secretKey
     * @return string
     */
    public static function generateNotify(array $payload, $secretKey)
    {
        $baseString = self::buildBaseNotifyString($payload).$secretKey;

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

    public static function validateNotify(array $payload, $secretKey, $signature)
    {
        return self::generateNotify($payload, $secretKey) === $signature;
    }

    private static function buildBaseString(array $payload)
    {
        $baseString = "partner={$payload['partner']}&banktype={$payload['banktype']}&paymoney={$payload['paymoney']}&ordernumber={$payload['ordernumber']}&callbackurl={$payload['callbackurl']}";
        
        return $baseString;
    }

    private static function buildBaseQueryString(array $payload)
    {
        
        $baseString = "p1_mchtid={$payload['p1_mchtid']}&p2_signtype={$payload['p2_signtype']}&p3_orderno={$payload['p3_orderno']}&p4_version={$payload['p4_version']}";
        
        return $baseString;
    }

    private static function buildBaseNotifyString(array $payload)
    {
        
        $baseString = "partner={$payload['partner']}&ordernumber={$payload['ordernumber']}&orderstatus={$payload['orderstatus']}&paymoney={$payload['paymoney']}";
        
        return $baseString;
    }

    private static function md5Hash($data)
    {
        
        return md5($data);
    }
}
