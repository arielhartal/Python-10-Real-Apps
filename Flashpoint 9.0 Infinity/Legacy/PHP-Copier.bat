@ECHO OFF
REM PHP file copier script by nosamu
REM 2020-04-28
SET FolderToCopyFrom=%1
SET FolderToCopyTo=%2
CD /D %~dp0

ECHO Copying files. Please wait...
ROBOCOPY %FolderToCopyFrom% %FolderToCopyTo% *.php *.php5 *.phtml *.jsp /S

IF %ERRORLEVEL% == 16 (
	ECHO Failed to copy files!
	ECHO Could not access source or destination folder.
	EXIT /B
)
IF %ERRORLEVEL% GTR 7 (
	ECHO Some files failed to copy!
	EXIT /B
)
IF %ERRORLEVEL% GEQ 4 (
	ECHO Source and destination folders were mismatched.
	ECHO Please check whether the files and folders copied successfully.
	EXIT /B
)
IF %ERRORLEVEL% GEQ 1 (
	ECHO Finished copying!
	EXIT /B
)
IF %ERRORLEVEL% == 0 (
	ECHO Nothing to copy!
	EXIT /B
)
