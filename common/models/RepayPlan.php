<?php

namespace common\models;

use common\models\mk\MkRepayPlan;
use common\models\RepayPlanItems;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class RepayPlan extends MkRepayPlan 
{

    const ONE_BEFORE = 1; // 单期提前
    // 单期不提前
    const ONE_NO_BEFORE = 2;
    // 多期只可以提前还全款
    const MORE_BEFORE_ALL = 1;
    // 多期可以提前还任意期数
    const MORE_BEFORE_ANY = 2;
    // 只可以还当前期数
    const MORE_BEFORE_CURRENT = 3;

    // 合并逾期
    const COMBINE_OVERDUE = 1;
    // 不合并逾期
    const NO_COMBINE_OVERDUE = 2;



    /**
     * getRepayPlanItems 还款计划子项
     * @author: 周晓坤1426801685@qq.com
     * @param {type} 
     * @Date: 2019-03-12 19:38:31
     */
    public function getRepayPlanItems()
    {
        return $this->hasMany(RepayPlanItems::className(),['repay_plan_id' => 'id']);
    }

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
        ];
    }
}