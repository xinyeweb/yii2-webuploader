<?php
/**
 * Created by PhpStorm.
 * User: hoter.zhang
 * Date: 2017/8/17
 * Time: 8:36
 */

namespace xinyeweb\webuploader;


use Yii;
use yii\base\Action;
use yii\web\UploadedFile;

class WebUploaderAction extends Action
{
    public function run(){
        $json = [
            'code'  => 1,
            'msg'  => '上传失败',
            'data' => ''
        ];
        if (!$imagePath = $this->uploadImage()) $this->ajaxReturn($json);
        if (!$res = $this->savePic($imagePath)) $this->ajaxReturn($json);
        $json = [
            'code' => 0,
            'url' => $res['url'],
            'attachment' => $res['attachment']
        ];
        $this->ajaxReturn($json);
    }

    public function savePic($imagePath){
//        $file_path = Yii::$app->params['upload']['path'].$url;
        $file_path = Yii::getAlias('@upload'). '/' .$imagePath;
        $file_md5  = md5_file($file_path);
        $image = Picture::find()->where(['md5'=>$file_md5])->asArray()->one();
        if ($image) {
            unlink($file_path); // 图片已存在，删除该图片
            return [
                'url' => '/uploads/'. $image['path'],
                'attachment' => $image['id']
            ];
        }
        $model = new Picture();
        $data['path'] = $imagePath;
        $data['md5']  = $file_md5;
        $data['created_at'] = time();
        $data['status'] = 1;
        $model->setAttributes($data);
        if ($model->save()) {
            return [
                'url' => '/uploads/'. $imagePath,
                'attachment' => $model->getPrimaryKey()
            ];
        }
        return false;
    }

    public function uploadImage(){
//        echo dirname(\Yii::$app->basePath);die;
//        echo Yii::getAlias('@upload');die;
        $upload = UploadedFile::getInstanceByName('file');
        $month = date('Ym');
        $dirName = Yii::getAlias('@upload') . DIRECTORY_SEPARATOR . $month . DIRECTORY_SEPARATOR;
        $dir = $this->fileExists($dirName);
        $imgName = time() . mt_rand(0, 9999) . '.' .$upload->extension;
        $file = $dir . $imgName;
        $imagePath = $month. '/' . $imgName;
        if ($upload->saveAs($file)) {
            return $imagePath;
        }
        return false;
    }

    public function ajaxReturn($data) {
        // 返回JSON数据格式到客户端 包含状态信息
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode($data));
    }

    protected function fileExists($dir) {
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
            chmod($dir, 0777);
        }
        return $dir;
    }
}