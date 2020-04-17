<?php
/**
 * Created by PhpStorm.
 * User: ligo
 * Date: 2019/12/30
 * Time: 11:52
 */

namespace Daishuwx\Wxapp;


use Daishuwx\Base;

/**
 * 服务市场
 * Class ServiceMarket
 * @package Daishuwx\Wxapp
 */
class ServiceMarket extends Base
{
    /**
     * 调用服务平台提供的服务
     * @param $service  服务 ID
     * @param $api 接口名
     * @param $data 服务提供方接口定义的 JSON 格式的数据
     * @param $clientMsgId 随机字符串 ID，调用方请求的唯一标识
     * @return array
     *User: ligo
     */
    public function invokeService($service,$api,$data,$clientMsgId){
        $postData = [
            'service'  => $service,
            'api'  => $api,
            'data'  => $data,
            'client_msg_id'  => $clientMsgId,
        ];
        $postData = json_encode($postData,JSON_UNESCAPED_UNICODE);

        $url = 'https://api.weixin.qq.com/wxa/servicemarket?access_token='.$this->token;
        $res = $this->curl_post($url,$postData);
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