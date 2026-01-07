# Changelog

All notable changes to the **WTCS Paddock** project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [v4.1.0-android-alpha] - 07-01-2026
### 📱 Added (Mobile Client)
- **Project Setup:** Initialized Android Studio project with Java & Gradle (Kotlin DSL) inside the monorepo structure.
- **Network Security:** Configured `network_security_config.xml` to allow cleartext traffic from Android Emulator to local Laravel backend (`10.0.2.2`).
- **Authentication:**
    - Implemented `LoginActivity` with JWT handling.
    - Created `SessionManager` for secure Token storage in `SharedPreferences`.
- **UI/UX Design:**
    - Applied "WTCS Dark Theme" (Slate 900/800 + Racing Red).
    - Designed `MainActivity` with `BottomNavigationView`.
    - Created high-fidelity `CalendarFragment` (Home) with card-based layout and gradient overlays.
- **Architecture:** Implemented basic MVVM-like package structure (`api`, `models`, `ui`, `utils`).

## [v4.0.0-api] - 07-01-2026
### 🚀 Added (REST API Architecture)
- **API Endpoints:**
    - `POST /login`: Secure authentication returning Sanctum Bearer Token and User Profile.
    - `GET /calendar`: Retrieves the full race schedule for the active season.
    - `GET /standings`: Returns a simplified JSON list of drivers ordered by championship points.
    - `GET /user`: Returns authenticated user details (Protected route via Authorization Header).
- **Server Configuration:** Updated `.htaccess` configuration to correctly handle `Authorization` headers in local Apache/XAMPP environments.

## [v2.8.0] - 19-12-2025
### 🚀 Added
- **Season Filtering:** Implemented a filter in the Laravel Admin Panel to segment races by season, reducing information overload for administrators.
- **Driver Comparison Tool:** Added functionality to compare performance metrics (graphs and stats) between the logged-in user and a selected rival driver.
- **API Foundation:** Installed and configured **Laravel Sanctum** to manage stateful and token-based authentication.
- **Token Logic:** Initial implementation of token generation logic for mobile client authentication (Work in Progress).

## [v2.7.0] - 18-12-2025
### 📊 Added (Analytics & Visualization)
- **Advanced Telemetry Charts:** Implemented reversed-axis charts for Race and Qualifying positions (P1 on top) using `Chart.js` to accurately represent racing tiers.
- **Visual Consistency:** Standardized chart color palette (Red for Race, Purple for Qualifying) and added gradient fill effects for improved readability.
- **Dashboard Grid:** Restructured the Driver Hub into a responsive 5-block grid system to accommodate new telemetry charts alongside "Big Numbers".
- **Public Profile Sync:** Ported the advanced Dashboard layout to the Public Profile view (`/driver/{id}`), ensuring UX consistency between private and public views.
- **Driver Cards:** Enhanced UI design for driver cards, now displaying avatar, nationality flag, and direct links to public profiles.
- **Visual Highlighting:** Applied uniform styling (light cyan highlight) to identify "Privateer" drivers and team results across the web portal and PDF reports.

### 📄 Added (Export & Specs)
- **PDF Standings:** Implemented a feature to generate and download current championship standings in a branded PDF format.
- **Team Livery Showcase:** Added functionality for Team Principals to upload and display the current season's car livery on the public team page.
- **Car Specifications:** Added metadata fields to display technical specifications next to the car model in the team view.

## [v2.6.0] - 17-12-2025
### 🔄 Changed (Scoring Engine)
- **Points System Refactor:** Updated `RaceResultObserver` to exclude "Fastest Lap" points following rule changes.
- **Qualifying Logic:** Updated `QualifyingResultObserver` to award points for Pole Position.
- **Standings Calculation:** Optimized `StandingsController` to aggregate points dynamically from both Race and Qualifying sessions.

### 🚀 Added (Manufacturer Championship)
- **Constructors Logic:** Implemented complex calculation logic to determine Manufacturer points based on the *best single result* per race rule.
- **Standings Tab:** Added a dedicated "Manufacturers" tab to the public standings page.

### 🎨 Changed (UI/UX)
- **Result Tables:** Redesigned public result tables (Race & Qualy) incorporating Team Colors, Status Badges, and Penalty Notices.
- **Qualifying Details:** Added color-coded badges for Tyre Compounds and a new "Car Model" column.
- **Bug Fix:** Resolved an issue where driver names failed to render in specific public result views.

## [v2.5.0] - 16-12-2025
### 👥 Added (Team Management)
- **Role Management System:** Refactored user roles to a JSON-based array system, enabling multi-role assignment (e.g., Driver + Team Principal).
- **Team Principal Portal:** Launched a dedicated management dashboard (`/my-team`) for team owners.
- **Roster Controls:** Implemented tools for Team Principals to sign Free Agents and release drivers, with logic to prevent duplicate contracts.
- **Contract Tiers:** Introduced distinction between 'Primary' and 'Reserve' drivers with visual indicators.
- **Public Visibility:** Added badges for "Team Principal" and "Reserve Driver" roles on public team pages.

### 🔄 Changed
- **Database Schema:** Migrated `users.role` column to `json` type and added `contract_type` enum.
- **Auth Helpers:** Updated User model with `hasRole()` and `isTeamPrincipal()` methods to handle the new permission structure.
- **UI UX:** Redesigned constructor cards to include "Car Model" and made driver rows clickable in the Team view.

## [v2.4.0] - 15-12-2025
### 🏛️ Added (Stewarding System & Historical Data)
- **Incident Reporting:** Implemented a public form for drivers to submit incident reports with video evidence links.
- **Steward Panel:** Created `IncidentReportResource` in Filament for admin review, investigation, and penalty assignment.
- **Data Snapshotting:** Implemented logic to store `car_name` in `race_results` at the time of creation, preventing historical data corruption if teams change cars.
- **CSV Import System:** Integrated `maatwebsite/excel` for bulk import of race results with smart parsing (auto-detection of DNF, DNS, and Penalties).

## [v2.3.0] - 14-12-2025
### 👤 Added (Driver Hub)
- **Career Dashboard:** Redesigned the driver landing page featuring "Big Numbers" stats (Starts, Wins, Podiums).
- **Profile Fields:** Added `bio` (SimRacing background) and `equipment` (Wheel/Pad) fields to the User model.
- **Qualifying History:** Added a scrollable historical view of qualifying results.

## [v2.2.0] - 12-12-2025
### 📄 Added (Reporting & Architecture)
- **PDF Generation:** Implemented `dompdf` for generating official "Event Reports".
- **Multi-Season Architecture:** Implemented global context switcher and middleware to handle historical data from previous seasons.

## [v2.0.0-alpha] - 10-12-2025
### 🚀 Added (Frontend & Public Web)
- **Tech Stack Upgrade:** Migrated to Tailwind CSS v4 and Alpine.js.
- **Public Core:** Implemented Home, Standings, Calendar, and Drivers pages.
- **Authentication:** Integrated Laravel Breeze with custom fields (Steam ID).

## [v1.0.0-alpha] - 09-12-2025
### 🛠 Added (Core Backend)
- **Admin Panel:** Implemented FilamentPHP Backoffice.
- **CRUD Operations:** Complete management for Teams, Tracks, Drivers, and Races.
- **Automated Scoring:** Implemented initial Observer pattern for points calculation.