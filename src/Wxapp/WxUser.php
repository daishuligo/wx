<?php
/**
 * Created by PhpStorm.
 * User: ligo
 * Date: 2019/12/26
 * Time: 10:29
 */

namespace Daishuwx\Wxapp;


use Daishuwx\Base;
use Daishuwx\Config;

class WxUser extends Base
{
    public function wxLogin($code){
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid='.$this->appId.'&secret='.$this->appSecret.'&js_code='.$code.'&grant_type=authorization_code';
        $res = $this->curl_get($url);
        $res = json_decode($res,true);
        if($res['errcode'] == 0){
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

    public function getPaidUnionId($openId){
        $token = $this->getToken();
        if($token === false){
            return [
                'status' => false,
                'mag'    => '获取token失败',
                'data'   => [],
            ];
        }

        $url = 'https://api.weixin.qq.com/wxa/getpaidunionid?access_token='.$token.'&openid='.$openId;
        $res = $this->curl_get($url);
        $res = json_decode($res,true);
        if($res['errcode'] == 0){
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
}