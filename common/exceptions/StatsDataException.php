<?php

namespace common\exceptions;

/**
 * 单文件一个异常
 */
class StatsDataException extends BaseException {
    const STORE_APP_PRODUCT_PV_TO_DB_FAIL   = 30001;
    const STORE_APP_PV_TO_DB_FAIL           = 30002;
    const STORE_USER_DAILY_DB_FAIL          = 30003;
    const SAVE_CHANNEL_DATA_FAIL            = 30004;

    public static $reasons = [
        self::STORE_APP_PRODUCT_PV_TO_DB_FAIL => '保存产品PVUV失败',
        self::STORE_APP_PV_TO_DB_FAIL => '保存appPVUV失败',
        self::STORE_USER_DAILY_DB_FAIL => '保存用户daily数据失败',
        self::SAVE_CHANNEL_DATA_FAIL => '保存渠道数据失败',
    ];
}