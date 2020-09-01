<?php
/**
 * Created by PhpStorm.
 * User: ligo
 * Date: 2020/9/1
 * Time: 10:24
 */

namespace Daishuwx\Wxapp\broadcast;


use Daishuwx\Base;

class Room extends Base
{
    public function createRoom($postData)
    {
        $postData = json_encode($postData,JSON_UNESCAPED_UNICODE);
        $url = 'https://api.weixin.qq.com/wxaapi/broadcast/room/create?access_token='.$this->token;
        $res = $this->curl_post($url,$postData,$hearder);
        $res = json_decode($res,true);
        if(isset($res['errcode']) && $res['errcode'] == 0){
            return [
                'status' => true,
                'msg'    => '创建成功',
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

    public function getRoomList($start = 0, $limit = 10)
    {
        $postData = [
            'start' => $start,
            'limit' => $limit,
        ];

        $postData = json_encode($postData,JSON_UNESCAPED_UNICODE);
        $url = 'https://api.weixin.qq.com/wxa/business/getliveinfo?access_token='.$this->token;
        $res = $this->curl_post($url,$postData,$hearder);
        $res = json_decode($res,true);
        if(isset($res['errcode']) && $res['errcode'] == 0){
            return [
                'status' => true,
                'msg'    => '创建成功',
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

    public function getReplay($roomId = null, $start = 0, $limit = 10)
    {
        $postData = [
            'action' => 'get_replay',
            'room_id' => $roomId,
            'start' => $start,
            'limit' => $limit,
        ];

        $postData = json_encode($postData,JSON_UNESCAPED_UNICODE);
        $url = 'https://api.weixin.qq.com/wxa/business/getliveinfo?access_token='.$this->token;
        $res = $this->curl_post($url,$postData,$hearder);
        $res = json_decode($res,true);
        if(isset($res['errcode']) && $res['errcode'] == 0){
            return [
                'status' => true,
                'msg'    => '创建成功',
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

    public function addGoods($roomId = null, $goodsIds = [])
    {
        $postData = [
            'room_id' => $roomId,
            'ids' => $goodsIds,
        ];

        $postData = json_encode($postData,JSON_UNESCAPED_UNICODE);
        $url = 'https://api.weixin.qq.com/wxaapi/broadcast/room/addgoods?access_token='.$this->token;
        $res = $this->curl_post($url,$postData,$hearder);
        $res = json_decode($res,true);
        if(isset($res['errcode']) && $res['errcode'] == 0){
            return [
                'status' => true,
                'msg'    => '创建成功',
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