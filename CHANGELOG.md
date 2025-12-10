# Changelog

All notable changes to the **WTCS Paddock** project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]
*Work in progress for v3.0 Features*

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
- **Driver Dashboard:** Created a private area (`/dashboard`) displaying the driver's current Team assignment.
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