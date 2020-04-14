<?php
namespace apps\api;
use workerbase\traits\BaseTool;

/**
* BaseController
*/
class BaseController
{
    use BaseTool;

    public function __construct()
    {
        if ($this->beforeAction(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)) === false) {
            $this->showResponse(-1,'api未授权！');
            exit(0);
        }
    }

    //前置操作
    protected function beforeAction($path)
    {
        return true;
    }
}