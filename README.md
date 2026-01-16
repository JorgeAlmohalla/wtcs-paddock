# 🏁 WTCS Paddock

> **Final Degree Project (DAM)** - *Development of Multi-Platform Applications*

**WTCS Paddock** is a comprehensive Multi-Platform ecosystem designed to professionalize the management of the **World Touring Car Series (WTCS)** SimRacing league. It replaces legacy spreadsheet workflows with a centralized API-driven architecture serving both a responsive Web Portal and a Native Android App.

![Project Status](https://img.shields.io/badge/Status-Feature%20Complete-success?style=flat-square)
![Version](https://img.shields.io/badge/Version-v4.5.0-blue?style=flat-square)
![License](https://img.shields.io/badge/License-MIT-gray?style=flat-square)

## 🧐 The Problem
The league was previously managed using static Excel spreadsheets shared via Discord images. This led to:
- **Scalability issues:** Spreadsheets reached their physical limits for driver entries.
- **Poor UX:** Mobile users had to zoom into images to check standings.
- **Manual Labor:** Admins had to manually calculate points, leading to potential human errors.

## 💡 The Solution
**WTCS Paddock** decouples data management from social interaction, providing a robust backend serving multiple frontend experiences:

1.  **Web Portal:** For desktop management, deep analytics, live standings, and administration.
2.  **Mobile App (Android Native):** A companion app for drivers to manage their career, check schedules, view telemetry graphs, and submit steward reports on the go.
3.  **Automated Scoring:** Algorithms calculate Driver, Team, and Manufacturer standings instantly upon result entry.

## 🛠️ Tech Stack

### 🖥️ Backend & API (The Core)
*   **Framework:** Laravel 11 (PHP 8.2)
*   **Database:** MySQL
*   **Admin Panel:** FilamentPHP v3
*   **Authentication:** Laravel Sanctum (Token-based Auth)
*   **Optimization:** Server-side Caching (Redis/File)

### 🌐 Client 1: Web Portal
*   **Frontend:** Blade Templates + Tailwind CSS v4
*   **Interactivity:** Alpine.js
*   **Charts:** Chart.js (Telemetry & Stats)
*   **Reporting:** DomPDF (Official Documents) & Maatwebsite Excel (Import)

### 📱 Client 2: Mobile App (Android)
*   **Language:** Java (Native)
*   **IDE:** Android Studio
*   **Networking:** Retrofit + Gson (REST API Consumption)
*   **Images:** Glide (with CircleCrop & Transformation)
*   **Charts:** MPAndroidChart (Performance Analytics)
*   **Architecture:** Clean Architecture (Model-View) with Singleton Network Layer.

## 🚀 Key Features Implemented

### 🌍 Web & Admin
- **Automated Standings:** Real-time calculation of Drivers, Constructors, and Manufacturers championships.
- **Admin Dashboard:** Custom widgets for quick league monitoring.
- **Historical Data:** Archive system for previous seasons.
- **Official Docs:** Automatic generation of PDF reports and steward decisions.

### 📱 Android App
- **Driver Hub:** Personal dashboard with editable bio, performance stats, and qualifying history.
- **Interactive Analytics:** Dynamic graphs for Race Finish Positions and Championship Points progression.
- **Stewarding System:** Native form to submit incident reports (video evidence, lap selection) and track their status (Pending/Resolved).
- **Team Center:** Detailed team profiles with official livery showcase and technical homologation specs.
- **Smart Navigation:** Centralized "Paddock Hub" bottom sheet for quick access to all modules.

## 📅 Release History & Roadmap

### Phase 1: Foundation (v1.0)
- [x] **Core Architecture:** Database Design, Git Workflow, and Environment Setup.
- [x] **Admin Backoffice:** FilamentPHP implementation for managing Users, Teams, and Races.
- [x] **Web Launch:** Public portal with Live Standings, Calendar, and News.

### Phase 2: Ecosystem Expansion (v2.0 - v3.0)
- [x] **Driver Experience:** Advanced Career Dashboard, Telemetry Charts, and Rival Comparison.
- [x] **League Management:** Stewarding System (Incident Reports), Team Principal Portal, and CSV Result Automation.
- [x] **Professionalization:** Multi-Season Architecture and PDF Report Generation.

### Phase 3: Mobile Integration (Final Phase - v4.5)
- [x] **API Development:** RESTful API implementation with protected routes (`/api/user`, `/api/incidents`).
- [x] **Mobile Auth:** Secure login system via Sanctum Tokens.
- [x] **Android App:** Full native implementation including:
    - **Home:** Live Countdown & News.
    - **Race Center:** Grouped Calendar & Session Details.
    - **Standings:** Multi-tab views with Privateer highlighting.
    - **Profile:** User dashboard with editing capabilities.
    - **Reports:** Incident reporting system integration.

## 🔮 Future Scope
- [ ] **Push Notifications:** Firebase integration for race alerts.
- [ ] **Live Timing:** WebSocket integration for real-time race telemetry.

## 📝 License
This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---
*Developed by Jorge Caro Almohalla - 2025/2026*