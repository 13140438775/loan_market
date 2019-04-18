<?php

namespace common\models;

use function foo\func;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "mk_hand_fill_term".
 *
 * @property int $id 手填项表
 * @property string $term_key 手填项key
 * @property string $term_name 名称
 * @property int $type 类型 1 txt 2 单选 3 多选
 * @property string $options 单选和多选的选项 存json {'1':'男','2':'女'}
 * @property int $career_type 手填项类型0不属于任何职业类型 1 上班族 2 企业主 3 个体户 4 自由职业 5 学生
 * @property int $is_must 是否是必填 0 选填 1 必填
 * @property string $place_holder 输入提示 place_holder
 * @property int $term_group_id 所属分组id
 * @property int data_type 类型 0 string 1 int
 * @property int $sort 排序 越大越靠前
 */
class HandFillTerm extends \common\models\mk\MkHandFillTerm
{
    const TXT_TYPE = 1;
    const SINGLE_SELECT_TYPE = 2;
    const MULTIPLE_SELECT_TYPE = 3;
    const AREA_TYPE = 4;
    static $type_set = [
        self::TXT_TYPE => '文本',
        self::SINGLE_SELECT_TYPE => '单选',
        self::MULTIPLE_SELECT_TYPE => '多选',
        self::AREA_TYPE => '地址'
    ];

     const DATA_TYPE_STRING = 0;
     const DATA_TYPE_INT = 1;
     static $data_type_set = [
         self::DATA_TYPE_STRING => '字符串',
         self::DATA_TYPE_INT => '整数'
     ];


    static $career_type_set = [
        '0' => '非职业类型',
        '1' => '上班族',
        '2' => '企业主',
        '3' => '个体户',
        '4' => '自由职业',
        '5' => '学生'
    ];
    static $career_type_set_key = [
        '1' => 'shangbanzu',
        '2' => 'qiyezhu',
        '3' => 'getihu',
        '4' => 'ziyouzhiye',
        '5' => 'xuesheng'
    ];
    static $is_must_set = [
        '0' => '非必填',
        '1' => '必填'
    ];

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['term_key', 'term_name', 'type', 'career_type', 'term_group_id'], 'required'],
            [['type', 'career_type', 'is_must', 'term_group_id', 'sort'], 'integer'],
            [['options'], 'string'],
            [['term_key', 'term_name'], 'string', 'max' => 255],
            [['term_key', 'term_name','options'], 'trim'],
            ['place_holder','default','value' => ''],
//            ['place_holder','required','when' => function($model){
//                return $model->type === self::TXT_TYPE;
//            }]

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '手填项表',
            'term_key' => '手填项key',
            'term_name' => '名称',
            'type' => '类型',
            'options' => '单选和多选的选项(json) ',
            'career_type' => '手填项类型',
            'is_must' => '是否是必填',
            'place_holder' => '输入提示',
            'term_group_id' => '所属分组id',
            'sort' => '排序 (越大越靠前)',
        ];
    }

    public static function getGroups()
    {
        //排除父级
//        $pids = UserInfoGroup::find()->select('pid')
//            ->where(['>','pid',0])
//            ->andWhere(['is_hand_term' => UserInfoGroup::IS_HAND_FILL])
//            ->column();
        //排除父级
        $r = UserInfoGroup::find()->select('id, group_name')
            ->where(['is_hand_term' => UserInfoGroup::IS_HAND_FILL])
//            ->andFilterWhere(['not in', 'id', $pids])
            ->asArray()->all();
        return ArrayHelper::map($r,'id','group_name');
        /**
         * [
         *  '基础' => [
         *      '1' => '职业信息',
         *      '2' => '学历信息'
         *  ],
         *  '父级2' => [
         *      '4' => 'nbbbb'
         *  ],
         *  '1' => '车辆'
         *
         * ]
         */
//        function arrayToTree(Array $items)
//        {
//            foreach ($items as $item) {
//                $items[$item['pid']]['son'][$item['id']] = &$items[$item['id']];
//            }
//            return isset($items[0]['son']) ? $items[0]['son'] : array();
//        }
//        $t = arrayToTree($r);
//        $result = [];
//        foreach ($t as $k => $i){
//            if(isset($i['son'])){
//                $temp = [];
//                foreach ($i['son'] as $item_k =>  $item){
//                    $temp[$item_k] = $item['group_name'];
//                }
//                $result[$i['group_name']] = $temp;
//            }else{
//                $result[$k] = $i['group_name'];
//            }
//        }
//        return $result;
    }
    //获取所有题目
    public static function getAllHandTerms(){
        $result = self::find()->select('id,term_key,term_name,type,options,career_type')->orderBy(['sort'=>SORT_ASC])->asArray()->all();
        return $result;
    }

    //获取职业联动的题目
    public static function getCareerTerms(){
        //获取职业类型题目
        $career = self::find()->select('id,term_key,term_name,type,options,career_type')->where(['sort' =>0])->asArray()->one();
        $result = self::find()->select('id,term_key,term_name,type,options,career_type')->where(['>','career_type','0'])->orderBy(['sort'=>SORT_DESC])->asArray()->all();
        //根据职业类型分组
        $result = ArrayHelper::index($result,null,'career_type');
        $terms = [];
        foreach ($result as $k => $item){
            $terms[self::$career_type_set_key[$k]] = $item;
        }

        return array_merge(['career' => $career],$terms);
    }
}
