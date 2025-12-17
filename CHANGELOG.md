# Changelog

All notable changes to the **WTCS Paddock** project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]
*Work in progress for v2.0 (API & Mobile App)*

## [v1.3.0] - 17-12-2025
*Focus: Analytics, Seasons Architecture, and Documentation Engine.*

### Added (Seasons & Docs)
- **Season Archive:** Implemented full multi-season support architecture with a global context switcher and `SetSeasonMiddleware`.
- **Season Selector:** Added a dynamic dropdown in the Navbar to switch between current and archived seasons.
- **Official Documentation:** Created an HTML-based "Decision Document" viewer mimicking FIA official reports with professional styling.
- **PDF Generation:** Implemented `dompdf` logic for generating downloadable Event Reports.
- **Visuals:** Implemented year simulation logic in documents based on the Season name.

### Added (Analytics UI)
- **Advanced Charts:** Implemented reversed-axis charts for Race and Qualifying positions (P1 on top) using Chart.js.
- **Visual Consistency:** Standardized chart colors (Red for Race, Purple for Qualy) and added fill effects.
- **Dashboard Layout:** Restructured driver hub into a 5-block grid system for better data density.

### Changed
- **Data Filtering:** Refactored `HomeController`, `CalendarController`, and `StandingsController` to filter data dynamically based on the selected season ID.
- **Database Schema:** Migrated `races` table to include a `season_id` foreign key.

### Fixed
- **Data Mapping:** Resolved variable naming conflicts in `DashboardController` (`$racePositionData` vs `$raceData`).

## [v1.2.0] - 16-12-2025
*Focus: Stewarding, Team Ecosystem, and Automation.*

### Added (Stewarding System)
- **Incident Reporting:** Implemented a public form for drivers to submit incident reports with video evidence links.
- **Admin Review Panel:** Created `IncidentReportResource` in Filament for stewards to review, investigate, and penalize incidents.
- **Feedback Loop:** Added a "Stewarding Reports" section to the Driver Dashboard to track status (Pending/Resolved).

### Added (Team Management)
- **Role System:** Refactored user roles to a JSON-based array system allowing multi-role assignment (e.g., Driver + Team Principal).
- **Team Principal Portal:** Created a dedicated management dashboard (`/my-team`) for signing/releasing drivers.
- **Public Team Page:** Developed detailed team profile pages (`/team/{id}`) displaying stats, car info, and full roster.

### Added (Automation)
- **CSV Import System:** Integrated `maatwebsite/excel` to allow bulk import of race results directly from the Admin Panel.
- **Smart Parsing Logic:** Custom algorithm to auto-detect drivers, parse times/penalties, and identify DNF/DNS statuses.

### Changed
- **Database Schema:** Created `incident_reports` table, migrated `users.roles` to json, and added `contract_type` enum.
- **Auth Logic:** Updated User model with helper methods (`hasRole`, `isTeamPrincipal`).

## [v1.1.0] - 14-12-2025
*Focus: Driver Experience and Event Hub.*

### Added (Driver Dashboard)
- **Career Hub:** Redesigned the driver landing page with "Big Numbers" stats (Starts, Wins, Podiums) and Bio.
- **Qualifying History:** Added a scrollable table in the Dashboard showing personal qualifying results.
- **Performance Chart:** Integrated `Chart.js` to visualize points progression throughout the season.
- **Profile Editing:** Updated the profile form to include new Bio and Equipment (Wheel/Pad) fields.

### Added (Event Hub)
- **Round View:** Unified "Round" page grouping Sprint and Feature races into a single event view.
- **Tabbed Interface:** Alpine.js implementation to switch seamlessly between Qualifying, Sprint, and Feature results.
- **Data Snapshot:** Implemented logic to store the vehicle model (`car_name`) in results to preserve historical accuracy.

### Changed
- **Calendar Redesign:** Refactored Race Cards to consolidate weekend sessions into single events.
- **Navigation:** Updated Calendar UI to link to the new Round details page.

## [v1.0.0] - 12-12-2025
*Focus: Public Launch, Frontend, and Core Logic.*

### Added (Public Frontend)
- **Full Public Portal:** Launched Home, Standings, Calendar, Drivers, Teams, and News pages.
- **Tech Stack:** Installed Tailwind CSS v4 via Vite/PostCSS and Alpine.js.
- **Home Page:** Dynamic landing page with Live Countdown, Championship Leader, and Latest News.
- **Standings:** Full championship table view with dual columns (Drivers & Constructors).

### Added (Logic Engine)
- **Automated Scoring:** Implemented `RaceResultObserver` to calculate points (25-18-15...) and Fastest Lap bonus automatically.
- **Qualifying System:** Implemented a dedicated parallel structure for Qualifying sessions within Races.
- **Results Entry:** Integrated `RelationManager` to input race results directly within the Race view with Status and Gap fields.

## [v0.1.0] - 10-12-2025
*Focus: Foundation, Admin Panel, and Authentication.*

### Added
- **Admin Panel:** Implemented full Backoffice using FilamentPHP with custom "WTCS Racing Red" branding.
- **Management:** Complete CRUD for Teams, Tracks, Drivers, Races, and News.
- **User System:** Integrated Laravel Breeze for secure Login/Registration.
- **Registration Flow:** Customized sign-up form to require SimRacing specific data (Steam ID & Nationality).
- **Database:** Designed Entity-Relationship (ER) model and initial migrations for Users, Teams, and Races.