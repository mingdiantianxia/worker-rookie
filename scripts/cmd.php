<?php

namespace workerbase\cli;

use workerbase\classs\Log;
use workerbase\classs\App;
use workerbase\classs\Error;
use workerbase\classs\Config;

/**
 * 控制器执行入口
 * @author fukaiyao
 */
// fix for fcgi

defined('STDIN') or define('STDIN', fopen('php://stdin', 'r'));

//定义app id
define('WK_APP_ID', "cron");
//定义项目根目录
define('WORKER_PROJECT_PATH', __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR);

require_once WORKER_PROJECT_PATH . 'workerbase/Load.php';
require_once WORKER_PROJECT_PATH . 'workerbase/vendor/autoload.php';

date_default_timezone_set('PRC');
loadc('loader')->run();

//初始化当前系统环境
define('WK_ENV',  Config::read('env'));

// 注册错误和异常处理机制
Error::register();
App::run();

$cmd_config = Config::read("cmd_path", "cron");

try{
    $result = loadf('cliRun', WORKER_PROJECT_PATH . $cmd_config['path'], $cmd_config['namespace'], $cmd_config['suffix']);
    if (isset($result['code']) && $result['code'] == -1) {
        //搜集没有返回true的任务日志
       Log::info('cmd_error:' . $result['msg'], $argv);
    }
} catch (\Exception $e) {
    Log::err('cmd_error:' . $e->getMessage() . "[" . $e->getFile() . ':' . $e->getLine() . "]", $argv);
} catch (\Error $e) {
    Log::err('cmd_error:' . $e->getMessage() . "[" . $e->getFile() . ':' . $e->getLine() . "]", $argv);
}

App::end();
