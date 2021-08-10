#!/bin/bash
ORACLE_SID=mmoney
ORACLE_HOME=/oracle/ora11g
PATH=/usr/bin:/bin:/usr/ucbbin:${ORACLE_HOME}/bin
LD_LIBRARY_PATH=/usr/lib:/usr/ucblib:/usrucb/lib:${ORACLE_HOME}/lib
export SHELL ORACLE_HOME PATH LD_LIBRARY_PATH RUNDIR LOGDIR m_date logfile ORACLE_SID PASS
LOCKFILE=/tmp/sun_m4k_lftp.lock
FILELOC=/oracle/scripts/data
LOGPATH=/oracle/scripts/logs
LOG=/home/oracle/scripts/telepin.log
dbHost=10.76.107.155
dbUser=mfs
dbPassword=mfs123
dbName=telepin
HOST=10.99.2.1
USER=mns
PASS=Tigo@123
LOGFILE=$LOGPATH/edr2_log.log
BADFILE=$LOGPATH/edr2_bad.log
CONNECT=tigopeesa/WaeFeso_1234@mmoney
CONTROL=/oracle/scripts/edr.ctl

datenow=`date +%d-%m-%y" "%H:%M:%S`;

hm=`/usr/bin/perl -e 'use POSIX qw(strftime); print strftime "%H%M\n",localtime(time());'`

#file_seq=`/usr/local/mysql/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "select lpad(max(file_seq)+1,10,'0') from file_manager" $dbName`
file_seq=$1
datenow=`date +%d-%m-%y" "%H:%M:%S`;
echo "$datenow - File Manager is running for file with Seq # $file_seq .." >> $LOGPATH/interface_lftp2.log

echo "$datenow - Initiating sftp for file with Seq # $file_seq" >> $LOGPATH/interface_lftp2.log

cd $FILELOC

/usr/local/bin/lftp -u ${USER},${PASS} sftp://${HOST} <<EOF
set ftp:passive-mode off
cd cdr
lcd $FILELOC
mget *$file_seq*
bye
EOF
cd $FILELOC
file_name=`ls -t vsalesjournal_domestic_*$file_seq*| head -1`
datenow=`date +%d-%m-%y" "%H:%M:%S`;
echo "$datenow - File downloaded successful: Seq # $file_seq , $file_name completed." >> $LOGPATH/interface_lftp2.log

file_records=`cat $FILELOC/$file_name|wc -l`


if [ -f "$FILELOC/$file_name" ]; then
datenow=`date +%d-%m-%y" "%H:%M:%S`;
echo "$datenow - Inserting the file details into mysql File_Manager Table for file with Seq # $file_seq" >> $LOGPATH/interface_lftp2.log

/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e "insert into file_manager(file_seq,file_name,file_records,load_status) values($file_seq,'$file_name',$file_records,1)" $dbName

cat $file_name|awk -F"|" 'BEGIN{OFS="|"}{print $54, $5,$8,$9,$3, $16, $17, $14, $15, $47, $48, $52, $53, $15, $38, $2, $1, $1, $11, $50, $51, $46, $10, $14, $1, $6, (-1*$18+$24), (-1*$19+$25), $17, $46, $45, $8, $1, $30, $12, $41,$34,$57,$60,$61}' >>$FILELOC/$file_seq.csv
datenow=`date +%d-%m-%y" "%H:%M:%S`;
echo "$datenow - Loading the file into oracle EDR_FCT_TRANSACTION Table for file with Seq # $file_seq" >> $LOGPATH/interface_lftp2.log
DATAFILE=$FILELOC/$file_seq.csv

$ORACLE_HOME/bin/sqlldr userid=$CONNECT control=$CONTROL readsize=20000000 bindsize=20000000 log=$LOGFILE bad=$BADFILE rows=2000 data=$DATAFILE errors=1000000 silent=feedback

grep "Rows successfully loaded" $LOGPATH/edr2_log.log >>$LOGPATH/interface_lftp2.log
cd $FILELOC


error_count=`cat $FILELOC/$file_name|awk -F"|" 'BEGIN{counter=0}{if (NF>57 || NF<57){counter=counter+1;}}END{print counter}'`;

if [ $error_count -gt 0 ]; then
datenow=`date +%d-%m-%y" "%H:%M:%S`;
message="Telepin EDR Alert:File $file_name have record issue(s). More or less than 55 fields per record $datenow";
/usr/bin/perl /oracle/scripts/sendsms.pl 255713123448 Tigopesa "$message";
fi
datenow=`date +%d-%m-%y" "%H:%M:%S`;
echo "$datenow - End of processing file $file_name . Thank you!" >> $LOGPATH/interface_lftp2.log
rm -f $FILELOC/$file_seq.csv;
rm -f $FILELOC/$file_name;
else
datenow=`date +%d-%m-%y" "%H:%M:%S`;
message="$datenow - Telepin EDR Alert:File with sequence# $file_seq doesn't exist";
/usr/bin/perl /oracle/scripts/sendsms.pl 255713123448 Tigopesa "$message";
fi
