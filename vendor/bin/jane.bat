@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../jane/jane/bin/jane
php "%BIN_TARGET%" %*
