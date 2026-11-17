@echo off
setlocal
title Student Hub Launcher
cd /d "%~dp0"

set "PORT=8033"
set "PHP_EXE="

where php >nul 2>nul
if not errorlevel 1 set "PHP_EXE=php"

if not defined PHP_EXE if exist "%~dp0..\php83\php.exe" set "PHP_EXE=%~dp0..\php83\php.exe"
if not defined PHP_EXE if exist "%~dp0..\php\php.exe" set "PHP_EXE=%~dp0..\php\php.exe"

if not defined PHP_EXE (
  echo.
  echo PHP was not found.
  echo.
  echo Install PHP 8+ or place a portable PHP folder at:
  echo   %~dp0..\php83
  echo.
  pause
  exit /b 1
)

rem If Student Hub is already being served, just open it.
powershell -NoProfile -Command "try { $r = Invoke-WebRequest -Uri 'http://127.0.0.1:%PORT%/' -UseBasicParsing -TimeoutSec 2; if ($r.Content -match 'Student Hub') { exit 0 } } catch {}; exit 1"
if not errorlevel 1 (
  start "" "http://127.0.0.1:%PORT%/"
  exit /b 0
)

rem Start PHP's built-in server in its own minimized window.
start "Student Hub PHP Server" /min "%PHP_EXE%" -S 127.0.0.1:%PORT% -t "%~dp0"
ping -n 3 127.0.0.1 >nul
start "" "http://127.0.0.1:%PORT%/"
exit /b 0
