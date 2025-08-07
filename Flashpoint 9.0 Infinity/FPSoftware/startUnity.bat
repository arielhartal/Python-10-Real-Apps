@ECHO OFF
PUSHD %~dp0
IF "%1"=="2.x" (
    IF "%3"=="" (
        REM Unity Web Player 2, no scaling
        FlashpointSecurePlayer unitywebplayer2 "%~2"
    ) ELSE (
        REM Unity Web Player 2, scaling
        FlashpointSecurePlayer unitywebplayer2scaling "%~2"
    )
) ELSE (
    IF "%1"=="3.x" (
        IF "%3"=="" (
            REM Unity Web Player 3, no scaling
            FlashpointSecurePlayer unitywebplayer3 "%~2"
        ) ELSE (
            REM Unity Web Player 3, scaling
            FlashpointSecurePlayer unitywebplayer3scaling "%~2"
        )
    ) ELSE (
        IF "%1"=="5.x" (
            IF "%3"=="" (
                REM Unity Web Player 5, no scaling
                FlashpointSecurePlayer unitywebplayer5 "%~2"
            ) ELSE (
                REM Unity Web Player 5, scaling
                FlashpointSecurePlayer unitywebplayer5scaling "%~2"
            )
        ) ELSE (
            IF "%2"=="" (
                REM Unity Web Player 5, no version specified, no scaling
                FlashpointSecurePlayer unitywebplayer5 "%~1"
            ) ELSE (
                REM Unity Web Player 5, no version specified, scaling
                FlashpointSecurePlayer unitywebplayer5scaling "%~1"
            )
        )
    )
)

IF %ERRORLEVEL% EQU -2 (
    ECHO MsgBox "You cannot run multiple instances of Flashpoint Secure Player." > %TMP%\TMP.VBS
    WSCRIPT /nologo %TMP%\TMP.VBS
    DEL %TMP%\TMP.VBS
)
POPD