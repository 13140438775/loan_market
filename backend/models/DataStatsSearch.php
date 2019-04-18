<?php
/**
 * ${FILE_NMAE}.
 * 文件描述
 * Created On 2019-02-14 15:46
 * Created By heyafei
 */

namespace backend\models;

use yii\base\Model;
use common\models\UserDailyData;
use common\models\ProductDailyData;

class DataStatsSearch extends Model
{
    public $date_begin;
    public $date_end;

    public function rules()
    {
        return [
            [['date_begin', 'date_end'], 'safe']
        ];
    }

    public function getSearchResult($params)
    {
        $this->load($params);
        $this->date_begin = $this->date_begin ? $this->date_begin : date('Y-m-d', time() - 6 * 86400);
        $this->date_end = $this->date_end ? $this->date_end : date('Y-m-d');

        // 1.获取所有的天数
        $date_arr = [];
        $dt_start = strtotime($this->date_begin);
        $dt_end = strtotime($this->date_end);
        while ($dt_start <= $dt_end){
            $date_arr[] = date("Ymd", $dt_start);
            $dt_start = strtotime('+1 day',$dt_start);
        }

        // 2.拿到所有的数据
        $select = [
            'date' => 'user_daily_data.date',
            'app_user' => "COUNT(app_user.id)"
        ];
        $date_user = UserDailyData::find()
            ->select($select)
            ->leftJoin("app_user", "app_user.user_id = user_daily_data.user_id AND app_user.app_id = user_daily_data.app_id")
            ->where(['>=', 'user_daily_data.date', date("Ymd", strtotime($this->date_begin))])
            ->andWhere(['<=', 'user_daily_data.date', date("Ymd", strtotime($this->date_end))])
            ->andWhere(['>', 'user_daily_data.product_num', 0])
            ->groupBy("user_daily_data.date")
            ->indexBy('date')
            ->asArray()
            ->all();

        $select = [
            'date' => 'date',
            'product_num' => "COUNT(product_id)",
            'uv_num' => "SUM(uv)",
            'pv_num' => "SUM(pv)"
        ];
        $date_data = ProductDailyData::find()
            ->select($select)
            ->where(['>=', 'date', date("Ymd", strtotime($this->date_begin))])
            ->andWhere(['<=', 'date', date("Ymd", strtotime($this->date_end))])
            ->groupBy('date')
            ->indexBy('date')
            ->asArray()
            ->all();

        $data_view = [];
        foreach ($date_arr as $date) {
            $temp_date_user = isset($date_user[$date]) ? $date_user[$date]: ['date' => $date,'app_user' => 0];
            $temp_date_data = isset($date_data[$date]) ? $date_data[$date]: ['date' => $date,'product_num' => 0, 'uv_num' => 0, 'pv_num' => 0];
            $data_view[] = array_merge($temp_date_user, $temp_date_data);
        }
        return $data_view;
    }

}