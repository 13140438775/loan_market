<?php

namespace api\services;


class Utils {

    public static function getSuiteRoute($route){
        if(YII_ENV === 'prod'){
            return 'api/'.$route;
        }else{
            return $route;
        }
    }
}