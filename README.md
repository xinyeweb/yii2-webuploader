yii2-webuploader
================
yii2-webuploader

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist xinyeweb/yii2-webuploader "*"
```

or add

```
"xinyeweb/yii2-webuploader": "dev-master"
```

to the require section of your `composer.json` file.


Usage
-----

單圖使用  :

```php
<?= \xinyeweb\webuploader\WebUploader::className(); ?>
```
多圖使用  :
```php
<?= 
    $form->field($model, 'pics')->widget(\app\core\widgets\webuploader\WebUploader::className(),[
        'clientOptions' => [
            'pick' => [
                'multiple' => true,
            ],
        ]
    ]) ?>
```
Controller 中配置上傳  :
```php
    public function actions() {
        return ArrayHelper::merge(parent::actions(), [
            'upload' => [
                'class' => '\xinyeweb\webuploader\WebUploaderAction',
            ],
        ]);
    }
```
高級版 上傳目錄
```php
    Yii::setAlias('@upload', dirname(dirname(__DIR__)) . '/web/uploads'); //存储目录path
```
基礎版 上傳目錄 入口文件
```php
    Yii::setAlias('@upload', __DIR__ . '/uploads'); //存储目录path
```