#!/bin/bash
dbHost=10.76.107.155
dbUser=mfs
dbName=Telepin_HC
dbPassword=mfs123

datenow=`date +%Y-%m-%d`

/opt/xampp/bin/mysql -h $dbHost --user=$dbUser --password=$dbPassword --skip-column-names -e " select 'NODE', 'Test Type','COMMAND','NODEA','NODEB','COMMENT' UNION ALL select Node,Test_Type,Command,NODEA,NODEB,Comment from health_check where Date='$datenow' INTO OUTFILE '/var/www/html/telepin/Tigopesa_Health_Check/Tigo_Pesa_Healthcheck.csv' FIELDS TERMINATED BY ','" $dbName

cd /var/www/html/telepin/Tigopesa_Health_Check/;

chmod 777 Tigo_Pesa_Healthcheck.csv;

health_report=`ls -t | grep "Tigo_Pesa_Healthcheck.csv"`;

#counts=`ls -t | grep "Tigo_Pesa_Healthcheck.csv" | wc -l`;

/opt/xampp/bin/php /oracle/scripts/healthcheck_report.php /var/www/html/telepin/Tigopesa_Health_Check/$health_report;

rm -f /var/www/html/telepin/Tigopesa_Health_Check/Tigo_Pesa_Healthcheck.csv;


