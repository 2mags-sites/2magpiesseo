const fs = require('fs');

// Create a simple 180x180 PNG with XP text
// This creates a minimal valid PNG file structure

// PNG file signature
const signature = Buffer.from([0x89, 0x50, 0x4E, 0x47, 0x0D, 0x0A, 0x1A, 0x0A]);

// IHDR chunk (image header)
const width = 180;
const height = 180;
const bitDepth = 8;
const colorType = 2; // RGB
const compression = 0;
const filter = 0;
const interlace = 0;

function createIHDR() {
    const data = Buffer.alloc(13);
    data.writeUInt32BE(width, 0);
    data.writeUInt32BE(height, 4);
    data.writeUInt8(bitDepth, 8);
    data.writeUInt8(colorType, 9);
    data.writeUInt8(compression, 10);
    data.writeUInt8(filter, 11);
    data.writeUInt8(interlace, 12);
    return createChunk('IHDR', data);
}

function createChunk(type, data) {
    const length = data.length;
    const chunk = Buffer.alloc(length + 12);
    chunk.writeUInt32BE(length, 0);
    chunk.write(type, 4);
    data.copy(chunk, 8);
    const crc = calculateCRC(chunk.slice(4, 8 + length));
    chunk.writeUInt32BE(crc, 8 + length);
    return chunk;
}

function calculateCRC(data) {
    // Simple CRC placeholder - in production would use proper CRC32
    return 0;
}

// Create image data (solid color for simplicity)
function createIDAT() {
    const pixelData = Buffer.alloc(width * height * 3 + height);
    let offset = 0;

    for (let y = 0; y < height; y++) {
        pixelData[offset++] = 0; // filter type none
        for (let x = 0; x < width; x++) {
            // Create orange XP on black background
            const isXP = (x > 50 && x < 130 && y > 60 && y < 120);
            if (isXP) {
                pixelData[offset++] = 0xFF; // R
                pixelData[offset++] = 0x8C; // G
                pixelData[offset++] = 0x00; // B
            } else {
                pixelData[offset++] = 0x0A; // R
                pixelData[offset++] = 0x0A; // G
                pixelData[offset++] = 0x0A; // B
            }
        }
    }

    // In a real implementation, would compress with zlib
    return createChunk('IDAT', pixelData);
}

// IEND chunk
const iend = createChunk('IEND', Buffer.alloc(0));

// For now, let's create a simple HTML file that will generate the icon
const html = `<!DOCTYPE html>
<html>
<head>
    <style>
        body { margin: 0; font-family: -apple-system, BlinkMacSystemFont, 'Inter', sans-serif; }
        #canvas { display: none; }
    </style>
</head>
<body>
    <canvas id="canvas" width="180" height="180"></canvas>
    <a id="download" download="apple-touch-icon.png">Download Apple Touch Icon</a>
    <script>
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');

        // Black background
        ctx.fillStyle = '#0a0a0a';
        ctx.fillRect(0, 0, 180, 180);

        // Orange XP text
        ctx.fillStyle = '#ff8c00';
        ctx.font = 'bold 95px -apple-system, BlinkMacSystemFont, "Inter", sans-serif';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.fillText('XP', 90, 90);

        // Convert to data URL and set download link
        const dataURL = canvas.toDataURL('image/png');
        document.getElementById('download').href = dataURL;

        // Show preview
        const img = new Image();
        img.src = dataURL;
        document.body.appendChild(img);
    </script>
</body>
</html>`;

fs.writeFileSync('output/generate-apple-icon.html', html);
console.log('Open generate-apple-icon.html in a browser and click Download to get the icon');