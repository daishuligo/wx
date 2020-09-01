<?php
/**
 * Created by PhpStorm.
 * User: ligo
 * Date: 2019/12/26
 * Time: 9:52
 */

namespace Daishuwx;

use Daishuwx\Config;

class PayBase
{
    /**
     *微信appId
     * @var string
     */
    public $appId = '';

    /**
     * 微信app密码
     * @var string
     */
    public $appSecret = '';

    /**
     * 商户id
     * @var string
     */
    public $mchId = '';

    /**
     * 秘钥
     * @var string
     */
    public $key = '';

    public $config;

    public function __construct($appId = null,$appSecret = null,$mchId = null, $key = null)
    {
        $this->config = new Config();

        if (!is_null($appId)){
            $this->appId = $appId;
        }

        if (!is_null($appSecret)){
            $this->appSecret = $appSecret;
        }

        if(!is_null($token)){
            $this->token = $token;
        }
    }

    public function getToken(){
        if($this->token){
           return $this->token; 
        }
        
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->appId.'&secret='.$this->appSecret;
        $res = $this->curl_get($url);
        $res = json_decode($res,true);
        if(isset($res['access_token'])){
            $this->token = $res['access_token'];
            return $res;
        }else{
            return false;
        }
    }

    public function setToken($token){
        $this->token = $token;
        return true;
    }

    public function curl_get($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $SSL = substr($url, 0, 8) == "https://" ? true : false;
        if ($SSL) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证书
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 检查证书中是否设置域名
        }
        $content = curl_exec($ch);
        curl_close($ch);
        return $content;
    }

    public function curl_post($url, $post_data,$header=[]){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS,$post_data);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER,$header);
        $SSL = substr($url, 0, 8) == "https://" ? true : false;
        if ($SSL) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证书
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); // 检查证书中是否设置域名
        }
        $res = curl_exec($curl);
        curl_close($curl);
        return $res;
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
        $string = $string . '&key=' . $this->key;

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
    protected function makeSign($values)
    {
        //签名步骤一：按字典序排序参数
        ksort($values);
        $string = $this->toUrlParams($values);
        //签名步骤二：在string后加入KEY
        $string = $string . '&key=' .$this->key;//支付商户账户获取key
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
    protected function fromXml($xml)
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
    protected function toXml($values)
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
    protected function postXmlCurl($url,$xml,$is_cert = false, $second = 30)
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