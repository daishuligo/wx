<?php
/**
 * Created by PhpStorm.
 * User: ligo
 * Date: 2019/12/26
 * Time: 14:05
 */

namespace Daishuwx\Wxapp;


use Daishuwx\Base;

class QRCode extends Base
{
    /**
     * 获取小程序码
     * 永久有效，有数量限制
     * @param $path 小程序路径
     * @param int $width 图片宽度
     * @return array|bool|string
     *User: ligo
     */
    public function createQRCode($path,$width = 430){
        if(!is_int($width)){
            return [
                'status' => false,
                'msg'    => '图片宽度必须为整数',
                'data'   => [],
            ];
        }

        $postData = [
            'path' => $path,
            'width' => $width,
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

        $url = 'https://api.weixin.qq.com/cgi-bin/wxaapp/createwxaqrcode?access_token='.$token;
        $res = $this->curl_post($url,$postData);
        $resEnd = json_decode($res,true);
        if(is_null($resEnd)){
            return $res;
        }else{
            return false;
        }
    }


    /**
     * 获取小程序码
     * 永久有效，有数量限制
     * @param $path 小程序路径
     * @param int $width 图片宽度
     * @param bool $autoColor 自动配置线条颜色
     * @param array $lineColor auto_color 为 false 时生效，使用 rgb 设置颜色
     * @param bool $isHyaline 是否需要透明底色
     * @return array|bool|string
     *User: ligo
     */
    public function get($path,$width = 430,$autoColor = false,$lineColor = ['r'=>0,'g'=>0,'b'=>0],$isHyaline = false){
        if(!is_int($width)){
            return [
                'status' => false,
                'msg'    => '图片宽度必须为整数',
                'data'   => [],
            ];
        }

        $postData = [
            'path' => $path,
            'width' => $width,
            'auto_color' => $autoColor,
            'line_color' => $lineColor,
            'is_hyaline' => $isHyaline,
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

        $url = 'https://api.weixin.qq.com/cgi-bin/wxaapp/createwxaqrcode?access_token='.$token;
        $res = $this->curl_post($url,$postData);
        $resEnd = json_decode($res,true);

        if(is_null($resEnd)){
            return $res;
        }else{
            return false;
        }
    }

    /**
     *获取小程序码
     *永久有效，数量暂无限制
     * @param $page 小程序路径
     * @param $scene 场景值
     * @param int $width 图片宽度
     * @param bool $autoColor 自动配置线条颜色
     * @param array $lineColor auto_color 为 false 时生效，使用 rgb 设置颜色
     * @param bool $isHyaline 是否需要透明底色
     * @return array|bool|string
     *User: ligo
     */
    public function getUnlimited($page,$scene = '',$width = 430,$autoColor = false,$lineColor = ['r'=>0,'g'=>0,'b'=>0],$isHyaline = false){
        if(!is_int($width)){
            return [
                'status' => false,
                'msg'    => '图片宽度必须为整数',
                'data'   => [],
            ];
        }

        $postData = [
            'scene' => $scene,
            'page' => $page,
            'width' => $width,
            'auto_color' => $autoColor,
            'line_color' => $lineColor,
            'is_hyaline' => $isHyaline,
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

        $url = 'https://api.weixin.qq.com/cgi-bin/wxaapp/createwxaqrcode?access_token='.$token;
        $res = $this->curl_post($url,$postData);
        $resEnd = json_decode($res,true);
        if(is_null($resEnd)){
            return $res;
        }else{
            return false;
        }
    }
}