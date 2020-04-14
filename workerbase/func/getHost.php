<?php 
namespace workerbase\func;
/**
 * 获取域名
 */
function getHost()
{
	$url = htmlspecialchars('http://' . $_SERVER['HTTP_HOST']);
	if(substr($url, -1) != '/') {
	    $url .= '/';
	}
	return $url;
}
