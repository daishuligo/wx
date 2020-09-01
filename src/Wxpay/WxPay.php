<?php
/**
 * Created by PhpStorm.
 * User: ligo
 * Date: 2019/12/30
 * Time: 14:26
 */

namespace Daishuwx\Wxpay;


use Daishuwx\PayBase;

/**
 * 微信支付
 * Class WxPay
 * @package Daishuwx\Wxpay
 */
class WxPay extends PayBase
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
        $time = microtime(true);
        // 生成随机字符串
        $nonceStr = md5($time.'#_pay@sign');
        // API参数
        $params = [
            'appid' => $this->appId,//公众账户ID
            'mch_id' => $this->mchId,//商户号
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
            'mch_id' => $this->mchId,//商户号
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
            'mch_id' => $this->mchId,//商户号
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
            'mch_id' => $this->mchId,//商户号
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
            'mch_id' => $this->mchId,//商户号
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
}