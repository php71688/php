<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/21
 * Time: 17:17
 */

namespace app\index\validate;


use think\Validate;

class Role extends Validate
{
    protected $rule = [
        'role_name'=>'require',
        'auth'=>'require',
    ];
    protected $message = [
        'role_name.require'=>'角色名字不能为空',
        'auth.require'=>'权限不能为空',
    ];
}