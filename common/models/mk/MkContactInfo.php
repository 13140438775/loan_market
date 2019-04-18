<?php
/**
 * Created by PhpStorm.
 * User: huangweihong
 * Date: 2019/3/8
 * Time: 上午9:58
 */

namespace common\models\mk;

use common\models\Base;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "mk_contact_info".
 *
 * @property int $id 用于存储用户的紧急联系人信息，自增长 ID
 * @property int $user_id 用户 ID, 是 loan_user_info 表的外键 id
 * @property string $name 第一联系人姓名
 * @property string $mobile 第一联系人手机号
 * @property string $relation 第一联系人与用户关系
 * @property string $name_spare 第二联系人姓名
 * @property string $mobile_spare 第二联系人手机号
 * @property string $relation_spare 第二联系人与用户关系
 * @property int $created_at 修改时间
 * @property int $updated_at 创建时间
 */

class MkContactInfo extends Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mk_contact_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'name', 'mobile', 'relation', 'name_spare', 'mobile_spare', 'relation_spare'], 'required'],
            [['user_id','created_at', 'updated_at'], 'integer'],
            [['name', 'relation', 'name_spare', 'relation_spare'], 'string', 'max' => 30],
            [['mobile', 'mobile_spare'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'name' => 'Name',
            'mobile' => 'Mobile',
            'relation' => 'Relation',
            'name_spare' => 'Name Spare',
            'mobile_spare' => 'Mobile Spare',
            'relation_spare' => 'Relation Spare',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

}