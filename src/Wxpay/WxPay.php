<?php
/**
 * Created by PhpStorm.
 * User: ligo
 * Date: 2019/12/30
 * Time: 14:26
 */

namespace Daishuwx\Wxpay;


use Daishuwx\Base;
use Daishuwx\Config;

/**
 * 微信支付
 * Class WxPay
 * @package Daishuwx\Wxpay
 */
class WxPay extends Base
{
    /**
     * 统一下单
     * @param $orderNo 订单号
     * @param $totalFee 总费用
     * @param string $tradeType 支付类型 JSAPI -JSAPI支付 NATIVE -Native支付  APP -APP支付
     * @param string $openid  trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识
     * @param string $attach 附加数据
     * @param string $body 商品描述
     * @param string $notifyUrl 异步通知地址，支付成功后微信支付异步回调地址
     * @return bool|mixed
     *User: ligo
     */
    public function unifiedOrder($orderNo,$totalFee,$tradeType = 'NATIVE',$openid = '',$attach = '',$body = '',$notifyUrl = ''){
        // 当前时间
        $time = time();
        // 生成随机字符串
        $nonceStr = md5($time.'#_pay@sign');
        // API参数
        $params = [
            'appid' => $this->appId,//公众账户ID
            'mch_id' => Config::get('mch_id'),//商户号
            'attach' => $attach,//附加数据
            'body' => $body,//商品描述
            'nonce_str' => $nonceStr,//随机字符串
            'sign_type' => 'MD5',//加密方式默认MD5可以省略
            'notify_url' => $notifyUrl,  // 异步通知地址，支付成功后微信支付异步回调地址
            'out_trade_no' => $orderNo,//商户订单号；如果有修改价格的需求建议表中单独设立微信支付编号；注意保证唯一性
            'spbill_create_ip' => \request()->ip(),//终端IP
            'total_fee' => $totalFee * 100, // 价格:单位分，默认币种人民币
            'trade_type' => $tradeType,
        ];

        //如果为JSAPI支付
        if($tradeType == 'JSAPI'){
            $params['openid'] = $openid;
        }

        // 生成签名
        $params['sign'] = $this->makeSign($params);

        // 请求API
        $url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
        $params = $this->toXml($params);
        $result = $this->postXmlCurl($url,$params);
        $prepay = $this->fromXml($result);//格式转换

        // 请求失败;只有返回数据return_code和result_code都为SUCCESS统一下单才成功
        if ($prepay['return_code'] !== 'SUCCESS' || $prepay['result_code'] !== 'SUCCESS') {
            return false;
        }

        return $prepay;
    }

    /**
     * 提交付款码支付
     * @param $orderNo
     * @param $totalFee
     * @param $authCode
     * @param string $deviceInfo
     * @param string $attach
     * @param string $body
     * @return bool|mixed
     *User: ligo
     */
    public function micropay($orderNo,$totalFee,$authCode,$deviceInfo = '',$attach = '',$body = ''){
        // 当前时间
        $time = time();
        // 生成随机字符串
        $nonceStr = md5($time.'#_pay@sign');
        // API参数
        $params = [
            'appid' => $this->appId,//公众账户ID
            'mch_id' => Config::get('mch_id'),//商户号
            'device_info' => $deviceInfo,//终端设备号(商户自定义，如门店编号)
            'attach' => $attach,//附加数据
            'body' => $body,//商品描述
            'nonce_str' => $nonceStr,//随机字符串
            'sign_type' => 'MD5',//加密方式默认MD5可以省略
            'out_trade_no' => $orderNo,//商户订单号；如果有修改价格的需求建议表中单独设立微信支付编号；注意保证唯一性
            'spbill_create_ip' => \request()->ip(),//终端IP
            'total_fee' => $totalFee * 100, // 价格:单位分，默认币种人民币
            'auth_code' => $authCode,
        ];


        // 生成签名
        $params['sign'] = $this->makeSign($params);

        // 请求API
        $url = 'https://api.mch.weixin.qq.com/pay/micropay';
        $params = $this->toXml($params);
        $result = $this->postXmlCurl($url,$params);
        $prepay = $this->fromXml($result);//格式转换

        // 请求失败;只有返回数据return_code和result_code都为SUCCESS统一下单才成功
        if ($prepay['return_code'] !== 'SUCCESS' || $prepay['result_code'] !== 'SUCCESS') {
            return false;
        }

        return $prepay;
    }

    /**
     * @param $wxpayNo 支付单号
     * @return bool|mixed
     *User: ligo
     */
    public function orderQuery($wxpayNo)
    {
        // 当前时间
        $time = time();
        // 生成随机字符串
        $nonceStr = md5($time.'#_pay@sign');
        // API参数
        $params = [
            'appid' => $this->appId,//公众账户ID
            'mch_id' => Config::get('mach_id'),//商户号
            'nonce_str' => $nonceStr,
            'out_trade_no' => $wxpayNo,
        ];

        // 生成签名
        $params['sign'] = $this->makeSign($params);
        // 请求API
        $url = 'https://api.mch.weixin.qq.com/pay/orderquery';
        $params = $this->toXml($params);
        $result = $this->postXmlCurl($url,$params);
        $prepay = $this->fromXml($result);

        if($prepay['return_code'] != 'SUCCESS'){
            return false;
        }

        return $prepay;
    }

    /**
     * 关闭订单
     * @param $wxpayNo 支付单号
     * @return bool|mixed
     *User: ligo
     */
    public function closeOrder($wxpayNo)
    {
        // 当前时间
        $time = time();
        // 生成随机字符串
        $nonceStr = md5($time.'#_pay@sign');
        // API参数
        $params = [
            'appid' => $this->appId,//公众账户ID
            'mch_id' => Config::get('mch_id'),//商户号
            'nonce_str' => $nonceStr,
            'out_trade_no' => $wxpayNo,
        ];

        // 生成签名
        $params['sign'] = $this->makeSign($params);
        // 请求API
        $url = 'https://api.mch.weixin.qq.com/pay/closeorder';
        $params = $this->toXml($params);
        $result = $this->postXmlCurl($url,$params);
        $prepay = $this->fromXml($result);
        if($prepay['return_code'] != 'SUCCESS' || $prepay['result_code'] != 'SUCCESS'){
            return false;
        }

        return $prepay;
    }

    /**
     * 申请退款
     * @param $wxpayNo 微信支付号
     * @param $outRefundNo 退款编号
     * @param $totalFee 单号总金额
     * @param $refundFee 退款金额
     * @param string $notifyUrl 退款成功通知地址
     * @return bool|mixed
     *User: ligo
     */
    public function refund($wxpayNo,$outRefundNo,$totalFee,$refundFee,$notifyUrl = '')
    {
        // 当前时间
        $time = time();
        // 生成随机字符串
        $nonceStr = md5($time.'#_pay@sign');
        // API参数
        $params = [
            'appid' => $this->appId,//公众账户ID
            'mch_id' => Config::get('mch_id'),//商户号
            'nonce_str' => $nonceStr,//随机字符串
            'out_trade_no' => $wxpayNo,//微信支付号
            'out_refund_no' => $outRefundNo,//退款编号
            'total_fee' => $totalFee * 100,//单号总金额
            'refund_fee' => $refundFee * 100,//退款金额，不能超过总金额
            'notify_url' =>  $notifyUrl,//退款成功通知地址
        ];

        // 生成签名
        $params['sign'] = $this->makeSign($params);

        // 请求API
        $url = 'https://api.mch.weixin.qq.com/secapi/pay/refund';
        $params = $this->toXml($params);
        $result = $this->postXmlCurl($url,$params,true);
        $prepay = $this->fromXml($result);
        if($prepay['return_code'] != 'SUCCESS' || $prepay['result_code'] != 'SUCCESS'){
            return false;
        }
        return $prepay;
    }

    /**
     * 请求支付的调用
     * @param $nonceStr
     * @param $prepayId
     * @param $timeStamp
     * @return string
     *User: ligo
     */
    public function makePaySign($nonceStr, $prepayId, $timeStamp)
    {
        $data = [
            'appId' => $this->appId,//公众账户ID
            'nonceStr' => $nonceStr,//随机字符串，与调起支付时相同
            'package' => 'prepay_id=' . $prepayId,//统一下单返回的prepay_id
            'signType' => 'MD5',
            'timeStamp' => $timeStamp,//时间戳，与调起支付时相同
        ];

        //签名步骤一：按字典序排序参数
        ksort($data);

        $string = $this->toUrlParams($data);

        //签名步骤二：在string后加入KEY
        $string = $string . '&key=' . config('wxpay.key');

        //签名步骤三：MD5加密
        $string = md5($string);

        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);

        return $result;
    }


    /**
     * 签名
     * @param $values
     * @return string
     *User: ligo
     */
    private function makeSign($values)
    {
        //签名步骤一：按字典序排序参数
        ksort($values);
        $string = $this->toUrlParams($values);
        //签名步骤二：在string后加入KEY
        $string = $string . '&key=' .Config::get('wxpay_key');//支付商户账户获取key
        //签名步骤三：MD5加密
        $string = md5($string);
        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);
        return $result;
    }


    /**
     * 将xml转数组
     * @param $xml
     * @return mixed
     *User: ligo
     */
    private function fromXml($xml)
    {
        // 禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    }

    /**
     * 将数组转xml
     * @param $values
     * @return bool|string
     *User: ligo
     */
    private function toXml($values)
    {
        if (!is_array($values)
            || count($values) <= 0
        ) {
            return false;
        }

        $xml = "<xml>";
        foreach ($values as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }

    /**
     * 进行POST请求
     * @param $url
     * @param $xml
     * @param bool $is_cert
     * @param int $second
     * @return bool|string
     *User: ligo
     */
    private function postXmlCurl($url,$xml,$is_cert = false, $second = 30)
    {
        $ch = curl_init();
        // 设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);//严格校验
        // 设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        // 要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        // post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);

        if($is_cert){
            curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM');
            curl_setopt($ch, CURLOPT_SSLCERT, __DIR__.'../data/wx/cert/apiclient_cert.pem');//证书的物理绝对路径
            //默认格式为PEM，可以注释
            curl_setopt($ch, CURLOPT_SSLKEYTYPE, 'PEM');
            curl_setopt($ch, CURLOPT_SSLKEY, __DIR__.'../data/wx/cert/apiclient_key.pem');//证书的物理绝对路径
        }

        // 运行curl
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;

    }
}