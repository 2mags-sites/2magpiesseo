from PIL import Image, ImageDraw, ImageFont
import os

# Create a 180x180 image with black background
img = Image.new('RGB', (180, 180), color='#0a0a0a')
draw = ImageDraw.Draw(img)

# Try to use a bold font, fallback to default if not available
try:
    # Try Windows font
    font = ImageFont.truetype("C:/Windows/Fonts/arialbd.ttf", 95)
except:
    try:
        # Try another common font
        font = ImageFont.truetype("arial.ttf", 95)
    except:
        # Use default font
        font = ImageFont.load_default()

# Draw orange XP text
text = "XP"
# Get text bounding box
bbox = draw.textbbox((0, 0), text, font=font)
text_width = bbox[2] - bbox[0]
text_height = bbox[3] - bbox[1]

# Center the text
x = (180 - text_width) // 2
y = (180 - text_height) // 2 - 10  # Slight adjustment

draw.text((x, y), text, fill='#ff8c00', font=font)

# Save the image
output_path = 'output/apple-touch-icon.png'
img.save(output_path)
print(f"Apple Touch Icon created at {output_path}")