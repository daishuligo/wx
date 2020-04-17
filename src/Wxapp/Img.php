<?php
/**
 * Created by PhpStorm.
 * User: ligo
 * Date: 2020/4/16
 * Time: 15:48
 */

namespace Daishuwx\Wxapp;


use Daishuwx\Base;

class Img extends Base
{
    /**
     * 本接口提供基于小程序的图片智能裁剪能力
     * @param $imgUrl  要检测的图片 url，传这个则不用传 img 参数。
     * @param $img form-data 中媒体文件标识，有filename、filelength、content-type等信息，传这个则不用传 img_url。
     * @return array
     *User: ligo
     */
    public function aiCrop($imgUrl = '',$img = ''){
        $postData = [
            'img_url'  => $imgUrl,
            'img'  => $img,
        ];
        $postData = json_encode($postData,JSON_UNESCAPED_UNICODE);

        $url = 'https://api.weixin.qq.com/cv/ocr/bankcard?type=MODE&img_url=ENCODE_URL&access_token='.$this->token;
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

    /**
     * 本接口提供基于小程序的条码/二维码识别的API。
     * @param string $imgUrl
     * @param string $img
     * @return array
     *User: ligo
     */
    public function scanQRCode($imgUrl = '',$img = ''){
        $postData = [
            'img_url'  => $imgUrl,
            'img'  => $img,
        ];
        $postData = json_encode($postData,JSON_UNESCAPED_UNICODE);

        $url = 'https://api.weixin.qq.com/cv/img/qrcode?img_url=ENCODE_URL&access_token='.$this->token;
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

    /**
     * 本接口提供基于小程序的图片高清化能力。
     * @param string $imgUrl
     * @param string $img
     * @return array
     *User: ligo
     */
    public function superresolution($imgUrl = '',$img = ''){
        $postData = [
            'img_url'  => $imgUrl,
            'img'  => $img,
        ];
        $postData = json_encode($postData,JSON_UNESCAPED_UNICODE);

        $url = 'https://api.weixin.qq.com/cv/img/qrcode?img_url=ENCODE_URL&access_token='.$this->token;
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