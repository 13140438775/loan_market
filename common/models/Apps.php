<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%apps}}".
 *
 * @property int $id
 * @property string $app_code
 * @property string $app_name
 * @property int $created_at
 */
class Apps extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%apps}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['app_code', 'app_name', 'created_at'], 'required'],
            [['created_at'], 'integer'],
            [['app_code', 'app_name'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'app_code' => 'App Code',
            'app_name' => 'App Name',
            'created_at' => 'Created At',
        ];
    }
    public static function getIdNameMap(){
        return ArrayHelper::map(self::find()->select('id,app_name')->asArray()->all(),'id','app_name');
    }
    public static function dropDownList(){
        return [0=>'全部']+ self::getIdNameMap();
    }
}
