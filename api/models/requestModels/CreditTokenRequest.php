<?php

namespace api\models\requestModels;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class CreditTokenRequest extends Model
{
  public $keyType;
  public $key;
  public $userId;



  /**
   * {@inheritdoc}
   */
  public function rules()
  {
    return [
      [['keyType', 'key', 'userId'], 'required'],
      [['userId'], 'integer'],
      [['key'], 'string', 'max' => 45],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function attributeLabels()
  {
    return [
      'userId' => '用户ID',
      'keyType' => 'token类型',
      'key' => 'token值'
    ];
  }
}
