<?php
/**
 * Created by PhpStorm.
 * User: ligo
 * Date: 2020/9/1
 * Time: 10:50
 */

namespace Daishuwx\Wxapp\broadcast;


use Daishuwx\Base;

class Goods extends Base
{
    public function addGoods($data)
    {
        $postData = [
            'goodsInfo' => $data,
        ];

        $postData = json_encode($postData,JSON_UNESCAPED_UNICODE);
        $url = 'https://api.weixin.qq.com/wxaapi/broadcast/goods/add?access_token='.$this->token;
        $res = $this->curl_post($url,$postData,$hearder);
        $res = json_decode($res,true);
        if(isset($res['errcode']) && $res['errcode'] == 0){
            return [
                'status' => true,
                'msg'    => '新增成功',
                'data'   => $res,
            ];
        }else{
            $wxCode = $this->config->get('wxcode');
            if(isset($res['errcode'])){
                $msg = isset($wxCode[$res['errcode']]) ? $wxCode[$res['errcode']] : (isset($res['errmsg']) ? $res['errmsg'] : '新增失败');
            }else{
                $msg = '新增失败';
            }
            return [
                'status' => false,
                'msg'    => $msg,
                'data'   => $res,
            ];
        }
    }

    public function restaudit($goodsId = null)
    {
        $postData = [
            'auditId' => 525022184,
            'goodsId' => $goodsId,
        ];

        $postData = json_encode($postData,JSON_UNESCAPED_UNICODE);
        $url = 'https://api.weixin.qq.com/wxaapi/broadcast/goods/resetaudit?access_token='.$this->token;
        $res = $this->curl_post($url,$postData,$hearder);
        $res = json_decode($res,true);
        if(isset($res['errcode']) && $res['errcode'] == 0){
            return [
                'status' => true,
                'msg'    => '提交成功',
                'data'   => $res,
            ];
        }else{
            $wxCode = $this->config->get('wxcode');
            if(isset($res['errcode'])){
                $msg = isset($wxCode[$res['errcode']]) ? $wxCode[$res['errcode']] : (isset($res['errmsg']) ? $res['errmsg'] : '提交失败');
            }else{
                $msg = '提交失败';
            }
            return [
                'status' => false,
                'msg'    => $msg,
                'data'   => $res,
            ];
        }
    }

    public function reVerify($goodsId = null)
    {
        $postData = [
            'goodsId' => $goodsId,
        ];

        $postData = json_encode($postData,JSON_UNESCAPED_UNICODE);
        $url = 'https://api.weixin.qq.com/wxaapi/broadcast/goods/audit?access_token='.$this->token;
        $res = $this->curl_post($url,$postData,$hearder);
        $res = json_decode($res,true);
        if(isset($res['errcode']) && $res['errcode'] == 0){
            return [
                'status' => true,
                'msg'    => '提交成功',
                'data'   => $res,
            ];
        }else{
            $wxCode = $this->config->get('wxcode');
            if(isset($res['errcode'])){
                $msg = isset($wxCode[$res['errcode']]) ? $wxCode[$res['errcode']] : (isset($res['errmsg']) ? $res['errmsg'] : '提交失败');
            }else{
                $msg = '提交失败';
            }
            return [
                'status' => false,
                'msg'    => $msg,
                'data'   => $res,
            ];
        }
    }

    public function delete($goodsId)
    {
        $postData = [
            'goodsId' => $goodsId,
        ];

        $postData = json_encode($postData,JSON_UNESCAPED_UNICODE);
        $url = 'https://api.weixin.qq.com/wxaapi/broadcast/goods/delete?access_token='.$this->token;
        $res = $this->curl_post($url,$postData,$hearder);
        $res = json_decode($res,true);
        if(isset($res['errcode']) && $res['errcode'] == 0){
            return [
                'status' => true,
                'msg'    => '删除成功',
                'data'   => $res,
            ];
        }else{
            $wxCode = $this->config->get('wxcode');
            if(isset($res['errcode'])){
                $msg = isset($wxCode[$res['errcode']]) ? $wxCode[$res['errcode']] : (isset($res['errmsg']) ? $res['errmsg'] : '删除失败');
            }else{
                $msg = '删除失败';
            }
            return [
                'status' => false,
                'msg'    => $msg,
                'data'   => $res,
            ];
        }
    }

    public function update($data)
    {
        $postData = [
            'goodsInfo' => $data,
        ];

        $postData = json_encode($postData,JSON_UNESCAPED_UNICODE);
        $url = 'https://api.weixin.qq.com/wxaapi/broadcast/goods/update?access_token='.$this->token;
        $res = $this->curl_post($url,$postData,$hearder);
        $res = json_decode($res,true);
        if(isset($res['errcode']) && $res['errcode'] == 0){
            return [
                'status' => true,
                'msg'    => '新增成功',
                'data'   => $res,
            ];
        }else{
            $wxCode = $this->config->get('wxcode');
            if(isset($res['errcode'])){
                $msg = isset($wxCode[$res['errcode']]) ? $wxCode[$res['errcode']] : (isset($res['errmsg']) ? $res['errmsg'] : '新增失败');
            }else{
                $msg = '新增失败';
            }
            return [
                'status' => false,
                'msg'    => $msg,
                'data'   => $res,
            ];
        }
    }

    public function getGoodsStatus($goodsIds = [])
    {
        $postData = [
            'goods_ids' => $goodsIds,
        ];

        $postData = json_encode($postData,JSON_UNESCAPED_UNICODE);
        $url = 'https://api.weixin.qq.com/wxa/business/getgoodswarehouse?access_token='.$this->token;
        $res = $this->curl_post($url,$postData,$hearder);
        $res = json_decode($res,true);
        if(isset($res['errcode']) && $res['errcode'] == 0){
            return [
                'status' => true,
                'msg'    => '新增成功',
                'data'   => $res,
            ];
        }else{
            $wxCode = $this->config->get('wxcode');
            if(isset($res['errcode'])){
                $msg = isset($wxCode[$res['errcode']]) ? $wxCode[$res['errcode']] : (isset($res['errmsg']) ? $res['errmsg'] : '新增失败');
            }else{
                $msg = '新增失败';
            }
            return [
                'status' => false,
                'msg'    => $msg,
                'data'   => $res,
            ];
        }
    }

    public function getList($status = 2, $start = 0, $limit = 10)
    {
        $url = 'https://api.weixin.qq.com/wxa/business/getgoodswarehouse?access_token='.$this->token.'&offset='.$start.'&limit='.$limit.'&status='.$start;
        $res = $this->curl_get($url);
        $res = json_decode($res,true);
        if(isset($res['errcode']) && $res['errcode'] == 0){
            return [
                'status' => true,
                'msg'    => '获取成功',
                'data'   => $res,
            ];
        }else{
            $wxCode = $this->config->get('wxcode');
            if(isset($res['errcode'])){
                $msg = isset($wxCode[$res['errcode']]) ? $wxCode[$res['errcode']] : (isset($res['errmsg']) ? $res['errmsg'] : '获取失败');
            }else{
                $msg = '获取失败';
            }
            return [
                'status' => false,
                'msg'    => $msg,
                'data'   => $res,
            ];
        }
    }
}