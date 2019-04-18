<?php

define("PHONE_REG", "/^(((1[3|4|5|7|8|9]{1}[0-9]{1}))[0-9]{8})$/");
defined("SMS_CAPTCHA_TMP") or define("SMS_CAPTCHA_TMP", 208115);
defined('TOKEN_INTERVAL') or define('TOKEN_INTERVAL', 60 * 60 * 24 * 30);
defined('CAPTCHA_INTERVAL') or define('CAPTCHA_INTERVAL', 60 * 5);
defined('TOKEN_OVERDUE') or define('TOKEN_OVERDUE', 0);
defined('CAPTCHA_PICTURE_INTERVAL') or define('CAPTCHA_PICTURE_INTERVAL', 60);
defined('REDIS_PREFIX') or define('REDIS_PREFIX', "loan_market");
