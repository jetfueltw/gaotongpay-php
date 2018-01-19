## 介紹

高通支付 PHP 版本封裝。

## 安裝

使用 Composer 安裝。

```
composer require jetfueltw/gaotongpay-php
```

## 使用方法

### 掃碼支付下單

使用微信支付、QQ錢包掃碼支付，下單後返回 QR Code網址。


```
$merchantId = 'XXXXXXXXXXXXXXX'; // 商家號
$secretKey = 'XXXXXXXXXXXXXXX'; // md5 密鑰
$tradeNo = '20180109023351XXXXX'; // 商家產生的唯一訂單號
$channel = Channel::WECHAT; // 支付通道，支援微信支付、QQ錢包
$amount = 1.00; // 消費金額 (元)
$clientIp = 'XXX.XXX.XXX.XXX'; // 消費者端 IP 位址
$notifyUrl = 'https://XXX.XXX.XXX'; // 交易完成後異步通知接口

```
```
$payment = new DigitalPayment(merchantId, secretKey);
$result = $payment->order($tradeNo, $channel, $amount, $clientIp, $notifyUrl);
```
```
Result:
[
    'qrcodeImgUrl' =>'https://wgtj.gaotongpay.com/zfapi/order/getqrcode?orderid=18573XXXX&sign=XXX', // QR Code 圖片網址
];
```

### 掃碼支付交易成功通知

消費者支付成功後，平台會發出 HTTP POST 請求到你下單時填的 $notifyUrl，商家在收到通知並處理完後必須回應 `ok`，否則平臺會認為通知失敗，再次反覆向 callbackurl 發送結果，直到商戶返回“ok”或者達到和商戶約定的重複發送次數。

* 商家必需正確處理重複通知的情況。
* 能使用 `NotifyWebhook@successNotifyResponse` 返回成功回應。  
* 務必使用 `NotifyWebhook@verifyNotifyPayload` 驗證簽證是否正確。
* 通知的消費金額單位為 `元`。 

```
Post Data: 
[
    'partner' => 'XXXXXXXXXXXXXXX'; // 商家號
    'ordernumber' => '20180109023351XXXXX'; // 商家產生的唯一訂單號
    'orderstatus' => 'X'; //1:支付成功，非1為支付失敗
    'paymoney'  => 1.00; //元
    'sysnumber' => 'XXXXXXXXXXXXX' //此次交易中高通支付介面系統內的訂單ID
    'attach' => 'XXXXXXXXXX' // 備註訊息
    'sign' => 'XXXXXXXXXXXXXXXXXXXXXX' // md5簽名
]
```

### 掃碼支付訂單查詢

使用商家訂單號查詢單筆訂單狀態。

```
$merchantId = 'XXXXXXXXXXXXXXX'; // 商家號
$secretKey = 'XXXXXXXXXXXXXXX'; // md5 密鑰
$tradeNo = '20180109023351XXXXX'; // 商家產生的唯一訂單號

```
```
$tradeQuery = new TradeQuery(merchantId, secretKey);
$result = $tradeQuery->find($tradeNo);
```
```
Result:
[
    'data' => //如果查詢失敗此欄會回傳 null
      [
        'r1_mchtid' => 'XXXXXXXXXXXXXXX'; // 商家號
        'r2_systemorderno' => 'XXXXXXXXXXXXXXXXX' //平台唯一流水号
        'r3_orderno' => '20180109023351XXXXX'; // 商家產生的唯一訂單號
        'r4_amount' => 1.00; //订单交易金额
        'r5_orderstate' => 'X'; //0.支付中 1.成功,2.失败，3.失败
        'r6_version' => 'v2.8';
        'sign' => 'XXXXXXXXXXXXXXXXXXXXXX' // md5簽名
      ]
    'rspCode' => '1' //1表示成功
    'rspMsg' => 'XXXXXX' // 回覆訊息
]
```

### 掃碼支付訂單支付成功查詢

使用商家訂單號查詢單筆訂單是否支付成功。

```
$merchantId = 'XXXXXXXXXXXXXXX'; // 商家號
$secretKey = 'XXXXXXXXXXXXXXX'; // SHA 密鑰
$tradeNo = '20180109023351XXXXX'; // 商家產生的唯一訂單號
```
```
$tradeQuery = new TradeQuery($merchantId, $secretKey);
$result = $tradeQuery->isPaid($tradeNo);
```
```
Result:
bool(true|false)
