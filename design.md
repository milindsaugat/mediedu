# MedicEdu Global — Design System & Technical Specification

## 1. Brand Identity & Color Palette
- **Deep Navy (Brand Primary & Free Consultation Button):** `#0A294D`
- **Secondary Blue / Accent:** `#1A56DB` (Hover: `#1442A6`)
- **Accent Warm Gold:** `#EAB308` / `#CA8A04`
- **Slate Text Colors:** `#0F172A` (Headings), `#334155` (Body text), `#64748B` (Muted captions)
- **Backgrounds:** `#FFFFFF` (Pure white cards), `#F8FAFC` (Soft section tone), `#F1F5F9` (Subtle borders)
- **Success / Compliance Badges:** `#10B981` (Green)

## 2. Typography
- **Headings Font:** `'Manrope', sans-serif` (Weights: 600, 700, 800)
- **Body & Controls Font:** `'Inter', sans-serif` (Weights: 400, 500, 600, 700)
- **Iconography:** `Remix Icon v4.6.0` (`ri-*` classes)

## 3. Key Layout Components
1. **Primary Navigation Bar:**
   - Links: `Home`, `About Us`, `Study Destinations` (Mega dropdown with 8 countries & flag icons), `Admission Process`.
   - CTA: Single **`Free Consultation`** button styled in `#0A294D` with crisp white text, gold border glow on hover, linking to `contact.html#counselling`.
2. **Infinite Dual-Track Marquee:**
   - Built using two `.flag-marquee-track` or `.marquee-track` flex children within an `overflow: hidden` wrapper.
   - Smooth `flagMarqueeScroll` CSS transform animation with zero text clipping or vertical overflow.
3. **Hero Section Slider:**
   - 3 high-definition slides with doctor visuals and floating credential badges (`WHO & NMC Recognized`, `Zero Hidden Charges`, `Indian Food Mess`).
4. **8 Country Cards & Dedicated Pages:**
   - Real campus imagery from `img/` (`bosnia.jpg`, `Serbia.webp`, `Romania.webp`, `Russia.webp`, `Armenia.webp`, `kg.webp`, `Kazakhstan.webp`, `uz.webp`).
   - Detailed breakdown tables, fees in EUR / USD / INR, ECTS credits, and NMC compliance status.
5. **GSAP + ScrollTrigger Animation Engine:**
   - Configured with `clearProps: 'all'` and window `load` event refresh to prevent elements from remaining hidden or shifting unexpectedly.
6. **Mobile Off-Canvas Navigation:**
   - Sliding side drawer with country flags, WhatsApp direct links, and call triggers.
