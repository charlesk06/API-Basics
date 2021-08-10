LOAD DATA
append INTO TABLE MIDDLEWARE_API_CDR
fields terminated by ',' optionally enclosed by '"' trailing nullcols
(
TRANS_DATE  DATE  'YYYY-MM-DD',
BEGINTIME  TIMESTAMP 'HH24:MI:SS.FF',
ENDTIME  TIMESTAMP 'HH24:MI:SS.FF',
SERVER_IP,
CONSUMERID,
API,
STATUS,
CODE,
SOAID,
DESCRIPTION
)

