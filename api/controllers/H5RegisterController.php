<?php
/**
 * RestFul example.
 *
 * @Author     : sunforcherry@gmail.com
 * @CreateTime 2018/3/17 17:02:14
 */

namespace api\controllers;

use common\components\actions\CaptchaPictureAction;

class H5RegisterController extends BaseController
{
    /**
     * 文件描述 获取图形验证码
     * Created On 2019-02-25 14:14
     * Created By heyafei
     * @return array
     */
    public function actions()
    {
        return  [
            'captcha' => [
                'class' => CaptchaPictureAction::class,
                'fixedVerifyCode' => strval(rand(1000,9999)),
                'backColor'=>0x000000,//背景颜色
                'maxLength' => 4, //最大显示个数
                'minLength' => 4,//最少显示个数
                'padding' => 5,//间距
                'height'=>40,//高度
                'width' => 140,  //宽度
                'foreColor'=>0xffffff,     //字体颜色
                'offset'=>16,        //设置字符偏移量 有效果
                //'controller'=>'login',        //拥有这个动作的controller
            ]
        ];
    }
}