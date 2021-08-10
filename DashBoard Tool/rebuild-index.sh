#!/bin/bash
ORACLE_SID=mmoney
ORACLE_HOME=/oracle/ora11g
PATH=/usr/bin:/bin:/usr/ucbbin:${ORACLE_HOME}/bin
LD_LIBRARY_PATH=/usr/lib:/usr/ucblib:/usrucb/lib:${ORACLE_HOME}/lib
export SHELL ORACLE_HOME PATH LD_LIBRARY_PATH RUNDIR LOGDIR m_date logfile ORACLE_SID PASS

CONNECT=tigopeesa/WaeFeso_1234@mmoney
$ORACLE_HOME/bin/sqlplus -s $CONNECT << ZLM
ALTER INDEX TIGOPEESA.IDX_EDR_TRANS_EXT rebuild online nologging;    
ALTER INDEX TIGOPEESA.IDX_EDR_TRANS_DATE rebuild online nologging;   
ALTER INDEX TIGOPEESA.IDX_EDR_TRANS_RECEIVER rebuild online nologging;    
ALTER INDEX TIGOPEESA.IDX_EDR_TRANS_SENDER rebuild online nologging;
ALTER INDEX TIGOPEESA.IDX_EDR_TRANS_PROMO rebuild online nologging;
ALTER INDEX TIGOPEESA.IDX_EDR_TRANS_BRAND rebuild online nologging;
ALTER INDEX TIGOPEESA.SCR_ACCOUNT_ID_I rebuild online nologging;
ALTER INDEX TIGOPEESA.IDX_EDR_TRANS_ID rebuild online nologging;
exit
ZLM

