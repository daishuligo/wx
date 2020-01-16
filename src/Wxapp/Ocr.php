<?php
/**
 * Created by PhpStorm.
 * User: ligo
 * Date: 2019/12/30
 * Time: 13:08
 */

namespace Daishuwx\Wxapp;


use Daishuwx\Base;

class Ocr extends Base
{
    /**
     * 本接口提供基于小程序的银行卡 OCR 识别
     * @param $img 图片url
     * @return array
     *User: ligo
     */
    public function bankCard($img){
        $token = $this->getToken();
        if($token === false){
            return [
                'status' => false,
                'msg'    => '获取token失败',
                'data'   => [],
            ];
        }

        $postData = [
            'img_url'  => $img,
        ];
        $postData = json_encode($postData,JSON_UNESCAPED_UNICODE);

        $url = 'https://api.weixin.qq.com/cv/ocr/bankcard?type=MODE&img_url=ENCODE_URL&access_token='.$token;
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
     * 本接口提供基于小程序的营业执照 OCR 识别
     * @param $img 图片url
     * @return array
     *User: ligo
     */
    public function businessLicense($img){
        $token = $this->getToken();
        if($token === false){
            return [
                'status' => false,
                'msg'    => '获取token失败',
                'data'   => [],
            ];
        }

        $postData = [
            'img_url'  => $img,
        ];
        $postData = json_encode($postData,JSON_UNESCAPED_UNICODE);

        $url = 'https://api.weixin.qq.com/cv/ocr/bizlicense?img_url=ENCODE_URL&access_token='.$token;
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
     * 本接口提供基于小程序的驾驶证 OCR 识别
     * @param $img 图片url
     * @return array
     *User: ligo
     */
    public function driverLicense($img){
        $token = $this->getToken();
        if($token === false){
            return [
                'status' => false,
                'msg'    => '获取token失败',
                'data'   => [],
            ];
        }

        $postData = [
            'img_url'  => $img,
        ];
        $postData = json_encode($postData,JSON_UNESCAPED_UNICODE);

        $url = 'https://api.weixin.qq.com/cv/ocr/drivinglicense?img_url=ENCODE_URL&access_token='.$token;
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
     * 本接口提供基于小程序的身份证 OCR 识别
     * @param $img 图片url
     * @return array
     *User: ligo
     */
    public function idcard($img){
        $token = $this->getToken();
        if($token === false){
            return [
                'status' => false,
                'msg'    => '获取token失败',
                'data'   => [],
            ];
        }

        $postData = [
            'img_url'  => $img,
        ];
        $postData = json_encode($postData,JSON_UNESCAPED_UNICODE);

        $url = 'https://api.weixin.qq.com/cv/ocr/idcard?type=MODE&img_url=ENCODE_URL&access_token='.$token;
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
     * 本接口提供基于小程序的通用印刷体 OCR 识别
     * @param $img 图片url
     * @return array
     *User: ligo
     */
    public function printedText($img){
    $token = $this->getToken();
    if($token === false){
        return [
            'status' => false,
            'msg'    => '获取token失败',
            'data'   => [],
        ];
    }

    $postData = [
        'img_url'  => $img,
    ];
    $postData = json_encode($postData,JSON_UNESCAPED_UNICODE);

    $url = 'https://api.weixin.qq.com/cv/ocr/comm?img_url=ENCODE_URL&access_token='.$token;
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
     * 本接口提供基于小程序的行驶证 OCR 识别
     * @param $img 图片url
     * @param string $type 图片识别模式，photo（拍照模式）或 scan（扫描模式）
     * @return array
     *User: ligo
     */
    public function vehicleLicense($img,$type = 'photo'){
        $token = $this->getToken();
        if($token === false){
            return [
                'status' => false,
                'msg'    => '获取token失败',
                'data'   => [],
            ];
        }

        $postData = [
            'img_url'  => $img,
            'type'     => $type,
        ];
        $postData = json_encode($postData,JSON_UNESCAPED_UNICODE);

        $url = 'https://api.weixin.qq.com/cv/ocr/driving?type=MODE&img_url=ENCODE_URL&access_token='.$token;
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