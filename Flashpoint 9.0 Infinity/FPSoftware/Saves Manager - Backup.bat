@ECHO OFF
ECHO Flash game save backup tool for Flashpoint - written by nosamu
ECHO This tool will back up your Flash Player save files.
PAUSE
ECHO Copying files...
PUSHD %~dp0
ROBOCOPY "%APPDATA%\Macromedia\Flash Player" "Flash Save Backup" /E > backup.log
POPD
ECHO Finished!
PAUSE
EXPLORER "%~dp0Flash Save Backup"