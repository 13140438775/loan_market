<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "channels".
 *
 * @property int $id 主键
 * @property string $channel_name 渠道名称
 * @property int $merchant_id 商户
 * @property int $type 渠道类型 1=>贷超 2=>应用市场(ios) 3=>应用市场(安卓) 4=>用户营销
 * @property int $cooperation 合作方式 1=>uv 2=>cpa 3=>cpc 4=>cps 5=>免费
 * @property int $is_filling 是否显示备案号 0=>禁用 1=>启用
 * @property int $is_company_name 是否显示公司名 0=>不显示 1=>显示
 * @property int $template_id H5模版ID
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 * @property int $channel_id 唯一标识
 */
class Channels extends \yii\db\ActiveRecord
{
    const CHANNELID = 1;
    public $channel_type = [
        self::CHANNELID => "用钱金卡",
    ];

    const FILLING = 1;
    public $filling_type = [
        self::FILLING => "备案号",
    ];

    const COMPANY_NAME = 1;
    public $company_type = [
        self::FILLING => "公司名",
    ];

    const LOAN_MARKET = 1;
    const APPLICATION_MARKET_IOS = 2;
    const APPLICATION_MARKET_ANDROID = 3;
    const MARKETING = 4;

    public $market_type_set = [
        self::LOAN_MARKET => "贷超",
        self::APPLICATION_MARKET_IOS => "应用市场(ios)",
        self::APPLICATION_MARKET_ANDROID => "应用市场(安卓)",
        self::MARKETING => "用户营销"
    ];


    const UV = 1;
    const CPA = 2;
    const CPC = 3;
    const CPS = 4;
    const FREE = 5;

    public $cooperation_set = [
        self::UV => "uv",
        self::CPA => "cpa",
        self::CPC => "cpc",
        self::CPS => "cps",
        self::FREE => "免费"
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'channels';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'type', 'cooperation', 'template_id', 'created_at', 'updated_at', 'channel_id'], 'integer'],
            [['channel_name', 'merchant_id', 'type', 'cooperation', 'channel_id', 'template_id'], 'required'],
            [['channel_id'], 'unique'],
            [['short_url'], 'default', 'value' => 'www.baidu.com'],
            [['channel_name'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '渠道ID',
            'channel_name' => '渠道名称',
            'merchant_id' => '商户',
            'type' => '渠道类型',
            'cooperation' => '合作方式',
            'short_url' => '注册页',
            'is_filling' => 'Is Filling',
            'is_company_name' => 'Is Company Name',
            'template_id' => '模板选择',
            'created_at' => '创建时间',
            'updated_at' => '上次修改时间',
            'created_id' => '创建人',
            'updated_id' => '上次修改人',
            'channel_id' => '唯一标识',
        ];
    }

    public function getLoanMerchantInfo()
    {
        return $this->hasOne(LoanMerchantinfo::className(), ['id' => 'merchant_id']);
    }

    public function getInteralUser(){
        return $this->hasOne(Admin::className(), ['id' => 'created_id']);
    }

    public function getCreatedInteralUser(){
        return $this->hasOne(Admin::className(), ['id' => 'created_id']);
    }

    public function getupdatedInteralUser(){
        return $this->hasOne(Admin::className(), ['id' => 'updated_id']);
    }

    public function gettemplate(){
        $template = [];
        foreach(\common\models\HtmlManage::find()->all() as $key => $item){
            $template[$item->id] = $item->name;
        }
        return $template;
    }

    public function beforeSave($insert){
        if(parent::beforeSave($insert)){
            if($insert){
                $this->created_id = Yii::$app->user->id;
                $this->updated_id = Yii::$app->user->id;
                $this->created_at = time();
                $this->updated_at = time();
            } else {
                $this->updated_at = time();
                $this->updated_id = Yii::$app->user->id;
            }

            return true;
        } else {
            return false;
        }
    }
}
