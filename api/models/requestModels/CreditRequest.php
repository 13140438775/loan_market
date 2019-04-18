<?php

namespace api\models\requestModels;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class CreditRequest extends Model
{
  public $userId;
  public $phoneNumber;
  public $realName;
  public $phaseCode;
  public $projectCode;
  public $bankCard;
  public $idCard;
  public $packageName;
  public $appName;
  


  /**
   * {@inheritdoc}
   */
  public function rules()
  {
    return [
      [['userId', 'phoneNumber', 'realName', 'bankCard', 'idCard'], 'required'],
      [['userId'], 'integer'],
      [['phoneNumber', 'realName', 'projectCode', 'phaseCode', 'bankCard', 'idCard', 'packageName'],'string', 'max'=>30],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function attributeLabels()
  {
    return [
      'userId' => '用户ID',
      'phoneNumber' => '手机号码',
      'realName' => '真实姓名',
      'projectCode' => '项目代码',
      'phaseCode' => '阶段代码',
      'bankCard' => '银行卡号',
      'idCard' => '身份证号',
      'packageName' => '包名',
      'appName' => 'app名称'
    ];
  }
}
