<?php
/**
 * Created by PhpStorm.
 * User: ligo
 * Date: 2020/7/22
 * Time: 17:11
 */

namespace Daishuwx\Wxofficial;


use Daishuwx\Base;

class Media extends Base
{
    public function uploadTempFile($fileUrl = '', $type = '')
    {
        if(class_exists('\CURLFile')){
            $postData = [
                'media' => new \CURLFile($fileUrl),
            ];
        }else{
            $postData = [
                'media' => '@' . realpath($fileUrl),
            ];
        }

        $url = 'https://api.weixin.qq.com/cgi-bin/media/upload?access_token='.$this->token.'&type='.$type;
        $res = $this->curl_post($url,$postData);
        $res = json_decode($res,true);
        if (isset($res['media_id'])){
            return [
                'status' => true,
                'msg'    => '上传成功',
                'data'   => $res,
            ];
        }else{
            $officialCode = $this->config->get('officialcode');
            if(isset($res['errcode'])){
                $msg = isset($wxCode[$res['errcode']]) ? $officialCode[$res['errcode']] : (isset($res['errmsg']) ? $res['errmsg'] : '请求失败');
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

    public function getTempFile($mediaId)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token='.$this->token.'&media_id='.$mediaId;
        $res = $this->curl_get($url);
        $res = json_decode($res,true);
        if (isset($res['video_url'])){
            return [
                'status' => true,
                'msg'    => '获取成功',
                'data'   => $res,
            ];
        }else{
            $officialCode = $this->config->get('officialcode');
            if(isset($res['errcode'])){
                $msg = isset($wxCode[$res['errcode']]) ? $officialCode[$res['errcode']] : (isset($res['errmsg']) ? $res['errmsg'] : '获取失败');
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

    public function uploadFile($fileUrl = '', $type = '')
    {
        if(class_exists('\CURLFile')){
            $postData = [
                'media' => new \CURLFile($fileUrl),
            ];
        }else{
            $postData = [
                'media' => '@' . realpath($fileUrl),
            ];
        }

        $url = 'https https://api.weixin.qq.com/cgi-bin/material/add_material?access_token='.$this->token.'&type='.$type;
        $res = $this->curl_post($url,$postData);
        $res = json_decode($res,true);
        if (isset($res['media_id'])){
            return [
                'status' => true,
                'msg'    => '上传成功',
                'data'   => $res,
            ];
        }else{
            $officialCode = $this->config->get('officialcode');
            if(isset($res['errcode'])){
                $msg = isset($wxCode[$res['errcode']]) ? $officialCode[$res['errcode']] : (isset($res['errmsg']) ? $res['errmsg'] : '请求失败');
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

    public function getFile($mediaId)
    {
        $postData = [
            'media_id' => $mediaId,
        ];
        $postData = json_encode($postData,JSON_UNESCAPED_UNICODE);
        $url = 'https https://api.weixin.qq.com/cgi-bin/material/add_material?access_token='.$this->token.'&type='.$type;
        $res = $this->curl_post($url,$postData);
        $res = json_decode($res,true);
        if ($res && (!isset($res['errcode']) || (isset($res['errcode']) && $res['errcode'] == 0))){
            return [
                'status' => true,
                'msg'    => '获取成功',
                'data'   => $res,
            ];
        }else{
            $officialCode = $this->config->get('officialcode');
            if(isset($res['errcode'])){
                $msg = isset($wxCode[$res['errcode']]) ? $officialCode[$res['errcode']] : (isset($res['errmsg']) ? $res['errmsg'] : '获取失败');
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


    public function delFile($mediaId)
    {
        $postData = [
            'media_id' => $mediaId,
        ];
        $postData = json_encode($postData,JSON_UNESCAPED_UNICODE);
        $url = 'https://api.weixin.qq.com/cgi-bin/material/del_material?access_token='.$this->token;
        $res = $this->curl_post($url,$postData);
        $res = json_decode($res,true);
        if ($res && (!isset($res['errcode']) || (isset($res['errcode']) && $res['errcode'] == 0))){
            return [
                'status' => true,
                'msg'    => '删除成功',
                'data'   => $res,
            ];
        }else{
            $officialCode = $this->config->get('officialcode');
            if(isset($res['errcode'])){
                $msg = isset($wxCode[$res['errcode']]) ? $officialCode[$res['errcode']] : (isset($res['errmsg']) ? $res['errmsg'] : '删除失败');
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

    public function getMaterialCount()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/material/get_materialcount?access_token='.$this->token;
        $res = $this->curl_get($url);
        $res = json_decode($res,true);
        if ($res && (!isset($res['errcode']) || (isset($res['errcode']) && $res['errcode'] == 0))){
            return [
                'status' => true,
                'msg'    => '获取成功',
                'data'   => $res,
            ];
        }else{
            $officialCode = $this->config->get('officialcode');
            if(isset($res['errcode'])){
                $msg = isset($wxCode[$res['errcode']]) ? $officialCode[$res['errcode']] : (isset($res['errmsg']) ? $res['errmsg'] : '获取失败');
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

    public function getmaterialList($type, $page = 1, $size = 15)
    {
        $postData = [
            'type' => $mediaId,
            'offset' => ($page - 1) * $size,
            'count'  => $size,
        ];
        $postData = json_encode($postData,JSON_UNESCAPED_UNICODE);
        $url = 'https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token='.$this->token;
        $res = $this->curl_post($url,$postData);
        $res = json_decode($res,true);
        if ($res && (!isset($res['errcode']) || (isset($res['errcode']) && $res['errcode'] == 0))){
            return [
                'status' => true,
                'msg'    => '获取成功',
                'data'   => $res,
            ];
        }else{
            $officialCode = $this->config->get('officialcode');
            if(isset($res['errcode'])){
                $msg = isset($wxCode[$res['errcode']]) ? $officialCode[$res['errcode']] : (isset($res['errmsg']) ? $res['errmsg'] : '获取失败');
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