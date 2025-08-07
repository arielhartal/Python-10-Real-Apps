@ECHO OFF
PUSHD %~dp0
FlashpointSecurePlayer shiva3d "%~1"

IF %ERRORLEVEL% EQU -2 (
    ECHO MsgBox "You cannot run multiple instances of Flashpoint Secure Player." > %TMP%\TMP.VBS
    WSCRIPT /nologo %TMP%\TMP.VBS
    DEL %TMP%\TMP.VBS
)
POPD