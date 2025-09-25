@echo off
echo Optimizing images for web...

REM Use magick (ImageMagick) if available, otherwise use PowerShell
where magick >nul 2>nul
if %ERRORLEVEL% EQU 0 (
    echo Using ImageMagick...
    magick hero-golf-simulator.jpg -resize 1920x1080> optimized/hero-golf-simulator.jpg
    magick features-modern-simulator.jpg -resize 1920x1080> optimized/features-modern-simulator.jpg
    magick partnership-professional-golfer.jpg -resize 1920x1080> optimized/partnership-professional-golfer.jpg
    magick tech-header-simulator-room.jpg -resize 1920x1080> optimized/tech-header-simulator-room.jpg
    magick tech-young-man-simulator.jpg -resize 1920x1080> optimized/tech-young-man-simulator.jpg
    magick data-analytics-golf.jpg -resize 1920x1080> optimized/data-analytics-golf.jpg
    magick app-smartphone-golf.jpg -resize 1920x1080> optimized/app-smartphone-golf.jpg
    magick experience-header-panoramic.jpg -resize 1920x1080> optimized/experience-header-panoramic.jpg
    magick experience-back-view-simulator.jpg -resize 1920x1080> optimized/experience-back-view-simulator.jpg
    magick experience-female-golfer.jpg -resize 1920x1080> optimized/experience-female-golfer.jpg
    magick booking-grandfather-teaching.jpg -resize 1920x1080> optimized/booking-grandfather-teaching.jpg
    echo Optimization complete!
) else (
    echo ImageMagick not found. Please download from: https://imagemagick.org/script/download.php#windows
    echo Or use online tools like TinyPNG, Squoosh, or similar to resize to 1920px width max
)

pause