@ECHO OFF
PUSHD %~dp0
DEL Netscape4\Communicator\Program\Plugins\npvrml.*
COPY /Y Netscape4\Communicator\Program\Plugins\XNPL3D32.DLL Netscape4\Communicator\Program\Plugins\npvrml.dll
COPY /Y Netscape4\Communicator\Program\Plugins\XNPL3D32.ZIP Netscape4\Communicator\Program\Plugins\npvrml.zip
FlashpointSecurePlayer live3d "%~1"

IF %ERRORLEVEL% EQU -2 (
    ECHO MsgBox "You cannot run multiple instances of Flashpoint Secure Player." > %TMP%\TMP.VBS
    WSCRIPT /nologo %TMP%\TMP.VBS
    DEL %TMP%\TMP.VBS
)
POPD