<?php
/**
 * Created by PhpStorm.
 * User: ligo
 * Date: 2019/12/26
 * Time: 11:00
 */

namespace Daishuwx\Wxapp;


use Daishuwx\Base;
use Daishuwx\Config;

class DataAnalysis extends Base
{
    /**
     * 获取用户访问小程序日留存
     * @param $beginDate开始日期
     * @return array
     *User: ligo
     */
    public function getDailyRetain($beginDate){
        //检测日期格式
        if (preg_match ("/^([0-9]{4})([0-9]{2})([0-9]{2})$/", $beginDate, $parts))
        {
            //检测是否为日期
            if(!checkdate($parts[2],$parts[3],$parts[1])){
                return [
                    'status' => false,
                    'msg'    => '开始日期格式错误',
                    'data'   => [],
                ];
            }
        }else{
            return [
                'status' => false,
                'msg'    => '开始日期格式错误',
                'data'   => [],
            ];
        }

        $time = strtotime($beginDate);
        if($time + 86400 > time()){
            return [
                'status' => false,
                'msg'    => '超过可查询期限',
                'data'   => [],
            ];
        }

        $token = $this->getToken();
        if($token === false){
            return [
                'status' => false,
                'msg'    => '获取token失败',
                'data'   => [],
            ];
        }

        $postData = [
            'begin_date' => $beginDate,
            'end_date'   => $beginDate,
        ];

        $postData = json_encode($postData);

        $url = 'https://api.weixin.qq.com/datacube/getweanalysisappiddailyretaininfo?access_token='.$token;
        $res = $this->curl_post($url,$postData);
        $res = json_decode($res,true);

        if(isset($res['visit_uv']) || $res['errcode'] == 0){
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
     * 获取用户访问小程序月留存
     * @param $beginDate开始日期
     * @return array
     *User: ligo
     */
    public function getMonthlyRetain($beginDate){
        //检测日期格式
        if (preg_match ("/^([0-9]{4})([0-9]{2})(01)$/", $beginDate, $parts))
        {
            //检测是否为日期
            if(!checkdate($parts[2],$parts[3],$parts[1])){
                return [
                    'status' => false,
                    'msg'    => '开始日期格式错误',
                    'data'   => [],
                ];
            }
        }else{
            return [
                'status' => false,
                'msg'    => '开始日期格式错误',
                'data'   => [],
            ];
        }

        $time = strtotime($beginDate);
        $t = date('t', $time);
        $endDate = date('Ym'.$t,$time);
        $endTime = strtotime($endDate);
        if($endTime + 86400 > time()){
            return [
                'status' => false,
                'msg'    => '超过可查询期限',
                'data'   => [],
            ];
        }

        $token = $this->getToken();
        if($token === false){
            return [
                'status' => false,
                'msg'    => '获取token失败',
                'data'   => [],
            ];
        }

        $postData = [
            'begin_date' => $beginDate,
            'end_date'   => (int)$endDate,
        ];


        $postData = json_encode($postData);

        $url = 'https://api.weixin.qq.com/datacube/getweanalysisappidmonthlyretaininfo?access_token='.$token;
        $res = $this->curl_post($url,$postData);
        $res = json_decode($res,true);

        if(isset($res['visit_uv']) || $res['errcode'] == 0){
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
     * 获取用户访问小程序周留存
     * @param $beginDate开始日期
     * @return array
     *User: ligo
     */
    public function getWeeklyRetain($beginDate){
        //检测日期格式
        if (preg_match ("/^([0-9]{4})([0-9]{2})([0-9]{2})$/", $beginDate, $parts))
        {
            //检测是否为日期
            if(!checkdate($parts[2],$parts[3],$parts[1])){
                return [
                    'status' => false,
                    'msg'    => '开始日期格式错误',
                    'data'   => [],
                ];
            }
        }else{
            return [
                'status' => false,
                'msg'    => '开始日期格式错误',
                'data'   => [],
            ];
        }

        $time = strtotime($beginDate);
        $w = date('w', $time);
        if($w != 1){
            return [
                'status' => false,
                'msg'    => '开始日期必须为周一',
                'data'   => [],
            ];
        }
        $endDate = date('Ymd',$time + 86400 * 6);
        $endTime = strtotime($endDate);
        if($endTime + 86400 > time()){
            return [
                'status' => false,
                'msg'    => '超过可查询期限',
                'data'   => [],
            ];
        }

        $token = $this->getToken();
        if($token === false){
            return [
                'status' => false,
                'msg'    => '获取token失败',
                'data'   => [],
            ];
        }

        $postData = [
            'begin_date' => $beginDate,
            'end_date'   => (int)$endDate,
        ];


        $postData = json_encode($postData);

        $url = 'https://api.weixin.qq.com/datacube/getweanalysisappidweeklyretaininfo?access_token='.$token;
        $res = $this->curl_post($url,$postData);
        $res = json_decode($res,true);

        if(isset($res['visit_uv']) || $res['errcode'] == 0){
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
     * 获取用户访问小程序数据概况
     * @param $beginDate开始日期
     * @return array
     *User: ligo
     */
    public function getDailySummary($beginDate){
        //检测日期格式
        if (preg_match ("/^([0-9]{4})([0-9]{2})([0-9]{2})$/", $beginDate, $parts))
        {
            //检测是否为日期
            if(!checkdate($parts[2],$parts[3],$parts[1])){
                return [
                    'status' => false,
                    'msg'    => '开始日期格式错误',
                    'data'   => [],
                ];
            }
        }else{
            return [
                'status' => false,
                'msg'    => '开始日期格式错误',
                'data'   => [],
            ];
        }

        $time = strtotime($beginDate);
        if($time + 86400 > time()){
            return [
                'status' => false,
                'msg'    => '超过可查询期限',
                'data'   => [],
            ];
        }

        $token = $this->getToken();
        if($token === false){
            return [
                'status' => false,
                'msg'    => '获取token失败',
                'data'   => [],
            ];
        }

        $postData = [
            'begin_date' => $beginDate,
            'end_date'   => $beginDate,
        ];

        $postData = json_encode($postData);

        $url = 'https://api.weixin.qq.com/datacube/getweanalysisappiddailysummarytrend?access_token='.$token;
        $res = $this->curl_post($url,$postData);
        $res = json_decode($res,true);

        if(isset($res['list']) || $res['errcode'] == 0){
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
     * 获取用户访问小程序数据日趋势
     * @param $beginDate开始日期
     * @return array
     *User: ligo
     */
    public function getDailyVisitTrend($beginDate){
        //检测日期格式
        if (preg_match ("/^([0-9]{4})([0-9]{2})([0-9]{2})$/", $beginDate, $parts))
        {
            //检测是否为日期
            if(!checkdate($parts[2],$parts[3],$parts[1])){
                return [
                    'status' => false,
                    'msg'    => '开始日期格式错误',
                    'data'   => [],
                ];
            }
        }else{
            return [
                'status' => false,
                'msg'    => '开始日期格式错误',
                'data'   => [],
            ];
        }

        $time = strtotime($beginDate);
        if($time + 86400 > time()){
            return [
                'status' => false,
                'msg'    => '超过可查询期限',
                'data'   => [],
            ];
        }

        $token = $this->getToken();
        if($token === false){
            return [
                'status' => false,
                'msg'    => '获取token失败',
                'data'   => [],
            ];
        }

        $postData = [
            'begin_date' => $beginDate,
            'end_date'   => $beginDate,
        ];

        $postData = json_encode($postData);

        $url = 'https://api.weixin.qq.com/datacube/getweanalysisappiddailyvisittrend?access_token='.$token;
        $res = $this->curl_post($url,$postData);
        $res = json_decode($res,true);

        if(isset($res['list']) || $res['errcode'] == 0){
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
     * 获取用户访问小程序数据月趋势
     * @param $beginDate开始日期
     * @return array
     *User: ligo
     */
    public function getMonthlyVisitTrend($beginDate){
        //检测日期格式
        if (preg_match ("/^([0-9]{4})([0-9]{2})(01)$/", $beginDate, $parts))
        {
            //检测是否为日期
            if(!checkdate($parts[2],$parts[3],$parts[1])){
                return [
                    'status' => false,
                    'msg'    => '开始日期格式错误',
                    'data'   => [],
                ];
            }
        }else{
            return [
                'status' => false,
                'msg'    => '开始日期格式错误',
                'data'   => [],
            ];
        }

        $time = strtotime($beginDate);
        $t = date('t', $time);
        $endDate = date('Ym'.$t,$time);
        $endTime = strtotime($endDate);
        if($endTime + 86400 > time()){
            return [
                'status' => false,
                'msg'    => '超过可查询期限',
                'data'   => [],
            ];
        }

        $token = $this->getToken();
        if($token === false){
            return [
                'status' => false,
                'msg'    => '获取token失败',
                'data'   => [],
            ];
        }

        $postData = [
            'begin_date' => $beginDate,
            'end_date'   => (int)$endDate,
        ];


        $postData = json_encode($postData);

        $url = 'https://api.weixin.qq.com/datacube/getweanalysisappidmonthlyvisittrend?access_token='.$token;
        $res = $this->curl_post($url,$postData);
        $res = json_decode($res,true);

        if(isset($res['list']) || $res['errcode'] == 0){
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
     * 获取用户访问小程序数据周趋势
     * @param $beginDate开始日期
     * @return array
     *User: ligo
     */
    public function getWeeklyVisitTrend($beginDate){
        //检测日期格式
        if (preg_match ("/^([0-9]{4})([0-9]{2})([0-9]{2})$/", $beginDate, $parts))
        {
            //检测是否为日期
            if(!checkdate($parts[2],$parts[3],$parts[1])){
                return [
                    'status' => false,
                    'msg'    => '开始日期格式错误',
                    'data'   => [],
                ];
            }
        }else{
            return [
                'status' => false,
                'msg'    => '开始日期格式错误',
                'data'   => [],
            ];
        }

        $time = strtotime($beginDate);
        $w = date('w', $time);
        if($w != 1){
            return [
                'status' => false,
                'msg'    => '开始日期必须为周一',
                'data'   => [],
            ];
        }
        $endDate = date('Ymd',$time + 86400 * 6);
        $endTime = strtotime($endDate);
        if($endTime + 86400 > time()){
            return [
                'status' => false,
                'msg'    => '超过可查询期限',
                'data'   => [],
            ];
        }

        $token = $this->getToken();
        if($token === false){
            return [
                'status' => false,
                'msg'    => '获取token失败',
                'data'   => [],
            ];
        }

        $postData = [
            'begin_date' => $beginDate,
            'end_date'   => (int)$endDate,
        ];


        $postData = json_encode($postData);

        $url = 'https://api.weixin.qq.com/datacube/getweanalysisappidweeklyvisittrend?access_token='.$token;
        $res = $this->curl_post($url,$postData);
        $res = json_decode($res,true);

        if(isset($res['list']) || $res['errcode'] == 0){
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
     * 获取小程序新增或活跃用户的画像分布数据
     * 时间范围支持昨天、最近7天、最近30天
     * @param $beginDate开始日期
     * @return array
     *User: ligo
     */
    public function getUserPortrait($type = 'daily'){
        $yesterdayTime = mktime(0,0,0,date('m'),date('d') -1,date('Y'));
        $endDate = date('Ymd',$yesterdayTime);
        if($type == 'weekly'){
            $beginDate = date('Ymd',$yesterdayTime - 86400 * 6);
        }elseif ($type == 'monthly'){
            $beginDate = date('Ymd',$yesterdayTime - 86400 * 29);
        }else{
            $beginDate = $endDate;
        }


        $token = $this->getToken();
        if($token === false){
            return [
                'status' => false,
                'msg'    => '获取token失败',
                'data'   => [],
            ];
        }

        $postData = [
            'begin_date' => (int)$beginDate,
            'end_date'   => (int)$endDate,
        ];


        $postData = json_encode($postData);

        $url = 'https://api.weixin.qq.com/datacube/getweanalysisappiduserportrait?access_token='.$token;
        $res = $this->curl_post($url,$postData);
        $res = json_decode($res,true);

        if(isset($res['visit_uv']) || $res['errcode'] == 0){
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
     * 获取用户小程序访问分布数据
     * @param $beginDate开始日期
     * @return array
     *User: ligo
     */
    public function getVisitDistribution($beginDate){
        //检测日期格式
        if (preg_match ("/^([0-9]{4})([0-9]{2})([0-9]{2})$/", $beginDate, $parts))
        {
            //检测是否为日期
            if(!checkdate($parts[2],$parts[3],$parts[1])){
                return [
                    'status' => false,
                    'msg'    => '开始日期格式错误',
                    'data'   => [],
                ];
            }
        }else{
            return [
                'status' => false,
                'msg'    => '开始日期格式错误',
                'data'   => [],
            ];
        }

        $time = strtotime($beginDate);
        if($time + 86400 > time()){
            return [
                'status' => false,
                'msg'    => '超过可查询期限',
                'data'   => [],
            ];
        }

        $token = $this->getToken();
        if($token === false){
            return [
                'status' => false,
                'msg'    => '获取token失败',
                'data'   => [],
            ];
        }

        $postData = [
            'begin_date' => $beginDate,
            'end_date'   => $beginDate,
        ];

        $postData = json_encode($postData);

        $url = 'https://api.weixin.qq.com/datacube/getweanalysisappidvisitdistribution?access_token='.$token;
        $res = $this->curl_post($url,$postData);
        $res = json_decode($res,true);

        if(isset($res['list']) || $res['errcode'] == 0){
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
     * 访问页面。目前只提供按 page_visit_pv 排序的 top200。
     * @param $beginDate开始日期
     * @return array
     *User: ligo
     */
    public function getVisitPage($beginDate){
        //检测日期格式
        if (preg_match ("/^([0-9]{4})([0-9]{2})([0-9]{2})$/", $beginDate, $parts))
        {
            //检测是否为日期
            if(!checkdate($parts[2],$parts[3],$parts[1])){
                return [
                    'status' => false,
                    'msg'    => '开始日期格式错误',
                    'data'   => [],
                ];
            }
        }else{
            return [
                'status' => false,
                'msg'    => '开始日期格式错误',
                'data'   => [],
            ];
        }

        $time = strtotime($beginDate);
        if($time + 86400 > time()){
            return [
                'status' => false,
                'msg'    => '超过可查询期限',
                'data'   => [],
            ];
        }

        $token = $this->getToken();
        if($token === false){
            return [
                'status' => false,
                'msg'    => '获取token失败',
                'data'   => [],
            ];
        }

        $postData = [
            'begin_date' => $beginDate,
            'end_date'   => $beginDate,
        ];

        $postData = json_encode($postData);

        $url = 'https://api.weixin.qq.com/datacube/getweanalysisappidvisitpage?access_token='.$token;
        $res = $this->curl_post($url,$postData);
        $res = json_decode($res,true);

        if(isset($res['list']) || $res['errcode'] == 0){
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
}