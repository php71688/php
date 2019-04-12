<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/22
 * Time: 9:18
 */

namespace app\index\validate;


use think\Validate;

class Auth extends Validate
{
    protected $rule = [
        'auth_name'=>'require',
        'controller'=>'require',
        'action'=>'require',

    ];
    protected $message = [
        'auth_name.require'=>'规则名字不能为空',
        'controller.require'=>'控制器不能为空',
        'action.require'=>'方法不能为空',

    ];
}