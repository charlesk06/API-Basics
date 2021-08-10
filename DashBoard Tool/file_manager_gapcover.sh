#!/bin/bash
ORACLE_SID=mmoney
ORACLE_HOME=/oracle/ora11g
PATH=/usr/bin:/bin:/usr/ucbbin:${ORACLE_HOME}/bin
LD_LIBRARY_PATH=/usr/lib:/usr/ucblib:/usrucb/lib:${ORACLE_HOME}/lib
export SHELL ORACLE_HOME PATH LD_LIBRARY_PATH RUNDIR LOGDIR m_date logfile ORACLE_SID 
LOCKFILE=/tmp/sun_m4k_lftp.lock
FILELOC=/oracle/scripts/data
LOGPATH=/oracle/scripts/logs
LOG=$LOGPATH/interface_lftp.log
dbHost=10.76.107.155
dbUser=mfs
dbPassword=mfs123
dbName=telepin
HOST=10.99.2.1
USER=mns
PASS=Tigo@123
export PASS
LOGFILE=$LOGPATH/edr_log.log
BADFILE=$LOGPATH/edr_bad.log
CONNECT=tigopeesa/WaeFeso_1234@mmoney
CONTROL=/oracle/scripts/edr.ctl

datenow=`date +%d-%m-%y" "%H:%M:%S`;
#ps -ef| grep 10.99.2.1 | awk '{print $2}' | xargs kill -9
dupe_script=`ps -ef | grep "file_manager_gapcover.sh" | grep -v grep | wc -l`

if [ ${dupe_script} -gt 1 ]; then
    echo "The file_manager_gapcover.sh script was already running $(date)" >> $LOG
    exit 0
fi
hm=`/usr/bin/perl -e 'use POSIX qw(strftime); print strftime "%H%M\n",localtime(time());'`
echo "$datenow - Table partition truncate trigger is $hm" >> $LOG


file_seq=`/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "select lpad(max(file_seq)+1,10,'0') from file_manager" $dbName`

datenow=`date +%d-%m-%y" "%H:%M:%S`;
echo "$datenow - File Manager is running for file with Seq # $file_seq .." >> $LOG

echo "$datenow - Initiating sftp for file with Seq # $file_seq" >> $LOG

cd $FILELOC

/usr/local/bin/lftp -u ${USER},${PASS} sftp://${HOST} <<EOF >> $LOG
set ftp:passive-mode off
cd cdr
lcd $FILELOC
mget vsalesjournal_domestic_*$file_seq*
bye
EOF

status=$?;
cd $FILELOC
file_name=`ls -t vsalesjournal_domestic_*$file_seq*| head -1`
datenow=`date +%d-%m-%y" "%H:%M:%S`;
echo "$datenow - File downloaded with Status: $status : Seq # $file_seq , $file_name completed." >> $LOG

file_records=`cat $FILELOC/$file_name|wc -l`


if [ -f "$FILELOC/$file_name" ]; then
datenow=`date +%d-%m-%y" "%H:%M:%S`;
echo "$datenow - Inserting the file details into mysql File_Manager Table for file with Seq # $file_seq" >> $LOG

/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into file_manager(file_seq,file_name,file_records,load_status) values($file_seq,'$file_name',$file_records,1)" $dbName

cat $file_name|awk -F"|" 'BEGIN{OFS="|"}{print $54, $5,$8,$9,$3, $16, $17, $14, $15, $47, $48, $52, $53, $15, $38, $2, $1, $1, $11, $50, $51, $46, $10, $14, $1, $6,(-1*$18+$24), (-1*$19+$25), $17, $46, $45, $8, $1, $30, $12, $41,$34,$57,$60,$61,$62,$3,$9,$62}' >>$FILELOC/$file_seq.csv
datenow=`date +%d-%m-%y" "%H:%M:%S`;
echo "$datenow - Loading the file into oracle EDR_FCT_TRANSACTION Table for file with Seq # $file_seq" >> $LOG
DATAFILE=$FILELOC/$file_seq.csv

$ORACLE_HOME/bin/sqlldr userid=$CONNECT control=$CONTROL readsize=20000000 bindsize=20000000 log=$LOGFILE bad=$BADFILE rows=10000 data=$DATAFILE errors=1000000 silent=feedback

grep "Rows successfully loaded" $LOGPATH/edr_log.log >>$LOG
cd $FILELOC


error_count=`cat $FILELOC/$file_name|awk -F"|" 'BEGIN{counter=0}{if (NF>57 || NF<57){counter=counter+1;}}END{print counter}'`;

rec_count=`cat $FILELOC/$file_name|wc -l`;

if [ $rec_count -lt 10 -o $error_count -gt 0 ]; then
datenow=`date +%d-%m-%y" "%H:%M:%S`;

message="Telepin EDR Alert:File $file_name has record(s) issue: Total file record(s) is $rec_count, error count is $error_count  $datenow";

fi

datenow=`date +%d-%m-%y" "%H:%M:%S`;
echo "$datenow - End of processing file $file_name . Thank you!" >> $LOG
rm -f $FILELOC/$file_seq.csv >> $LOG;
rm -f $FILELOC/$file_name >> $LOG;
else
datenow=`date +%d-%m-%y" "%H:%M:%S`;
message="$datenow - Telepin EDR Alert:File with sequence# $file_seq doesn't exist";

fi
