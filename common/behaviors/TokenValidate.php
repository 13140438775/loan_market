<?php
/**
 * Token validate
 *
 * @Author     heyafei
 * @CreateTime 2019/01/22 15:00:42
 */
namespace common\behaviors;

use Yii;
use common\filters\RequestFilter;
use common\exceptions\RequestException;
use app\services\LoginService;

class TokenValidate extends RequestFilter {
    public function beforeAction($request) {
        $authorization = $request->getHeaders()->get('authorization');
        $jwt = \Yii::$app->jwt->loadToken($authorization);
        if(is_null($jwt)){
            $this->handleFailure();
        }
        $user_id = $jwt->getClaim('id');
        $token = \Yii::$app->redis->get(LoginService::getTokenKey($user_id));
        $jwt = Yii::$app->jwt->loadToken($token);
        if(is_null($jwt)){
            $this->handleFailure();
        }

        Yii::$app->user->findIdentity($jwt);
    }
    
    public function handleFailure(){
        throw new RequestException(RequestException::UNAUTHORIZED_TOKEN);
    }
}