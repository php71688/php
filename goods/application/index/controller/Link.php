<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/2
 * Time: 13:32
 */
namespace app\index\controller;

use think\Controller;
use think\Db;
use app\index\model\Link as links;
use think\Session;

class Link extends Controller
{
    public function links(){
        $tid = session('type_id');

        $strs="QWERTYUIOPASDFGHJKLZXCVBNM1234567890qwertyuiopasdfghjklzxcvbnm";
        $name=substr(str_shuffle($strs),mt_rand(0,strlen($strs)-11),5);
//        $sUrl = urlShort($name);
        $url = 'https://img.boxinid.com/Link/link_list/'.$name."\n\n";
        $data = [
            'url'=>$url,
            'addtime'=>time(),
            'tid'=>$tid,
        ];
        $add = links::insertGetId($data);
        if ($add){
            return json(['status' => '1', 'msg' => '生成成功']);
        } else {
            return json(['status' => '2', 'msg' => "生成失败"]);
        }
    }

    public function lists(){
        $list = Db::table('links')->order('id','desc')->find();
        return view('',['list'=>$list]);
    }

    public function link_list(){
        $id = input('id');
        $data =Db::table('links a')
            ->join('type b','a.tid=b.id')
            ->join('goods c','c.tid=b.id')
            ->order('c.id','desc')
            ->where('a.id',$id)
            ->field('b.cover as img,c.*')
            ->limit(10)
            ->select();
        foreach($data as $k => $v){
            $data[$k]['cover'] = explode(',',$v['cover']);
        }
//        dump($data);
        return $this->fetch('link_list',['data'=>$data]);
    }


}