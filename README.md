# 🏁 WTCS Paddock

> **Final Degree Project (DAM)** - *Development of Multi-Platform Applications*

**WTCS Paddock** is a comprehensive Multi-Platform ecosystem designed to professionalize the management of the **World Touring Car Series (WTCS)** SimRacing league. It replaces legacy spreadsheet workflows with a centralized API-driven architecture serving both Web and Mobile clients.

![Project Status](https://img.shields.io/badge/Status-Active%20Development-success?style=flat-square)
![Version](https://img.shields.io/badge/Version-v1.3.0-blue?style=flat-square)
![License](https://img.shields.io/badge/License-MIT-gray?style=flat-square)

## 🧐 The Problem
The league was previously managed using static Excel spreadsheets shared via Discord images. This led to:
- **Scalability issues:** Spreadsheets reached their physical limits for driver entries.
- **Poor UX:** Mobile users had to zoom into images to check standings.
- **Manual Labor:** Admin had to manually calculate points, leading to potential human errors.

## 💡 The Solution
**WTCS Paddock** decouples data management from social interaction. It provides a robust backend serving multiple frontend experiences:
- **Web Portal:** For desktop management, deep analytics, and administration.
- **Mobile App (Android):** A native companion app for drivers to check schedules, standings, and receive race notifications on the go.
- **Automated Scoring:** Algorithms calculate Driver, Team, and Manufacturer standings instantly.

## 🛠️ Tech Stack

### Backend & API (The Core)
- **PHP 8.2** & **Laravel 11**: Robust MVC architecture acting as the central API.
- **MySQL**: Relational database managing complex relationships (Drivers <-> Teams <-> Results).
- **FilamentPHP v3**: Rapid Admin Panel development for league organizers.
- **Laravel Sanctum**: API Authentication for secure mobile access.

### Client 1: Web Portal
- **Blade Templates**: Server-side rendering with component-based architecture.
- **Tailwind CSS v4**: Modern utility-first CSS framework configured via Vite/PostCSS.
- **Alpine.js**: Lightweight JavaScript for interactive UI (Tabs, Mobile Menu, Countdowns).
- **Chart.js**: Data visualization for driver performance analytics.
- **DomPDF**: Engine for generating official printable reports.
- **Maatwebsite Excel**: Bulk data import handler.

### Client 2: Mobile App (Android)
- **Java (Android Native):** Development using Android Studio with Retrofit for API consumption.
- **Material Design:** Native UI components for optimal mobile experience.
- **Firebase:** Push notifications service.

## 🚀 Release History & Roadmap

### Phase 1: Foundation (v0.x)
- [x] **v0.1 - Genesis:** Project Architecture, Git Workflow, Database Schema & Migrations.
- [x] **v0.5 - Admin Core:** Implementation of FilamentPHP Backoffice, Authentication (Breeze), and CRUDs for Users/Teams.

### Phase 2: Public Launch (v1.0)
- [x] **v1.0 - The Platform:** Full Frontend Launch (Home, Standings, Calendar), Scoring Engine & Qualifying Logic.

### Phase 3: Ecosystem Expansion (Current)
- [x] **v1.1 - Driver Experience:** Advanced Driver Dashboard, Career Stats, Round-based Events & Visual Charts.
- [x] **v1.2 - Management Suite:** Stewarding System (Incident Reports), Team Principal Portal & CSV Automation.
- [x] **v1.3 - Professionalization:** Multi-Season Architecture, Official FIA-style Docs (PDF), and Advanced Analytics.

### Phase 4: Mobile & API (Future Scope)
- [ ] **v2.0 - API First:** REST API Development (Sanctum Auth & Endpoints).
- [ ] **v2.x - Mobile Client:** Native Android App Release.

## 📝 License
This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---
*Developed by Jorge Caro Almohalla - 2025*