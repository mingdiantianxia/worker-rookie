<?php
namespace workerbase\cli;

use workerbase\classs\Config;
use workerbase\classs\App;
use workerbase\classs\Error;

/**
 * worker工作进程执行入口
 * @author fukaiyao
 */

// fix for fcgi
defined('STDIN') or define('STDIN', fopen('php://stdin', 'r'));

//定义app id
define('WK_APP_ID', "worker");
//定义项目根目录
define('WORKER_PROJECT_PATH',  __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR);

require_once WORKER_PROJECT_PATH.'workerbase/classs/worker/signalDefine.php';
require_once WORKER_PROJECT_PATH.'workerbase/helper.php';
require_once WORKER_PROJECT_PATH.'workerbase/vendor/autoload.php';

date_default_timezone_set('PRC');
loadc('Loader')->run();

//初始化当前系统环境
define('WK_ENV',  Config::read('env'));

//定义worker环境
define('IS_WK_WORKER', true);

// 注册错误和异常处理机制
Error::register();
App::run();

$options = getopt('t:k:p:');
if (!isset($options['t']) || empty($options['t'])) {
    echo "invalid params.";
    exit(0);
}

//队列工作名
$jobName = $options['t'];

//进程是否值守
$onDuty = true;
if (isset($options['k']) && !$options['k']) {
    $onDuty = false;
}

//主进程id
$masterPid = 0;
if (isset($options['p']) && $options['p']) {
    $masterPid = $options['p'];
}

\workerbase\classs\worker\Worker::getInstance($jobName, $onDuty, $masterPid)->run();
