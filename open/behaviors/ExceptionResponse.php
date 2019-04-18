<?php
/**
 * Exception Response Behaviors
 *
 * @Author     heyafei
 * @CreateTime 2019/01/22 15:00:42
 */

namespace open\behaviors;

use open\components\ErrorHandle;
use Yii;
use yii\base\Behavior;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;
use open\exceptions\BaseException;
use open\exceptions\RequestException;

class ExceptionResponse extends Behavior {
    /**
     * prod err code
     *
     * @var int
     */
    public $prodCode = 10000;
    /**
     * prod err msg
     *
     * @var string
     */
    public $prodMsg = 'system busy';
    
    public function events() {
        return [
            ErrorHandle::EVENT_BEFORE_RENDER => 'beforeRender'
        ];
    }
    
    public function beforeRender() {
        $exception = $this->owner->exception;

        $data['status'] = $exception->getCode();
        $data['message']  = $exception->getMessage();

        
        if ($exception instanceof BaseException) {
            Yii::$app->response->setStatusCode(200);
        }
        else if ($exception instanceof NotFoundHttpException) {
            Yii::$app->response->setStatusCode(404);
            $data['status'] = RequestException::URI_ERR;
            $data['message']  = RequestException::getReason(RequestException::URI_ERR);
        }
        else if ($exception instanceof ForbiddenHttpException) {
            Yii::$app->response->setStatusCode(403);
            $data['status'] = RequestException::PERMISSION_DENIED;
            $data['message']  = RequestException::getReason(RequestException::PERMISSION_DENIED);
        }
        else if ($exception instanceof UnauthorizedHttpException) {
            Yii::$app->response->setStatusCode(401);
            $data['status'] = RequestException::UNAUTHORIZED_TOKEN;
            $data['message']  = RequestException::getReason(RequestException::UNAUTHORIZED_TOKEN);
        }
        else {
            Yii::$app->response->setStatusCode(500);
            $data['debug'] = $this->owner->getInfo();
            Yii::error($data, 'ex');

            if (YII_ENV_PROD) {
                $data['status'] = $this->prodCode;
                $data['message']  = $this->prodMsg;
                unset($data['debug']);
            }
        }
        
        Yii::$app->response->data = $data;
        Yii::$app->response->send();
    }
}