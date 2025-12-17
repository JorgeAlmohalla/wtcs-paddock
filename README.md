# 🏁 WTCS Paddock

> **Final Degree Project (DAM)** - *Development of Multi-Platform Applications*

**WTCS Paddock** is a comprehensive Multi-Platform ecosystem designed to professionalize the management of the **World Touring Car Series (WTCS)** SimRacing league. It replaces legacy spreadsheet workflows with a centralized API-driven architecture serving both Web and Mobile clients.

![Project Status](https://img.shields.io/badge/Status-In%20Development-orange?style=flat-square)
![License](https://img.shields.io/badge/License-MIT-blue?style=flat-square)

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

## 🚀 Features Roadmap & Version History

### Phase 1: Core Foundation
- [x] **v1.0:** Project Architecture, Git Workflow & Database Schema Design.
- [x] **v2.0:** Authentication System (Breeze), Security & User Profiles.
- [x] **v3.0:** Administration Backoffice (FilamentPHP) & Data CRUDs.

### Phase 2: Race Logic & Public Web
- [x] **v4.0:** Competition Engine (Results, Qualifying, & Scoring Observer).
- [x] **v5.0:** Public Frontend Launch (Home, Standings, News).
- [x] **v6.0:** Advanced Calendar & Round Event Hub (Tabs System).

### Phase 3: Driver & Team Ecosystem
- [x] **v7.0:** Driver Dashboard & Analytics (Chart.js Integration).
- [x] **v8.0:** Team Management Portal (Principals, Roster, Contracts).
- [x] **v9.0:** Automation Suite (CSV Bulk Import & Smart Parsing).

### Phase 4: Professional Management
- [x] **v10.0:** Stewarding System (Incident Reports & Resolution).
- [x] **v11.0:** Documentation Engine (Official Decision Docs & PDF).
- [x] **v12.0:** Multi-Season Architecture (Historical Archive).

### Phase 5: Mobile & API (Future Scope)
- [ ] **v13.0:** REST API Development (Sanctum Auth & Endpoints).
- [ ] **v14.0:** Native Android App (Java).

## 📝 License
This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---
*Developed by Jorge Caro Almohalla - 2025*