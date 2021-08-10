#!/bin/bash

###################################################################################################################
# Writer Name      :  FREDRICK MBILINYI                                                                           #
# Scripting Date   :  10/09/2020                                                                                  #
# Title Name       :  MFS Support Engineer 																		  # 
# Company Name     :  MIC-TIGO/DD                                                                                 #
# Description      :  Script which runs every day at 07:10AM to check Core Platform Nodes Health Check            #
# Quote            :  "IT'S MATTER OF TIME"                                                                       #
# Script Name      :  script_to_send_Tigopesa_health_report.sh                                                    #
# Reporting to     :  Zakayo Malisa/MIC-TIGO(Technical Manager)                                                   #
# Supervisor       :  Jackson Mnanka                                                                              #
###################################################################################################################

dbHost=10.76.107.155
dbUser=mfs
dbPassword=mfs123
dbName=Telepin_HC
HOST=10.99.2.1
USER=fredrick_mbilinyi
PASS=Amina@123?

/usr/local/bin/lftp -u ${USER},${PASS} sftp://${HOST} <<EOF
set ftp:passive-mode off
set xfer:clobber on
lcd /var/www/html/telepin/Tigopesa_Health_Check
cd /export/home/fredrick_mbilinyi/Health_Repository/
mget *.txt
cd /tmp/Health_D/
mget *.txt

bye
EOF

cd /var/www/html/telepin/Tigopesa_Health_Check
date=`date +%d-%m-%y`
datenow=`date +%Y-%m-%d`
disk_command="df -kh"
active_pr_command="prstat -a"
cpu_command="sar -u"
cpu_testType="CPU Utilization"
memory="Memory utilization"
memory_command="sar -r"
disk_test_type="Disk Space usage"
active_pr="Active process statistics"
java_processweb1_test_type="web process"
java_processweb1_command="ps -ef | grep java"

javaRepo_processweb1_test_type="database services: Oracle"
javaRepo_processweb1_command="ps -ef | grep ora_"

rejection_command="/telepin/TCS/log/server/TCS.AccessPool"
rejection_test_type="Frequent timeouts Rejections and queue delays for license violations"

#DB

service_user_expiration_command="Found in original Health Check sheet"
service_user_expiration_type="service user expiration: DB"
tablespace_status_command="Found in original Health Check sheet"
tablespace_status_type="Tablespace Status"
datafile_command="Found in original Health Check sheet"
datafile_status_type="Datafile Status"
flashrecovery_command="Found in original Health Check sheet"
flashrecovery_status_type="Flash Recovery Area Size"
ASM_diskgroup_SAN_command="Found in original Health Check sheet"
ASM_diskgroup_SAN_command_type="ASM diskgroup (SAN Storage)"
tablespace_consuming_space_command="Found in original Health Check sheet"
tablespace_consuming_space_type="Tablespace Usage"

nodeDatabase="DB"

nodeA_NOK="NOK"
nodeA_OK="OK"
nodeB_NOK="NOK"
nodeB_OK="OK"

nodeweb1="INTERNAL WEB"
nodeExtweb="EXTERNAL WEB"
nodeReporting="REPORTING SERVER"
nodeTCS="TCS"

#IntWebs

#nodeweb1=`cat disk_IntWeb1.txt | cut -d' ' -f1 | tail -1f`
disk_comweb1=`cat disk_IntWeb1.txt |nawk -F' ' '{if (int($2)>=70 && $3 != "/cdrom/sol_10_113_sparc") {print $2,$3}}' ORS=', ' | tr -d ','`
active_processweb1=`cat active_processIntWeb1.txt | nawk -F',' '{if($2>=10 || $3>=10 || $4>=10) print $0}' | wc -l`
Active_process_loadweb1=`cat active_processIntWeb1.txt | nawk -F',' '{if($2>=10 || $3>=10 || $4>=10) print $2,$3,$4}'`
cpu_countweb1=`tail -10  cpu_IntWeb1.txt | nawk -F' ' '{if(int($2)>=90) print $0}' | wc -l`
disk_countweb1=`cat disk_IntWeb1.txt |nawk -F' ' '{if (int($2)>=70 && $3 != "/cdrom/sol_10_113_sparc") {print $2,$3}}' | wc -l`
java_processweb1=`cat java_processIntWeb1.txt | wc -l`
disk_commentweb1=$disk_comweb1
Active_load_commentweb1=$Active_process_loadweb1

#nodeweb2=`cat disk_IntWeb2.txt | cut -d' ' -f1 | tail -1f`
disk_comweb2=`cat disk_IntWeb2.txt |nawk -F' ' '{if (int($2)>=70 && $3 != "/cdrom/sol_10_113_sparc") {print $2,$3}}' ORS=', ' | tr -d ','`
active_processweb2=`cat active_processIntWeb2.txt | nawk -F',' '{if($2>=10 || $3>=10 || $4>=10) print $0}' | wc -l`
Active_process_loadweb2=`cat active_processIntWeb2.txt | nawk -F',' '{if($2>=10 || $3>=10 || $4>=10) print $2,$3,$4}'`
cpu_countweb2=`tail -10  cpu_IntWeb2.txt | nawk -F' ' '{if(int($2)>=90) print $0}' | wc -l`
disk_countweb2=`cat disk_IntWeb2.txt |nawk -F' ' '{if (int($2)>=70 && $3 != "/cdrom/sol_10_113_sparc") {print $2,$3}}' | wc -l`
java_processweb2=`cat java_processIntWeb2.txt`
disk_commentweb2=$disk_comweb2
Active_load_commentweb2=$Active_process_loadweb2

#ExtWeb

#nodeExtweb=`cat disk_ExtWeb1.txt | cut -d' ' -f1 | tail -1f`
disk_comExweb1=`cat disk_ExtWeb1.txt |nawk -F' ' '{if (int($2)>=70 && $3 != "/cdrom/sol_10_113_sparc") {print $2,$3}}' ORS=', ' | tr -d ','`
active_processExweb1=`cat active_processExtWeb1.txt | nawk -F',' '{if($2>=10 || $3>=10 || $4>=10) print $0}' | wc -l`
Active_process_loadExweb1=`cat active_processExtWeb1.txt | nawk -F',' '{if($2>=10 || $3>=10 || $4>=10) print $2,$3,$4}'`
cpu_countExweb1=`tail -10  cpu_ExtWeb1.txt | nawk -F' ' '{if(int($2)>=90) print $0}' | wc -l`
disk_countExweb1=`cat disk_ExtWeb1.txt |nawk -F' ' '{if (int($2)>=70 && $3 != "/cdrom/sol_10_113_sparc") {print $2,$3}}' | wc -l`
java_processExweb1=`cat java_processExtWeb1.txt | wc -l`
disk_commentExweb1=$disk_comExweb1
Active_load_commentExweb1=$Active_process_loadExweb1

#nodeweb2=`cat disk_ExtWeb2.txt | cut -d' ' -f1 | tail -1f`
disk_comExweb2=`cat disk_ExtWeb2.txt |nawk -F' ' '{if (int($2)>=70 && $3 != "/cdrom/sol_10_113_sparc") {print $2,$3}}' ORS=', ' | tr -d ','`
active_processExweb2=`cat active_processExtWeb2.txt | nawk -F',' '{if($2>=10 || $3>=10 || $4>=10) print $0}' | wc -l`
Active_process_loadExweb2=`cat active_processExtWeb2.txt | nawk -F',' '{if($2>=10 || $3>=10 || $4>=10) print $2,$3,$4}'`
cpu_countExweb2=`tail -10  cpu_ExtWeb2.txt | nawk -F' ' '{if(int($2)>=90) print $0}' | wc -l`
disk_countExweb2=`cat disk_ExtWeb2.txt |nawk -F' ' '{if (int($2)>=70 && $3 != "/cdrom/sol_10_113_sparc") {print $2,$3}}' | wc -l`
java_processExweb2=`cat java_processExtWeb2.txt`
disk_commentExweb2=$disk_comExweb2
Active_load_commentExweb2=$Active_process_loadExweb2

#TCS

disk_comTCS1=`cat disk_tcs1.txt |nawk -F' ' '{if (int($2)>=70 && $3 != "/cdrom/sol_10_113_sparc") {print $2,$3}}' ORS=', ' | tr -d ','`
active_processTCS1=`cat active_process1.txt | nawk -F',' '{if($2>=10 || $3>=10 || $4>=10) print $0}' | wc -l`
Active_process_loadTCS1=`cat active_process1.txt | nawk -F',' '{if($2>=10 || $3>=10 || $4>=10) print $2,$3,$4}'`
cpu_countTCS1=`tail -10  cpu_tcs1.txt | nawk -F' ' '{if(int($2)>=90) print $0}' | wc -l`
disk_countTCS1=`cat disk_tcs1.txt |nawk -F' ' '{if (int($2)>=70 && $3 != "/cdrom/sol_10_113_sparc") {print $2,$3}}' | wc -l`
reject_tcs1=`cat tcs1_rejection.txt`
disk_commentTCS1=$disk_comTCS1
Active_load_commentTCS1=$Active_process_loadTCS1

disk_comTCS2=`cat disk_tcs2.txt |nawk -F' ' '{if (int($2)>=70 && $3 != "/cdrom/sol_10_113_sparc") {print $2,$3}}' ORS=', ' | tr -d ','`
active_processTCS2=`cat active_process2.txt | nawk -F',' '{if($2>=10 || $3>=10 || $4>=10) print $0}' | wc -l`
Active_process_loadTCS2=`cat active_process2.txt | nawk -F',' '{if($2>=10 || $3>=10 || $4>=10) print $2,$3,$4}'`
cpu_countTCS2=`tail -10  cpu_tcs2.txt | nawk -F' ' '{if(int($2)>=90) print $0}' | wc -l`
disk_countTCS2=`cat disk_tcs2.txt |nawk -F' ' '{if (int($2)>=70 && $3 != "/cdrom/sol_10_113_sparc") {print $2,$3}}' | wc -l`
reject_tcs2=`cat tcs2_rejection.txt`
disk_commentTCS2=$disk_comTCS2
Active_load_commentweb2=$Active_process_loadTCS2

#DB

disk_commentdb1=`cat disk_space_db1.txt |nawk -F' ' '{if (int($2)>=70 && $3 != "/cdrom/sol_10_113_sparc") {print $2,$3}}' ORS=', ' | tr -d ','`
active_processdb1=`cat active_process_db1.txt | nawk -F',' '{if($2>=10 || $3>=10 || $4>=10) print $0}' | wc -l`
Active_process_loaddb1=`cat active_process_db1.txt | nawk -F',' '{if($2>=10 || $3>=10 || $4>=10) print $2,$3,$4}'`
cpu_countdb1=`tail -10  cpu_db1.txt | nawk -F' ' '{if(int($2)>=90) print $0}' | wc -l`
disk_countdb1=`cat disk_space_db1.txt |nawk -F' ' '{if (int($2)>=70 && $3 != "/cdrom/sol_10_113_sparc") {print $2,$3}}' | wc -l`
#java_processdb1=`cat database_oracle_SERVICE.txt | wc -l`
whatconsumingASM=`cat ASM_diskgroup_SAN_db1.txt | nawk -F' ' '{if($2>80){print $1,$2}}' | tr -d '\n'`
ASMcounts=`cat ASM_diskgroup_SAN_db1.txt | nawk -F' ' '{if($2>80){print $1,$2}}' | wc -l`

db_instace=`cat database_instance_db1.txt | tr -d ',' | tr -d ' '`
metric=`cat Relative_wait_times_and_actual_CPU_processing_db1.txt | tr -d '\n,' | tr -d ' '`
whatconsuming_command="Found in original Health Check sheet"
whatconsuming=`cat what_consuming_flash_recovery_db1.txt | tr -d '\n,'`
flasharea=`cat flash_recovery_db1.txt`
datafilecounts=`cat datafiledb1.txt`
tablespace_consuming_space=`cat tablespace_usage_db1.txt | nawk -F',' '{if($2>70){print $1,$2}}' | tr -d '\n,'`
tablespace_consuming_count=`cat tablespace_usage_db1.txt | nawk -F',' '{if($2>70){print $1,$2}}' | wc -l`
service_user_expiration=`cat service_user_expiration_db1.txt`
tablespace_status=`cat tablespace_status_db1.txt`
disk_commentdb1=$disk_commentdb1
Active_load_commentdb1=$Active_process_loaddb1

#Reporting server

diskRepo_comweb1=`cat disk_Reporting.txt |nawk -F' ' '{if (int($2)>=70 && $3 != "/cdrom/sol_10_113_sparc") {print $2,$3}}' ORS=', ' | tr -d '\n,'`
activeRepo_processweb1=`cat active_processReporting.txt | nawk -F',' '{if($2>=10 || $3>=10 || $4>=10) print $0}' | wc -l`
ActiveRepo_process_loadweb1=`cat active_processReporting.txt | nawk -F',' '{if($2>=10 || $3>=10 || $4>=10) print $2,$3,$4}'`
cpuRepo_countweb1=`tail -10  cpu_Reporting.txt | nawk -F' ' '{if(int($2)>=90) print $0}' | wc -l`
diskRepo_countweb1=`cat disk_Reporting.txt |nawk -F' ' '{if (int($2)>=70 && $3 != "/cdrom/sol_10_113_sparc") {print $2,$3}}' | wc -l`
javaRepo_processweb1=`cat database_oracle_processReporting.txt | wc -l`
diskRepo_commentweb1=$diskRepo_comweb1
ActiveRepo_load_commentweb1=$ActiveRepo_process_loadweb1



activeWebs_function()
{
if(($active_processweb1 > 0 && $active_processweb2 == 0))
then
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeweb1','$active_pr','$active_pr_command','$Active_load_commentweb1','$datenow','$nodeA_NOK','$nodeB_OK')" $dbName
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeweb1','$memory','$memory_command','Memory utilized not normal','$datenow','$nodeA_NOK','$nodeB_OK')" $dbName

elif(($active_processweb1 == 0 && $active_processweb2 > 0))
then
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeweb1','$active_pr','$active_pr_command','$Active_load_commentweb2','$datenow','$nodeA_OK','$nodeB_NOK')" $dbName
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeweb1','$memory','$memory_command','Memory utilized not normal','$datenow','$nodeA_OK','$nodeB_NOK')" $dbName

elif(($active_processweb1 > 0 && $active_processweb2 > 0))
then
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeweb1','$active_pr','$active_pr_command','$Active_load_commentweb2','$datenow','$nodeA_NOK','$nodeB_NOK')" $dbName
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeweb1','$memory','$memory_command','Memory utilized not normal','$datenow','$nodeA_NOK','$nodeB_NOK')" $dbName

else
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeweb1','$active_pr','$active_pr_command',' ','$datenow','$nodeA_OK','$nodeB_OK')" $dbName
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeweb1','$memory','$memory_command',' ','$datenow','$nodeA_OK','$nodeB_OK')" $dbName
fi
}

diskWebs_function()
{
if(($disk_countweb1 > 0 && $disk_countweb2 == 0))
then
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeweb1','$disk_test_type','$disk_command','$disk_commentweb1','$datenow','$nodeA_NOK','$nodeB_OK')" $dbName

elif(($disk_countweb1 == 0 && $disk_countweb2 > 0))
then
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeweb1','$disk_test_type','$disk_command','$disk_commentweb2','$datenow','$nodeA_OK','$nodeB_NOK')" $dbName

elif(($disk_countweb1 > 0 && $disk_countweb2 > 0))
then
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeweb1','$disk_test_type','$disk_command','$disk_commentweb2','$datenow','$nodeA_NOK','$nodeB_NOK')" $dbName

else
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeweb1','$disk_test_type','$disk_command',' ','$datenow','$nodeA_OK','$nodeB_OK')" $dbName
fi
}

cpuWebs_function()
{
if(($cpu_countweb1 < 3 && $cpu_countweb2 >= 4))
then
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeweb1','$cpu_testType','$cpu_command',' ','$datenow','$nodeA_NOK','$nodeB_OK')" $dbName

elif(($cpu_countweb1 >= 4 && $cpu_countweb2 < 3))
then
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeweb1','$cpu_testType','$cpu_command',' ','$datenow','$nodeA_OK','$nodeB_NOK')" $dbName

elif(($cpu_countweb1 < 3 && $cpu_countweb2 < 3))
then
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeweb1','$cpu_testType','$cpu_command',' ','$datenow','$nodeA_NOK','$nodeB_NOK')" $dbName

else
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeweb1','$cpu_testType','$cpu_command',' ','$datenow','$nodeA_OK','$nodeB_OK')" $dbName
fi
}

#javaprocessWebs_function()
#{
#if(($java_processweb1 < 3 && $java_processweb2 == 4))
#/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeweb1','$java_processweb1_test_type','$java_processweb1_command',' ','$datenow','$nodeA_NOK','$nodeB_OK')" $dbName

#elif(($java_processweb1 == 3 && $java_processweb2 < 4))
#then
#/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeweb1','$java_processweb1_test_type','$java_processweb1_command',' ','$datenow','$nodeA_OK','$nodeB_NOK')" $dbName

#elif(($java_processweb1 < 3 && $java_processweb2 < 4))
#then
#/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeweb1','$java_processweb1_test_type','$java_processweb1_command',' ','$datenow','$nodeA_NOK','$nodeB_NOK')" $dbName

#else
#/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeweb1','$java_processweb1_test_type','$java_processweb1_command',' ','$datenow','$nodeA_OK','$nodeB_OK')" $dbName
#fi
#}

#ExtWebs

activeExWebs_function()
{
if(($active_processExweb1 > 0 && $active_processExweb2 == 0))
then
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeExtweb','$active_pr','$active_pr_command','$Active_load_commentExweb1','$datenow','$nodeA_NOK','$nodeB_OK')" $dbName
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeExtweb','$memory','$memory_command','Memory utilized not normal','$datenow','$nodeA_NOK','$nodeB_OK')" $dbName

elif(($active_processExweb1 == 0 && $active_processExweb2 > 0))
then
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeExtweb','$active_pr','$active_pr_command','$Active_load_commentExweb2','$datenow','$nodeA_OK','$nodeB_NOK')" $dbName
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeExtweb','$memory','$memory_command','Memory utilized not normal','$datenow','$nodeA_OK','$nodeB_NOK')" $dbName

elif(($active_processExweb1 > 0 && $active_processExweb2 > 0))
then
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeExtweb','$active_pr','$active_pr_command','$Active_load_commentExweb2','$datenow','$nodeA_NOK','$nodeB_NOK')" $dbName
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeExtweb','$memory','$memory_command','Memory utilized not normal','$datenow','$nodeA_NOK','$nodeB_NOK')" $dbName

else
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeExtweb','$active_pr','$active_pr_command',' ','$datenow','$nodeA_OK','$nodeB_OK')" $dbName
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeExtweb','$memory','$memory_command',' ','$datenow','$nodeA_OK','$nodeB_OK')" $dbName
fi
}

diskExWebs_function()
{
if(($disk_countExweb1 > 0 && $disk_countExweb2 == 0))
then
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeExtweb','$disk_test_type','$disk_command','$disk_commentExweb1','$datenow','$nodeA_NOK','$nodeB_OK')" $dbName

elif(($disk_countExweb1 == 0 && $disk_countExweb2 > 0))
then
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeExtweb','$disk_test_type','$disk_command','$disk_commentExweb2','$datenow','$nodeA_OK','$nodeB_NOK')" $dbName

elif(($disk_countExweb1 > 0 && $disk_countExweb2 > 0))
then
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeExtweb','$disk_test_type','$disk_command','$disk_commentExweb2','$datenow','$nodeA_NOK','$nodeB_NOK')" $dbName

else
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeExtweb','$disk_test_type','$disk_command',' ','$datenow','$nodeA_OK','$nodeB_OK')" $dbName
fi
}

cpuExWebs_function()
{
if(($cpu_countExweb1 < 3 && $cpu_countExweb2 >= 4))
then
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeExtweb','$cpu_testType','$cpu_command',' ','$datenow','$nodeA_NOK','$nodeB_OK')" $dbName

elif(($cpu_countExweb1 >= 4 && $cpu_countExweb2 < 3))
then
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeExtweb','$cpu_testType','$cpu_command',' ','$datenow','$nodeA_OK','$nodeB_NOK')" $dbName

elif(($cpu_countExweb1 < 3 && $cpu_countExweb2 < 3))
then
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeExtweb','$cpu_testType','$cpu_command',' ','$datenow','$nodeA_NOK','$nodeB_NOK')" $dbName

else
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeExtweb','$cpu_testType','$cpu_command',' ','$datenow','$nodeA_OK','$nodeB_OK')" $dbName
fi
}

#javaprocessExWebs_function()
#{
#if(($java_processExweb1 < 3 && $java_processExweb2 == 3))
#then
#/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeExtweb','$java_processweb1_test_type','$java_processweb1_command',' ','$datenow','$nodeA_NOK','$nodeB_OK')" $dbName

#elif(($java_processExweb1 == 3 && $java_processExweb2 < 3))
#then
#/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeExtweb','$java_processweb1_test_type','$java_processweb1_command',' ','$datenow','$nodeA_OK','$nodeB_NOK')" $dbName

#elif(($java_processExweb1 < 3 && $java_processExweb2 < 3))
#then
#/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeExtweb','$java_processweb1_test_type','$java_processweb1_command',' ','$datenow','$nodeA_NOK','$nodeB_NOK')" $dbName

#else
#/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeExtweb','$java_processweb1_test_type','$java_processweb1_command',' ','$datenow','$nodeA_OK','$nodeB_OK')" $dbName
#fi
#}

#Reporting server

activeReport_function()
{
if(($activeRepo_processweb1 > 0))
then
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeReporting','$active_pr','$active_pr_command','$ActiveRepo_load_commentweb1','$datenow','$nodeA_NOK','')" $dbName
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeReporting','$memory','$memory_command','Memory utilized not normal','$datenow','$nodeA_NOK','')" $dbName

else
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeReporting','$active_pr','$active_pr_command',' ','$datenow','$nodeA_OK','')" $dbName
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeReporting','$memory','$memory_command',' ','$datenow','$nodeA_OK','')" $dbName
fi
}

diskReport_function()
{
if(($diskRepo_countweb1 > 0))
then
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeReporting','$disk_test_type','$disk_command','$diskRepo_commentweb1','$datenow','$nodeA_NOK','')" $dbName

else
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeReporting','$disk_test_type','$disk_command',' ','$datenow','$nodeA_OK','')" $dbName
fi
}

cpuReport_function()
{
if(($cpuRepo_countweb1 < 3))
then
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeReporting','$cpu_testType','$cpu_command',' ','$datenow','$nodeA_NOK','')" $dbName

else
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeReporting','$cpu_testType','$cpu_command',' ','$datenow','$nodeA_OK','')" $dbName
fi
}

javaprocessReport_function()
{
if(($javaRepo_processweb1 < 53))
then
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeReporting','$javaRepo_processweb1_test_type','$javaRepo_processweb1_command',' ','$datenow','$nodeA_NOK','')" $dbName

else
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeReporting','$javaRepo_processweb1_test_type','$javaRepo_processweb1_command',' ','$datenow','$nodeA_OK','')" $dbName
fi
}

#TCS

activeTCS_function()
{
if(($active_processTCS1 > 0 && $active_processTCS2 == 0))
then
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeTCS','$active_pr','$active_pr_command','$Active_load_commentTCS1','$datenow','$nodeA_NOK','$nodeB_OK')" $dbName
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeTCS','$memory','$memory_command','Memory utilized not normal','$datenow','$nodeA_NOK','$nodeB_OK')" $dbName

elif(($active_processTCS1 == 0 && $active_processTCS2 > 0))
then
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeTCS','$active_pr','$active_pr_command','$Active_load_commentweb2','$datenow','$nodeA_OK','$nodeB_NOK')" $dbName
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeTCS','$memory','$memory_command','Memory utilized not normal','$datenow','$nodeA_OK','$nodeB_NOK')" $dbName

elif(($active_processTCS1 > 0 && $active_processTCS2 > 0))
then
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeTCS','$active_pr','$active_pr_command','$Active_load_commentweb2','$datenow','$nodeA_NOK','$nodeB_NOK')" $dbName
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeTCS','$memory','$memory_command','Memory utilized not normal','$datenow','$nodeA_NOK','$nodeB_NOK')" $dbName

else
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeTCS','$active_pr','$active_pr_command',' ','$datenow','$nodeA_OK','$nodeB_OK')" $dbName
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeTCS','$memory','$memory_command',' ','$datenow','$nodeA_OK','$nodeB_OK')" $dbName
fi
}

diskTCS_function()
{
if(($disk_countTCS1 > 0 && $disk_countTCS2 == 0))
then
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeTCS','$disk_test_type','$disk_command','$disk_commentTCS1','$datenow','$nodeA_NOK','$nodeB_OK')" $dbName

elif(($disk_countTCS1 == 0 && $disk_countTCS2 > 0))
then
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeTCS','$disk_test_type','$disk_command','$disk_commentTCS2','$datenow','$nodeA_OK','$nodeB_NOK')" $dbName

elif(($disk_countTCS1 > 0 && $disk_countTCS2 > 0))
then
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeTCS','$disk_test_type','$disk_command','$disk_commentTCS2','$datenow','$nodeA_NOK','$nodeB_NOK')" $dbName

else
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeTCS','$disk_test_type','$disk_command',' ','$datenow','$nodeA_OK','$nodeB_OK')" $dbName
fi
}

cpuTCS_function()
{
if(($cpu_countTCS1 < 3 && $cpu_countTCS2 >= 4))
then
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeTCS','$cpu_testType','$cpu_command',' ','$datenow','$nodeA_NOK','$nodeB_OK')" $dbName

elif(($cpu_countTCS1 >= 4 && $cpu_countTCS2 < 3))
then
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeTCS','$cpu_testType','$cpu_command',' ','$datenow','$nodeA_OK','$nodeB_NOK')" $dbName

elif(($cpu_countTCS1 < 3 && $cpu_countTCS2 < 3))
then
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeTCS','$cpu_testType','$cpu_command',' ','$datenow','$nodeA_NOK','$nodeB_NOK')" $dbName

else
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeTCS','$cpu_testType','$cpu_command',' ','$datenow','$nodeA_OK','$nodeB_OK')" $dbName
fi
}

rejectionTCS_function()
{
if(($reject_tcs1 > 0 && $reject_tcs2 == 0))
then
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeTCS','$rejection_test_type','$rejection_command','There is rejections on NODE1','$datenow','$nodeA_NOK','$nodeB_OK')" $dbName

elif(($reject_tcs1 == 0 && $reject_tcs2 > 0))
then
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeTCS','$rejection_test_type','$rejection_command','There is rejections on NODE2','$datenow','$nodeA_OK','$nodeB_NOK')" $dbName

elif(($reject_tcs1 > 0 && $reject_tcs2 > 0))
then
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeTCS','$rejection_test_type','$rejection_command','There is rejections on both NODES','$datenow','$nodeA_NOK','$nodeB_NOK')" $dbName

else
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeTCS','$rejection_test_type','$rejection_command',' ','$datenow','$nodeA_OK','$nodeB_OK')" $dbName
fi
}

#DB

activeDB_function()
{
if(($active_processdb1 > 0))
then
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeDatabase','$active_pr','$active_pr_command','$Active_load_commentdb1','$datenow','$nodeA_NOK','')" $dbName
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeDatabase','$memory','$memory_command','Memory utilized not normal','$datenow','$nodeA_NOK','')" $dbName

else
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeDatabase','$active_pr','$active_pr_command',' ','$datenow','$nodeA_OK','')" $dbName
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeDatabase','$memory','$memory_command',' ','$datenow','$nodeA_OK','')" $dbName
fi
}

diskDB_function()
{
if(($disk_countdb1 > 0))
then
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeDatabase','$disk_test_type','$disk_command','$disk_commentdb1','$datenow','$nodeA_NOK','')" $dbName

else
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeDatabase','$disk_test_type','$disk_command',' ','$datenow','$nodeA_OK','')" $dbName
fi
}

cpuDB_function()
{
if(($cpu_countdb1 < 3))
then
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeDatabase','$cpu_testType','$cpu_command',' ','$datenow','$nodeA_NOK','')" $dbName

else
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeDatabase','$cpu_testType','$cpu_command',' ','$datenow','$nodeA_OK','')" $dbName
fi
}

service_user_expirationDB_function()
{
if(($service_user_expiration < 9))
then
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeDatabase','$service_user_expiration_type','$service_user_expiration_command','User Exipired','$datenow','$nodeA_NOK','')" $dbName

else
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeDatabase','$service_user_expiration_type','$service_user_expiration_command',' ','$datenow','$nodeA_OK','')" $dbName
fi
}

tablespace_statusDB_function()
{
if(($tablespace_status < 8))
then
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeDatabase','$tablespace_status_type','$tablespace_status_command','There is an issue with Table space','$datenow','$nodeA_NOK','')" $dbName

else
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeDatabase','$tablespace_status_type','$tablespace_status_command',' ','$datenow','$nodeA_OK','')" $dbName
fi
}

DatafileDB_function()
{
if(($datafilecounts < 126))
then
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeDatabase','$datafile_status_type','$datafile_command',' ','$datenow','$nodeA_NOK','')" $dbName

else
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeDatabase','$datafile_status_type','$datafile_command',' ','$datenow','$nodeA_OK','')" $dbName
fi
}

FlashrecoveryDB_function()
{
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeDatabase','What is Consuming Flash Recovery Area Size','$whatconsuming_command','currently flash recovery is $flasharea','$datenow','OK/NOK','')" $dbName
}

Relative_wait_times_and_actual_CPU_processingDB_function()
{
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeDatabase','Examining session waits','Found in original Health sheet','currently situation is $metric','$datenow','OK/NOK','')" $dbName
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeDatabase','Database instace','Found in original Health sheet','$db_instace','$datenow','OK/NOK','')" $dbName
}

ASM_diskgroup_SAN_function()
{
if(($ASMcounts > 0))
then
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeDatabase','$ASM_diskgroup_SAN_command_type','$ASM_diskgroup_SAN_command','$whatconsumingASM','$datenow','$nodeA_NOK','')" $dbName

else
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeDatabase','$ASM_diskgroup_SAN_command_type','$ASM_diskgroup_SAN_command',' ','$datenow','$nodeA_OK','')" $dbName
fi
}

Tablespaceusage_function()
{
if(($tablespace_consuming_count > 0))
then
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeDatabase','$tablespace_consuming_space_type','$tablespace_consuming_space_command','$tablespace_consuming_space','$datenow','$nodeA_NOK','')" $dbName

else
/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into health_check(Node,Test_Type,Command,Comment,Date,NODEA,NODEB) values('$nodeDatabase','$tablespace_consuming_space_type','$tablespace_consuming_space_command',' ','$datenow','$nodeA_OK','')" $dbName
fi
}



#TCS Functions
activeTCS_function
diskTCS_function
cpuTCS_function
rejectionTCS_function

#DB Functions
activeDB_function
diskDB_function
cpuDB_function
service_user_expirationDB_function
tablespace_statusDB_function
DatafileDB_function
FlashrecoveryDB_function
Relative_wait_times_and_actual_CPU_processingDB_function
ASM_diskgroup_SAN_function
Tablespaceusage_function

#IntWebs Functions
activeWebs_function
diskWebs_function
cpuWebs_function
#javaprocessWebs_function

#ExtWebs Functions
activeExWebs_function
diskExWebs_function
cpuExWebs_function
#javaprocessExWebs_function

#Reporting Server function
activeReport_function
diskReport_function
cpuReport_function
javaprocessReport_function









