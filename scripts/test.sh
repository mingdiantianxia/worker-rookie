#!/bin/bash

cd test1

#检查没有启动服务的环境
#for i in `ls|grep -vE "API_test|VM-|test_|erp|cron18-20|crontab0-2|crontab3-5|crontab6-8"`;do res=`grep '2020-08-18T19:[0-5][0-9].*start worker server' ./$i/*18_worker* 2> /dev/null`;  if [ ! -n "$res" ]; then echo $i; fi; done

#是否收到退出信号
#for i in `ls|grep -vE "API_test|VM-|test_|erp|cron18-20|crontab0-2|crontab3-5|crontab6-8"`;do res=`grep '2020-08-18T19:[0-5][0-9].*recv terminate signal, exit worker' ./$i/*18_worker* 2> /dev/null`;if [ ! -n "$res" ]; then echo $i;fi;done

#是否正常退出
#for i in `ls|grep -vE "API_test|VM-|test_|erp|cron18-20|crontab0-2|crontab3-5|crontab6-8"`;do res=`grep '2020-08-18T19:[1-2][0-9].*worker server shutdown' ./$i/*18_worker* 2> /dev/null`;if [ ! -n "$res" ]; then echo $i;fi;done

#是否触发过worker启动或退出
#for i in `ls|grep -vE "wzk|API_test|VM-|test_|erp|cron18-20|crontab0-2|crontab3-5|crontab6-8"`;do res=`grep -E '2020-08-19T20:[2-5][0-9].*(start worker server|recv terminate signal, exit worker|worker server shutdown|master shutdown)' ./$i/*19_worker* 2> /dev/null`;if [ ! -n "$res" ]; then echo $i;fi;done


#grep -E  'start worker server|recv terminate signal, exit worker|worker server shutdown' test_1/*_worker_info_cli.log


#for i in `ls|grep -vE "wzk|API_test|VM-|test_|erp|cron18-20|crontab0-2|crontab3-5|crontab6-8"`;do res=`grep -E $(date +%Y-%m-%d)'T1[45]:[0-5][0-9].*start worker server' ./$i/*$(date +%d)_worker_info* 2> /dev/null`;if [ ! -n "$res" ]; then echo $i;fi;done

#grep -E $(date +%Y-%m-%d)'T14:[2-4][0-9].*recv terminate signal, exit worker' ./aikewei/*$(date +%d)_worker_info*


for i in `ls|grep -vE "wzk|API_test|VM-|test_|erp|cron18-20|crontab0-2|crontab3-5|crontab6-8"`;do res=`grep -E $(date +%Y-%m-%d)'.*recv terminate signal, exit worker' ./$i/*$(date +%d)_worker_info* 2> /dev/null`;if [ -n "$res" ]; then echo $i;fi;done

