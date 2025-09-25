@echo off
echo ================================================================================
echo WEBSITE REBUILDER - Generate PHP Website from Any URL
echo ================================================================================
echo.
set /p url="Enter website URL (e.g., https://example.com): "
echo.
set /p keywords="Enter target keywords (comma-separated) or press Enter to auto-generate: "
echo.
echo Starting website rebuild...
echo.

if "%keywords%"=="" (
    python main.py --url "%url%"
) else (
    python main.py --url "%url%" --keywords "%keywords%"
)

echo.
echo ================================================================================
echo Website generation complete! Check the 'output' folder.
echo ================================================================================
echo.
pause