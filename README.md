# 🏁 WTCS Paddock

> **Final Degree Project (DAM)** - *Development of Multi-Platform Applications*

**WTCS Paddock** is a Progressive Web App (PWA) designed to professionalize the management of the **World Touring Car Series (WTCS)** SimRacing league. It replaces legacy spreadsheet workflows with a centralized, automated, and mobile-first digital ecosystem.

![Project Status](https://img.shields.io/badge/Status-In%20Development-orange?style=flat-square)
![License](https://img.shields.io/badge/License-MIT-blue?style=flat-square)

## 🧐 The Problem
The league was previously managed using static Excel spreadsheets shared via Discord images. This led to:
- **Scalability issues:** Spreadsheets reached their physical limits for driver entries.
- **Poor UX:** Mobile users had to zoom into images to check standings.
- **Manual Labor:** Admin had to manually calculate points, leading to potential human errors.

## 💡 The Solution
**WTCS Paddock** decouples data management from social interaction (Discord). It provides:
- **Automated Scoring:** Algorithms calculate Driver, Team, and Manufacturer standings instantly after race results are input.
- **Mobile-First Dashboard:** Drivers can check the next race countdown, top standings, and their own performance charts at a glance.
- **Role-Based Access:** Secure environment for Stewards (Admins) and Drivers.

## 🛠️ Tech Stack

**Backend**
- **PHP 8.2** & **Laravel 11**: Robust MVC architecture with Eloquent ORM.
- **MySQL**: Relational database managing complex relationships (Drivers <-> Teams <-> Race/Qualy Results).
- **FilamentPHP v3**: Rapid Admin Panel development with custom Resources and Relation Managers.

**Frontend**
- **Blade Templates**: Server-side rendering with component-based architecture.
- **Tailwind CSS v4**: Modern utility-first CSS framework configured via Vite/PostCSS.
- **Alpine.js**: Lightweight JavaScript for interactive UI (Tabs, Mobile Menu, Countdowns).
- **Chart.js**: Data visualization for driver performance analytics.

## 🚀 Features Roadmap

- [x] **v0.1 (Architecture):** Project Setup, Database Design (ER Model), & Git Workflow.
- [x] **v1.0 (Foundation):** User Authentication (Breeze), Basic Admin Panel & Database Infrastructure.
- [x] **v2.0 (Management):** Calendar Management, Race/Qualifying Data Entry & Public Frontend Pages.
- [x] **v3.0 (The Core):** Automated Scoring Engine (Points/Penalties), Standings Calculation & Public Mobile Dashboard.
- [x] **v4.0 (Expansion):** News Module, Advanced Driver Profiles (Steam ID/Nationality), & Performance Charts.
- [ ] **v5.0 (Automation & Analytics):**
    - **CSV Import:** Bulk import race results from game server logs.
    - **PDF Exports:** Generate official classification documents.
    - **Historical Data:** Season archives and legacy data filtering.

## 📝 License
This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---
*Developed by Jorge Caro Almohalla - 2025*