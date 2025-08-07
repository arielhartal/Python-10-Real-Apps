@ECHO OFF
PUSHD %~dp0

IF NOT EXIST "%APPDATA%\Adobe\atmosphere.dll\" (
    MKDIR "%APPDATA%\Adobe\atmosphere.dll\"
)

IF NOT EXIST "%APPDATA%\Adobe\atmosphere.dll\PrefFile.prf" (
    COPY Atmo216\PrefFile.prf "%APPDATA%\Adobe\atmosphere.dll\PrefFile.prf"
)

FlashpointSecurePlayer atmo216 %1
POPD