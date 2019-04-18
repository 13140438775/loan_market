<?php
/**
 * Created by PhpStorm.
 *
 * @Author     : sunforcherry@gmail.com
 * @CreateTime 03/08/2018 11:13:07
 */

namespace app\models;

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
}
