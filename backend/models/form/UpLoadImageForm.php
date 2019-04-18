<?php

namespace backend\models\form;

use yii\base\Model;
use yii\web\UploadedFile;
use OSS\OssClient;
use OSS\Core\OssException;
use Yii;

class UpLoadImageForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $imageFile;

    public function rules()
    {
        return [
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png,jpg,jpeg'],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            try {
                $ossConfig = \Yii::$app->params['oss'];
                $filePrefix = md5(microtime());
                $localFile = \Yii::getAlias('@backend').'/upload/' . $filePrefix . '.' . $this->imageFile->extension;
                $this->imageFile->saveAs($localFile);
                $accessKeyId = $ossConfig['key'];
                $accessKeySecret = $ossConfig['secret'];
                $endpoint = $ossConfig['endpoint'];
                $bucket = $ossConfig['bucket'];
                $object = 'loan_market/'.$filePrefix.'.'.$this->imageFile->extension;
                $filePath = $localFile;
                $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
                $ossClient->uploadFile($bucket, $object, $filePath);
                @unlink($localFile);
                return $object;
            } catch (OssException $e) {
                return false;
            }
        } else {
            return false;
        }
    }
}