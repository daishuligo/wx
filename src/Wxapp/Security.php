<?php
/**
 * Created by PhpStorm.
 * User: ligo
 * Date: 2019/12/26
 * Time: 15:42
 */

namespace Daishuwx\Wxapp;


use Daishuwx\Base;
use Daishuwx\Config;

class Security extends Base
{
    /**
     * 校验一张图片是否含有违法违规内容
     * @param $img 图片的本地路径
     * @return array
     *User: ligo
     */
    public function imgSecCheck($img){

        $cfile = new \CURLFile($img,'image/jpeg','test_name');

        $postData = array('name' => 'Foo',"file"=>$cfile);

        $token = $this->getToken();
        if($token === false){
            return [
                'status' => false,
                'msg'    => '获取token失败',
                'data'   => [],
            ];
        }

        $url = 'https://api.weixin.qq.com/wxa/img_sec_check?access_token='.$token;
        $res = $this->curl_post($url,$postData);
        $res = json_decode($res,true);
        if($res['errcode'] == 0){
            return [
                'status' => true,
                'msg'    => '内容合法',
                'data'   => $res,
            ];
        }else{
            $wxCode = Config::get('wx_code');
            $msg = (isset($wxCode[$res['errcode']]) ? $wxCode[$res['errcode']] : '内容违规');
            return [
                'status' => false,
                'msg'    => $msg,
                'data'   => $res,
            ];
        }
    }

    /**
     * 异步校验图片/音频是否含有违法违规内容
     * @param $media 要检测的多媒体url
     * @param int $type 1:音频;2:图片
     * @return array
     *User: ligo
     */
    public function mediaCheckAsync($media,$type = 2){
        if(!in_array($type,[1,2])){
            $type = 2;
        }

        $postData = [
            'media_url'  => $media,
            'media_type' => $type,
        ];

        $postData = json_encode($postData);

        $token = $this->getToken();
        if($token === false){
            return [
                'status' => false,
                'msg'    => '获取token失败',
                'data'   => [],
            ];
        }

        $url = 'https://api.weixin.qq.com/wxa/media_check_async?access_token='.$token;
        $res = $this->curl_post($url,$postData);
        $res = json_decode($res,true);
        if($res['errcode'] == 0){
            return [
                'status' => true,
                'msg'    => '检测已接收',
                'data'   => $res,
            ];
        }else{
            $wxCode = Config::get('wx_code');
            $msg = (isset($wxCode[$res['errcode']]) ? $wxCode[$res['errcode']] : '检测接收不成功');
            return [
                'status' => false,
                'msg'    => $msg,
                'data'   => $res,
            ];
        }
    }

    /**
     * 检查一段文本是否含有违法违规内容。
     * @param $content 内容
     * @return array
     *User: ligo
     */
    public function msgSecCheck($content){
        $postData = [
            'content'  => $content,
        ];
        $postData = json_encode($postData,JSON_UNESCAPED_UNICODE);

        $token = $this->getToken();
        if($token === false){
            return [
                'status' => false,
                'msg'    => '获取token失败',
                'data'   => [],
            ];
        }

        $url = 'https://api.weixin.qq.com/wxa/msg_sec_check?access_token='.$token;
        $res = $this->curl_post($url,$postData);
        $res = json_decode($res,true);
        if($res['errcode'] == 0){
            return [
                'status' => true,
                'msg'    => '内容正常',
                'data'   => $res,
            ];
        }else{
            $wxCode = Config::get('wx_code');
            $msg = (isset($wxCode[$res['errcode']]) ? $wxCode[$res['errcode']] : '内容违规');
            return [
                'status' => false,
                'msg'    => $msg,
                'data'   => $res,
            ];
        }
    }
}