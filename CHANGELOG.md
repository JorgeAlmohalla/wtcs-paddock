# Changelog

All notable changes to the **WTCS Paddock** project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]
*Work in progress for v1.0 MVP*

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

## [v1.0.0-alpha] - 09-12-2025
### Added (Core Features)
- **Admin Panel:** Implemented full Backoffice using FilamentPHP with custom "WTCS Racing Red" branding.
- **Teams Management:** Complete CRUD for Teams including type (Works/Privateer) and color coding.
- **Tracks Management:** Catalog system with image upload support for circuit layouts.
- **Drivers Management:** User system extended with SimRacing data (SteamID, Nationality) and dynamic Team linking.
- **Race Calendar:** Scheduling system for races linked to tracks.

### Added (Logic Engine)
- **Results System:** Integrated `RelationManager` to input race results directly within the Race view.
- **Automated Scoring:** Implemented `RaceResultObserver` to automatically calculate points (25-18-15...) and Fastest Lap bonus upon saving.

### Fixed
- **Environment:** Corrected database connection from SQLite to MySQL.
- **Assets:** Solved image 404 errors by configuring `APP_URL` and linking storage.

## [v2.0.0-alpha] - 10-12-2025
### Added (Frontend)
- **Tech Stack:** Installed Tailwind CSS v4 via Vite/PostCSS.
- **Layout System:** Created main Blade layout (`layouts/app`) with Dark Mode enabled by default.
- **Home Page:** Implemented landing page prototype with "Next Race" and "Standings" cards.
- **Navbar:** Added responsive navigation bar (desktop view).

### Added (Frontend Logic)
- **Public Controllers:** Implemented logic for `Home`, `Standings`, `Calendar`, `Drivers`, `Teams`, and `News`.
- **Dynamic Views:** Connected all Blade templates to Eloquent models.
- **News System:** Added full "Read Article" view and a paginated News Archive page (`/news`).
- **Formatting:** Implemented integer formatting for points to remove decimals.

### Fixed
- **Controller Architecture:** Resolved namespace conflicts in `HomeController`.
- **Routing:** Fixed naming conventions for Blade view resolution.

### Added (Backend)
- **News System:** Created `Post` model and Filament resource with Rich Text Editor and automatic Slug generation.

### Added (Logic)
- **Home Controller:** Implemented backend logic to fetch the next scheduled race and current championship leader.
- **Dynamic Frontend:** Connected Blade views with database models using Eloquent relationships.

### Added (Features)
- **Standings Page:** Created full championship table view with dual columns (Drivers & Constructors).
- **Scoring Logic:** Implemented backend calculation to aggregate points from `RaceResult` dynamically for both users and teams.
- **Calendar Page:** Implemented season timeline view displaying scheduled races and past results.
- **Winner Display:** Logic to automatically show the race winner on completed events in the calendar.
- **Drivers Page:** Created a grid view for the driver lineup (`/drivers`).
- **Dynamic Styling:** Driver cards automatically inherit their Team's primary color.
- **Flag Integration:** Implemented FlagCDN to render country flags based on ISO codes.
- **Teams Page:** Implemented public constructors overview (`/teams`) displaying car info and active driver roster.
- **Dynamic Gradient:** Team cards generate background gradients based on the team's primary hex color.

### Added (UX/UI)
- **Responsive Navigation:** Fixed mobile layout implementation using Alpine.js state management.
- **Live Countdown:** Replaced static date with a real-time JS timer (Days/Hours/Mins/Secs) for the next race event.
- **Interactivity:** Made Dashboard cards clickable to improve navigation flow to Standings.

### Added (Authentication & UI)
- **User System:** Integrated Laravel Breeze for secure Login/Registration.
- **Role Management:** Implemented logic to differentiate Guest vs Logged-in Driver views in the Navbar.
- **Unified Layout:** Refactored `app.blade.php` to support both public views and the Driver Dashboard, resolving conflicts with Breeze components.
- **Driver Dashboard:** Created a private area (`/dashboard`) displaying the driver's current Team assignment.

### Fixed
- **Styles:** Resolved Tailwind v4 compatibility issues by updating PostCSS configuration and CSS imports.