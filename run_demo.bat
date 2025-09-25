@echo off
echo ================================================================================
echo WEBSITE REBUILDER - DEMO MODE
echo ================================================================================
echo.
echo This will generate a complete demo website using example data.
echo.
echo Installing dependencies...
pip install -r requirements.txt
echo.
echo Running demo...
python main.py --demo
echo.
echo ================================================================================
echo Demo complete! Check the 'output' folder for your generated website.
echo ================================================================================
echo.
pause