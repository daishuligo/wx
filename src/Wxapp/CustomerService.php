<?php
/**
 * Created by PhpStorm.
 * User: ligo
 * Date: 2019/12/30
 * Time: 15:08
 */

namespace Daishuwx\Wxapp;


use Daishuwx\Base;

class CustomerService extends Base
{

    public function send($touser,$msgtype,$data){
        $token = $this->getToken();
        if($token === false){
            return [
                'status' => false,
                'msg'    => '获取token失败',
                'data'   => [],
            ];
        }

        $postData = [
            'touser'  => $touser,
            'msgtype'  => $msgtype,
        ];

        if($msgtype = 'text'){
            if(isset($data['content']) && !empty($data['content'])){
                $postData['text']['content'] = $data['content'];
            }else{
                return [
                    'status' => false,
                    'msg'    => '文本内容不能为空',
                    'data'   => [],
                ];
            }
        }elseif ($msgtype = 'image'){
            if(isset($data['media_id']) && !empty($data['media_id'])){
                $postData['image']['media_id'] = $data['media_id'];
            }else{
                return [
                    'status' => false,
                    'msg'    => '图片的媒体ID不能为空',
                    'data'   => [],
                ];
            }
        }elseif ($msgtype = 'link'){
            if(isset($data['title'])){
                $postData['link']['title'] = $data['title'];
            }else{
                return [
                    'status' => false,
                    'msg'    => '消息标题未设置',
                    'data'   => [],
                ];
            }

            if(isset($data['description'])){
                $postData['link']['description'] = $data['description'];
            }else{
                return [
                    'status' => false,
                    'msg'    => '图文链接消息未设置',
                    'data'   => [],
                ];
            }

            if(isset($data['url'])){
                $postData['link']['url'] = $data['url'];
            }else{
                return [
                    'status' => false,
                    'msg'    => '跳转的链接未设置',
                    'data'   => [],
                ];
            }

            if(isset($data['thumb_url'])){
                $postData['link']['thumb_url'] = $data['thumb_url'];
            }else{
                return [
                    'status' => false,
                    'msg'    => '图片链接未设置',
                    'data'   => [],
                ];
            }
        }elseif ($msgtype = 'miniprogrampage '){
            if(isset($data['title'])){
                $postData['miniprogrampage ']['title'] = $data['title'];
            }else{
                return [
                    'status' => false,
                    'msg'    => '消息标题未设置',
                    'data'   => [],
                ];
            }

            if(isset($data['pagepath'])){
                $postData['miniprogrampage ']['pagepath'] = $data['pagepath'];
            }else{
                return [
                    'status' => false,
                    'msg'    => '小程序的页面路径未设置',
                    'data'   => [],
                ];
            }

            if(isset($data['thumb_media_id'])){
                $postData['miniprogrampage ']['thumb_media_id'] = $data['thumb_media_id'];
            }else{
                return [
                    'status' => false,
                    'msg'    => '消息卡片的封面未设置',
                    'data'   => [],
                ];
            }
        }else{
            return [
                'status' => false,
                'msg'    => '消息类型不存在',
                'data'   => [],
            ];
        }



        $postData = json_encode($postData,JSON_UNESCAPED_UNICODE);

        $url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$token;
        $res = $this->curl_post($url,$postData);
        $res = json_decode($res,true);
        if($res['errcode'] == 0){
            return [
                'status' => true,
                'msg'    => '发送成功',
                'data'   => $res,
            ];
        }else{
            $wxCode = Config::get('wx_code');
            $msg = (isset($wxCode[$res['errcode']]) ? $wxCode[$res['errcode']] : '发送失败');
            return [
                'status' => false,
                'msg'    => $msg,
                'data'   => $res,
            ];
        }
    }

    /**
     * 获取客服消息内的临时素材
     * @param $mediaId 媒体文件 ID
     * @return array
     *User: ligo
     */
    public function getTempMedia($mediaId){
        $token = $this->getToken();
        if($token === false){
            return [
                'status' => false,
                'mag'    => '获取token失败',
                'data'   => [],
            ];
        }

        $url = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token='.$token.'&media_id='.$mediaId;
        $res = $this->curl_get($url);
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

    /**
     * 下发客服当前输入状态给用户
     * @param $touser 用户的 OpenID
     * @param $command 命令 Typing	对用户下发"正在输入"状态；CancelTyping	取消对用户的"正在输入"状态
     * @return array
     *User: ligo
     */
    public function setTyping($touser,$command){
        $token = $this->getToken();
        if($token === false){
            return [
                'status' => false,
                'msg'    => '获取token失败',
                'data'   => [],
            ];
        }

        $postData = [
            'touser'  => $touser,
            'command'  => $command,
        ];
        $postData = json_encode($postData,JSON_UNESCAPED_UNICODE);

        $url = 'https://api.weixin.qq.com/cgi-bin/message/custom/typing?access_token='.$token;
        $res = $this->curl_post($url,$postData);
        $res = json_decode($res,true);
        if($res['errcode'] == 0){
            return [
                'status' => true,
                'msg'    => '设置成功',
                'data'   => $res,
            ];
        }else{
            $wxCode = Config::get('wx_code');
            $msg = (isset($wxCode[$res['errcode']]) ? $wxCode[$res['errcode']] : '设置失败');
            return [
                'status' => false,
                'msg'    => $msg,
                'data'   => $res,
            ];
        }
    }

    /**
     * 把媒体文件上传到微信服务器。目前仅支持图片。用于发送客服消息或被动回复用户消息。
     * @param $media form-data 中媒体文件标识，有filename、filelength、content-type等信息
     * @return array
     *User: ligo
     */
    public function uploadTempMedia($media){
        $token = $this->getToken();
        if($token === false){
            return [
                'status' => false,
                'msg'    => '获取token失败',
                'data'   => [],
            ];
        }

        $postData = [
            'media'  => '@'.$media,
        ];
        $postData = json_encode($postData,JSON_UNESCAPED_UNICODE);

        $url = 'https://api.weixin.qq.com/cgi-bin/media/upload?access_token='.$token.'&type=image';
        $res = $this->curl_post($url,$postData);
        $res = json_decode($res,true);
        if($res['errcode'] == 0){
            return [
                'status' => true,
                'msg'    => '上传成功',
                'data'   => $res,
            ];
        }else{
            $wxCode = Config::get('wx_code');
            $msg = (isset($wxCode[$res['errcode']]) ? $wxCode[$res['errcode']] : '上传失败');
            return [
                'status' => false,
                'msg'    => $msg,
                'data'   => $res,
            ];
        }
    }
}