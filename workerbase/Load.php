<?php
    //加载函数和类的助手函数
   /**
    * [loadf 加载函数]
    * @return [type] [description]
    */
    function loadf() {
        $arguments = func_get_args();//获取传给函数的参数（数组）
        $name = array_shift($arguments);//弹出第一个参数，即函数名
        if ($name == '') {
            die('function name is empty!');
        } else {
            $call_exist = stripos($name, 'call:');//如果有call:字样，就直接返回函数名
            if ($call_exist === 0) {
               $callf = explode(':', $name);
               $name = $callf[1];
            }

            $function = "workerbase\\func\\".$name;
            if (!function_exists($function)) {
                $func =  dirname(__FILE__).DIRECTORY_SEPARATOR.'func'.DIRECTORY_SEPARATOR. $name . '.php';

                if (!is_file($func)) {
                    die(' function ' . $name . ' Not Found!');
                }
                require_once $func;
            }

            if ($call_exist === 0) {
                return $function;
            } else {
                return  call_user_func_array ($function , $arguments);//调用函数，并传递参数
            }
        }
    }
    /*
    加载类
    */
    function loadc() {
        $arguments = func_get_args();//获取传给函数的参数（数组）
        $name = array_shift($arguments);//弹出第一个参数，即类名
        if ($name == '') {
            die('class name is empty!');
        }

        $name = strtolower($name);
        static $workerbase_modules = array();
        if (isset($workerbase_modules[$name])) {
            return $workerbase_modules[$name];
        }

        $class_name = "workerbase\\classs\\" . ucfirst($name);
        if (!class_exists($class_name)) {
            $class =  dirname(__FILE__).DIRECTORY_SEPARATOR.'classs'.DIRECTORY_SEPARATOR. $name . '.php';

            if (!is_file($class)) {
                die(' class ' . $name . ' Not Found!');
            }
            require_once $class;
        }

        $class_name = new \ReflectionClass($class_name);//反射类
        $workerbase_modules[$name] = $class_name->newInstanceArgs($arguments);//传入参数
        return $workerbase_modules[$name];
    }
