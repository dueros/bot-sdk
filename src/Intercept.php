<?php
/**
 * 拦截器
 * 通过重载before，能够在处理通过addHandler，addEventListener添加的回调之前，定义一些逻辑。
 * 通过重载after能够对回调函数的返回值，进行统一的处理
 * @author yuanpeng01@baidu.com
 **/
namespace Baidu\Duer\Botsdk;

abstract class Intercept{
    /**
     * @param Bot $bot
     * @return mixed
     * 如果返回非null，跳过后面addHandler，addEventListener添加的回调
     **/
    public function preprocess($bot) {
    
    }

    /**
     * @desc 在调用response->build 之前统一对handler的输出结果进行修改
     * @param Bot $bot
     * @param array
     * @return array
     **/
    public function postprocess($bot, $result){
        return $result;
    }
}
