<?php

namespace app\admin\controller;

class Image extends AdminBase
{
        public  function upload()
        {
            if (!$this->request->isPost()){
                return show(config('status.error'),'请求不合法');
            }
            // 获取表单上传文件 例如上传了001.jpg
            $file = request()->file('file');
            // 上传到本地服务器
            $filename = \think\facade\Filesystem::disk('public')->putFile( 'upload', $file);
            if (!$filename){
                return show(config('status.error'),'上传图片失败');
            }
            $imageUrl=['image'=>"/storage/".$filename];
            return show(config('status.status'),'OK',$imageUrl);

        }
        public  function layUpload()
        {

            if (!$this->request->isPost()){
                return show(config('status.error'),'请求不合法');
            }
            // 获取表单上传文件 例如上传了001.jpg
            $file = request()->file('file');
            // 上传到本地服务器
            $filename = \think\facade\Filesystem::disk('public')->putFile( 'upload', $file);
            if (!$filename){
               return  json(["code"=>1,"data"=>[]],200);
            }
            $result=[
                "code"=>0,
                'data'=>[
                    'src'=>"/storage/".$filename
                ]
            ];

            return json($result,200);
        }
}