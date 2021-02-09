<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/16
 * Time: 20:33
 */

namespace xinyeweb\webuploader;


use yii\web\AssetBundle;
class WebUploaderAsset extends AssetBundle
{
    public $css = [
        'style.css',
        'webuploader.css',
        'css/style.css',
    ];
    public $js = [
        'webuploader.min.js',
        'init.js',
        'js/sortable.min.js'
    ];
    public $depends = [
        'yii\bootstrap\BootstrapPluginAsset',
    ];
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets';
        parent::init();
    }
}