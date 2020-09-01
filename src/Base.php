<?php
/**
 * Created by PhpStorm.
 * User: ligo
 * Date: 2019/12/26
 * Time: 9:52
 */

namespace Daishuwx;

use Daishuwx\Config;

class Base
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
     * 微信Token
     * @var string
     */
    public $token = '';

    public $config;

    public function __construct($appId = null,$appSecret = null,$token = null)
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
}