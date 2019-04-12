<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/7
 * Time: 11:59
 */
class Me_wx{

        /*
         * 开发者提交信息后，微信服务器将发送GET请求到填写的服务器地址URL上
         * 开发者通过检验signature对请求进行校验（下面有校验方式）。
         * 若确认此次GET请求来自微信服务器，请原样返回echostr参数内容，则接入生效，成为开发者成功，否则接入失败
         *
         * 1）将token、timestamp、nonce三个参数进行字典序排序
         * 2）将三个参数字符串拼接成一个字符串进行sha1加密
         * 3）开发者获得加密后的字符串可与signature对比，标识该请求来源于微信
         */
        //声明一个token常量值与微信接口配置信息一致，这个是定义好的
            const TOKEN = "jwj";
        //公众号和小程序均可以使用AppID和AppSecret调用本接口来获取access_token，主动获取用户信息的关键,ID和秘钥是固定的设置一个常量
            const APPID = "wx1f68f5242cfff7f9";
            const APPSECRET = "1d52e06cb09be2370e4641adcc008637";

    //链接微信平台
    public function check_request(){
        //获取微信平台发送过来的客户端请求
        $signature=$_GET["signature"];
        $timestamp=$_GET["timestamp"];
        $nonce=$_GET["nonce"];
        $echostr=$_GET["echostr"];

        //对token、timestamp、nonce三个参数进行字典序排序  用到sort（）；先让转换成数组
        $array=[self::TOKEN,$timestamp,$nonce];
        sort($array);
        //将三个参数字符串拼接成一个字符串进行sha1加密，需要将已经排序好的数组转换成字符串
        $str=implode("",$array);
        $sign_str=sha1($str);
        //开发者获得加密后的字符串可与signature对比，标识该请求来源于微信
        //进行验证串验证
        if($sign_str==$signature){
            //告诉微信验证成功
            echo $echostr;
            die();
        }
    }


    //公共回复消息封装
    public function msg_public($postObj,$content){
        //返回去的信息
        $str ="<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[%s]]></Content></xml>";
        return sprintf($str,$postObj->FromUserName,$postObj->ToUserName,time(),$content);
    }

    //接收消息并返回信息  当微信接口成功后就是用户关注  需要你推送公众号 这就是事件推送
    public function fh_response(){
        //客户关注过后微信平台推送过来的事件是订阅事件  是XML格式的值
        /*<xml>
        <ToUserName>< ![CDATA[toUser] ]></ToUserName>
        <FromUserName>< ![CDATA[FromUser] ]></FromUserName>
        <CreateTime>123456789</CreateTime>
        <MsgType>< ![CDATA[event] ]></MsgType>
        <Event>< ![CDATA[subscribe] ]></Event>
        </xml>*/

        //获取微信服务器推送过来的POST数据(XML格式)
        $postXML=$GLOBALS['HTTP_RAW_POST_DATA'];
        //将XML转成对象
        $postObj=simplexml_load_string($postXML);
        //消息来源  需要开发者给客户返回消息的
        $fromUser=$postObj->FromUserName;
        //	开发者微信号   接收者就是自己
        $toUser=$postObj->ToUserName;
        //消息类型，event
        $msgType=$postObj->MsgType;
        //事件类型，subscribe(订阅)、unsubscribe(取消订阅)
        $event=$postObj->Event;

        //判断事件类型
        switch ($msgType){
            //如果消息类型是事件类型 在执行
            case "event":
                //如果是事件类型是订阅了就给客户回信息
                switch ($event){
                    //客户关注后回复信息
                    case "subscribe":
                        $content = "您好！谢谢您的关注\n
                                    \n1.开心一笑请输入“开心+数字”，如：开心1。
                                    \n2.如果您需要查询天气，请输入“天气+城市”，如：天气郑州 。
                                    \n3.如果您需要查询手机号码归属地，请直接输入手机号”，如：15033877750.
                                    \n4.如果您需要查询快递单号，请输入“快递公司+快递+单号”，如：申通快递3381596955703。";
                        echo $this->msg_public($postObj,$content);
                        break;

                     //获取客户地理位置
                    case "LOCATION":
                        $content = "您现在所在位置坐标为：\n经度:".$postObj->Longitude."\n"."纬度:".$postObj->Latitude."\n"."精度:".$postObj->Precision;
                        echo $this->msg_public($postObj,$content);
                        break;

                     //图文消息 点击事件
                    case "CLICK":
                        $eventKey=$postObj->EventKey;
                        switch($eventKey){
                            case "sing":
                                $array=[
                                    [
                                        "title"=>"《后来》是由施人诚作词，玉城千春作曲，王继康编曲，刘若英演唱的歌曲，收录于刘若英1999年11月1日发行的专辑《我等你》中。《后来》是刘若英的代表作品之一。2001年，该歌曲获得Hit Fm年度百首单曲第49名。",
                                        "description1"=>"刘若英",
                                        'picurl'=>"https://gss0.bdstatic.com/94o3dSag_xI4khGkpoWK1HF6hhy/baike/w%3D268%3Bg%3D0/sign=d75ae2ce1630e924cfa49b377433093b/48540923dd54564e420a1bffbade9c82d0584f8d.jpg",
                                        'url'=>"https://baike.baidu.com/item/%E5%90%8E%E6%9D%A5/875444?fr=aladdin"
                                    ],
                                    [
                                        "title"=>"刘若英 Rene Liu），1970年6月1日出生于台湾省台北市， 中国台湾女歌手、演员、导演、词曲创作者，毕业于美国加州州立大学音乐系。",
                                        "description1"=>"刘若英",
                                        'picurl'=>"https://gss3.bdstatic.com/-Po3dSag_xI4khGkpoWK1HF6hhy/baike/w%3D268%3Bg%3D0/sign=5e51a26c9b2397ddd6799f0261b9d58a/5bafa40f4bfbfbed900dadf472f0f736aec31fe0.jpg",
                                        'url'=>"https://baike.baidu.com/item/%E5%88%98%E8%8B%A5%E8%8B%B1/146910?fr=aladdin"
                                    ],
                                ];
                                echo $this->send_news($postObj,$array);
                                break;
                        }

                }
                break;

                //回复文本消息
            case "text":
                if (($index=mb_strpos($postObj->Content,"天气"))!==false){//天气出现的位置返回值包含从零开始也是找到有的值 只有不等于FALSE时
                    //截取字符
                    $city=mb_substr($postObj->Content,$index+mb_strlen("天气")); //$index+mb_strlen("天气")  还可以写成  $index+6  一个汉字在utf8中占3个字节
                   /* //封装起来  下边封装
                    switch ($city){
                        case "郑州":
                            $city_code="101180101";
                            break;
                        case "中牟":
                            $city_code="101180107";
                            break;
                        case "新郑":
                            $city_code="101180106";
                            break;
                        default:
                            $city_code=0;
                            break;
                    }
                    $url="http://www.weather.com.cn/data/sk/{$city_code}.html";
                    file_put_contents("wechat_tq.text","tq_city_code:".$city_code.PHP_EOL,FILE_APPEND);//日志
                    //模拟get请求
                    $json_str=$this->curl_get($url);
                    //转换json字符成数组
                    $json_array=json_decode($json_str,true);
                    file_put_contents("wechat_tq.text","tq_json_array:".$json_array.PHP_EOL,FILE_APPEND);//日志
                    //内容拼接*/
                    $json_array=$this->get_weather($city);
                    $content = '城市：'.$json_array['weatherinfo']['city'].PHP_EOL;
                    $content.= '温度：'.$json_array['weatherinfo']['temp'].PHP_EOL;
                    $content.= '风向：'.$json_array['weatherinfo']['WD'].PHP_EOL;
                    $content.= '风级：'.$json_array['weatherinfo']['WS'];
                    echo $this->msg_public($postObj,$content);
                }elseif (preg_match('/1[3456789][0-9]{9}/',$postObj->Content)){
                    //手机号归属地
                    $phone=$postObj->Content;
                    $url = "http://apis.juhe.cn/mobile/get?phone={$phone}&dtype=&key=852d860157009b9e1ad98ca3cf1960b1";//此处的key值是聚合数据接口的值
                    $json_str=$this->curl_get($url);
                    $json_array=json_decode($json_str,true);
                    $content = '省份：'.$json_array['result']['province'].PHP_EOL;
                    $content.= '城市：'.$json_array['result']['city'].PHP_EOL;
                    $content.= '运营商：'.$json_array['result']['company'].PHP_EOL;
                    echo $this->msg_public($postObj,$content);
                }elseif(($index=mb_strpos($postObj->Content,"快递"))!==false){
                    //截取快递公司名称
                    $express_name=mb_substr($postObj->Content,0,6);
                    //截取快递单号
                    $express_dh=mb_substr($postObj->Content,$index+6);
                    //调用快递查询方法
                    $json_array=$this->get_express($express_dh,$express_name);
                    //声明一个变量为空
                    $content="";
                    foreach ($json_array["data"] as $v){
                        $content.="时间：".$v["time"]."状态：".$v["context"].PHP_EOL;
                    }
                    echo $this->msg_public($postObj,$content);

                }elseif(($con=mb_strpos($postObj->Content,"开心"))!==false){
                    //笑话大全
                    //截取输入的字符 获取需要发送的数字
                    $num=mb_substr($postObj->Content,$con+mb_strlen("开心"));
                    //接口地址
                    $url="http://v.juhe.cn/joke/content/text.php?page={$num}&pagesize=5&key=290606baba021925f0cbf076731446a8";
                    file_put_contents("wechat_kx.text","kx_url:".$url.PHP_EOL,FILE_APPEND);//日志
                    //模拟get请求
                    $json_str=$this->curl_get($url);
                    file_put_contents("wechat_kx.text","kx_json_str:".$json_str.PHP_EOL,FILE_APPEND);//日志
                    $json_array=json_decode($json_str,true);
                    file_put_contents("wechat_kx.text","kx_content:".print_r($json_array).PHP_EOL,FILE_APPEND);//日志
                    $array=$json_array["result"]["data"];
                    $content="";
                    foreach ($array as $v){
                        $content.="开心一笑：".$v["content"].PHP_EOL;
                    }
                    echo $this->msg_public($postObj,$content);

                }/*elseif(($index=mb_strpos($postObj->Content,"菜谱"))!==false){

                    //截取发送过来的菜名
                    $cai_name=mb_substr($postObj->Content,$index+mb_strlen("菜谱"));
                    //接口地址
                    $url="http://apis.juhe.cn/cook/query.php?menu={$cai_name}&dtype=&pn=&rn=3&albums=&=&key=266985af3db0e2dce4ecd691433714b8";
                    //模拟get请求
                    $json_str=$this->curl_get($url);
                    file_put_contents("wechat_cp.text","cp_json_str:".$json_str.PHP_EOL,FILE_APPEND);//日志
                    //转换成数组
                    $json_array=json_decode($json_str,true);
                    file_put_contents("wechat_cp.text","cp_json_array:".print_r($json_array).PHP_EOL,FILE_APPEND);//日志
                    $array=$json_array["result"]["data"];
                    file_put_contents("wechat_cp.text","cp_array:".print_r($array).PHP_EOL,FILE_APPEND);//日志
                    $content="";
                  foreach ($array as $v){
                        $content.="菜名：".$v["title"].PHP_EOL;
                        $content.="菜品：".$v["tags"].PHP_EOL;
                        $content.="主料：".$v["ingredients"].PHP_EOL;
                        $content.="辅料：".$v["burden"].PHP_EOL;
                        $content1="";
                        foreach ($v["steps"] as $vv){
                            $content1.=$vv["img"].PHP_EOL;
                            $content1.=$vv["step"].PHP_EOL;
                        }
                      $content.="操作：".$content1.PHP_EOL.PHP_EOL;

                    }
                    file_put_contents("wechat_cp.text","cp_content:".$content.PHP_EOL,FILE_APPEND);//日志
                    echo $this->msg_public($postObj,$content);


                }*/else{
                    $content = "你发送的请求不符合要求";
                    echo $this->msg_public($postObj,$content);
                }
                break;

                //接收语音消息
            case "voice":
                $recognition=$postObj->Recognition;
                $content=$recognition;
                echo $this->msg_public($postObj,$content);
                break;

                //当所有请求条件都无法满足时
            default:
                echo $this->send_text($postObj,'您进入了未知世界');
                break;
        }
    }




    //通过curl（）函数来模拟实现在代码中的get请求  为后续获取access_token做接口
    public function curl_get($url){
        //curl_init — 初始化一个CURL会话
        $ch=curl_init();
        //curl_setopt — 设置一个cURL传输选项
        curl_setopt($ch,CURLOPT_URL,$url);//设置请求地址
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);//将curl_exec()获取的信息以文件流的形式返回，而不是直接输出。不显示
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//关闭ssl证书验证
        $mn_get=curl_exec($ch);//执行curl
        curl_close($ch);//关闭资源
        //程序中一定养成写日志习惯， 写入文件日志
        file_put_contents("wechat.text","curl_get:".$mn_get.PHP_EOL,FILE_APPEND);
        //判断是否转换get形式成功
        if($mn_get==true){
            return $mn_get;
        }
       return "因网络问题未能找到你需要的信息";
    }

    //access_token是公众号的全局唯一接口调用凭据，公众号调用各接口时都需使用access_token。公众号和小程序均可以使用AppID和AppSecret调用本接口来获取access_token。
    //微信公众号中主动获取客户信息需要的是通过access_token来获取
    public function get_token(){
        //https请求方式: GET方式获取   在代码中如果要获取一个地址中的get数据就需要用到一个发生一个请求，但在代码中无法去请求用到 curl函数来模拟实现
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".self::APPID."&secret=".self::APPSECRET;
        //通过curl模拟get请求从微信平台中获取过来的token是一个josn数据包
        $json_string=$this->curl_get($url);
        //将json格式的数据转换成数组形式 json_decode();
        $array=json_decode($json_string,true);//true   必须加上去
        //通过数组下标方式获取token
        $access_token=$array["access_token"];
        file_put_contents("wechat.text","access_token:".$access_token.PHP_EOL,FILE_APPEND);//日志
        //根据业务需求进行判断
        if($access_token==true){
            return $access_token;
        }
        if($access_token=="errcode"){
            return "因网络问题未能找到你需要的信息";
        }

    }

    //通过access_token来获取用户openid,用户列表
    public function get_userlist(){
        //获取access_token
        $token=$this->get_token();
        //通过地址转换get请求获取openid
        $url="https://api.weixin.qq.com/cgi-bin/user/get?access_token={$token}&next_openid=";
        //调用curl模拟get方法
        $json_string=$this->curl_get($url);
        //将json数据转换成数组形式
        $array=json_decode($json_string,true);
        //通过数组下标获取oppenid
        $openid=$array["data"]["openid"];
        return $openid;
    }

    //获取用户的基本信息
    public function get_user_info($openid){
        //获取access_token
        $token=$this->get_token();
        //通过微信平台地址栏get请求对接接口
        $url="https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$token."&openid=".$openid."&lang=zh_CN";
        $json_string=$this->curl_get($url);

        $array=json_decode($json_string,true);
        return $array;
    }

    ////通过curl（）函数来模拟实现在代码中的post请求  为后续获取access_token做接口
    public function curl_post($url,$data){
        //curl_init — 初始化一个CURL会话
        $ch=curl_init();
        //curl_setopt — 设置一个cURL传输选项
        curl_setopt($ch,CURLOPT_URL,$url);//设置请求地址
        curl_setopt($ch,CURLOPT_POST,true);//启用时会发送一个常规的POST请求
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);//模拟post请求中传送的数据
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);//将curl_exec()获取的信息以文件流的形式返回，而不是直接输出。不显示
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);//关闭ssl证书验证
        $mn_post=curl_exec($ch);
        return $mn_post;
    }

    //群发消息
    public function set_mass(){
        //获取access_token
        $token=$this->get_token();
        $url="https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token=".$token;
        //获取openid
        $openid=$this->get_userlist();
        //字符拼接   $str='"OPENID1","OPENID2",....';
        $str='';
        foreach($openid as $v){
            $str.='"'.$v.'",';
        }
        $str=rtrim($str,',');
        file_put_contents("wechat.text","mass_openid:".$str.PHP_EOL,FILE_APPEND);
        $content='感谢您的关注!';
        $data='{
                    "touser":['.$str.'],
                    "msgtype": "text",
                    "text": { "content": "'.$content.'"}
                }';
        $json_string=$this->curl_post($url,$data);
        file_put_contents("wechat.text","mass_openid:".$json_string.PHP_EOL,FILE_APPEND);
        //将json数据转换成数组形式
        $array=json_decode($json_string,true);
        if($array["errcode"]==0){
            return true;
        }
        return $array["errmsg"];
    }

    //自定义菜单
    public function set_menu(){
        //获取access_token
        $token=$this->get_token();
        $url="https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$token;
        //网页授权地址
    /*    $redirect_uri = urlencode("http://47.101.132.107/tjk_wechat/wysq.php");
        file_put_contents("wechat.text","redirect_uri:".$redirect_uri.PHP_EOL,FILE_APPEND);//日志*/
        $wz=urlencode("http://abc.jiawenjie.com");
        $link = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . self::APPID . "&redirect_uri=$wz&response_type=code&scope=snsapi_userinfo#wechat_redirect";
        file_put_contents("wechat.text","link:".$link.PHP_EOL,FILE_APPEND);//日志
        //post 请求的结果集
        $data='{
                 "button":[{
                      "type":"click",
                      "name":"今日歌曲",
                      "key":"sing"
                  },
                  {
                  "name":"菜单",
                       "sub_button":[{    
                           "type":"view",
                           "name":"搜索",
                           "url":"https://www.baidu.com/"
                        },{    
                           "type":"view",
                           "name":"我的微站博客",
                           "url":"'.$link.'"
                        },                     
                        {
                           "type":"click",
                           "name":"赞一下我们",
                           "key":"dianzhan"
                        }]
                   }]
                }';
        $json_string=$this->curl_post($url,$data);
        file_put_contents("wechat.text","set_menu:".$json_string.PHP_EOL,FILE_APPEND);//日志
        //将json数据转换成数组形式
        $array=json_decode($json_string,true);
        if($array["errcode"]==0){
            return true;
        }
        return $array["errmsg"];
    }

    //发送图文消息
    public function send_news($postObj,$data=[]){
        //发送时间
        $time=time();
        //发送的条数
        $count=count($data);
        $str_msg="<xml>
                    <ToUserName><![CDATA[$postObj->FromUserName]]></ToUserName>
                    <FromUserName><![CDATA[$postObj->ToUserName]]></FromUserName>
                    <CreateTime>{$time}</CreateTime>
                    <MsgType><![CDATA[news]]></MsgType>
                    <ArticleCount>{$count}</ArticleCount><Articles>";
        foreach ($data as $v)
        $str_msg.="<item>
                            <Title><![CDATA[{$v['title']}]]></Title>
                            <Description><![CDATA[{$v['description1']}]]></Description>
                            <PicUrl><![CDATA[{$v['picurl']}]]></PicUrl>
                            <Url><![CDATA[{$v['url']}]]></Url>
                   </item>";
        $str_msg.="</Articles></xml>";
        return $str_msg;
    }

    //天气预报接口
    public function get_weather($city){
        //写的配置文件包含所有城市   代替switch
        $array=include "weather_config.php";
        $city_code=$array["$city"];
        $url="http://www.weather.com.cn/data/sk/{$city_code}.html";
        file_put_contents("wechat_tq.text","tq_city_code:".$city_code.PHP_EOL,FILE_APPEND);//日志
        //模拟get请求
        $json_str=$this->curl_get($url);
        //转换json字符成数组
        $json_array=json_decode($json_str,true);
        file_put_contents("wechat_tq.text","tq_json_array:".print_r($json_array).PHP_EOL,FILE_APPEND);//日志
        return $json_array;
    }

    //快递接口
    public function get_express($express_dh,$express_name="顺丰"){

        switch ($express_name){
            case '顺丰':
                $pinyin = 'shunfeng';
                break;
            case '申通':
                $pinyin = 'shentong';
                break;
            case '邮政':
                $pinyin = 'ems';
                break;
            case '圆通':
                $pinyin = 'yuantong';
                break;
            case '中通':
                $pinyin = 'zhongtong';
                break;
            case '韵达':
                $pinyin = 'yunda';
                break;
            case '天天':
                $pinyin = 'tiantian';
                break;
            default:
                $pinyin = '';
                break;
        }

        //接口链接地址
        $url="http://www.kuaidi100.com/query?type={$pinyin}&postid={$express_dh}";
        //模拟get请求
        $json_str=$this->curl_get($url);
        $json_array=json_decode($json_str,true);
        return $json_array;
    }

}

