<?php
/**
 * Created by PhpStorm.
 * User: ligo
 * Date: 2019/12/26
 * Time: 10:29
 */

namespace Daishuwx\Wxapp;


use Daishuwx\Base;
use Daishuwx\ConfigBak;

/**
 * 微信用户信息
 * Class WxUser
 * @package Daishuwx\Wxapp
 */
class WxUser extends Base
{
    /**
     * 用户登录获取用户openid
     * @param $code 登录凭证
     * @return array
     *User: ligo
     */
    public function wxLogin($code){
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid='.$this->appId.'&secret='.$this->appSecret.'&js_code='.$code.'&grant_type=authorization_code';
        $res = $this->curl_get($url);
        $res = json_decode($res,true);
        if(isset($res['errcode']) && $res['errcode'] == 0){
            return [
                'status' => true,
                'msg'    => '请求成功',
                'data'   => $res,
            ];
        }else{
            $wxCode = $this->config->get('wxcode');
            if(isset($res['errcode'])){
                $msg = isset($wxCode[$res['errcode']]) ? $wxCode[$res['errcode']] : (isset($res['errmsg']) ? $res['errmsg'] : '请求失败');
            }else{
                $msg = '请求失败';
            }
            return [
                'status' => false,
                'msg'    => $msg,
                'data'   => $res,
            ];
        }
    }

    /**
     * 通过openid获取UnionId
     * @param $openId
     * @return array
     *User: ligo
     */
    public function getPaidUnionId($openId)
    {
        $url = 'https://api.weixin.qq.com/wxa/getpaidunionid?access_token='.$this->token.'&openid='.$openId;
        $res = $this->curl_get($url);
        $res = json_decode($res,true);
        if(isset($res['errcode']) && $res['errcode'] == 0){
            return [
                'status' => true,
                'msg'    => '请求成功',
                'data'   => $res,
            ];
        }else{
            $wxCode = $this->config->get('wxcode');
            if(isset($res['errcode'])){
                $msg = isset($wxCode[$res['errcode']]) ? $wxCode[$res['errcode']] : (isset($res['errmsg']) ? $res['errmsg'] : '请求失败');
            }else{
                $msg = '请求失败';
            }
            return [
                'status' => false,
                'msg'    => $msg,
                'data'   => $res,
            ];
        }
    }
}