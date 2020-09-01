<?php
/**
 * Created by PhpStorm.
 * User: ligo
 * Date: 2019/12/30
 * Time: 11:56
 */

namespace Daishuwx\Wxapp;


use Daishuwx\Base;

/**
 * 小程序搜索
 * Class Search
 * @package Daishuwx\Wxapp
 */
class Search extends Base
{
    /**
     * 本接口提供基于小程序的站内搜商品图片搜索能力
     * @param $img  form-data中媒体文件标识，有filename、filelength、content-type等信息
     * @return array
     *User: ligo
     */
    public function imageSearch($img){
        $postData = [
            'img'  => $img,
        ];
        $postData = json_encode($postData,JSON_UNESCAPED_UNICODE);

        $url = 'https://api.weixin.qq.com/wxa/imagesearch?access_token='.$this->token;
        $res = $this->curl_post($url,$postData);
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
     * 小程序内部搜索API提供针对页面的查询能力
     * @param $keyword
     * @param string $nextPageInfo
     * @return array
     *User: ligo
     */
    public function siteSearch($keyword,$nextPageInfo = ''){
        $postData = [
            'keyword'  => $keyword,
            'next_page_info' => $nextPageInfo,
        ];
        $postData = json_encode($postData,JSON_UNESCAPED_UNICODE);

        $url = 'https://api.weixin.qq.com/wxa/sitesearch?access_token='.$this->token;
        $res = $this->curl_post($url,$postData);
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
     * 小程序开发者可以通过本接口提交小程序页面url及参数信息，
     * 让微信可以更及时的收录到小程序的页面信息，
     * 开发者提交的页面信息将可能被用于小程序搜索结果展示
     * @param $pages 小程序页面信息列表
     * @return array
     *User: ligo
     */
    public function submitPages($pages){
        $postData = [
            'pages'  => $pages,
        ];
        $postData = json_encode($postData,JSON_UNESCAPED_UNICODE);

        $url = 'https://api.weixin.qq.com/wxa/search/wxaapi_submitpages?access_token='.$this->token;
        $res = $this->curl_post($url,$postData);
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