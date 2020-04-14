#!/bin/bash
#filename:worker服务运行脚本
#author:fukaiyao
#date:2020-4-2

#. /etc/profile
basedir=`cd $(dirname $0); pwd`
cd $basedir

pidPath="../runtime/log/workerlog/workerServer.pid"
logPath="../runtime/log/workerlog/"
logFileName="workerServer.log"

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
        echo "stop worker server, pid="$pid"..."

        check_worker_exist
        pidIsExits=$?
        if [ $pidIsExits -eq 1 ]; then
            kill $pid
        else
            echo "worker server not exist."
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
			echo "stop workerServer ok."
		fi

    fi

	return $isFalse
}

#启动workerServer
start() {
    check_worker_exist
    pidIsExits=$?
    if [ $pidIsExits -eq 1 ]; then
        echo "workerServer server had running..."
    else
        #杀死所有残留的子进程
        ps -eaf |grep "workerServer.php" | grep -v "grep"| awk '{print $2}'|xargs kill &> /dev/null
        echo "start workerServer server..."
        cmd=$phpbin" workerServer.php -d"
        if [[ ! -f $cronolog ]]; then
            $cmd &> $logPath$logFileName
        else
            $cmd | $cronolog $logPath%Y%m/%d_$logFileName &> /dev/null &
        fi

    fi
	return 0
}

#重启workerServer
restart() {
	if stop && start;then
		echo "restart workerServer ok."
		return 0
	else
		echo "restart workerServer failed."
		return 1
	fi
}

#检测workerServer进程是否存在
check_worker_exist() {
    if [ ! -f $pidPath ]; then
        return 0
    fi

    pid=`cat $pidPath`
    pids=`ps aux | grep workerServer.php | grep -v grep | awk '{print $2}'`
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
    echo "Usage: workerServer.sh {start|stop|restart|help}"
    exit 1
esac
