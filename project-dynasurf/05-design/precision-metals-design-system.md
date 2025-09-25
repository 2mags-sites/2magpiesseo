# Precision Metals Engineering Design System

## Table of Contents
1. [Overview](#overview)
2. [Brand Philosophy](#brand-philosophy)
3. [Visual Identity](#visual-identity)
4. [Color System](#color-system)
5. [Typography](#typography)
6. [Material Effects](#material-effects)
7. [Component Architecture](#component-architecture)
8. [UI Components](#ui-components)
9. [Animation & Motion](#animation--motion)
10. [Implementation Guidelines](#implementation-guidelines)

## Overview

This design system embodies the precision, craftsmanship, and innovation of high-end metal engineering. Built with a dark-mode-first approach, it creates an interface that reflects the quality of aerospace-grade materials and the sophistication of advanced manufacturing.

### Core Principles
- **Engineered Precision**: Mathematical accuracy in every element
- **Material Authenticity**: Digital representation of real metals
- **Industrial Elegance**: Where function meets luxury
- **Depth & Dimension**: Layered, tactile interface
- **Performance First**: Optimized for technical professionals

## Brand Philosophy

### Vision
Create a digital experience that mirrors the precision of CNC machining and the beauty of perfectly engineered components - where every pixel is placed with the same care as every thousandth of an inch in our manufacturing.

### Brand Personality
- **Precise**: Zero-tolerance for imperfection
- **Technical**: Engineering-driven aesthetics
- **Premium**: Aerospace-grade quality
- **Innovative**: Cutting-edge manufacturing
- **Reliable**: Swiss-watch consistency

## Visual Identity

### Logo Treatment
- **Concept**: Technical precision mark with subtle dimensionality
- **Construction**: Based on golden ratio and technical drawing standards
- **Weight**: Variable weight suggesting machined edges
- **Finish Effects**:
  - Brushed metal texture overlay
  - Subtle inner bevel for depth
  - Laser-etched appearance on dark backgrounds

### Visual Principles
- Technical drawing inspiration
- Precise geometric construction
- Subtle material textures
- Dimensional depth through gradients
- Micro-animations suggesting precision machinery

## Color System

### Primary Palette - "Machined Metals"
```css
/* Core Metals */
--carbon-black: #0B0E10;       /* rgb(11, 14, 16) - Deepest black */
--graphite: #1A1D21;           /* rgb(26, 29, 33) - Primary dark */
--tungsten: #313740;           /* rgb(49, 55, 64) - Secondary dark */
--titanium: #4A5158;           /* rgb(74, 81, 88) - Tertiary dark */
--steel: #8B949E;              /* rgb(139, 148, 158) - Mid-tone metal */
--aluminum: #C9D1D9;           /* rgb(201, 209, 217) - Light metal */
--chrome: #E6EDF3;             /* rgb(230, 237, 243) - Bright metal */
```

### Accent Colors - "Precision Indicators"
```css
/* Engineering Accents */
--laser-green: #A9E702;        /* rgb(169, 231, 2) - Primary accent/active */
--plasma-blue: #00D4FF;        /* rgb(0, 212, 255) - Secondary accent */
--heat-orange: #FF6B2B;        /* rgb(255, 107, 43) - Warning/heat */
--coolant-cyan: #22D3EE;       /* rgb(34, 211, 238) - Info/cooling */
--safety-yellow: #FFD700;      /* rgb(255, 215, 0) - Caution */
--oxide-purple: #8B5CF6;       /* rgb(139, 92, 246) - Special */
```

### Functional Colors - "Quality Control"
```css
/* System States */
--pass-green: #10B981;         /* rgb(16, 185, 129) - Within tolerance */
--warning-amber: #F59E0B;      /* rgb(245, 158, 11) - Near tolerance */
--fail-red: #EF4444;           /* rgb(239, 68, 68) - Out of tolerance */
--info-blue: #3B82F6;          /* rgb(59, 130, 246) - Information */
--neutral: #6B7280;            /* rgb(107, 114, 128) - Inactive */
```

### Surface Gradients - "Metal Finishes"
```css
/* Gradient Overlays */
--gradient-brushed: linear-gradient(135deg, 
  rgba(201, 209, 217, 0.03) 0%, 
  rgba(201, 209, 217, 0.01) 100%);

--gradient-machined: linear-gradient(180deg,
  rgba(139, 148, 158, 0.05) 0%,
  rgba(139, 148, 158, 0) 50%,
  rgba(139, 148, 158, 0.05) 100%);

--gradient-polished: radial-gradient(
  ellipse at top,
  rgba(230, 237, 243, 0.08) 0%,
  transparent 70%);
```

## Typography

### Font Selection
**Primary**: Neue Montreal (as shown in reference)
```css
font-family: 
  "Neue Montreal",
  "Inter",
  -apple-system,
  system-ui,
  sans-serif;

/* Weights */
--font-regular: 400;
--font-medium: 500;
--font-semibold: 600;
```

**Technical**: Monospace for data/specs
```css
font-family:
  "JetBrains Mono",
  "SF Mono",
  "Consolas",
  monospace;
```

### Type Scale - "Technical Hierarchy"
```css
/* Display - Hero/Marketing */
.display-xl { 
  font-size: 4rem; 
  font-weight: var(--font-regular); 
  letter-spacing: -0.03em;
  background: var(--gradient-polished);
  -webkit-background-clip: text;
  background-clip: text;
}

/* Headings - Section Titles */
.heading-xl { font-size: 2rem; font-weight: var(--font-medium); }
.heading-lg { font-size: 1.75rem; font-weight: var(--font-medium); }
.heading-md { font-size: 1.5rem; font-weight: var(--font-regular); }
.heading-sm { font-size: 1.25rem; font-weight: var(--font-regular); }

/* Body - Content */
.body-lg { font-size: 1.125rem; line-height: 1.75; }
.body-md { font-size: 1rem; line-height: 1.65; }
.body-sm { font-size: 0.875rem; line-height: 1.5; }

/* Technical - Specifications */
.spec { font-family: var(--font-mono); font-size: 0.875rem; }
.measurement { font-family: var(--font-mono); font-variant-numeric: tabular-nums; }
```

## Material Effects

### Metallic Text Effect
```css
.text-metallic {
  background: linear-gradient(
    135deg,
    var(--aluminum) 0%,
    var(--chrome) 45%,
    var(--aluminum) 50%,
    var(--steel) 100%
  );
  -webkit-background-clip: text;
  background-clip: text;
  -webkit-text-fill-color: transparent;
  text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
}
```

### Frosted Glass Layers
```css
.glass-surface {
  background: rgba(26, 29, 33, 0.4);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  border: 1px solid rgba(201, 209, 217, 0.1);
  box-shadow: 
    inset 0 1px 0 rgba(255, 255, 255, 0.05),
    0 8px 32px rgba(0, 0, 0, 0.3);
}

.glass-folder {
  background: linear-gradient(
    to bottom,
    rgba(26, 29, 33, 0.6),
    rgba(26, 29, 33, 0.3)
  );
  backdrop-filter: blur(20px);
  border-top: 1px solid rgba(201, 209, 217, 0.15);
  position: relative;
}

.glass-folder::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 1px;
  background: linear-gradient(
    90deg,
    transparent,
    rgba(255, 255, 255, 0.1) 50%,
    transparent
  );
}
```

### Background Pattern
```css
.precision-grid {
  background-image: 
    repeating-linear-gradient(
      0deg,
      transparent,
      transparent 39px,
      rgba(201, 209, 217, 0.02) 39px,
      rgba(201, 209, 217, 0.02) 40px
    ),
    repeating-linear-gradient(
      90deg,
      transparent,
      transparent 39px,
      rgba(201, 209, 217, 0.02) 39px,
      rgba(201, 209, 217, 0.02) 40px
    );
  background-size: 40px 40px;
}

.technical-dots {
  background-image: radial-gradient(
    circle at 1px 1px,
    rgba(201, 209, 217, 0.05) 1px,
    transparent 1px
  );
  background-size: 20px 20px;
}
```

### Laser Line Effect
```css
.laser-underline {
  position: relative;
  display: inline-block;
}

.laser-underline::after {
  content: '';
  position: absolute;
  bottom: -2px;
  left: 50%;
  right: 50%;
  height: 2px;
  background: linear-gradient(
    90deg,
    transparent,
    var(--laser-green) 20%,
    var(--laser-green) 80%,
    transparent
  );
  box-shadow: 
    0 0 8px rgba(169, 231, 2, 0.6),
    0 0 16px rgba(169, 231, 2, 0.3);
  opacity: 0;
  transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

.laser-underline:hover::after {
  left: 0;
  right: 0;
  opacity: 1;
}
```

## Component Architecture

### Elevation System - "Machined Layers"
```css
/* Shadow Depths */
--shadow-surface: 0 1px 3px rgba(0, 0, 0, 0.4);
--shadow-raised: 0 4px 8px rgba(0, 0, 0, 0.5);
--shadow-floating: 0 8px 24px rgba(0, 0, 0, 0.6);
--shadow-lifted: 0 16px 48px rgba(0, 0, 0, 0.7);

/* Inner Shadows - Machined Edges */
--shadow-inset-subtle: inset 0 1px 2px rgba(0, 0, 0, 0.3);
--shadow-inset-deep: inset 0 2px 4px rgba(0, 0, 0, 0.5);
```

### Border Radius System
```css
/* Precision Corners */
--radius-sharp: 2px;      /* Minimal, technical */
--radius-sm: 4px;         /* Subtle softness */
--radius-md: 8px;         /* Cards, containers */
--radius-lg: 12px;        /* Buttons, inputs */
--radius-xl: 16px;        /* Modal, large cards */
--radius-pill: 9999px;    /* CTAs, badges */
```

## UI Components

### Buttons - "Precision Controls"
```css
/* Primary CTA - Laser Green */
.btn-primary {
  background: linear-gradient(135deg, var(--laser-green), #8BC400);
  color: var(--carbon-black);
  border: none;
  border-radius: var(--radius-pill);
  padding: 12px 32px;
  font-weight: var(--font-semibold);
  box-shadow: 
    0 4px 12px rgba(169, 231, 2, 0.3),
    inset 0 1px 0 rgba(255, 255, 255, 0.2);
  transition: all 0.2s ease;
}

.btn-primary:hover {
  transform: translateY(-1px);
  box-shadow: 
    0 6px 20px rgba(169, 231, 2, 0.4),
    inset 0 1px 0 rgba(255, 255, 255, 0.3);
}

/* Secondary - Metal Outline */
.btn-secondary {
  background: transparent;
  color: var(--chrome);
  border: 1px solid rgba(201, 209, 217, 0.3);
  border-radius: var(--radius-lg);
  padding: 12px 28px;
  position: relative;
  overflow: hidden;
}

.btn-secondary::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(
    90deg,
    transparent,
    rgba(201, 209, 217, 0.1),
    transparent
  );
  transition: left 0.5s ease;
}

.btn-secondary:hover::before {
  left: 100%;
}
```

### Input Fields - "Precision Entry"
```css
.input-precision {
  background: rgba(26, 29, 33, 0.6);
  border: 1px solid rgba(201, 209, 217, 0.15);
  border-radius: var(--radius-lg);
  color: var(--chrome);
  padding: 14px 16px;
  font-size: 0.9375rem;
  transition: all 0.2s ease;
}

.input-precision:focus {
  background: rgba(26, 29, 33, 0.8);
  border-color: var(--laser-green);
  box-shadow: 
    0 0 0 3px rgba(169, 231, 2, 0.1),
    inset 0 0 0 1px rgba(169, 231, 2, 0.2);
  outline: none;
}

.input-precision::placeholder {
  color: var(--steel);
  opacity: 0.7;
}
```

### Cards - "Component Modules"
```css
.card-precision {
  background: linear-gradient(
    135deg,
    rgba(26, 29, 33, 0.9),
    rgba(26, 29, 33, 0.7)
  );
  backdrop-filter: blur(10px);
  border: 1px solid rgba(201, 209, 217, 0.1);
  border-radius: var(--radius-md);
  padding: 24px;
  position: relative;
  overflow: hidden;
}

/* Holographic Edge Effect */
.card-precision::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 1px;
  background: linear-gradient(
    90deg,
    transparent,
    var(--plasma-blue) 20%,
    var(--laser-green) 50%,
    var(--plasma-blue) 80%,
    transparent
  );
  opacity: 0.5;
}
```

### Navigation - "Control Panel"
```css
.nav-precision {
  background: rgba(11, 14, 16, 0.95);
  backdrop-filter: blur(20px);
  border-bottom: 1px solid rgba(201, 209, 217, 0.1);
  padding: 16px 0;
}

.nav-item {
  color: var(--steel);
  font-weight: var(--font-regular);
  padding: 8px 16px;
  position: relative;
  transition: color 0.2s ease;
}

.nav-item:hover {
  color: var(--chrome);
}

.nav-item.active {
  color: var(--laser-green);
}

.nav-item.active::after {
  content: '';
  position: absolute;
  bottom: -17px;
  left: 16px;
  right: 16px;
  height: 2px;
  background: var(--laser-green);
  box-shadow: 0 0 8px rgba(169, 231, 2, 0.6);
}
```

## Animation & Motion

### Precision Timing
```css
/* Easing Functions - Machine Movement */
--ease-precision: cubic-bezier(0.25, 0.46, 0.45, 0.94);
--ease-smooth: cubic-bezier(0.43, 0.13, 0.23, 0.96);
--ease-mechanical: cubic-bezier(0.68, -0.55, 0.27, 1.55);

/* Duration Scale */
--duration-instant: 150ms;
--duration-quick: 250ms;
--duration-standard: 350ms;
--duration-slow: 500ms;
```

### Hover Animations
```css
/* Laser Scan Effect */
@keyframes laser-scan {
  0% { transform: translateX(-100%); }
  100% { transform: translateX(100%); }
}

.laser-scan-container {
  position: relative;
  overflow: hidden;
}

.laser-scan-container::after {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(
    90deg,
    transparent,
    rgba(169, 231, 2, 0.2),
    transparent
  );
  transform: translateX(-100%);
}

.laser-scan-container:hover::after {
  animation: laser-scan 1s ease;
}

/* Pulse Effect */
@keyframes precision-pulse {
  0%, 100% { opacity: 0.3; }
  50% { opacity: 1; }
}

.pulse-indicator {
  width: 8px;
  height: 8px;
  background: var(--laser-green);
  border-radius: 50%;
  box-shadow: 0 0 12px rgba(169, 231, 2, 0.6);
  animation: precision-pulse 2s infinite;
}
```

### Loading States
```css
/* Machining Progress */
.progress-bar {
  background: rgba(26, 29, 33, 0.6);
  height: 4px;
  border-radius: 2px;
  overflow: hidden;
  position: relative;
}

.progress-bar::after {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  height: 100%;
  width: 40%;
  background: linear-gradient(
    90deg,
    var(--laser-green),
    var(--plasma-blue)
  );
  box-shadow: 0 0 8px rgba(169, 231, 2, 0.6);
  animation: machining 2s linear infinite;
}

@keyframes machining {
  0% { transform: translateX(-100%); }
  100% { transform: translateX(250%); }
}
```

## Implementation Guidelines

### CSS Architecture
```css
/* Base Layer - Reset and Variables */
:root {
  /* All color variables */
  /* All spacing variables */
  /* All animation variables */
}

/* Component Layer */
.component {
  /* Component-specific styles */
}

/* Utility Layer */
.u-text-metallic { /* Metallic text effect */ }
.u-glass { /* Glass surface effect */ }
.u-laser-glow { /* Laser glow effect */ }
```

### Responsive Breakpoints
```css
/* Precision Breakpoints */
--screen-xs: 475px;   /* Mobile */
--screen-sm: 640px;   /* Large mobile */
--screen-md: 768px;   /* Tablet */
--screen-lg: 1024px;  /* Desktop */
--screen-xl: 1280px;  /* Large desktop */
--screen-2xl: 1536px; /* Ultra wide */
```

### Performance Optimizations
```css
/* GPU Acceleration for Smooth Effects */
.gpu-accelerated {
  transform: translateZ(0);
  will-change: transform;
}

/* Reduced Motion Support */
@media (prefers-reduced-motion: reduce) {
  * {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
  }
}
```

### Accessibility
```css
/* High Contrast Mode Support */
@media (prefers-contrast: high) {
  :root {
    --laser-green: #00FF00;
    --chrome: #FFFFFF;
    --carbon-black: #000000;
  }
}

/* Focus Visible Enhancement */
.focus-visible:focus-visible {
  outline: 2px solid var(--laser-green);
  outline-offset: 2px;
}
```

This design system brings the precision and beauty of metal engineering into the digital space, with careful attention to the subtle effects that make interfaces feel premium and engineered.