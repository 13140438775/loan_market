<?php

namespace common\models\mk;

use Yii;

/**
 * This is the model class for table "mk_orders".
 *
 * @property int $id 订单表ID
 * @property int $user_id 用户ID
 * @property int $product_id 产品ID
 * @property string $order_sn 唯一订单号
 * @property int $loan_amount 借款金额（分）
 * @property int $loan_term 借款期限
 * @property int $term_type 期限类型(1: 按天; 2: 按月; 3: 按年;)
 * @property int $application 订单来源(2: 现金白卡; 3: 去哪借;)
 * @property string $data_source 订单源数据，存oss
 * @property string $ret_data 订单源数据，存oss
 * @property string $once_msg 一推消息
 * @property int $once_time 一推次数
 * @property string $twice_msg 二推消息
 * @property int $twice_time 二推次数
 * @property int $status 订单状态[0:推单未完成1:待审核；2:审核失败；3.待绑卡;4.待放款;5.放款失败；6.放款成功（未还款状态：包含剩XX天还款/已逾期XX天）；7.已还款；8. 还款中; 9.推单超时; 10.待签约]
 * @property string $remark 备注
 * @property string $contact_info 合同信息存json
 * @property int $loan_bank_id 放款银行卡ID
 * @property int $repay_bank_id 还款银行卡ID
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 * @property int $confirm_amount 待签约借款金额（分）
 * @property int $confirm_term 待签约借款期限
 * @property int $confirm_term_type 待签约期限类型(1: 按天; 2: 按月; 3: 按年;)
 */
class MkOrders extends \common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mk_orders';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'product_id', 'order_sn', 'loan_amount', 'loan_term', 'term_type', 'application'], 'required'],
            [['user_id', 'product_id', 'loan_amount', 'loan_term', 'term_type', 'application', 'once_time', 'twice_time', 'status', 'loan_bank_id', 'repay_bank_id', 'created_at', 'updated_at', 'confirm_amount', 'confirm_term', 'confirm_term_type'], 'integer'],
            [['contact_info'], 'string'],
            [['order_sn'], 'string', 'max' => 32],
            [['data_source', 'once_msg', 'twice_msg'], 'string', 'max' => 256],
            [['ret_data'], 'string', 'max' => 100],
            [['remark'], 'string', 'max' => 128],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '订单表ID',
            'user_id' => '用户ID',
            'product_id' => '产品ID',
            'order_sn' => '唯一订单号',
            'loan_amount' => '借款金额（分）',
            'loan_term' => '借款期限',
            'term_type' => '期限类型(1: 按天; 2: 按月; 3: 按年;)',
            'application' => '订单来源(2: 现金白卡; 3: 去哪借;)',
            'data_source' => '订单源数据，存oss',
            'ret_data' => '订单源数据，存oss',
            'once_msg' => '一推消息',
            'once_time' => '一推次数',
            'twice_msg' => '二推消息',
            'twice_time' => '二推次数',
            'status' => '订单状态[0:推单未完成1:待审核；2:审核失败；3.待绑卡;4.待放款;5.放款失败；6.放款成功（未还款状态：包含剩XX天还款/已逾期XX天）；7.已还款；8. 还款中; 9.推单超时; 10.待签约]',
            'remark' => '备注',
            'contact_info' => '合同信息存json',
            'loan_bank_id' => '放款银行卡ID',
            'repay_bank_id' => '还款银行卡ID',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
            'confirm_amount' => '待签约借款金额（分）',
            'confirm_term' => '待签约借款期限',
            'confirm_term_type' => '待签约期限类型(1: 按天; 2: 按月; 3: 按年;)',
        ];
    }
}
