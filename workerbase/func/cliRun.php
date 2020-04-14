<?php 
namespace workerbase\func;

/**
 * [cliRun php命令执行]
 * @param  string $path      命令目录
 * @param  string $namespace 命令目录对应的命名空间
 * @param  string $suffix   后缀
 * @return array            []
 */
function cliRun($path, $namespace="\\", $suffix = '') {
    global $argc,$argv;
	$return_arr = array(
				'code'=> -1,
				'msg'=> 'false',
				'data'=>''
			);
    $arguments_count = $argc;//参数数量
    if ($arguments_count < 3) {
		$return_arr['msg'] = 'params false';
		return $return_arr;
	}

    $arguments = $argv;//获取用户输入的参数（数组）
	array_shift($arguments);//弹出第一个参数，为当前文件的相对路径
	$class_name = array_shift($arguments);//弹出第二个参数，即类名
	$func_name = array_shift($arguments);//弹出第三个参数，即函数名
	if ($class_name == '') {
	    $return_arr['msg'] = 'class_name is empty!';
		return $return_arr;
	} 
	elseif ($func_name == '') {
	    $return_arr['msg'] = 'func_name is empty!';
		return $return_arr;
	} 
	else {
	    //检测命名空间
	    if ($namespace != "\\") {
	    	$namespace = trim($namespace,"\\");
		    if ($namespace != '') {
		    	$namespace = "\\".$namespace."\\";
		    } else {
		    	$namespace = "\\";
		    }
	    }
	    
	    //带命名空间的类名
	    $class_name = $namespace . $class_name . $suffix;

        if (!class_exists($class_name)) {
            //加载类文件
            $class_file =  $path . $class_name . $suffix .'.php';
            if (!is_file($class_file)) {
                $return_arr['msg'] = ' class ' . $class_name . ' Not Found!';
                return $return_arr;
            }
            require_once $class_file;
        }

        try{
            //检查类和方法是否正确
            if (!class_exists($class_name)) {
                $return_arr['msg'] = $class_name . ' does not exist! ';
                return $return_arr;
            }
            $reflectionClass = new \ReflectionClass($class_name);
            if (!$reflectionClass->IsInstantiable()) { //是否可实例化
                $return_arr['msg'] = $class_name . ' is not instantiable! ';
                return $return_arr;
            }
            if (!$reflectionClass->hasMethod($func_name)) { //方法是否存在
                $return_arr['msg'] = $class_name . '::' . $func_name . ' does not exist! ';
                return $return_arr;
            }

            $instance = new $class_name;
            $result = call_user_func_array ([$instance, $func_name], $arguments); //调用函数，并传递参数

            $return_arr['code'] = 200;
            $return_arr['msg'] = 'success';
            $return_arr['data'] = $result?$result:'';
            return $return_arr;
        } catch (\Exception $e) {
            $return_arr['msg'] = $e->getMessage() . "[" . $e->getFile() . ':' . $e->getLine() . "]";
            return $return_arr;
        } catch (\Error $e) {
            $return_arr['msg'] = $e->getMessage() . "[" . $e->getFile() . ':' . $e->getLine() . "]";
            return $return_arr;
        }
	}
}
