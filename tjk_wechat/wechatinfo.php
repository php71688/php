<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/9
 * Time: 15:53
 */
include_once "wechat.class.php";

$openid=$_GET["openid"];

$get_info=new Me_wx();
$list=$get_info->get_user_info($openid);

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
        <td>openid</td>
        <td>昵称</td>
        <td>性别</td>
        <td>城市</td>
        <td>头像</td>
    </tr>
        <tr>
            <td><?php echo $list["openid"]?></td>
            <td><?php echo $list["nickname"]?></td>
            <td><?php
                if($list["sex"]==1){
                    echo "男";
                }elseif($list["sex"]==2){
                    echo "女";
                };
                ?>
            </td>
            <td><?php echo $list["city"]?></td>
            <td><img src="<?php echo $list["headimgurl"]?>" alt=""></td>
        </tr>
</table>
</body>
</html>
