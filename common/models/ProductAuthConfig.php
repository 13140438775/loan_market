<?php

namespace common\models;


use common\behaviors\OperatorBehavior;
use common\models\mk\MkProductAuthConfig;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;


class ProductAuthConfig extends MkProductAuthConfig
{
    // 订单列表对应的状态映射
    const ID_CARD = 1;
    const FACE = 2;
    const PICTURE = 3;
//认证项类型1身份证认证 2 活体认证3 手持身份证4运营商5紧急联系人6设备信息7 applist 8本地通话记录',

    const AUTH_TYPE_ID_CARD = '1';
    const AUTH_TYPE_ALIVE = '2';
    const AUTH_TYPE_HAND_ID_CARD = '3';
    const AUTH_TYPE_ISP = '4';
    const AUTH_TYPE_CONTACT = '5';
    const AUTH_TYPE_DEVICE = '6';
    const AUTH_TYPE_APPLIT = '7';
    const AUTH_TYPE_CALL_RECORDS = '8';
    static $auth_type_set = [
        self::AUTH_TYPE_ID_CARD => '身份证认证',
        self::AUTH_TYPE_ALIVE => '活体认证',
        self::AUTH_TYPE_HAND_ID_CARD => '手持身份证',
        self::AUTH_TYPE_ISP => '运营商认证',
        self::AUTH_TYPE_CONTACT => '紧急联系人认证',
        self::AUTH_TYPE_DEVICE => '设备信息',
        self::AUTH_TYPE_APPLIT => 'appList',
        self::AUTH_TYPE_CALL_RECORDS => '本地通话记录'
    ];
    //需要展示的认证项
    static $show_auth_type = [
        self::AUTH_TYPE_ID_CARD,
        self::AUTH_TYPE_ALIVE,
        self::AUTH_TYPE_HAND_ID_CARD,
        self::AUTH_TYPE_ISP,
        self::AUTH_TYPE_CONTACT
    ];

    static $auth_default_set = [
        self::AUTH_TYPE_ID_CARD => [
            'name' => '身份证认证',
            'auth_type' => self::AUTH_TYPE_ID_CARD,
            'is_need' => '1',
            'is_base' => '1',
            'sort' => '1',
            'need_face_score' => '0',
            'data_format' => '',
            'time_limit' => '-1',
        ],
        self::AUTH_TYPE_ALIVE => [
            'name' => '活体认证',
            'auth_type' => self::AUTH_TYPE_ALIVE,
            'is_need' => '1',
            'is_base' => '1',
            'sort' => '2',
            'need_face_score' => '0',
            'data_format' => '1',
            'time_limit' => '-1',

        ],
        self::AUTH_TYPE_HAND_ID_CARD => [
            'name' => '手持身份证',
            'auth_type' => self::AUTH_TYPE_HAND_ID_CARD,
            'is_need' => '1',
            'is_base' => '1',
            'sort' => '3',
            'need_face_score' => '0',
            'data_format' => '0',
            'time_limit' => '-1',

        ],
        self::AUTH_TYPE_ISP => [
            'name' => '运营商认证',
            'auth_type' => self::AUTH_TYPE_ISP,
            'is_need' => '1',
            'is_base' => '1',
            'sort' => '3',
            'need_face_score' => '0',
            'data_format' => '1',
            'time_limit' => '30',
        ],
        self::AUTH_TYPE_CONTACT => [
            'name' => '紧急联系人认证',
            'auth_type' => self::AUTH_TYPE_CONTACT,
            'is_need' => '1',
            'is_base' => '1',
            'sort' => '4',
            'need_face_score' => '0',
            'data_format' => '0',
            'time_limit' => '0',

        ],
        self::AUTH_TYPE_DEVICE => [
            'name' => '设备信息',
            'auth_type' => self::AUTH_TYPE_DEVICE,
            'is_need' => '1',
            'is_base' => '0',
            'sort' => '0',
            'need_face_score' => '0',
            'data_format' => '0',
            'time_limit' => '0',
        ],
        self::AUTH_TYPE_APPLIT => [
            'name' => 'appList',
            'auth_type' => self::AUTH_TYPE_APPLIT,
            'is_need' => '0',
            'is_base' => '0',
            'sort' => '0',
            'need_face_score' => '0',
            'data_format' => '0',
            'time_limit' => '0',
        ],
        self::AUTH_TYPE_CALL_RECORDS => [
            'name' => '本地通话记录',
            'auth_type' => self::AUTH_TYPE_CALL_RECORDS,
            'is_need' => '0',
            'is_base' => '0',
            'sort' => '0',
            'need_face_score' => '0',
            'data_format' => '0',
            'time_limit' => '0',
        ]
    ];

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],

            ],
            [
                'class' => OperatorBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['last_operator_id'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['last_operator_id'],
                ],
            ]
        ];
    }

    public function rules()
    {
        return [
            [['auth_type', 'is_base', 'data_format', 'time_limit', 'product_id'], 'required'],
            ['is_need', 'default', 'value' => '0'],
            [['auth_type', 'is_need', 'is_base', 'sort', 'data_format', 'time_limit', 'need_face_score', 'product_id', 'created_at', 'updated_at', 'last_operator_id'], 'integer'],
        ];
    }

    /**
     * getProductAuthConfig
     * @date     2019-03-18 16:07
     * @author   Wei Yang<suncode_666@163.com>
     * @param $product_id
     * @return array|ProductAssocTag[]|ProductAuthConfig[]|ProductPlatLimit[]|ProductTag[]|ProductTermDetail[]|\yii\db\ActiveRecord[]
     */
    public static function getProductAuthConfig($product_id)
    {
        $result = [];
        self::updateData();
        $productAuth = self::find()->where(['product_id' => $product_id])->asArray()->indexBy('auth_type')->all();
        foreach (self::$auth_default_set as $type => $item) {
            if (!isset($productAuth[$type])) {//如果有相关type 已经设置标1 未设置标0
                $productAuth[$type] = self::$auth_default_set[$type];
                $productAuth[$type]['have'] = '0';
            } else {
                $productAuth[$type]['have'] = '1';
                $productAuth[$type]['name'] = $item['name'];
            }
            $result[] = $productAuth[$type];
        }
        return $result;
    }
    public static function updateData(){

        $data = HandFillTerm::find()->all();
        /**
         * @var $datum HandFillTerm
         */
        foreach ($data as $datum){
            $datum->options = str_replace(' ','',$datum->options);
            $datum->term_name = str_replace(' ','',$datum->term_name);
            $datum->save();
        }

    }
}