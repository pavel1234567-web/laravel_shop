@echo off
cd /d %~dp0

:: Формируем дату YYYY-MM-DD
set YYYY=%date:~-4%
set MM=%date:~3,2%
set DD=%date:~0,2%

:: Формируем время HH-MM
set HH=%time:~0,2%
set Min=%time:~3,2%
set HH=%HH: =0%

:: Сообщение коммита
set MESSAGE=Auto update %YYYY%-%MM%-%DD% %HH%:%Min%

:: Лог-файл
set LOGFILE=git_update.log

echo ============================== >> %LOGFILE%
echo Start: %date% %time% >> %LOGFILE%
echo Commit message: %MESSAGE% >> %LOGFILE%
echo ============================== >> %LOGFILE%

git pull origin main >> %LOGFILE% 2>&1
git add . >> %LOGFILE% 2>&1
git commit -m "%MESSAGE%" >> %LOGFILE% 2>&1
git push >> %LOGFILE% 2>&1

echo Finished: %date% %time% >> %LOGFILE%
echo. >> %LOGFILE%

pause
