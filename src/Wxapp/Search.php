<?php
/**
 * Created by PhpStorm.
 * User: ligo
 * Date: 2019/12/30
 * Time: 11:56
 */

namespace Daishuwx\Wxapp;


use Daishuwx\Base;

class Search extends Base
{
    /**
     * 小程序开发者可以通过本接口提交小程序页面url及参数信息，
     * 让微信可以更及时的收录到小程序的页面信息，
     * 开发者提交的页面信息将可能被用于小程序搜索结果展示
     * @param $pages 小程序页面信息列表
     * @return array
     *User: ligo
     */
    public function submitPages($pages){
        $token = $this->getToken();
        if($token === false){
            return [
                'status' => false,
                'msg'    => '获取token失败',
                'data'   => [],
            ];
        }

        $postData = [
            'pages'  => $pages,
        ];
        $postData = json_encode($postData,JSON_UNESCAPED_UNICODE);

        $url = 'https://api.weixin.qq.com/wxa/search/wxaapi_submitpages?access_token='.$token;
        $res = $this->curl_post($url,$postData);
        $res = json_decode($res,true);
        if($res['errcode'] == 0){
            return [
                'status' => true,
                'msg'    => '提交成功',
                'data'   => $res,
            ];
        }else{
            $wxCode = Config::get('wx_code');
            $msg = (isset($wxCode[$res['errcode']]) ? $wxCode[$res['errcode']] : '提交失败');
            return [
                'status' => false,
                'msg'    => $msg,
                'data'   => $res,
            ];
        }
    }
}