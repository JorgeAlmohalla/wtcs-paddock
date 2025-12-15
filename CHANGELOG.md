# Changelog

All notable changes to the **WTCS Paddock** project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]
*Work in progress for v3.0 (API & Mobile App)*

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

### Changed
- **Database Schema:** Migrated `teams` table to include `car_model` and updated results tables to store the snapshot.
- **Observers:** Created `QualifyingResultObserver` to mirror the snapshot logic used in Race Results.

### Added (Automation)
- **CSV Import System:** Integrated `maatwebsite/excel` to allow bulk import of race results directly from the Admin Panel.
- **Smart Parsing Logic:** Custom import algorithm that:
    - Auto-detects driver by name (fuzzy matching).
    - Parses complex time strings ("44:03 +5") into separate Time and Penalty fields.
    - Identifies status codes (DNF, DNS, DSQ) from text descriptions.
    - Detects "Fastest Lap" metadata from the CSV footer row and assigns it to the correct driver.

## [v2.3.0] - 14-12-2025
### Added (Driver Hub & Analytics)
- **Advanced Dashboard:** Redesigned the driver landing page to act as a complete career hub, featuring:
    - **"Big Numbers" Stats:** Total Starts, Wins, Podiums, Poles, and Points.
    - **Driver Bio:** New field for drivers to write their SimRacing background.
    - **Equipment Tracker:** Field to display input method (Wheel, Pad, Keyboard).
- **Qualifying History:** Added a scrollable table in the Dashboard showing personal qualifying results across the season.
- **Database Schema:** Added `bio` (text) and `equipment` (enum) fields to the `users` table.

### Changed
- **Profile Editing:** Updated the profile form to include the new Bio and Equipment fields.

## [v2.2.0-beta] - 12-12-2025
### Added (Reporting & Architecture)
- **PDF Generation:** Implemented `dompdf` to generate official "Event Reports" downloadable from the Round details page.
- **Season Archive:** Implemented full multi-season support architecture with a global context switcher (Middleware).
- **Season Selector:** Added a dropdown in the Navbar to switch between current and archived seasons.

### Changed
- **Round Architecture:** Refactored Calendar logic to group races by `round_number`, creating a unified event view with tabs.
- **Data Filtering:** Refactored Controllers to filter data dynamically based on the selected season ID.

## [v2.1.1] - 12-12-2025
### Added (Quality of Life)
- **Race Configuration:** Added `total_laps` field to Race model.
- **Auto-Fill:** Result entry form now automatically pre-fills `laps_completed`.

### Changed
- **Public UI:** Refined result tables to match Admin panel styling (dark mode, badges, and status colors).

## [v2.1.0-beta] - 11-12-2025
### Added (Features)
- **Qualifying System:** Implemented a dedicated parallel structure for Qualifying sessions within Races (`qualifying_results` table).
- **Public Race View:** Created dynamic Race Detail page (`/races/{id}`) with Alpine.js tabs.
- **Performance Chart:** Integrated `Chart.js` into the Driver Dashboard.

## [v2.0.0-alpha] - 10-12-2025
### Added (Frontend & Public Web)
- **Tech Stack:** Installed Tailwind CSS v4 via Vite/PostCSS and Alpine.js.
- **Layout System:** Created main Blade layout with Dark Mode and responsive Navbar.
- **Home Page:** Implemented dynamic landing page with Live Countdown.
- **Public Controllers:** Implemented logic for `Home`, `Standings`, `Calendar`, `Drivers`, `Teams`, and `News`.

### Added (Authentication)
- **User System:** Integrated Laravel Breeze.
- **Registration Flow:** Customized sign-up form with SimRacing data (Steam ID).

## [v1.0.0-alpha] - 09-12-2025
### Added (Core Backend)
- **Admin Panel:** Implemented FilamentPHP Backoffice.
- **Management:** Complete CRUD for Teams, Tracks, Drivers, Races, and News.
- **Automated Scoring:** Implemented `RaceResultObserver` to calculate points automatically.

## [v0.1.0] - 09-12-2025
### Added
- **Project Setup:** Initialized Git repository, Laravel environment, and Database Design.