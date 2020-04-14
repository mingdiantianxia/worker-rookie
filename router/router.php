<?php
use workerbase\classs\Router;

Router::setPrefix('\/?');
Router::get('test/', 'test\TestController@test');
Router::ANY('test2/', 'test\TestController@test2');
//any传参
Router::ANY('test3:any', 'test\TestController@test3');







Router::error(function(){
  echo '404:: ' . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) . ' Not Found！';
});
Router::dispatch();
