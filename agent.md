# MedicEdu Global — Project Agent Context & Architecture

## Overview
MedicEdu Global is a modern medical education consultancy website for Indian students seeking MBBS admissions abroad across 8 target countries:

### Country Detail Pages (`countries/*.html`)
1. [countries/bosnia.html](file:///c:/Users/milin/Desktop/medicedu%20global/countries/bosnia.html) — Standard 9-section architecture: €3,600/yr (University of East Sarajevo), 5 state universities, 6-step roadmap, dual document checklist.
2. [countries/serbia.html](file:///c:/Users/milin/Desktop/medicedu%20global/countries/serbia.html) — Standard 9-section architecture: 14 advantages, 5 top universities (€6,000–€8,000/yr), 6-step roadmap, dual document checklist.
3. [countries/romania.html](file:///c:/Users/milin/Desktop/medicedu%20global/countries/romania.html) — Standard 9-section architecture: EU & Schengen MD degree, 5 universities (€7,000–€8,500/yr), 6-step roadmap, dual document checklist.
4. [countries/russia.html](file:///c:/Users/milin/Desktop/medicedu%20global/countries/russia.html) — Standard 9-section architecture: Century-old state universities, 6 universities (RUB 350k–650k/yr), 6-step roadmap, dual document checklist.
5. [countries/armenia.html](file:///c:/Users/milin/Desktop/medicedu%20global/countries/armenia.html) — Standard 9-section architecture: High FMGE pass percentage, 5 universities ($3,500–$5,500/yr), 6-step roadmap, dual document checklist.
6. [countries/kyrgyzstan.html](file:///c:/Users/milin/Desktop/medicedu%20global/countries/kyrgyzstan.html) — Standard 9-section architecture: Budget 5-year MBBS, 5 universities ($3,000–$4,500/yr), 6-step roadmap, dual document checklist.
7. [countries/kazakhstan.html](file:///c:/Users/milin/Desktop/medicedu%20global/countries/kazakhstan.html) — Standard 9-section architecture: National medical universities, 6 universities ($3,600–$5,000/yr), 6-step roadmap, dual document checklist.
8. [countries/uzbekistan.html](file:///c:/Users/milin/Desktop/medicedu%20global/countries/uzbekistan.html) — Standard 9-section architecture: Smart 3D digital campuses, 5 universities ($3,200–$4,000/yr), 6-step roadmap, dual document checklist.

## Contact Information
- **Phone:** `+91 94106 24320` (`94 10 62 43 20`)
- **WhatsApp:** `https://wa.me/919410624320`
- **Email:** `tarunrockthakur@gmail.com`

## Codebase Structure
```
medicedu-global/
├── index.html              # Homepage with 8 full sections, hero slider, accreditation marquee
├── about.html              # About page with 8 sections, mission, ethics pillars, milestones, flag marquee
├── countries.html          # Study Destinations hub with 8 country cards & comparison matrix
├── admission.html          # Step-by-step 6-stage roadmap, checklist & flag marquee
├── contact.html            # Free consultation & contact page with lead form
├── countries/
│   ├── russia.html         # Dedicated MBBS in Russia subpage with fee tables & universities
│   ├── serbia.html         # Dedicated MBBS in Serbia subpage
│   ├── romania.html        # Dedicated MBBS in Romania subpage
│   ├── bosnia.html         # Dedicated MBBS in Bosnia subpage
│   ├── armenia.html        # Dedicated MBBS in Armenia subpage
│   ├── kyrgyzstan.html     # Dedicated MBBS in Kyrgyzstan subpage
│   ├── kazakhstan.html     # Dedicated MBBS in Kazakhstan subpage
│   └── uzbekistan.html     # Dedicated MBBS in Uzbekistan subpage
├── css/
│   └── style.css           # Vanilla CSS design system with HSL tokens, mega dropdown, marquee & cards
├── js/
│   └── main.js             # GSAP + ScrollTrigger with clearProps, hero slider, offcanvas drawer & FAQ
├── img/
│   ├── logo.png            # MedicEdu Global Official Logo
│   ├── hero-1.jpg, hero-2.jpg, hero-3.jpg # Doctor hero slider visuals
│   ├── about.jpg           # About section visual
│   ├── bosnia.jpg, Bosnia & Herzegovina.webp, Serbia.webp, Romania.webp, Russia.webp, Armenia.webp, kg.webp, Kazakhstan.webp, uz.webp
│   └── flags/              # SVG country flags for Russia, Serbia, Bosnia, Armenia, Kyrgyzstan, Kazakhstan, Uzbekistan, Romania
├── design.md               # Design guidelines & color palette tokens
└── agent.md                # Project architecture & context summary
```

## Key Technical Features
- **GSAP ScrollTrigger Animation:** Uses `clearProps: "all"` and `ScrollTrigger.refresh()` on window load so sections are never hidden or blank.
- **Dual-Track Infinite Marquee:** Seamless CSS `@keyframes` transformation with zero text clipping or line wrapping issues.
- **Button Styling:** Standalone `Contact Us` link removed. Primary CTA is **`Free Consultation`** with `#0A294D` background and white text.
