<?php
namespace apps\console;
use workerbase\classs\Config;
use workerbase\classs\ConfigStorage;
use workerbase\classs\Log;


/**
 * 重载定时任务和worker配置（由外部命令执行）
 */
class CronWorkerConfigCommand
{
    //加载定时任务配置
    public function setCronConfig()
    {
        $getCofig = new ConfigStorage();
        $getCofig->setConfig('cron', Config::read('', 'cron'));
        unset($getCofig);
        return true;//定时任务执行完需要返回true
    }

    //加载worker配置
    public function setWorkerConfig()
    {
        Log::error('进来了');
        Log::error(var_export(Config::read('', 'worker'), true));
        $getCofig = new ConfigStorage();
        $getCofig->setConfig('worker', Config::read('', 'worker'));
        unset($getCofig);
        return true;//定时任务执行完需要返回true
    }

}