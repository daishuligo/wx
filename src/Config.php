<?php
/**
 * Created by PhpStorm.
 * User: ligo
 * Date: 2019/12/26
 * Time: 9:53
 */

namespace Daishuwx;


class Config
{
    static public function get($name){
        $config = include __DIR__.'/data/config.php';
        if(isset($config[$name])){
            return $config[$name];
        }else{
            return '';
        }
    }
}