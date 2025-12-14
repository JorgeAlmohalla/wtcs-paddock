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
- **Blade Templates**: Server-side rendering.
- **Tailwind CSS v4**: Modern utility-first CSS framework.
- **Alpine.js & Chart.js**: Interactivity and data visualization.

### Client 2: Mobile App (Android)
- **Java (Android Native):** Development using Android Studio with Retrofit for API consumption.
- **Material Design:** Native UI components for optimal mobile experience.
- **Firebase:** Push notifications service.

## 🚀 Features Roadmap

- [x] **v0.1 (Architecture):** Project Setup, Database Design (ER Model), & Git Workflow.
- [x] **v1.0 (Foundation):** User Authentication, Basic Admin Panel & Database Infrastructure.
- [x] **v2.0 (Web Management):** Calendar, Results Logic, Public Frontend & PDF Reports.
- [x] **v2.3 (Driver Experience):** Advanced Dashboard with Stats, Charts, and Profile Management.
- [ ] **v3.0 (API & Mobile):**
    - **REST API:** Expose endpoints for Races, Standings, and Auth (Sanctum).
    - **Android App:** Native Java application implementation.
    - **Notifications:** Push alerts for upcoming races.
- [ ] **v4.0 (Advanced):**
    - **Stewarding System:** Incident reporting and resolution workflow.

## 📝 License
This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---
*Developed by Jorge Caro Almohalla - 2025*