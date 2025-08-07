@ECHO OFF
ECHO Flash game save file restore tool for Flashpoint - written by nosamu
ECHO This tool will restore your Flash Player save files.
ECHO If there are any save files you do not want to restore, 
ECHO remove them from the ^"Flash Save Backup^" folder before continuing.
ECHO This may overwrite ^(replace^) some save files already on your computer.
ECHO All save files currently on your computer will be backed up in the folder ^"Old Saves^"
PAUSE
ECHO Copying files...
PUSHD %~dp0
ROBOCOPY "%APPDATA%\Macromedia\Flash Player" "Old Saves" /E > restore.log
ROBOCOPY "Flash Save Backup" "%APPDATA%\Macromedia\Flash Player" /E >> restore.log
POPD
ECHO Finished restoring your save files!
PAUSE