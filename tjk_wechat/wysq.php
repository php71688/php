<?php
include "wechat.class.php";
$wechat=new Me_wx();
$code=$_GET["code"];
$url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$wechat::APPID."&secret=".$wechat::APPSECRET."&code={$code}&grant_type=authorization_code";
$json_str=$wechat->curl_get($url);
$json_array=json_decode($json_str,true);

$url2 = "https://api.weixin.qq.com/sns/userinfo?access_token={$json_array['access_token']}&openid={$json_array['openid']}&lang=zh_CN";
$json_string2 = $wechat->curl_get($url2);
$json_array2 = json_decode($json_string2,true);
?>
<html>
<head>
    <meta charset = 'utf-8'/>
    <title>网页授权页面</title>
</head>
<p>回调页面</p>
<table border="1" cellpadding="10">
    <tr>
        <th>openid</th>
        <th>昵称</th>
        <th>性别</th>
        <th>城市</th>
        <th>头像</th>
    </tr>

  <tr>
       <td><?php echo $json_array2['openid'] ?></td>
        <td><?php echo $json_array2['nickname'] ?></td>
        <td><?php
            if($json_array2["sex"]==1){
                echo "男";
            }elseif($json_array2["sex"]==2){
                echo "女";
            };
            ?></td>
        <td><?php echo $json_array2['city'] ?></td>
        <td><img src = "<?php echo $json_array2['headimgurl'] ?>" /></td>
    </tr>
</table>
</html>
