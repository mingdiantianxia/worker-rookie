#!/bin/bash
#filename:cron服务运行脚本
#author:fukaiyao
#date:2020-4-2

#. /etc/profile
basedir=`cd $(dirname $0); pwd`
cd $basedir
pidPath="../runtime/log/workerlog/crond.pid"
logPath="../runtime/log/workerlog/"
logFileName="crond.log"

#需要分割日志的cronolog地址
cronolog=""

phpbin=`cat ../config/config.php |grep phpbin|awk -F "[\"']" '{print $4}'`

if [[ ! -f $phpbin ]]; then
   echo 'php命令路径未找到'
   exit 1
fi

if [[ ! -d $logPath ]]; then
   mkdir -p $logPath
fi

stop() {
	isFalse=0
    if [ -f $pidPath ]; then
        pid=`cat $pidPath`
        echo "stop crond server, pid="$pid"..."

        check_crond_exist
        pidIsExits=$?
        if [ $pidIsExits -eq 1 ]; then
            kill $pid
        else
            echo "crond server not exist."
            rm -f $pidPath
        fi

		isFalse=1
        try=0
        while test $try -lt 60; do
            if [ ! -f "$pidPath" ]; then
                try=''
				isFalse=0
                break
            fi
            echo -n
            try=`expr $try + 1`
            sleep 1
        done

		if [ $isFalse -eq 1 ];then
			echo "stop timeout failed."
		else
			 echo "stop crond ok."
		fi

    fi

	return $isFalse
}

#启动crond server
start() {
    check_crond_exist
    pidIsExits=$?
    if [ $pidIsExits -eq 1 ]; then
        echo "crond server had running..."
    else
        #杀死所有残留的子进程
        ps -eaf |grep "crond.php" | grep -v "grep"| awk '{print $2}'|xargs kill &> /dev/null
        echo "start crond server..."
        #启动并传递一个d参数作为后台进程
        cmd=$phpbin" crond.php -d"
        if [[ ! -f $cronolog ]]; then
            $cmd &> $logPath$logFileName
        else
            $cmd | $cronolog $logPath%Y%m/%d_$logFileName &> /dev/null &
        fi

    fi
	return 0
}

#重启cron服务
restart() {
	if stop && start;then
		echo "restart crond ok."
		return 0
	else
		echo "restart crond failed."
		return 1
	fi
}

#检测crond进程是否存在
check_crond_exist() {
    if [ ! -f $pidPath ]; then
        return 0
    fi

    pid=`cat $pidPath`
    pids=`ps aux | grep crond.php | grep -v grep | awk '{print $2}'`
    pidIsExits=0;
    for i in ${pids[@]}
        do
            if [ "$i" -eq "$pid" ]; then
                pidIsExits=1
                break
            fi

        done
    return  $pidIsExits
}

#当输入的参数为
case "$1" in
start)
    start
    ;;
stop)
    stop
    ;;
restart)
    restart
    ;;
*)
    echo "Usage: crond.sh {start|stop|restart|help}"
    exit 1
esac
