The steps for SSL upgrade
1. Stop nginx service
# service nginx stop
2. Navigate to ssl path (where the certificate are stored)
#cd /etc/nginx/ssl
3. Start generating the certificate by writing below script and fill required information
#openssl req -days 365 -new -x509 -nodes -out testtigopesamapps.tigo.co.tz.crt -keyout testtigopesamapps.tigo.co.tz.key
Country Name (2 letter code) [XX]:TZ
State or Province Name (full name) []:Dar es Salaam
Locality Name (eg, city) [Default City]:Dar es Salaam
Organization Name (eg, company) [Default Company Ltd]:MIC Tanzania Limited
Organizational Unit Name (eg, section) []:Mobile Financial Services
Common Name (eg, your name or your server's hostname) []:testtigopesamapps.tigo.co.tz
Email Address []:licensing@tigo.co.tz
4. Important: Take a look of nginx's vhost regarding SSL certs names declared on the config file and adjust them to the new ones.
conf file path: /etc/nginx/conf.d/443-testtigopesamapps.conf
5. Now, we check nginx's configs files and start it.
# nginx -t
6. restart the nginx service
# sudo service nginx restart
Now download the updated certificate through URL https://10.222.150.209/web/ and export to any folder
7. Lastly upload the certificate on Access Gateway
I/ Go to task tab on Policy Manager
ii) Then Manage Certificate
iii) Lastly Import Certificate from where you export on URL
iv) Now done

open ssl crt details ie days/validity
#openssl x509 -text -noout -in tigopesamapps.tigo.co.tz.crt

Read ssl valid on port
# openssl s_client -showcerts -connect 10.222.150.209:443

httpd conf.
cd/etc/nginx/conf.d

Tasks -> Manage certificates -> Search and remove the old one -> Add new certificate

Check Trusted
Check first 3 options

-----------tail mfsapp logs--------
 tail -f *.log | grep 255654876740

---cat GUID,CorrelationID,SessionID-------
cat  *.log | grep bruJc3


Authorization: Basic VEcwNTM6eDM0UWprOz5BN0VeP2Ry

