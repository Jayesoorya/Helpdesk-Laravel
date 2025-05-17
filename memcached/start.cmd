@echo off

title Memcached

set PATH=%SystemRoot%\system32;%SystemRoot%;%SystemRoot%\System32\Wbem

echo Starting Memcached
echo Press [Ctrl+C] to stop.
echo.

%~dp0memcached.exe -v
