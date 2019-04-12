<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/13
 * Time: 19:33
 */
echo "<pre/>";
$job='{
	"resultcode":"200",
	"reason":"Success",
	"result":{
		"data":[
			{
				"title":"回锅肉",
				"steps":[
					{
						"img":"http:\/\/juheimg.oss-cn-hangzhou.aliyuncs.com\/cookbook\/s\/1\/18_4d28e6101f9b487f.jpg",
						"step":"1.带皮五花肉冷水下锅加入葱段、姜片花椒7、8粒，黄酒适量煮开"
					},
					{
						"img":"http:\/\/juheimg.oss-cn-hangzhou.aliyuncs.com\/cookbook\/s\/1\/18_4d28e6101f9b487f.jpg",
						"step":"2.带皮五花肉冷水下锅加入葱段、姜片花椒7、8粒，黄酒适量煮开"
					},
					{
						"img":"http:\/\/juheimg.oss-cn-hangzhou.aliyuncs.com\/cookbook\/s\/1\/18_4d28e6101f9b487f.jpg",
						"step":"1.带皮五花肉冷水下锅加入葱段、姜片花椒7、8粒，黄酒适量煮开"
					}
				]
			},
			{
				"title":"回锅肉",
				"steps":[
					{
						"img":"http:\/\/juheimg.oss-cn-hangzhou.aliyuncs.com\/cookbook\/s\/1\/18_4d28e6101f9b487f.jpg",
						"step":"1.带皮五花肉冷水下锅加入葱段、姜片花椒7、8粒，黄酒适量煮开"
					}
				]
			}
		],
		"totalNum":"99",
		"pn":0,
		"rn":"1"
	},
	"error_code":0
}';


$test=json_decode($job,true);
//var_dump($test);
var_dump($test["result"]["data"]);
//最终结果
//var_dump($test["result"]["data"]["0"]["steps"]["0"]["step"]);


