@ECHO OFF
CD /D %~dp0
CD conf
IF NOT EXIST httpd.conf.bak (EXIT /B)
DEL httpd.conf
COPY httpd.conf.bak httpd.conf
DEL httpd.conf.bak
CD ..
