# Image Optimization Script
Add-Type -AssemblyName System.Drawing

function Resize-Image {
    param(
        [string]$InputPath,
        [string]$OutputPath,
        [int]$MaxWidth = 1920
    )
    
    $img = [System.Drawing.Image]::FromFile($InputPath)
    
    # Calculate new dimensions maintaining aspect ratio
    $ratio = $img.Height / $img.Width
    $newWidth = [Math]::Min($MaxWidth, $img.Width)
    $newHeight = [int]($newWidth * $ratio)
    
    Write-Host "Resizing $InputPath from $($img.Width)x$($img.Height) to ${newWidth}x${newHeight}"
    
    # Create new bitmap
    $newImg = New-Object System.Drawing.Bitmap($newWidth, $newHeight)
    $graphics = [System.Drawing.Graphics]::FromImage($newImg)
    $graphics.CompositingQuality = [System.Drawing.Drawing2D.CompositingQuality]::HighQuality
    $graphics.InterpolationMode = [System.Drawing.Drawing2D.InterpolationMode]::HighQualityBicubic
    $graphics.SmoothingMode = [System.Drawing.Drawing2D.SmoothingMode]::HighQuality
    
    # Draw resized image
    $graphics.DrawImage($img, 0, 0, $newWidth, $newHeight)
    
    # Save with high quality JPEG
    $encoderParams = New-Object System.Drawing.Imaging.EncoderParameters(1)
    $encoderParams.Param[0] = New-Object System.Drawing.Imaging.EncoderParameter([System.Drawing.Imaging.Encoder]::Quality, 85)
    $jpegCodec = [System.Drawing.Imaging.ImageCodecInfo]::GetImageEncoders() | Where-Object {$_.MimeType -eq "image/jpeg"}
    
    $newImg.Save($OutputPath, $jpegCodec, $encoderParams)
    
    # Cleanup
    $graphics.Dispose()
    $newImg.Dispose()
    $img.Dispose()
    
    $newSize = (Get-Item $OutputPath).Length
    Write-Host "Saved: $OutputPath ($([math]::Round($newSize/1MB, 2))MB)"
}

# Set location
Set-Location "C:\Users\dave\Documents\Projects\site-builder\projects\tour-experiences\output\images"

# Create optimized directory
New-Item -ItemType Directory -Force -Name "optimized"

# Optimize all images
$images = @(
    "hero-golf-simulator.jpg",
    "features-modern-simulator.jpg", 
    "partnership-professional-golfer.jpg",
    "tech-header-simulator-room.jpg",
    "tech-young-man-simulator.jpg",
    "data-analytics-golf.jpg",
    "app-smartphone-golf.jpg",
    "experience-header-panoramic.jpg",
    "experience-back-view-simulator.jpg",
    "experience-female-golfer.jpg",
    "booking-grandfather-teaching.jpg"
)

foreach ($image in $images) {
    if (Test-Path $image) {
        Resize-Image -InputPath $image -OutputPath "optimized\$image" -MaxWidth 1920
    }
}

Write-Host "Optimization complete!"