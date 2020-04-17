<?php
/**
 * Created by PhpStorm.
 * User: ligo
 * Date: 2020/4/16
 * Time: 14:58
 */

namespace Daishuwx\Wxapp;


use Daishuwx\Base;

/**
 * 动态消息
 * Class UpdatableMessage
 * @package Daishuwx\Wxapp
 */
class UpdatableMessage extends Base
{
    /**
     * 创建被分享动态消息的 activity_id
     * @return array
     *User: ligo
     */
    public function createActivityId(){
        $url = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/activityid/create?access_token='.$this->token;
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
     * 修改被分享的动态消息
     * @param $activityId  动态消息的 ID，通过 updatableMessage.createActivityId 接口获取
     * @param $targetState 动态消息修改后的状态（具体含义见后文） 1\2
     * @param $templateInfo  动态消息对应的模板信息  parameter_list => [[name=>'',value=>'']]
     * @return array
     *User: ligo
     */
    public function setUpdatableMsg($activityId,$targetState,$templateInfo){
        $postData = [
            'activity_id'  => $activityId,
            'target_state'  => $targetState,
            'template_info'  => $templateInfo,
        ];
        $postData = json_encode($postData,JSON_UNESCAPED_UNICODE);

        $url = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/updatablemsg/send?access_token='.$this->token;
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
}