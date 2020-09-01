<?php
/**
 * Created by PhpStorm.
 * User: ligo
 * Date: 2020/4/16
 * Time: 15:29
 */

namespace Daishuwx\Wxapp;


use Daishuwx\Base;

/**
 * 附近小程序
 * Class NearbyPoi
 * @package Daishuwx\Wxapp
 */
class NearbyPoi extends Base
{
    /**
     * 添加地点
     * @param $picList  门店图片，最多9张，最少1张，上传门店图片如门店外景、环境设施、商品服务等，图片将展示在微信客户端的门店页。
     *                  图片链接通过文档https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1444738729中的《上传图文消息内的图片获取URL》接口获取。
     *                  必填，文件格式为bmp、png、jpeg、jpg或gif，大小不超过5M pic_list是字符串，内容是一个json
     * @param $serviceInfos  必服务标签列表 必填，需要填写
     *                       1、 服务标签ID
     *                       2、 服务类型tpye
     *                       3、 服务名称name
     *                       详细字段格式见下方《服务标签id编号、类型与服务名称表》
     *                       4、 APPID
     *                       5、 对应服务落地页的path路径：path路径页面要与对应的服务标签一致，例如选取外卖服务，path路径应该是小程序的外卖对应的那个页面，path路径获取咨询开发或者到小程序管理后台-工具-生成小程序码页面获取
     *                       6、新增服务描述desc：描述服务内容，例如满减、折扣等优惠信息或新品、爆品等商品信息，仅标准服务都可添加，10个字符以内。
     *                       service_infos是字符串，内容是一个json
     * @param $kfInfo   客服信息 选填，可自定义服务头像与昵称，具体填写字段见下方示例kf_info pic_list是字符串，内容是一个json
     * @param $storeName  门店名字 必填，门店名称需按照所选地理位置自动拉取腾讯地图门店名称，不可修改，如需修改请重现选择地图地点或重新创建地点。
     * @param $hour 营业时间，格式11:11-12:12 必填
     * @param $address  地址 必填
     * @param $poiId 如果创建新的门店，poi_id字段为空 如果更新门店，poi_id参数则填对应门店的poi_id 选填
     * @param $companyName  主体名字 必填
     * @param $contractPhone  门店电话 必填
     * @param $credential  资质号 必填, 15位营业执照注册号或9位组织机构代码
     * @param $qualificationList  证明材料 必填 如果company_name和该小程序主体不一致，需要填qualification_list，
     *                            详细规则见附近的小程序使用指南-如何证明门店的经营主体跟公众号或小程序帐号主体相关http://kf.qq.com/faq/170401MbUnim17040122m2qY.html
     * @param $mapPoiId  对应《在腾讯地图中搜索门店》中的sosomap_poi_uid字段 腾讯地图那边有些数据不一致，如果不填map_poi_id的话，小概率会提交失败！
     *                   注：
     *                   poi_id与map_poi_id关系：
     *                   map_poi_id是腾讯地图对于poi的唯一标识
     *                   poi_id是门店进驻附近后的门店唯一标识
     * @return array
     *User: ligo
     */
    public function add($picList,$serviceInfos,$kfInfo,$storeName,$hour,$address,$poiId,$companyName,$contractPhone,$credential,$qualificationList,$mapPoiId){
        $postData = [
            'is_comm_nearby'  => 1,
            'pic_list'  => $picList,
            'service_infos'  => $serviceInfos,
            'kf_info'  => $kfInfo,
            'store_name'  => $storeName,
            'hour'  => $hour,
            'address'  => $address,
            'poi_id'  => $poiId,
            'company_name'  => $companyName,
            'contract_phone'  => $contractPhone,
            'credential'  => $credential,
            'qualification_list'  => $qualificationList,
            'map_poi_id'  => $mapPoiId,
        ];
        $postData = json_encode($postData,JSON_UNESCAPED_UNICODE);

        $url = 'https://api.weixin.qq.com/wxa/addnearbypoi?access_token='.$this->token;
        $res = $this->curl_post($url,$postData);
        $res = json_decode($res,true);
        if(isset($res['errcode']) && $res['errcode'] == 0){
            return [
                'status' => true,
                'msg'    => '提交成功',
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
     * 删除地点
     * @param $poiId  附近地点 ID
     * @return array
     *User: ligo
     */
    public function delete($poiId){
        $postData = [
            'poi_id'  => $poiId,
        ];
        $postData = json_encode($postData,JSON_UNESCAPED_UNICODE);

        $url = 'https://api.weixin.qq.com/wxa/delnearbypoi?access_token='.$this->token;
        $res = $this->curl_post($url,$postData);
        $res = json_decode($res,true);
        if(isset($res['errcode']) && $res['errcode'] == 0){
            return [
                'status' => true,
                'msg'    => '提交成功',
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
     * 查看地点列表
     * @param $page 页面
     * @param $pageRow 每页展示数量
     * @return array
     *User: ligo
     */
    public function getList($page,$pageRow){
        $url = 'https://api.weixin.qq.com/wxa/getnearbypoilist?page='.$page.'&page_rows='.$pageRow.'&access_token='.$this->token;
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
     * 展示/取消展示附近小程序
     * @param $poiId  附近地点 ID
     * @param $status  是否展示 0/1
     * @return array
     *User: ligo
     */
    public function setShowStatus($poiId,$status){
        $postData = [
            'poi_id'  => $poiId,
            'status'  => $status,
        ];
        $postData = json_encode($postData,JSON_UNESCAPED_UNICODE);

        $url = 'https://api.weixin.qq.com/wxa/setnearbypoishowstatus?access_token='.$this->token;
        $res = $this->curl_post($url,$postData);
        $res = json_decode($res,true);
        if(isset($res['errcode']) && $res['errcode'] == 0){
            return [
                'status' => true,
                'msg'    => '提交成功',
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