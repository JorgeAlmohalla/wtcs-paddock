# Changelog

All notable changes to the **WTCS Paddock** project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]
*Work in progress for v13.0 (API & Mobile App)*

## [v12.0.0] - 17-12-2025
### Added (Season Architecture)
- **Season Archive:** Implemented full multi-season support architecture with a global context switcher (Middleware).
- **Global Context:** Created `SetSeasonMiddleware` to inject the active season context into all views and controllers automatically.
- **Season Selector:** Added a dynamic dropdown in the Navbar to switch between current and archived seasons.
- **Admin Management:** Created `SeasonResource` with logic to ensure only one season is active at a time.

### Changed
- **Data Filtering:** Refactored `HomeController`, `CalendarController`, and `StandingsController` to filter data dynamically based on the selected season ID.
- **Database Schema:** Migrated `races` table to include a `season_id` foreign key.

## [v11.0.0] - 17-12-2025
### Added (Documentation Engine)
- **Official Documentation:** Created an HTML-based "Decision Document" viewer mimicking FIA official reports (Logos, Styling, Signatures).
- **PDF Generation:** Implemented `dompdf` logic for generating downloadable Event Reports with professional styling.
- **Visuals:** Implemented year simulation logic in documents based on the Season name (e.g., "Season 1999" -> displays 1999 date).
- **Professional Styling:** Custom CSS for documents to match FIA-style reports (Clean layout, Status colors, Tyre badges).

## [v10.0.0] - 16-12-2025
### Added (Stewarding System)
- **Incident Reporting:** Implemented a public form for drivers to submit incident reports with video evidence links.
- **Admin Review Panel:** Created `IncidentReportResource` in Filament for stewards to review, investigate, and penalize incidents.
- **Dashboard Integration:** Added "Report Incident" button to the driver dashboard for quick access.
- **Feedback Loop:** Added a "Stewarding Reports" section to the Driver Dashboard, allowing users to track the status (Pending/Resolved) and outcome of their reports.
- **Database Schema:** Created `incident_reports` table linking reporters, accused drivers, and races.

## [v9.0.0] - 15-12-2025
### Added (Automation)
- **CSV Import System:** Integrated `maatwebsite/excel` to allow bulk import of race results directly from the Admin Panel.
- **Smart Parsing Logic:** Custom import algorithm that:
    - Auto-detects driver by name (fuzzy matching).
    - Parses complex time strings ("44:03 +5") into separate Time and Penalty fields.
    - Identifies status codes (DNF, DNS, DSQ) from text descriptions.
    - Detects "Fastest Lap" metadata from the CSV footer row and assigns it to the correct driver.

## [v8.0.0] - 15-12-2025
### Added (Team Ecosystem)
- **Role System:** Refactored user roles to a JSON-based array system allowing multi-role assignment (e.g., Driver + Team Principal).
- **Team Principal Portal:** Created a dedicated management dashboard for team owners (`/my-team`).
- **Roster Management:** Implemented tools for Team Principals to sign Free Agents and release drivers from their contracts.
- **Contract Types:** Added distinction between 'Primary' and 'Reserve' drivers, with visual indicators in the roster list.
- **Public Team Page:** Developed detailed team profile pages (`/team/{id}`) displaying stats, car info, and full roster.

### Changed
- **Database Schema:** Migrated `users.role` (string) to `users.roles` (json) and added `contract_type` enum.
- **Auth Logic:** Updated User model with helper methods (`hasRole`, `isTeamPrincipal`) to handle the new permission structure.

## [v7.0.0] - 14-12-2025
### Added (Driver Experience & Analytics)
- **Advanced Dashboard:** Redesigned the driver landing page to act as a complete career hub, featuring:
    - **"Big Numbers" Stats:** Total Starts, Wins, Podiums, Poles, and Points.
    - **Driver Bio:** New field for drivers to write their SimRacing background.
    - **Equipment Tracker:** Field to display input method (Wheel, Pad, Keyboard).
- **Qualifying History:** Added a scrollable table in the Dashboard showing personal qualifying results across the season.
- **Performance Chart:** Integrated `Chart.js` into the Driver Dashboard to visualize points progression throughout the season.
- **Data Calculation:** Backend logic (`DashboardController`) to aggregate historical race results dynamically for the chart dataset.

### Changed
- **Profile Editing:** Updated the profile form to include the new Bio and Equipment fields.

## [v6.0.0] - 13-12-2025
### Added (Event Hub)
- **Round View:** Unified "Round" page grouping Sprint and Feature races into a single event view.
- **Tabbed Interface:** Alpine.js implementation to switch seamlessly between Qualifying, Sprint, and Feature race results.
- **Calendar Redesign:** Refactored Race Cards with `flex-1` layout to optimize space usage and consolidate weekend sessions into single events.
- **Round Integration:** Embedded "Steward Decision" buttons directly within Sprint/Feature race headers for easy access.

### Changed
- **Navigation:** Updated Calendar UI to link to the new Round details page instead of individual race sessions.

## [v5.0.0] - 12-12-2025
### Added (Frontend Launch)
- **Tech Stack:** Installed Tailwind CSS v4 via Vite/PostCSS and Alpine.js for interactivity.
- **Layout System:** Created main Blade layout (`layouts/app`) with Dark Mode enabled and responsive Navbar using Alpine.js state management.
- **Home Page:** Implemented dynamic landing page showing:
    - **Live Countdown:** Real-time JS timer (Days/Hours/Mins/Secs) for the next scheduled race.
    - **Championship Leader:** Dynamic card linking to standings.
    - **Latest News:** Featured article preview with background image.
- **Public Controllers:** Implemented logic for `Home`, `Standings`, `Calendar`, `Drivers`, `Teams`, and `News`.
- **Standings Page:** Full championship table view with dual columns (Drivers & Constructors) and integer point formatting.
- **News System:** Added full "Read Article" view and a paginated News Archive page (`/news`).

## [v4.0.0] - 11-12-2025
### Added (Logic Engine)
- **Automated Scoring:** Implemented `RaceResultObserver` to automatically calculate points (25-18-15...) and Fastest Lap bonus upon saving.
- **Qualifying System:** Implemented a dedicated parallel structure for Qualifying sessions within Races (`qualifying_results` table).
- **Results System:** Integrated `RelationManager` to input race results directly within the Race view.
- **Car Model Snapshot:** Implemented data snapshotting logic to store the vehicle model name (`car_name`) directly in results tables to preserve historical data accuracy.

### Changed
- **Race Results Schema:** Expanded results table to include Status (DNF/DNS), Gap/Time, and Laps completed.
- **Admin UI:** Enhanced Results table with conditional formatting (colors/icons) for Fastest Lap and Penalty status.

## [v3.0.0] - 10-12-2025
### Added (Administration)
- **Admin Panel:** Implemented full Backoffice using FilamentPHP with custom "WTCS Racing Red" branding.
- **Management:** Complete CRUD for Teams, Tracks, Drivers, Races, and News.
- **Race Configuration:** Added `total_laps` field to Race model to define race distance.
- **Auto-Fill:** Result entry form now automatically pre-fills `laps_completed` based on the parent Race's total laps configuration.

## [v2.0.0] - 10-12-2025
### Added (Security & Onboarding)
- **User System:** Integrated Laravel Breeze for secure Login/Registration.
- **Registration Flow:** Customized sign-up form to require SimRacing specific data (Steam ID & Nationality) with backend validation.
- **Role Management:** Implemented logic to differentiate Guest vs Logged-in Driver views in the Navbar.
- **Driver Profile:** Expanded `users` table with `bio` and `equipment` fields.

## [v1.0.0] - 09-12-2025
### Added (Genesis)
- **Project Setup:** Initialized Git repository, Laravel environment, and Database Design.
- **Database:** Designed Entity-Relationship (ER) model covering Users, Teams, Races, and Results.
- **Migrations:** Created initial database structure for the core entities.