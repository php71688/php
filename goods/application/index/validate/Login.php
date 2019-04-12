<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/2/18
 * Time: 14:34
 */
namespace app\index\validate;

use think\Validate;
class Login extends Validate
{
    // 验证规则
    protected $rule = [
        'username|用户名' => 'require',
        'password' => 'require',
        'captcha' => 'require|checkCaptcha:null',
    ];
    protected $message = [
        'captcha.require' => '验证码错误!',  //验证码空的时候验证的它
    ];

    protected function checkCaptcha($value)  //验证码输入的进验证的它
    {

        $captcha = new Captcha();
        if ($captcha->check($value)) {
            return true;
        } else {
            return '验证码错误!';
        }
    }

}