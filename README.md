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
- **Mobile-First Dashboard:** Drivers can check the next race countdown and top standings at a glance.
- **Role-Based Access:** Secure environment for Stewards (Admins) and Drivers.

## 🛠️ Tech Stack

**Backend**
- **PHP 8.2** & **Laravel 10**: Robust MVC architecture.
- **MySQL**: Relational database for complex data integrity (Drivers <-> Teams <-> Races).
- **FilamentPHP**: Rapid Admin Panel development.

**Frontend**
- **Blade Templates**: Server-side rendering.
- **Tailwind CSS**: Utility-first CSS framework for responsive design.
- **Alpine.js**: Lightweight JavaScript for interactivity.

## 🚀 Features Roadmap

- [x] **v0.1:** Project Setup, Database Design, & Architecture definition.
- [ ] **v1.0 (Foundation):** User Authentication, Basic Admin Panel & Database Infrastructure.
- [ ] **v2.0 (Management):** Calendar Management & Data Entry Tools (Backoffice).
- [ ] **v3.0 (The Core):** Automated Scoring Engine, Standings Calculation & Public Mobile Dashboard.
- [ ] **v4.0 (Expansion):** News Module, Rules Repository & Advanced Driver Profiles.
- [ ] **v5.0 (Analytics):** Performance Charts, PDF Exports, Historical Data & Sanctions System.

## 📝 License
This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---
*Developed by Jorge Caro Almohalla - 2025*
