<?php
/**
 * Created by PhpStorm.
 * User: ligo
 * Date: 2020/4/17
 * Time: 16:07
 */

namespace Daishuwx\Wxofficial;


use Daishuwx\Base;
use Daishuwx\Config;

/**
 * 基础信息
 * Class Official
 * @package Daishuwx\Wxofficial
 */
class Official extends Base
{
    /**
     * 获取微信callback IP地址
     * @return array
     *User: ligo
     */
    public function callbackIp(){
        $url = 'https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token='.$this->token;
        $res = $this->curl_get($url);
        $res = json_decode($res,true);
        if(isset($res['ip_list'])){
            return [
                'status' => true,
                'msg'    => '请求成功',
                'data'   => $res,
            ];
        }else{
            $wxCode = Config::get('wx_code');
            $msg = (isset($wxCode[$res['errcode']]) ? $wxCode[$res['errcode']] : '请求失败');
            return [
                'status' => false,
                'msg'    => $msg,
                'data'   => $res,
            ];
        }
    }

    /**
     * 获取微信API接口 IP地址
     * @return array
     *User: ligo
     */
    public function apiDomainIp(){
        $url = 'https://api.weixin.qq.com/cgi-bin/get_api_domain_ip?access_token='.$this->token;
        $res = $this->curl_get($url);
        $res = json_decode($res,true);
        if(isset($res['ip_list'])){
            return [
                'status' => true,
                'msg'    => '请求成功',
                'data'   => $res,
            ];
        }else{
            $wxCode = Config::get('wx_code');
            $msg = (isset($wxCode[$res['errcode']]) ? $wxCode[$res['errcode']] : '请求失败');
            return [
                'status' => false,
                'msg'    => $msg,
                'data'   => $res,
            ];
        }
    }


    /**
     * 网络检测
     * @param string $action
     * @param string $checkOperator
     * @return array
     *User: ligo
     */
    public function callbackCheck($action = 'all',$checkOperator = 'DEFAULT'){
        $postData = [
            'action'  => $action,
            'check_operator'  => $checkOperator,
        ];
        $postData = json_encode($postData,JSON_UNESCAPED_UNICODE);

        $url = 'https://api.weixin.qq.com/cgi-bin/callback/check?access_token='.$this->token;
        $res = $this->curl_post($url,$postData);
        $res = json_decode($res,true);
        if($res['errcode'] == 0){
            return [
                'status' => true,
                'msg'    => '验证成功',
                'data'   => $res,
            ];
        }else{
            $wxCode = Config::get('wx_code');
            $msg = (isset($wxCode[$res['errcode']]) ? $wxCode[$res['errcode']] : '验证失败');
            return [
                'status' => false,
                'msg'    => $msg,
                'data'   => $res,
            ];
        }
    }

    /**
     * 消息验证
     * @param $signature
     * @param $timestamp
     * @param $nonce
     * @return bool
     *User: ligo
     */
    public function checkSignature($signature,$timestamp,$nonce)
    {
        $token = $this->token;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
}