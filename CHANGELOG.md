# Changelog

All notable changes to the **WTCS Paddock** project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]
*Work in progress for v3.0 (API & Mobile App)*

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