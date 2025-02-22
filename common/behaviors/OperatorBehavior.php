<?php
/**
 * Created by PhpStorm.
 * User: suns
 * Date: 2019-03-12
 * Time: 14:03
 */

namespace common\behaviors;


use yii\behaviors\AttributeBehavior;
use yii\db\BaseActiveRecord;

class OperatorBehavior extends AttributeBehavior
{


    public $lastOperatorId = 'last_operator_id';
    public $value;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        if (empty($this->attributes)) {
            $this->attributes = [
                BaseActiveRecord::EVENT_BEFORE_INSERT => [$this->lastOperatorId],
                BaseActiveRecord::EVENT_BEFORE_UPDATE => $this->lastOperatorId,
            ];
        }
    }

    /**
     * {@inheritdoc}
     *
     * In case, when the [[value]] is `null`, the result of the PHP function [time()](http://php.net/manual/en/function.time.php)
     * will be used as value.
     */
    protected function getValue($event)
    {
        if ($this->value === null) {
            return \Yii::$app->user->getId();
        }

        return parent::getValue($event);
    }

    /**
     * Updates a timestamp attribute to the current timestamp.
     *
     * ```php
     * $model->touch('lastVisit');
     * ```
     * @param string $attribute the name of the attribute to update.
     * @throws InvalidCallException if owner is a new record (since version 2.0.6).
     */
    public function touch($attribute)
    {
        /* @var $owner BaseActiveRecord */
        $owner = $this->owner;
        if ($owner->getIsNewRecord()) {
            throw new InvalidCallException('Updating the timestamp is not possible on a new record.');
        }
        $owner->updateAttributes(array_fill_keys((array) $attribute, $this->getValue(null)));
    }

}