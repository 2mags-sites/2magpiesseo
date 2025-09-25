const fs = require('fs');
const { createCanvas } = require('canvas');

// Create a 180x180 canvas (Apple Touch Icon size)
const canvas = createCanvas(180, 180);
const ctx = canvas.getContext('2d');

// Black background
ctx.fillStyle = '#0a0a0a';
ctx.fillRect(0, 0, 180, 180);

// Orange XP text
ctx.fillStyle = '#ff8c00';
ctx.font = 'bold 100px Arial';
ctx.textAlign = 'center';
ctx.textBaseline = 'middle';
ctx.fillText('XP', 90, 95);

// Save as PNG
const buffer = canvas.toBuffer('image/png');
fs.writeFileSync('output/apple-touch-icon.png', buffer);
console.log('Apple Touch Icon created successfully!');