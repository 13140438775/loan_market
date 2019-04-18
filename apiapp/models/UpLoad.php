<?php

namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;
use OSS\OssClient;
use OSS\Core\OssException;


class UpLoad extends Model
{
    /**
     * @var UploadedFile
     */
    public $imageFiles;

    public function rules()
    {
        return [
            [['imageFiles'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png,jpg,jpeg','maxSize'=>2*1024*1024, 'maxFiles' => 5],
        ];
    }

    public function upload()
    {

        if ($this->validate()) {
            try {
                foreach ($this->imageFiles as $imageFile) {
                    $ossConfig = \Yii::$app->params['oss'];
                    $filePrefix = md5(microtime());

                    $localFile = \Yii::getAlias('@apiapp') . '/upload/' . $filePrefix . '.' . $imageFile->extension;

                    $imageFile->saveAs($localFile);
                    $accessKeyId = $ossConfig['key'];
                    $accessKeySecret = $ossConfig['secret'];
                    $endpoint = $ossConfig['endpoint'];
                    $bucket = $ossConfig['bucket'];
                    $object = 'loan_market/' . $filePrefix .'_'. $imageFile->name;
                    $filePath = $localFile;
                    $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
                    //TODO 判断是否上传成功，需要私密请求
                    $ossClient->uploadFile($bucket, $object, $filePath);
                    @unlink($localFile);
                    $url[] = $object;
                }
                return $url;
            } catch (OssException $e) {
                return false;
            }
        } else {
            return false;
        }
    }

}