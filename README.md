# 🏁 WTCS Paddock

> **Final Degree Project (DAM)** - *Development of Multi-Platform Applications*

**WTCS Paddock** is a comprehensive Multi-Platform ecosystem designed to professionalize the management of the **World Touring Car Series (WTCS)** SimRacing league. It replaces legacy spreadsheet workflows with a centralized API-driven architecture serving both Web and Mobile clients.

![Project Status](https://img.shields.io/badge/Status-Active%20Development-success?style=flat-square)
![Version](https://img.shields.io/badge/Version-v4.0.0--API-blue?style=flat-square)
![License](https://img.shields.io/badge/License-MIT-gray?style=flat-square)

## 🧐 The Problem
The league was previously managed using static Excel spreadsheets shared via Discord images. This led to:
- **Scalability issues:** Spreadsheets reached their physical limits for driver entries.
- **Poor UX:** Mobile users had to zoom into images to check standings.
- **Manual Labor:** Admins had to manually calculate points, leading to potential human errors.

## 💡 The Solution
**WTCS Paddock** decouples data management from social interaction, providing a robust backend serving multiple frontend experiences:

1.  **Web Portal:** For desktop management, deep analytics, and administration.
2.  **Mobile App (Android Native):** A companion app for drivers to check schedules, standings, and profiles on the go.
3.  **Automated Scoring:** Algorithms calculate Driver, Team, and Manufacturer standings instantly upon result entry.

## 🛠️ Tech Stack

### 🖥️ Backend & API (The Core)
*   **Framework:** Laravel 11 (PHP 8.2)
*   **Database:** MySQL
*   **Admin Panel:** FilamentPHP v3
*   **Authentication:** Laravel Sanctum (API Token Auth)

### 🌐 Client 1: Web Portal
*   **Frontend:** Blade Templates + Tailwind CSS v4
*   **Interactivity:** Alpine.js
*   **Charts:** Chart.js (Telemetry & Stats)
*   **Reporting:** DomPDF (Official Documents) & Maatwebsite Excel (Import)

### 📱 Client 2: Mobile App (Android)
*   **Language:** Java (Native)
*   **IDE:** Android Studio
*   **Networking:** Retrofit + Gson (REST API Consumption)
*   **Images:** Glide
*   **Architecture:** Clean Architecture (Model-View) with Singleton Network Layer.

## 📂 Project Structure
The repository is organized as a Monorepo containing both the backend and the mobile client:

```text
wtcs-paddock/
├── backend/          # Laravel Project (API & Web)
│   ├── app/
│   ├── routes/
│   └── ...
├── android/          # Android Studio Project
│   ├── app/
│   │   ├── src/main/java/com/example/wtcspaddock/
│   │   │   ├── api/      # Retrofit Client & Services
│   │   │   ├── models/   # POJO Data Models
│   │   │   └── ui/       # Activities & Fragments
│   └── ...
└── README.md
```

## 🚀 Release History & Roadmap

### Phase 1: Foundation (v0.x - v1.0)
- [x] **Core Architecture:** Database Design, Git Workflow, and Environment Setup.
- [x] **Admin Backoffice:** FilamentPHP implementation for managing Users, Teams, and Races.
- [x] **Web Launch:** Public portal with Live Standings, Calendar, and News.

### Phase 2: Ecosystem Expansion (v2.x - v3.x)
- [x] **Driver Experience:** Advanced Career Dashboard, Telemetry Charts, and Rival Comparison.
- [x] **League Management:** Stewarding System (Incident Reports), Team Principal Portal, and CSV Result Automation.
- [x] **Professionalization:** Multi-Season Architecture and PDF Report Generation.

### Phase 3: Mobile Integration (Current - v4.0)
- [x] **API Development:** RESTful API implementation with protected routes (`/api/user`).
- [x] **Mobile Auth:** Login system via Sanctum Tokens.
- [ ] **Android App (Alpha):** Building UI for Calendar and Standings (WIP).
- [ ] **Push Notifications:** Firebase integration (Planned).

## 📝 License
This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---
*Developed by Jorge Caro Almohalla - 2025/2026*
```
