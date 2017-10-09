@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../jane/open-api/bin/jane-openapi
php "%BIN_TARGET%" %*
