@ECHO OFF
PUSHD %~dp0
DEL Netscape4\Communicator\Program\Plugins\npvrml.*
COPY /Y Netscape4\Communicator\Program\Plugins\xnpcosmop211.dll Netscape4\Communicator\Program\Plugins\npvrml.dll
COPY /Y Netscape4\Communicator\Program\Plugins\xnpcosmop211.jar Netscape4\Communicator\Program\Plugins\npvrml.jar
FlashpointSecurePlayer cosmo211 "%~1"

IF %ERRORLEVEL% EQU -2 (
    ECHO MsgBox "You cannot run multiple instances of Flashpoint Secure Player." > %TMP%\TMP.VBS
    WSCRIPT /nologo %TMP%\TMP.VBS
    DEL %TMP%\TMP.VBS
)
POPD