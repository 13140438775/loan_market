<?php
/**
 * Params validate
 *
 * @Author     heyafei
 * @CreateTime 2019/01/22 15:00:42
 */

namespace common\behaviors;

use common\exceptions\RequestException;
use common\filters\RequestFilter;
use Yii;
use yii\base\DynamicModel;
use yii\helpers\ArrayHelper;

class ParamsValidate extends RequestFilter {
    /**
     * validate data
     * ```
     * 'requestHeadersFilter' => [
     *      'class' => ParamsValidate::class,
     *      'data' => \Yii::$app->request->getHeaders()->toArray(),
     *      ...
     * ]
     *
     * ```
     * @var
     */
    public $data;
    /**
     * validate rule
     * ```
     * 'requestHeadersFilter' => [
     *      'class' => ParamsValidate::class,
     *      'data' => \Yii::$app->request->getHeaders()->toArray(),
     *      'rules' => [
     *          '*' => [
     *              [['head1'], 'required', 'message' => '参数错误']
     *          ],
     *          'user/create' => [
     *              [['param1', 'param2'], 'required']
     *          ]
     *      ]
     * ]
     *
     * ```
     * @var array
     */
    public $rules = [];
    
    /**
     * err Func
     *
     * ```
     * 'requestHeadersFilter' => [
     *      'class'   => ParamsValidate::class,
     *      'data'    => \Yii::$app->request->getHeaders()->toArray(),
     *      'errFunc' => function($data){
     *          Yii::$app->response->setStatusCode(403);
     *          throw new RequestException(RequestException::INVALID_PARAM, $data);
     *      }
     * ]
     * ```
     * @var
     */
    public $errFunc;
    
    private $_validateKey = [];
    
    public function init() {
        $this->data = self::dealHeader($this->data);
        if (!is_callable($this->errFunc)) {
            $this->errFunc = function ($data) {
                throw new RequestException(RequestException::INVALID_PARAM, reset($data));
            };
        }
    }
    
    public function beforeAction($request) {
        $url = rtrim(Yii::$app->controller->action->getUniqueId(), '/');
        $rules = array_merge(ArrayHelper::getValue($this->rules, '*', []), ArrayHelper::getValue($this->rules, "{$url}", []));
        
        $this->setValidateKey($rules);
        $this->setValidateVal($this->data);
        
        $DynamicModel = DynamicModel::validateData($this->_validateKey, $rules);
        if ($DynamicModel->hasErrors()) {
            call_user_func($this->errFunc, $DynamicModel->getFirstErrors());
        }
    }
    
    public function setValidateKey($rules) {
        foreach ($rules as $rule) {
            if (is_array($rule[0])) {
                foreach ($rule[0] as $v) {
                    $this->_validateKey[$v] = '';
                }
                continue;
            }
            
            $this->_validateKey[$rule[0]] = '';
        }
    }
    
    public function setValidateVal($post) {
        foreach ($this->_validateKey as $k => $v) {
            if (isset($post[$k])) {
                $this->_validateKey[$k] = $post[$k];
            }
        }
    }

    public function dealHeader($data)
    {
        foreach ($data AS $key => $val) {
            if(is_array($val)) {
                $data[$key] = implode("", $val);
            }
        }
        return $data;
    }
}