# Changelog

All notable changes to the **WTCS Paddock** project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]
*Work in progress for v3.0 Features*

## [v2.1.1] - 12-12-2025
### Added (Quality of Life)
- **Race Configuration:** Added `total_laps` field to Race model to define race distance.
- **Auto-Fill:** Result entry form now automatically pre-fills `laps_completed` based on the parent Race's total laps configuration.

### Changed
- **Public UI:** Refined result tables to match Admin panel styling (dark mode, badges, and status colors).

## [v2.1.0-beta] - 11-12-2025
### Added (Features)
- **Qualifying System:** Implemented a dedicated parallel structure for Qualifying sessions within Races (`qualifying_results` table).
- **Public Race View:** Created dynamic Race Detail page (`/races/{id}`) featuring Alpine.js tabs to toggle between Race and Qualifying results.
- **Performance Chart:** Integrated `Chart.js` into the Driver Dashboard to visualize points progression throughout the season.
- **Dashboard Hub:** Enhanced driver dashboard with "My Team" status, License data card, and quick action buttons.

### Changed (UI/UX)
- **Calendar Redesign:** Refactored Race Cards with `flex-1` layout to optimize space usage and improved "Winner" badge visibility.
- **Race Results Schema:** Expanded results table to include Status (DNF/DNS), Gap/Time, and Laps completed.
- **Admin UI:** Added visual cues (Purple color) for Fastest Lap and conditional formatting for penalties.

### Fixed
- **View Architecture:** Solved conflicts between Breeze components and Blade layouts by standardizing `@yield` usage.
- **CSRF & Sessions:** Fixed 419 errors during profile updates by segregating route logic.

## [v2.0.0-alpha] - 10-12-2025
### Added (Frontend & Public Web)
- **Tech Stack:** Installed Tailwind CSS v4 via Vite/PostCSS and Alpine.js for interactivity.
- **Layout System:** Created main Blade layout (`layouts/app`) with Dark Mode enabled and responsive Navbar using Alpine.js state management.
- **Home Page:** Implemented dynamic landing page showing:
    - **Live Countdown:** Real-time JS timer (Days/Hours/Mins/Secs) for the next scheduled race.
    - **Championship Leader:** Dynamic card linking to standings.
    - **Latest News:** Featured article preview with background image.
- **Public Controllers:** Implemented logic for `Home`, `Standings`, `Calendar`, `Drivers`, `Teams`, and `News`.
- **Standings Page:** Full championship table view with dual columns (Drivers & Constructors) and integer point formatting.
- **Calendar Page:** Season timeline view displaying scheduled races and highlighting winners for completed events.
- **Drivers & Teams Pages:** Grid views for lineups using dynamic color gradients based on team branding and FlagCDN integration.
- **News System:** Added full "Read Article" view and a paginated News Archive page (`/news`).

### Added (Authentication & Onboarding)
- **User System:** Integrated Laravel Breeze for secure Login/Registration.
- **Registration Flow:** Customized sign-up form to require SimRacing specific data (Steam ID & Nationality) with backend validation.
- **Role Management:** Implemented logic to differentiate Guest vs Logged-in Driver views in the Navbar.

### Fixed
- **Styles:** Resolved Tailwind v4 compatibility issues by updating PostCSS configuration.
- **Controller Architecture:** Resolved namespace conflicts in `HomeController`.
- **Routing:** Fixed naming conventions for Blade view resolution.
- **Unified Layout:** Refactored `app.blade.php` to resolve conflicts between Breeze components (`$slot`) and Blade directives (`@yield`).

## [v1.0.0-alpha] - 09-12-2025
### Added (Core Backend)
- **Admin Panel:** Implemented full Backoffice using FilamentPHP with custom "WTCS Racing Red" branding.
- **Teams Management:** Complete CRUD for Teams including type (Works/Privateer) and color coding.
- **Tracks Management:** Catalog system with image upload support for circuit layouts.
- **Drivers Management:** User system extended with SimRacing data (SteamID, Nationality) and dynamic Team linking.
- **Race Calendar:** Scheduling system for races linked to tracks.
- **News Backend:** Created `Post` model and Filament resource with Rich Text Editor and automatic Slug generation.

### Added (Logic Engine)
- **Results System:** Integrated `RelationManager` to input race results directly within the Race view.
- **Automated Scoring:** Implemented `RaceResultObserver` to automatically calculate points (25-18-15...) and Fastest Lap bonus upon saving.

### Fixed
- **Environment:** Corrected database connection from SQLite to MySQL.
- **Assets:** Solved image 404 errors by configuring `APP_URL` and linking storage.

## [v0.1.0] - 09-12-2025
### Added
- **Project Setup:** Initialized Git repository and Laravel environment structure.
- **Documentation:** Added project proposal PDF and initial tech stack definition.
- **Design:** Created initial high-fidelity wireframes for the Dashboard using Figma.
- **Database:** Designed Entity-Relationship (ER) model covering Users, Teams, Races, and Results.

### Changed
- **Rebranding:** Pivoted project name from "WTCS Social Hub" to **"WTCS Paddock"** to better reflect its managerial focus.
- **Scope Definition:** Defined milestones for v1.0 through v5.0.
- **Environment:** Resolved port conflicts (3306) and successfully configured local XAMPP stack with Composer.