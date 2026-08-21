@echo off
:: Small wrapper to start Mailpit via PowerShell script
powershell -NoProfile -ExecutionPolicy Bypass -File "%~dp0\scripts\start-mailpit.ps1"
pause
