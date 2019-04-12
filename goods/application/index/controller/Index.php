<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/14
 * Time: 17:57
 */

namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Session;
use app\index\model\Goods;
class Index extends Base
{
    public function index()
    {
        return view();
    }

    public function welcome()
    {

        if (request()->isPost()) {
            $files = request()->file('imgs');

                foreach ($files as $file) {
                    // 移动到框架应用根目录/public/uploads/ 目录下
                    $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
                    if($info){
                        $arr[] = '/uploads/' . $info->getSaveName();
                        $imgp = str_replace("\\", "/", $arr);
                    }else{
                        return json(['status'=>3,'msg'=>$file->getError()]);
                    }
            }
            $data['cover'] = implode(',', $imgp);
            $data['title'] = input('title');
            $data['name'] = input('name');
            $data['specifications'] = input('specifications');
            $data['makerprice'] = input('makerprice');
            $data['tid'] = input('tid');
            $data['detail'] = input('detail');
            $data['shop_price'] = input('shop_price');
            $data['sold'] = input('sold');
            $data['addtime'] = time();

            session('type_id', $data['tid']);
            $res = Goods::insertGetId($data);

            if ($res) {
                return json(['status' => '1', 'msg' => '添加成功']);
            } else {
                return json(['status' => '2', 'msg' => "添加失败"]);
            }
        }

        $list = Db::table('type')->select();
        return view('', ['list' => $list]);

    }


    public function add(){
        if(request()->isPost()) {
            $file = request()->file("cover");
            if (!empty($file)) {
                $info = $file->validate(['ext' => 'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'upload');
                if ($info) {
                    //返回文件路径
                    $attrurl = 'upload' . '/' . $info->getSaveName();
                    //文件目录根转义
                    $imgp = str_replace("\\", "/", $attrurl);
                    $data['cover'] = $imgp;
                } else {
                    return json(['status' => 3, 'msg' => '图片格式不正确']);
                }
                $data['name'] = input('name');
                $add = Db::table('type')->insert($data);
                if($add){
                    return json(['status' => '1','msg' => '添加成功']);
                }else{
                    return json(['status' => '2', 'msg' => "添加失败"]);
                }
            }else{
                return json(['status' => '3', 'msg' => "请选择图片"]);
            }
        }

        return view();
    }
}