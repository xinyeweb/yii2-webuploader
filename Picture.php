<?php
/**
 * Created by PhpStorm.
 * User: hoter.zhang
 * Date: 2017/9/7
 * Time: 10:48
 */

namespace xinyeweb\webuploader;


use Yii;

class Picture extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%picture}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'status'], 'integer'],
            [['path'], 'string', 'max' => 255],
            [['md5'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'path' => 'Path',
            'md5' => 'Md5',
            'created_at' => 'Created At',
            'status' => 'Status',
        ];
    }

    /**
     * 获取图片信息
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function getPic($id) {
        return static::find()->where(['id'=>$id])->asArray()->one();
    }

    /**
     * 下面的方法目前沒用到
     */

    /**
     * 保存一張圖片到數據庫
     * @param $url 图片路径
     * @return array|bool|null|\yii\db\ActiveRecord
     */
    public static function savePic($url) {
        $filePath = \Yii::$app->params['upload']['path'].$url;
        $fileMd5 = md5_file($filePath);
        $image = static::find()->where(['md5'=>$fileMd5])->asArray()->one();
        if ($image) {
            @unlink($filePath);
            return $image;
        }
        $model = new Picture();
        $data['path'] = $url;
        $data['md5']  = $fileMd5;
        $data['create_time'] = time();
        $data['status'] = 1;
        $model->setAttributes($data);
        if ($model->save()) {
            return $model->getAttributes();
        }
        return false;
    }

    /**
     * 清除不在picture表中的图片
     * @param $sPath 图片所在路径
     */
    public static function clearPic($sPath){
        if (is_dir($sPath)) {
            $fp = opendir($sPath);
            while(!false == ($fn = readdir($fp))){
                if($fn == '.' || $fn =='..') continue;
                if(strpos($fn, 'editor') === 0) continue; //编辑器上传的图片被忽略
                $sFilePath = $sPath.DIRECTORY_SEPARATOR.$fn;
                self::clearPic($sFilePath);
            }
        } else {
            //. ..文件直接跳过，不处理
            if ($sPath != '.' && $sPath != '..') {
                $file_md5 = md5_file($sPath);
                $picModel = static::find()->where(['md5' => $file_md5])->asArray()->one();
                /* md5和文件名都正确才不删除 */
                if (!$picModel || strpos($sPath, $picModel['path']) === false) {
                    if (@unlink($sPath) === true) {
                        echo date("Y-m-d H:i:s") . '成功删除文件' . $sPath . PHP_EOL;
                    } else {
                        echo date("Y-m-d H:i:s") . '删除失败========ERROR=========' . $sPath . PHP_EOL;
                    }
                } else {
                    echo '文件存在，id=' . $picModel['id'] . ' ' . PHP_EOL;
                }
            }
        }
    }

    /**
     *  函数功能信息 public private protected static
     */
    public static function clearPic1(){
        $query = static::find()->orderBy('id ASC');
        foreach ($query->each() as $value) {//var_dump($value->path);
            $file_path = Yii::$app->params['upload']['path'].$value->path;
            if (!is_file($file_path)) {
                echo '文件在磁盘中不存在，已删除 id='.$value->id.PHP_EOL;
                $value->delete();
                continue;
            }
            $file_md5  = md5_file($file_path);
            if ($file_md5 == $value['md5']) {
                continue;
            } else {
                echo '文件被修改，注意是否含木马 '.$file_path.PHP_EOL;
            }
        }
    }
}