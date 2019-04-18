<?php
/**
 * Created by PhpStorm.
 *
 * @Author     : sunforcherry@gmail.com
 * @CreateTime 03/08/2018 11:13:07
 */

namespace common\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\base\InvalidCallException;

class Base extends ActiveRecord
{

    /**
     * 每页数目
     * @var int
     */
    const PAGE_NUM = 10;

    protected static $map = [];

    /**
     * @var $query ActiveQuery
     */
    protected $query;

    protected $additions = [];

    public function __construct($additions = [], $config = [])
    {
        parent::__construct($config);
        $this->additions = $additions;
        $this->query     = $this->newQuery();
    }

    /**
     * @param array $additions
     * @return $this
     */
    public function setAdditions(array $additions = [])
    {
        $additions = array_merge($this->additions, $additions);
        foreach ($additions as $key => $v) {
            if (method_exists($this->query, $key)) {
                call_user_func_array([$this->query, $key], $v);
            } else {
                throw new InvalidCallException("invalid method call");
            }
        }
        return $this;
    }

    /**
     * @return ActiveQuery
     */
    public function getQuery()
    {

        return $this->query;
    }

    /**
     * @param string $q
     * @param null   $db
     * @return int|string
     * @CreateTime 18/3/14 11:47:31
     * @Author     : fangxing@likingfit.com
     */
    public function count($q = "*", $db = null)
    {

        return $this->getQuery()->count($q, $db);
    }

    /**
     * 文件描述
     * Created On 2019-02-28 11:58
     * Created By heyafei
     * @return object
     * @throws \yii\base\InvalidConfigException
     */
    public function newQuery()
    {
        return Yii::createObject(ActiveQuery::class, [get_called_class()]);
    }

    /**
     * 文件描述 翻页
     * Created On 2019-02-28 11:58
     * Created By heyafei
     * @param array $conditions
     * @param array $join
     * @param bool $return
     * @return $this|array|ActiveRecord[]
     */
    public function getList($conditions = [], $join = [], $return = false)
    {
        $join = array_replace([null, true, "LEFT JOIN"], $join);
        list($with, $eagerLoading, $joinType) = $join;
        $this->setAdditions()
            ->getQuery()
            ->joinWith($with, $eagerLoading, $joinType)
            ->filterWhere($conditions);
        if ($return) {
            return $this;
        }
        return $this->getQuery()->asArray()->all();
    }

    /**
     * 文件描述 分页函数
     * Created On 2019-02-28 11:57
     * Created By heyafei
     * @param int $page
     * @param int $pageSize
     * @param array $join
     * @param array $conditions
     * @return array
     */
    public function paginate($page = 1, $pageSize = 10, $join = [], $conditions = [])
    {
        $this->setAdditions();
        $query = $this->getQuery();
        list($with, $eagerLoading, $joinType) = array_replace([null, true, "LEFT JOIN"], $join);
        $count = $query->joinWith($with, $eagerLoading, $joinType)
            ->filterWhere($conditions)
            ->count();
        $rows  = $query->limit($pageSize)
            ->offset(($page - 1) * $pageSize)
            ->asArray()
            ->all();
        return [
            'total' => $count,
            'rows'  => $rows
        ];
    }

    /**
     * 文件描述
     * Created On 2019-02-28 11:58
     * Created By heyafei
     * @param array $conditions
     * @param array $join
     * @param bool $return
     * @return $this|array|ActiveRecord|null
     */
    public function getOneRecord($conditions = [], $join = [], $return = false)
    {
        $this->setAdditions()
            ->getQuery()
            ->with($join)
            ->filterWhere($conditions);
        if ($return) {
            return $this;
        }
        return $this->getQuery()->asArray()->one();
    }

    /**
     * 文件描述 批量写入
     * Created On 2019-02-28 11:56
     * Created By heyafei
     * @param $data
     * @return int
     * @throws \yii\db\Exception
     */
    public function batchInsert($data)
    {
        if (empty($data)) {
            return 0;
        }
        $field = array_keys(reset($data));
        return static::find()->createCommand()
            ->batchInsert(static::tableName(), $field, $data)
            ->execute();
    }

    /**
     * 文件描述 此方法获取某个逻辑位位1 的所有二级制数
     * Created On 2019-03-08 17:03
     * Created By Wei Yang<suncode_666@163.com>
     * @param $len 数据库字段数字长度 int 11 是10
     * @param $one_pos 不变逻辑位1所在位
     * @param $valid_len 有效位 有效位包含第一位占位
     * @return array
     * @throws \Exception
     */
    public  static  function binary_set_calc($len, $one_pos,$valid_len){
        if($one_pos === 1){
            throw new \Exception('不变逻辑位1 不能是第一位 第一位是占位');
        }
        if($one_pos > $valid_len){
            throw new \Exception('1所在位置不是有效位');
        }
        $set = [];
        //初始化 预留扩展位
        $append_string = '';//无效位0补充 减去占位
        foreach (range(1, $len-$valid_len) as $n){
            $append_string .= '0';
        }
        //逻辑位
        $logic_len = $valid_len - 1; //逻辑位长度 是有效位减1 因为第一位是占位1
        $logic_string = '';
        //转10进制
        foreach (range(1, $logic_len) as $n){
            $logic_string .= '0';
        }
        //逻辑位所能表达的最大10进制数
        $decimal = bindec('1'.$logic_string)-1;
        //遍历逻辑位能表示的所有10进制数 转成2进制 位数不足前面补0
        foreach (range(1,$decimal) as $n){
            $line = decbin($n);
            $lineOriginLen = strlen($line);
            if($lineOriginLen < $logic_len){ //不足补零
                foreach (range(1,$logic_len-$lineOriginLen) as $x){
                    $line = '0'.$line;
                }
            }
            $line = '1'.$line.$append_string;
            if($line[$one_pos - 1] === '1'){
                $set[] = $line;
            }
        }
        return $set;
    }

    /**
     * 文件描述 code集合
     * Created On 2019-03-08 19:47
     * Created By heyafei
     * @param $code
     * @param $valid_len
     * @return array
     * @throws \Exception
     */
    public static function codeCollection($code, $valid_len){
        $len = strlen($code);
        $pos = strpos($code,'1',1);
        $pos += 1;
        return self::binary_set_calc($len, $pos, $valid_len);
    }
}
