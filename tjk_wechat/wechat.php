<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/7
 * Time: 11:59
 */
include_once "wechat.class.php";


$wx = new Me_wx();
//验证一次就好，成功就关闭
//$wx->check_request();
//微信相应请求返回
$wx->fh_response();



