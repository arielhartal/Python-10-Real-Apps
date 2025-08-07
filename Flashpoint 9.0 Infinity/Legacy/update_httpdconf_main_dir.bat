@ECHO OFF
CD /D %~dp0
SET ServerFolder=%~dp0
SET ServerFolder=%ServerFolder:~0,-1%
IF EXIST conf\httpd.conf.bak (CALL reset_httpdconf_main_dir.bat)
COPY conf\httpd.conf conf\httpd.conf.bak
REM options: literal match, whole file (not line by line), 
REM file conf\httpd.conf, output file same as input file.
CALL JREPL.bat "SERVER ROOT FULL PATH" "%ServerFolder%" /L /M /F conf\httpd.conf /O -
