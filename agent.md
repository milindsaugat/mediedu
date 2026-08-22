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
├── admin/                      # PHP Admin Panel Portal
│   ├── index.php               # Admin Login
│   ├── dashboard.php           # Analytics & KPI Overview
│   ├── leads.php               # Student Inquiries CRM (Search, Filter, Export CSV, Bulk Status)
│   ├── lead-view.php           # Single Inquiry Detail & Counselor Follow-up Notes
│   ├── countries.php           # 8 Study Destinations Manager
│   ├── country-edit.php        # Country Details & Fee Highlight Editor
│   ├── universities.php        # Medical Universities & Fee Structure Matrix Editor
│   ├── settings.php            # Site Settings (Phone, WhatsApp, Email, Session Year)
│   ├── profile.php             # Admin Account & Password Change
│   ├── logout.php              # Session Logout
│   ├── inc/                    # Header, Sidebar, Footer, Auth Middleware
│   └── css/admin.css           # Modern SaaS Dashboard Stylesheet
├── config/
│   ├── db.php                  # PDO MySQL Connection
│   └── helpers.php             # Sanitization, CSRF, Flash Messages & Utilities
├── database/
│   ├── schema.sql              # Complete MySQL Database Schema & Seed Data
│   └── install.php             # 1-Click Visual Database Setup Wizard
├── api/
│   └── submit-lead.php         # Real-time AJAX Lead Handler for Website Forms
├── index.html, about.html, countries.html, admission.html, contact.html, 404.html
├── countries/*.html            # 8 Standardized Country Detail Pages
├── css/style.css, js/main.js, img/
├── .htaccess, vercel.json, netlify.toml, _redirects
├── design.md, README.md, agent.md
```

## Admin Panel Credentials
- **URL:** `/admin/`
- **Default Email:** `tarunrockthakur@gmail.com`
- **Default Password:** `Admin@2026!`
- **Database Installer:** `/database/install.php` (or import `database/schema.sql` via phpMyAdmin)

## Key Technical Features
- **PHP & MySQL Admin Panel:** Full CRM for student inquiry management, country fee matrices, and site settings.
- **GSAP ScrollTrigger Animation:** Uses `clearProps: "all"` and `ScrollTrigger.refresh()` on window load so sections are never hidden or blank.
- **Dual-Track Infinite Marquee:** Seamless CSS `@keyframes` transformation with zero text clipping or line wrapping issues.
- **Button Styling:** Standalone `Contact Us` link removed. Primary CTA is **`Free Consultation`** with `#0A294D` background and white text.
- **Clean Extensionless URLs (.html hidden):** Automatic browser address bar `.html` stripping and complete server rewrite configs (`.htaccess`, `vercel.json`, `netlify.toml`, `_redirects`, and branded `404.html`) supporting back/forward history navigation without errors.
