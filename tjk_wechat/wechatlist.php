<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/9
 * Time: 15:53
 */
include_once "wechat.class.php";

$wechat=new Me_wx();

$list=$wechat->get_userlist();
//print_r($list);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<style>
    .bg{
        border-collapse:collapse;
    }
    .bg tr td{
        border: 1px solid red;
        width: 150px;
        height:40px;
    }
</style>
<body>
        <table class="bg">
            <tr>
                <td>
                    用户
                </td>
            </tr>
            <?php foreach ($list as $v){?>
            <tr>
                <td>
                    <a href="wechatinfo.php?openid=<?php echo $v?>"><?php echo $v?></a>
                </td>
            </tr>
            <?php }?>
        </table>
</body>
</html>
