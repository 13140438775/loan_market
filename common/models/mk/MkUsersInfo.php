<?php

namespace common\models\mk;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "mk_users_info".
 *
 * @property int $id 用户信息扩展表ID
 * @property int $user_id 用户 ID, 是 loan_user_info 表的外键 id
 * @property string $face_recognition_picture 人脸照片，多张json
 * @property string $face_recognition_picture_score 人脸分
 * @property int $face_recognition_picture_time 活体认证时间
 * @property string $id_number_picture 手持身份证头像URL
 * @property int $id_number_picture_time 手持认证时间
 * @property string $id_number_z_picture 身份证正面照片URL
 * @property string $id_number_f_picture 身份证反面照片URL
 * @property string $ocr_name OCR识别身份证姓名
 * @property string $ocr_race OCR识别身份证民族
 * @property string $ocr_sex OCR识别身份证性别
 * @property string $ocr_birthday OCR识别身份证出生日期
 * @property string $ocr_id_number OCR识别身份证号
 * @property string $ocr_address OCR识别身份证地址
 * @property string $ocr_issued_by OCR识别身份证发证机关
 * @property string $ocr_start_time OCR识别身份证有效期开始时间
 * @property string $ocr_end_time OCR识别身份证有效期结束时间
 * @property string $operator 运营商数据，oss文件和验证时间
 * @property string $profession 职业信息大全
 * @property int $operator_online
 * @property int $created_at 修改时间
 * @property int $updated_at 创建时间
 */

class MkUsersInfo extends \common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mk_users_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'face_recognition_picture'], 'required'],
            [['user_id', 'face_recognition_picture_time', 'id_number_picture_time', 'operator_online', 'created_at', 'updated_at'], 'integer'],
            [['profession'], 'string'],
            [['face_recognition_picture', 'operator'], 'string', 'max' => 512],
            [['face_recognition_picture_score', 'ocr_birthday', 'ocr_start_time', 'ocr_end_time'], 'string', 'max' => 10],
            [['id_number_picture', 'id_number_z_picture', 'id_number_f_picture', 'ocr_address', 'ocr_issued_by'], 'string', 'max' => 100],
            [['ocr_name', 'ocr_race'], 'string', 'max' => 30],
            [['ocr_sex'], 'string', 'max' => 3],
            [['ocr_id_number'], 'string', 'max' => 18],
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
            'face_recognition_picture' => 'Face Recognition Picture',
            'face_recognition_picture_score' => 'Face Recognition Picture Score',
            'face_recognition_picture_time' => 'Face Recognition Picture Time',
            'id_number_picture' => 'Id Number Picture',
            'id_number_picture_time' => 'Id Number Picture Time',
            'id_number_z_picture' => 'Id Number Z Picture',
            'id_number_f_picture' => 'Id Number F Picture',
            'ocr_name' => 'Ocr Name',
            'ocr_race' => 'Ocr Race',
            'ocr_sex' => 'Ocr Sex',
            'ocr_birthday' => 'Ocr Birthday',
            'ocr_id_number' => 'Ocr Id Number',
            'ocr_address' => 'Ocr Address',
            'ocr_issued_by' => 'Ocr Issued By',
            'ocr_start_time' => 'Ocr Start Time',
            'ocr_end_time' => 'Ocr End Time',
            'operator' => 'Operator',
            'profession' => 'Profession',
            'operator_online' => 'Operator Online',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

}