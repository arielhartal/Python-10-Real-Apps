@ECHO OFF
SETLOCAL
PUSHD %~dp0

:GET_GAME
SET "GAME=%~1"
SHIFT
IF NOT "%~1"=="" GOTO GET_GAME

TYPE "3DGrooveGX\%GAME%.ini" > "3DGrooveGX\GroovePlayer.ini"
FlashpointSecurePlayer 3dgroovegx %*

IF %ERRORLEVEL% EQU -2 (
    ECHO MsgBox "You cannot run multiple instances of Flashpoint Secure Player." > %TMP%\TMP.VBS
    WSCRIPT /nologo %TMP%\TMP.VBS
    DEL %TMP%\TMP.VBS
)
POPD