@ECHO OFF
PUSHD %~dp0
OldCPUSimulator\OldCPUSimulator -t 200 --set-synced-process-affinity-one --synced-process-main-thread-only --refresh-rate-floor-fifteen -sw FlashpointSecurePlayer svr %*

IF %ERRORLEVEL% EQU -2 (
    ECHO MsgBox "You cannot run multiple instances of Flashpoint Secure Player." > %TMP%\TMP.VBS
    WSCRIPT /nologo %TMP%\TMP.VBS
    DEL %TMP%\TMP.VBS
)
POPD