<?php
/**
 * Created by PhpStorm.
 * User: ligo
 * Date: 2020/4/17
 * Time: 16:26
 */

namespace Daishuwx\Wxofficial;


use Daishuwx\Base;
use Daishuwx\Config;

/**
 * 自定义菜单 -- 缺乏个性化菜单
 * Class Menu
 * @package Daishuwx\Wxofficial
 */
class Menu extends Base
{
    /**
     * 创建自定义菜单
     * @param $postData
     * @return array
     *User: ligo
     */
    public function create($postData){

        $postData = json_encode($postData,JSON_UNESCAPED_UNICODE);

        $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$this->token;
        $res = $this->curl_post($url,$postData);
        $res = json_decode($res,true);
        if($res['errcode'] == 0){
            return [
                'status' => true,
                'msg'    => '验证成功',
                'data'   => $res,
            ];
        }else{
            $wxCode = Config::get('wx_code');
            $msg = (isset($wxCode[$res['errcode']]) ? $wxCode[$res['errcode']] : '验证失败');
            return [
                'status' => false,
                'msg'    => $msg,
                'data'   => $res,
            ];
        }
    }

    /**
     * 获取当前使用的自定义菜单
     * @return array
     *User: ligo
     */
    public function getCurrentSelfmenuInfo(){
        $url = 'https://api.weixin.qq.com/cgi-bin/get_current_selfmenu_info?access_token='.$this->token;
        $res = $this->curl_get($url);
        $res = json_decode($res,true);
        if(isset($res['is_menu_open'])){
            return [
                'status' => true,
                'msg'    => '请求成功',
                'data'   => $res,
            ];
        }else{
            $wxCode = Config::get('wx_code');
            if(isset($res['errcode'])){
                $msg = (isset($wxCode[$res['errcode']]) ? $wxCode[$res['errcode']] : '请求失败');
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
     * 删除当前使用的自定义菜单
     * @return array
     *User: ligo
     */
    public function delete(){
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/delete?access_token='.$this->token;
        $res = $this->curl_get($url);
        $res = json_decode($res,true);
        if(isset($res['errcode']) && $res['errcode'] == 0){
            return [
                'status' => true,
                'msg'    => '删除成功',
                'data'   => $res,
            ];
        }else{
            $wxCode = Config::get('wx_code');
            if(isset($res['errcode'])){
                $msg = (isset($wxCode[$res['errcode']]) ? $wxCode[$res['errcode']] : '请求失败');
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