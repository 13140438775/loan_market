<?php
/**
 * Created by PhpStorm.
 * User: huangweihong
 * Date: 2019/3/23
 * Time: 下午2:33
 */

namespace common\models;

use Yii;

/**
 * This is the model class for table "area".
 *
 * @property int $id
 * @property string $parent 上级区域
 * @property string $keyname 键值
 * @property string $name 名称
 * @property string $en 国家英文名
 * @property string $abridge
 * @property string $mobile_code
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
class Area extends \common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'area';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'keyname', 'name', 'en', 'abridge', 'mobile_code'], 'required'],
            [['id', 'status'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['parent'], 'string', 'max' => 30],
            [['keyname'], 'string', 'max' => 20],
            [['name', 'en'], 'string', 'max' => 100],
            [['abridge'], 'string', 'max' => 10],
            [['mobile_code'], 'string', 'max' => 5],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent' => 'Parent',
            'keyname' => 'Keyname',
            'name' => 'Name',
            'en' => 'En',
            'abridge' => 'Abridge',
            'mobile_code' => 'Mobile Code',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }
}
