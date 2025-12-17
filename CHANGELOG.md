# Changelog

All notable changes to the **WTCS Paddock** project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]
*Work in progress for v3.0 (API & Mobile App)*

## [v2.5.0] - 17-12-2025
### Added (Team Ecosystem)
- **Role System:** Refactored user roles to a JSON-based array system allowing multi-role assignment (e.g., Driver + Team Principal).
- **Team Principal Portal:** Created a dedicated management dashboard for team owners (`/my-team`).
- **Roster Management:** Implemented tools for Team Principals to sign Free Agents and release drivers from their contracts.
- **Contract Types:** Added distinction between 'Primary' and 'Reserve' drivers, with visual indicators in the roster list.
- **Public Team Page:** Developed detailed team profile pages (`/team/{id}`) displaying stats, car info, and full roster.

### Changed
- **Database Schema:** Migrated `users.role` (string) to `users.roles` (json) and added `contract_type` enum.
- **Auth Logic:** Updated User model with helper methods (`hasRole`, `isTeamPrincipal`) to handle the new permission structure.
- **UI Enhancements:**
    - Redesigned constructor cards to include "Car Model" and improved grid layout.
    - Made the entire driver row clickable in the Team view for better UX.

## [v3.1.1-beta] - 16-12-2025
### Added (Stewarding & Docs)
- **Official Documentation:** Created an HTML-based "Decision Document" viewer mimicking FIA official reports (Logos, Styling, Signatures).
- **Incident Feedback:** Added a "Stewarding Reports" section to the Driver Dashboard, allowing users to track the status (Pending/Resolved) and outcome of their reports.
- **Round Integration:** Embedded "Steward Decision" buttons directly within Sprint/Feature race headers for easy access.

### Changed
- **Document Generation:** Switched from PDF download to a responsive HTML view for better mobile compatibility and faster loading.
- **Historical Logic:** Implemented year simulation logic in documents based on the Season name (e.g., "Season 1999" -> displays 1999 date).

## [v3.1.0-beta] - 15-12-2025
### Added (Stewarding System)
- **Incident Reporting:** Implemented a public form for drivers to submit incident reports with video evidence.
- **Admin Review Panel:** Created `IncidentReportResource` in Filament for stewards to review, investigate, and penalize incidents.
- **Dashboard Integration:** Added "Report Incident" button to the driver dashboard.
- **Database Schema:** Created `incident_reports` table linking reporters, accused drivers, and races.

### Changed
- **Driver Profile:** Expanded `users` table with `bio` and `equipment` fields.

## [v2.4.0] - 15-12-2025
### Added (Historical Data)
- **Car Model Snapshot:** Implemented data snapshotting logic to store the vehicle model name (`car_name`) directly in `race_results` and `qualifying_results` tables upon creation. This prevents historical data corruption if a team changes cars in future seasons.
- **Car Model Display:** Added "Car" column to all public result tables (Race & Qualy) and PDF reports.
- **Automation:** Integrated `maatwebsite/excel` for CSV bulk import of race results with smart parsing logic.

### Changed
- **Database Schema:** Migrated `teams` table to include `car_model`.
- **Observers:** Created `QualifyingResultObserver` to mirror the snapshot logic used in Race Results.

## [v2.3.0] - 14-12-2025
### Added (Driver Hub & Analytics)
- **Advanced Dashboard:** Redesigned the driver landing page to act as a complete career hub with stats and charts.
- **Qualifying History:** Added a scrollable table in the Dashboard showing personal qualifying results.
- **Database Schema:** Added `bio` and `equipment` fields to `users`.

## [v2.2.0-beta] - 12-12-2025
### Added (Reporting & Architecture)
- **PDF Generation:** Implemented `dompdf` to generate official "Event Reports".
- **Season Archive:** Implemented full multi-season support architecture with global context switcher.

### Changed
- **Round Architecture:** Refactored Calendar logic to group races by `round_number`.
- **Data Filtering:** Refactored Controllers to filter data dynamically based on the selected season ID.

## [v2.1.1] - 12-12-2025
### Added
- **Race Configuration:** Added `total_laps` field to Race model.
- **Auto-Fill:** Result entry form now automatically pre-fills `laps_completed`.

## [v2.1.0-beta] - 11-12-2025
### Added
- **Qualifying System:** Implemented a dedicated parallel structure for Qualifying sessions.
- **Public Race View:** Created dynamic Race Detail page with tabs.
- **Performance Chart:** Integrated `Chart.js` into the Dashboard.

## [v2.0.0-alpha] - 10-12-2025
### Added (Frontend)
- **Tech Stack:** Installed Tailwind CSS v4 via Vite/PostCSS and Alpine.js.
- **Layout System:** Created main Blade layout with Dark Mode and responsive Navbar.
- **Home Page:** Implemented dynamic landing page with Live Countdown.
- **Public Controllers:** Implemented logic for all public pages.
- **User System:** Integrated Laravel Breeze for authentication.

## [v1.0.0-alpha] - 09-12-2025
### Added (Core Backend)
- **Admin Panel:** Implemented FilamentPHP Backoffice.
- **Management:** Complete CRUD for Teams, Tracks, Drivers, Races, and News.
- **Automated Scoring:** Implemented `RaceResultObserver` to calculate points automatically.

## [v0.1.0] - 09-12-2025
### Added
- **Project Setup:** Initialized Git repository, Laravel environment, and Database Design.