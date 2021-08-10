#!/bin/bash
ORACLE_SID=mmoney
ORACLE_HOME=/oracle/ora11g
PATH=/usr/bin:/bin:/usr/ucbbin:${ORACLE_HOME}/bin
LD_LIBRARY_PATH=/usr/lib:/usr/ucblib:/usrucb/lib:${ORACLE_HOME}/lib
export SHELL ORACLE_HOME PATH LD_LIBRARY_PATH RUNDIR LOGDIR m_date logfile ORACLE_SID PASS
FILELOC=/oracle/scripts/data
LOGPATH=/oracle/scripts/logs
dbHost=10.76.107.155
dbUser=mfs
dbPassword=mfs123
dbName=telepin

LOGFILE=$LOGPATH/mw_edr_log.log
BADFILE=$LOGPATH/mw_edr_bad.log
CONNECT=tigopeesa/WaeFeso_1234@mmoney
CONTROL=/oracle/scripts/mw_edr.ctl
DATAFILE=$1

$ORACLE_HOME/bin/sqlldr userid=$CONNECT control=$CONTROL readsize=20000000 bindsize=20000000 log=$LOGFILE bad=$BADFILE rows=2000 data=$DATAFILE errors=1000000 silent=feedback

