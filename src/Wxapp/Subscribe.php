<?php
/**
 * Created by PhpStorm.
 * User: ligo
 * Date: 2019/12/26
 * Time: 16:46
 */

namespace Daishuwx\Wxapp;


use Daishuwx\Base;
use Daishuwx\ConfigBak;

/**
 * 订阅消息
 * Class Subscribe
 * @package Daishuwx\Wxapp
 */
class Subscribe extends Base
{
    /**
     * 获取当前帐号下的个人模板列表
     * @return array
     *User: ligo
     */
    public function getTemplateList(){
        $url = 'https://api.weixin.qq.com/wxaapi/newtmpl/gettemplate?access_token='.$this->token;
        $res = $this->curl_get($url);
        $res = json_decode($res,true);
        if(isset($res['errcode']) && $res['errcode'] == 0){
            return [
                'status' => true,
                'msg'    => '请求成功',
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
     * 组合模板并添加至帐号下的个人模板库
     * @param $tid 模板标题 id，可通过接口获取，也可登录小程序后台查看获取
     * @param $kidList 开发者自行组合好的模板关键词列表，关键词顺序可以自由搭配（例如 [3,5,4] 或 [4,5,3]），最多支持5个，最少2个关键词组合
     * @param $sceneDesc 服务场景描述，15个字以内
     * @return array
     *User: ligo
     */
    public function addTemplate($tid,$kidList,$sceneDesc){
        $postData = [
            'tid' => $tid,
            'kidList' => $kidList,
            'sceneDesc' => $sceneDesc,
        ];

        $postData = json_encode($postData,JSON_UNESCAPED_UNICODE);

        $hearder[] = 'content-type: application/json';
        $url = 'https://api.weixin.qq.com/wxaapi/newtmpl/addtemplate?access_token='.$this->token;
        $res = $this->curl_post($url,$postData,$hearder);
        $res = json_decode($res,true);
        if(isset($res['errcode']) && $res['errcode'] == 0){
            return [
                'status' => true,
                'msg'    => '内容正常',
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
     * 删除帐号下的个人模板
     * @param $priTmplId 要删除的模板id
     * @return array
     *User: ligo 
     */
    public function deleteTemplate($priTmplId){
        $postData = [
            'priTmplId' => $priTmplId,
        ];

        $postData = json_encode($postData);

        $hearder[] = 'content-type: application/json';
        $url = 'https://api.weixin.qq.com/wxaapi/newtmpl/deltemplate?access_token='.$this->token;
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
     * 获取小程序账号的类目
     * @return array
     *User: ligo
     */
    public function getCategory(){
        $url = 'https://api.weixin.qq.com/wxaapi/newtmpl/getcategory?access_token='.$this->token;
        $res = $this->curl_get($url);
        $res = json_decode($res,true);
        if(isset($res['errcode']) && $res['errcode'] == 0){
            return [
                'status' => true,
                'msg'    => '请求成功',
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
     * 获取模板标题下的关键词列表
     * @param $tid 模板标题 id，可通过接口获取
     * @return array
     *User: ligo
     */
    public function getPubTemplateKeyWordsById($tid){
        $url = 'https://api.weixin.qq.com/wxaapi/newtmpl/getpubtemplatekeywords?access_token='.$this->token.'&tid='.$tid;
        $res = $this->curl_get($url);
        $res = json_decode($res,true);
        if(isset($res['errcode']) && $res['errcode'] == 0){
            return [
                'status' => true,
                'msg'    => '请求成功',
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
     * 获取帐号所属类目下的公共模板标题
     * @param $ids 类目 id，多个用逗号隔开
     * @param $start 用于分页，表示从 start 开始。从 0 开始计数
     * @param $limit 用于分页，表示拉取 limit 条记录。最大为 30。
     * @return array
     *User: ligo
     */
    public function getPubTemplateTitleList($ids,$start,$limit = 30){
        $postData = [
            'ids' => $ids,
            'start' => $start,
            'limit' => $limit,
        ];
        $postData = json_encode($postData);

        $url = 'https://api.weixin.qq.com/wxaapi/newtmpl/getpubtemplatetitles?access_token='.$this->token.'&ids='.$ids.'&start='.$start.'&limit=30';
        
        $res = $this->curl_get($url);
        $res = json_decode($res,true);
        if(isset($res['errcode']) && $res['errcode'] == 0){
            return [
                'status' => true,
                'msg'    => '请求成功',
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
     * 发送订阅消息
     * @param $toUser 接收者（用户）的 openid
     * @param $templateId 所需下发的订阅模板id
     * @param $data 模板内容，格式形如 { "key1": { "value": any }, "key2": { "value": any } }
     * @param string $page 点击模板卡片后的跳转页面，仅限本小程序内的页面。支持带参数,（示例index?foo=bar）。该字段不填则模板无跳转
     * @return array
     *User: ligo
     */
    public function send($toUser,$templateId,$data,$page = ''){
        $postData = [
            'touser'  => $toUser,
            'template_id'  => $templateId,
            'page'  => $page,
            'data'  => $data,
        ];
        $postData = json_encode($postData,JSON_UNESCAPED_UNICODE);

        $url = 'https://api.weixin.qq.com/wxa/msg_sec_check?access_token='.$this->token;
        $res = $this->curl_post($url,$postData);
        $res = json_decode($res,true);
        if(isset($res['errcode']) && $res['errcode'] == 0){
            return [
                'status' => true,
                'msg'    => '发送成功',
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