@echo off
title Reset Database Cloud Market
color 0b
echo ===============================================================
echo            CLOUD MARKET - RESET DATABASE RUNNER
echo ===============================================================
echo.
echo PERHATIAN: Ini akan mengembalikan database ke kondisi awal bersih!
echo.
set /p confirm="Lanjutkan proses reset database? (Y/N): "
if /i not "%confirm%"=="Y" (
    echo.
    echo Reset dibatalkan oleh pengguna.
    echo.
    pause
    exit /b 0
)

echo.
echo Menjalankan skrip PHP reset...
echo.
php "%~dp0reset.php"

echo.
pause

