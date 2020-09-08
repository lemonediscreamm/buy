<?php
/*
*ファイルパス:C:\xampp\htdocs\DT\buy\Bootstrap.class.php
*ファイル名:Bootstrap.class.php
*アクセスURL:http://localhost/DT/buy/Bootstrap.class.php
*/
namespace buy;

date_default_timezone_set('Asia/Tokyo');

require_once dirname(__FILE__). './../vendor/autoload.php';

class Bootstrap
{
    const DB_HOST = 'localhost';

    const DB_NAME = 'buy_db';

    const DB_USER = 'buy_user';

    const DB_PASS = 'buy_pass';

    const DB_TYPE = 'mysql';

    const APP_DIR = 'C:/Users/lemon/downloads/pleiades-2018-09-php-win-64bit-jre_20181004/pleiades/xampp/htdocs/DT/';

    const TEMPLATE_DIR = self::APP_DIR . 'templates/buy/';

    const CACHE_DIR = false;

    const APP_URL = 'http://localhost/DT/';

    const ENTRY_URL = self::APP_URL . 'buy/';

    public static function loadClass($class)
    {
        $path = str_replace('\\', '/', self::APP_DIR . $class . '.class.php');
        require_once $path;
    }  
}

//これを実行しないとオートローダーとして動かない
spl_autoload_register([
    'buy\Bootstrap',
    'loadClass'
]);