<?php
/**
 * Created by PhpStorm.
 * User: ligo
 * Date: 2020/4/16
 * Time: 16:36
 */

namespace Daishuwx\Wxapp;


use Daishuwx\Base;

class Logistics extends Base
{
    /**
     * @param $addSource  订单来源，0为小程序订单，2为App或H5订单，填2则不发送物流服务通知
     * @param $wxAppid  App或H5的appid，add_source=2时必填，需和开通了物流助手的小程序绑定同一open帐号
     * @param $orderId 订单ID，须保证全局唯一，不超过512字节
     * @param $openid 用户openid，当add_source=2时无需填写（不发送物流服务通知）
     * @param $deliveryId 快递公司ID，参见getAllDelivery
     * @param $bizId  快递客户编码或者现付编码
     * @param $customRemark  快递备注信息，比如"易碎物品"，不超过1024字节
     * @param $tagid  订单标签id，用于平台型小程序区分平台上的入驻方，tagid须与入驻方账号一一对应，非平台型小程序无需填写该字段
     * @param $sender  发件人信息 name/tel/mobile/company/post_code/country/province/city/area/address
     * @param $receiver  收件人信息 name/tel/mobile/company/post_code/country/province/city/area/address
     * @param $cargo  包裹信息，将传递给快递公司  count/weight/space_x/space_y/space_z/detail_list
     * @param $shop  商品信息，会展示到物流服务通知和电子面单中  wxa_path/img_url/goods_name/goods_count
     * @param $insured 保价信息 use_insured/insured_value
     * @param $service 服务类型 service_type/service_name
     * @param $expectTime Unix 时间戳, 单位秒，顺丰必须传。 预期的上门揽件时间，0表示已事先约定取件时间；
     * 否则请传预期揽件时间戳，需大于当前时间，收件员会在预期时间附近上门。例如expect_time为“1557989929”，
     * 表示希望收件员将在2019年05月16日14:58:49-15:58:49内上门取货。说明：若选择 了预期揽件时间，请不要自己打单，由上门揽件的时候打印。
     * 如果是下顺丰散单，则必传此字段，否则不会有收件员上门揽件
     * @return array
     *User: ligo
     */
    public function addOrder($addSource,$wxAppid,$orderId,$openid,$deliveryId,$bizId,$customRemark,$tagid,$sender,$receiver,$cargo,$shop,$insured,$cargo,$service,$expectTime){
        $postData = [
            'add_source'  => $addSource,
            'wx_appid'  => $wxAppid,
            'order_id'  => $orderId,
            'openid'  => $openid,
            'delivery_id'  => $deliveryId,
            'biz_id'  => $bizId,
            'custom_remark'  => $customRemark,
            'tagid'  => $tagid,
            'sender'  => $sender,
            'receiver'  => $receiver,
            'cargo'  => $cargo,
            'shop'  => $shop,
            'insured'  => $insured,
            'cargo'  => $cargo,
            'service'  => $service,
            'expect_time'  => $expectTime,
        ];
        $postData = json_encode($postData,JSON_UNESCAPED_UNICODE);

        $url = 'https://api.weixin.qq.com/cgi-bin/express/business/order/add?access_token='.$this->token;
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
     * 批量获取运单数据
     * @param $orderList  订单列表, 最多不能超过100个  order_id/delivery_id/waybill_id
     * @return array
     *User: ligo
     */
    public function scanQRCode($orderList){
        $postData = [
            'order_list'  => $orderList,
        ];
        $postData = json_encode($postData,JSON_UNESCAPED_UNICODE);

        $url = 'https://api.weixin.qq.com/cgi-bin/express/business/order/batchget?access_token='.$this->token;
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
     * 绑定、解绑物流账号
     * @param $type  bind表示绑定，unbind表示解除绑定
     * @param $bizId  快递公司客户编码
     * @param $deliveryId  快递公司ID
     * @param $password  快递公司客户密码, ems，顺丰，京东非必填
     * @param $remarkContent  备注内容（提交EMS审核需要）
     * @return array
     *User: ligo
     */
    public function bindAccount($type,$bizId,$deliveryId,$password,$remarkContent){
        $postData = [
            'type'  => $type,
            'biz_id'  => $bizId,
            'delivery_id'  => $deliveryId,
            'password'  => $password,
            'remark_content'  => $remarkContent,
        ];
        $postData = json_encode($postData,JSON_UNESCAPED_UNICODE);

        $url = 'https://api.weixin.qq.com/cgi-bin/express/business/account/bind?access_token='.$this->token;
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
     * 取消运单
     * @param $orderId 订单 ID，需保证全局唯一
     * @param $openid 用户openid，当add_source=2时无需填写（不发送物流服务通知）
     * @param $deliveryId 快递公司ID，参见getAllDelivery
     * @param $waybillId 运单ID
     * @return array
     *User: ligo
     */
    public function cancelOrder($orderId,$openid,$deliveryId,$waybillId){
        $postData = [
            'order_id'  => $orderId,
            'openid'  => $openid,
            'delivery_id'  => $deliveryId,
            'waybill_id'  => $waybillId,
        ];
        $postData = json_encode($postData,JSON_UNESCAPED_UNICODE);

        $url = 'https://api.weixin.qq.com/cgi-bin/express/business/order/cancel?access_token='.$this->token;
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
     * 获取所有绑定的物流账号
     * @return array
     *User: ligo
     */
    public function getAllAccount(){
        $url = 'https://api.weixin.qq.com/cgi-bin/express/business/account/getall?access_token='.$this->token;
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
     * 获取支持的快递公司列表
     * @return array
     *User: ligo
     */
    public function getAllDelivery(){
        $url = 'https://api.weixin.qq.com/cgi-bin/express/business/delivery/getall?access_token='.$this->token;
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
     * 获取运单数据
     * @param $orderId  订单 ID，需保证全局唯一
     * @param $openid  用户openid，当add_source=2时无需填写（不发送物流服务通知）
     * @param $deliveryId  快递公司ID，参见getAllDelivery, 必须和waybill_id对应
     * @param $waybillId  运单ID
     * @return array
     *User: ligo
     */
    public function getOrder($orderId,$openid,$deliveryId,$waybillId){
        $postData = [
            'order_id'  => $orderId,
            'openid'  => $openid,
            'delivery_id'  => $deliveryId,
            'waybill_id'  => $waybillId,
        ];
        $postData = json_encode($postData,JSON_UNESCAPED_UNICODE);

        $url = 'https://api.weixin.qq.com/cgi-bin/express/business/order/get?access_token='.$this->token;
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
     * 查询运单轨迹
     * @param $orderId  订单 ID，需保证全局唯一
     * @param $openid  用户openid，当add_source=2时无需填写（不发送物流服务通知）
     * @param $deliveryId  快递公司ID，参见getAllDelivery, 必须和waybill_id对应
     * @param $waybillId  运单ID
     * @return array
     *User: ligo
     */
    public function getPath($orderId,$openid,$deliveryId,$waybillId){
        $postData = [
            'order_id'  => $orderId,
            'openid'  => $openid,
            'delivery_id'  => $deliveryId,
            'waybill_id'  => $waybillId,
        ];
        $postData = json_encode($postData,JSON_UNESCAPED_UNICODE);

        $url = 'https://api.weixin.qq.com/cgi-bin/express/business/path/get?access_token='.$this->token;
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
     * 获取打印员
     * @return array
     *User: ligo
     */
    public function getPrinter(){
        $url = 'https://api.weixin.qq.com/cgi-bin/express/business/printer/getall?access_token='.$this->token;
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
     * 获取电子面单余额
     * @param $deliveryId  快递公司ID，参见getAllDelivery
     * @param $bizId  快递公司客户编码
     * @return array
     *User: ligo
     */
    public function getQuota($deliveryId,$bizId){
        $postData = [
            'delivery_id'  => $deliveryId,
            'biz_id'  => $bizId,
        ];
        $postData = json_encode($postData,JSON_UNESCAPED_UNICODE);

        $url = 'https://api.weixin.qq.com/cgi-bin/express/business/quota/get?access_token='.$this->token;
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
     * 配置面单打印员，可以设置多个
     * @param $openid  打印员 openid
     * @param $updateType  更新类型
     * @param $tagidList  用于平台型小程序设置入驻方的打印员面单打印权限，同一打印员最多支持10个tagid，使用半角逗号分隔，
     * 中间不加空格，如填写123,456，表示该打印员可以拉取到tagid为123和456的下的单，非平台型小程序无需填写该字段
     * @return array
     *User: ligo
     */
    public function updatePrinter($openid,$updateType,$tagidList){
        $postData = [
            'openid'  => $openid,
            'update_type'  => $updateType,
            'tagid_list'  => $tagidList,
        ];
        $postData = json_encode($postData,JSON_UNESCAPED_UNICODE);

        $url = 'https://api.weixin.qq.com/cgi-bin/express/business/printer/update?access_token='.$this->token;
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