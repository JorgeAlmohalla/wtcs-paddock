# Changelog

All notable changes to the **WTCS Paddock** project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [v4.6.0-stable] - 21-01-2026

### ⚡ Performance & Optimization
- **Smart Image Compression:** Implemented a backend-side `CompressUploads` Trait using `Intervention Image`.
    - Automatically detects uploaded images larger than 300KB.
    - Resizes excessively large images to HD standard (1200px width).
    - Compresses file size by ~80% (e.g., 550KB -> 94KB) to ensure instant loading on mobile networks.
- **Server Infrastructure:** Installed `intervention/image-laravel` driver to handle image processing pipelines within the Filament Admin Panel lifecycle.

### 🛠️ Backend Fixes
- **Data Integrity:** Refined `TeamResource` and `TrackResource` to trigger optimization events (`saved`) correctly, ensuring no heavy assets bypass the optimization filter.
- **Logging:** Added detailed logging to `laravel.log` to track compression ratios and file operations for debugging purposes.

### 🏁 Milestone
- **Feature Complete:** System is now fully optimized for production deployment. Mobile App and Web Dashboard are synchronized and performant under 4G conditions.

## [v4.5.1-android-rc] - 19-01-2026

### 📱 Android App (Polishing & Fixes)
- **Networking Architecture:**
    - **Local Network Support:** Refactored `Constants` and `NetworkSecurityConfig` to support physical device testing via local IP (`192.168.x.x`), abandoning the emulator-only `10.0.2.2` bridge.
    - **Centralized URL Patching:** Created a static `fixImageUrl` helper to automatically rewrite localhost URLs from the API to the correct reachable IP address across all models.

### 📉 Performance
- **Infrastructure Insight:** Identified `php artisan serve` single-threaded limitations as the root cause for image loading timeouts on heavy requests, implementing client-side mitigations.

### 📦 Distribution & Compatibility
- **Device Support:** Downgraded `minSdk` version to **24 (Android 7.0)** to resolve "There was a problem parsing the package" errors on older devices.
- **APK Building:** Verified build process to ensure universal APK generation (supporting both ARM and x86 architectures) for wider distribution outside the emulator.

## [v4.5.0-beta] - 16-01-2026

### 🖥️ Added (Web & Admin)
- **Admin Dashboard:** Created custom `StatsOverview` widget displaying key metrics (Total Drivers, Pending Reports, Next Race) for quick monitoring.
- **Branding:** Customized FilamentPHP admin panel with "WTCS Paddock" branding, official logo, and corporate red color scheme.
- **Favicon:** Implemented custom favicon across both public and admin interfaces.

### 📱 Added (Android App - User Experience)
- **Profile Editing Suite:**
    - Implemented `EditProfileFragment` allowing users to update Bio, Nationality, and Equipment.
    - **Avatar Upload:** Added `Multipart` support and `FileUtils` helper to select images from the gallery and upload them to the server.
    - **Security:** Added a custom Dialog for password changes with validation.
- **Native Splash Screen:** Implemented Android 12+ compatible Splash Screen API (`windowSplashScreenAnimatedIcon`) for an instant, branded startup experience without artificial delays.
- **Dynamic Menu Icon:** The bottom navigation "Profile" button now fetches and renders the user's actual avatar in a circle, replacing the generic icon.
- **Custom Iconography:** Replaced stock assets with custom SVGs for Home, Paddock, and Profile navigation items.

### 🎨 Changed (UI Polish)
- **Team Detail View:** 
    - Refined the "Homologation Data" table layout for better alignment.
    - Applied `RoundedCorners` transformation to Team Logos to integrate square images seamlessly.
- **Driver Detail View:** 
    - Fixed layout issues where the "Input Method" text overlapped with team names.
    - Enforced circular styling on Avatars using `ShapeableImageView`.
- **Loading States:** Implemented central `ProgressBar` logic in Home and Profile screens to prevent placeholder text ("Mancinelli") from flashing before API data arrives.

### 🐛 Fixed
- **(Web) Widget Architecture:** Corrected namespace issues with Filament Widgets to ensure proper loading on the dashboard.
- **(Android) Networking:** Fixed `java.net.ProtocolException` when loading large assets (Car Liveries) from the local Laravel server by enforcing connection closure in Glide.
- **(Android) Data Binding:** Fixed multiple instances of hardcoded strings and incorrect ID mappings in the Team Specs view.

## [v4.4.0-android-beta] - 14-01-2026

### 🏎️ Added (Teams Ecosystem)
- **Teams List UI:** Implemented card-based team list with "Works/Privateer" badges and dynamic colored strips.
- **Team Detail Hub:** Created a comprehensive detail view featuring:
    - **Dynamic Specs Table:** Displays engine, power, layout, and chassis data fetched directly from the DB.
    - **Livery Showcase:** Large car image header.
    - **Smart Roster:** Driver list with automatic logic to highlight "Team Principals" (Gold badge) and "Reserve Drivers".
- **Backend Integration:** Updated `/teams/{id}` endpoint to expose technical specifications and improved role detection logic.

### ⚖️ Added (Stewarding System)
- **Incident Reporting:** Full implementation of the reporting flow.
    - **Form Interface:** Created a submission form with dynamic dropdowns for Races and Drivers.
    - **API Integration:** Connected `POST /incidents` to store reports in the database.
- **Report History:** Dashboard table showing the status (Pending/Resolved) of reports filed by or against the user.
- **Report Detail View:** Deep-dive view showing full description, video evidence links, and the final Steward Decision/Penalty.

### 👤 Changed (User Profile)
- **Unified Dashboard:** Merged the "Public Profile" and "Private Dashboard" concepts.
    - Users now see their own stats/graphs in the "Profile" tab alongside editing tools.
- **Edit Profile:** Implemented a form to update Bio, Nationality, and Equipment via `Multipart` request (prepared for avatar upload).
- **Backend Sync:** Updated API to expose `bio` and `reported_id` correctly to the mobile client.

### 🏁 Changed (Race Center)
- **Visual Feedback:**
    - **Privateer Highlighting:** Rows for privateer teams now have a distinct blue background in race results.
    - **Penalties:** Added red text indicators (e.g., "+5s pen") below race times for penalized drivers.
- **Activity to Fragment:** Refactored `RaceDetailActivity` into `RaceDetailFragment` to maintain the bottom navigation bar visible while browsing results.

### 🐛 Fixed (Technical Debt)
- **Networking:**
    - Fixed `java.net.ProtocolException` with large images by implementing Local IP configuration and `Connection: close` headers in Glide.
    - Added JSON Interceptor to Retrofit to prevent HTML error responses from Laravel.
- **Database Logic:**
    - Fixed SQL Error `1364` by aligning Laravel Model `$fillable` fields (`reported_id`) with the database schema.
    - Fixed `RelationNotFoundException` by aliasing `accused()` and `reported()` relationships.
- **Data Parsing:** Updated `ResultRow` model to handle both String and Object formats for Team data to prevent Gson crashes.

## [v4.4.0-android-beta] - 13-01-2026

### 📰 Added (News Module)
- **News Feed:** Implemented `NewsFragment` displaying a scrollable list of articles with large cover images, titles, and dates.
- **Article Reader:** Created `NewsDetailFragment` to render full article content (supporting HTML text) and high-resolution header images.
- **Home Integration:** Added a "Latest News" card to the Home Dashboard that deep-links directly to the most recent article.

### 👤 Added (User Management & Private Dashboard)
- **Unified MyDashboard:** Created a hybrid view (`MyDashboardFragment`) combining the public analytics (Charts, History) with private management controls.
- **Profile Editing:** Implemented `EditProfileFragment` allowing users to update their:
    - Personal Info (Name, Email, Nationality).
    - SimRacing Data (Steam ID, Race Number, Equipment).
    - Driver Bio (Multi-line text).
- **Multipart API Support:** Configured Retrofit to handle `Multipart/Form-Data` for profile updates (preparing for future avatar uploads).

### 🏠 Changed (Home & Navigation)
- **Smart Loading State:** Implemented a semaphore-based loading logic (`apiCallsPending`) in the Home Dashboard to wait for all parallel requests (Race, Leader, News) before revealing the UI.
- **Navigation Flow:** Standardized fragment transactions using `.addToBackStack(null)` to ensure the Android "Back" button navigates history instead of exiting the app.
- **Login UX:** Implemented `OnBackPressedDispatcher` in the Login screen to prevent users from returning to the app after logging out.

### 📊 Visuals & Charts Refinement
- **Chart Cleanup:** Removed grid lines, right-axis labels, and touch highlights from `MPAndroidChart` for a cleaner, minimal look.
- **Data Padding:** Adjusted Y-Axis scaling (`setSpaceBottom`, `setSpaceTop`) to prevent data points (like P1 or P20) from being cut off at the chart edges.
- **Stat Cards V2:** Redesigned statistics cards to support dynamic background colors (Red for Points) and text colors (Purple for Poles), matching the web design.

### 🛠️ Backend & Bug Fixes
- **Endpoint `/user/update-profile`:** Created a new secure endpoint to handle profile form submissions.
- **Endpoint `/teams/{id}`:** Fixed "Hardcoded Specs" issue; the API now dynamically fetches Chassis, Engine, Power, and Layout from the database.
- **Role Detection Logic:** Rewrote the PHP logic to correctly identify "Team Principal" roles by parsing JSON columns and checking contract types flexibly.
- **Data Integrity:** Fixed `NullPointerException` issues in adapters by adding robust null-checks for optional API fields (Bio, Car Image).

### Fixed (UI/UX)
- **News Card Interaction:** Refactored the "Latest News" card on the Homepage to support dual-link behavior (Main area -> Article, Footer -> News Archive) using z-index layering.
- **Visual Visibility:** Fixed readability issues on the News card by removing excessive overlay opacity and applying a solid background to the card footer.

## [v4.3.0-android-beta] - 12-01-2026

### 👤 Added (Driver Profile & Analytics)
- **Driver Detail View:** Implemented `DriverDetailFragment` accessible from any driver link in the app.
- **Interactive Charts:** Integrated `MPAndroidChart` to visualize performance:
    - **Race Finish Position:** Inverted Y-axis line chart with cubic bezier curves (Red).
    - **Championship Points:** Cumulative points progression chart with filled area (Gold).
    - **Clean UI:** Custom chart configuration removing grid lines, axis labels, and clutter for a minimal "Dark Mode" look.
- **Qualifying History:** Added a `RecyclerView` table showing historical qualifying results (Time, Grid Position) with color-coded tyre compound dots (Soft/Medium/Hard).
- **Header Redesign:** Implemented circular avatars with team-colored borders and improved layout for Input method (Wheel/Pad) and Team Name.

### 🏎️ Added (Teams Ecosystem)
- **Teams List:** Created `TeamsFragment` with a new card design featuring a colored side-strip, removing generic initial letters for a cleaner look.
- **Team Detail View:** Implemented a comprehensive Team Hub including:
    - **Header:** Dynamic background color based on team branding.
    - **Official Livery:** Large display of the car model image.
    - **Homologation Data:** Dynamic table showing Chassis, Engine, Power (highlighted), Layout, and Gearbox data fetched from the API.
    - **Team Stats:** Grid showing Active Drivers, Wins, Podiums, and Points.
- **Roster Management:**
    - Implemented `RosterAdapter` to display team members.
    - **Smart Role Badges:** Logic to distinguish and highlight "Team Principal" (Gold Badge) vs "Primary" vs "Reserve" drivers based on API data.

### 🛠️ Backend & API Improvements
- **Endpoint `GET /drivers/{id}`:** 
    - Injected a `history` array containing calculated race-by-race data for graph generation.
    - Fixed SQL column mapping for qualifying times (`best_time`) and tyres (`tyre_compound`).
- **Endpoint `GET /teams/{id}`:** 
    - Updated to include dynamic vehicle specifications (`tech_chassis`, `tech_power`, etc.) instead of hardcoded values.
    - Improved Role detection logic to parse JSON roles and identify "Team Principal" robustly.

### 🎨 UI/UX Polish
- **Loading States:** Implemented `ProgressBar` overlays in Home and Driver Profile screens to prevent UI "flashing" (placeholder data) while fetching API responses.
- **Navigation:**
    - Enabled deep-linking: Clicking a driver in the Team Roster navigates to their Driver Profile.
    - Enabled deep-linking: Clicking a team in the Driver Profile (future scope) or list navigates to the Team Detail.
- **Visual consistency:** Standardized card heights in lists and aligned text/badges to match the Web Portal design.

### 🐛 Fixed & Refined
- **Search Logic:** Refined the search algorithm in `DriversAdapter` to filter strictly by **Driver Name**, excluding Team Name matches to improve search relevance.
- **UI Adjustments:** Improved contrast for the search bar text in Dark Mode.

## [v4.2.0-android-beta] - 08-01-2026
### 🔄 Changed (Navigation Architecture)
- **Central Hub Overhaul:** Replaced standard bottom tabs with a "hub-centric" navigation (Home - Paddock Hub - Profile).
- **Menu BottomSheet:** Implemented a modal grid menu providing access to Dashboard, Calendar, Standings, Drivers, Teams, and News.
- **Logout Flow:** Integrated secure logout logic with confirmation dialog within the navigation hub.

### 🏠 Added (Home Dashboard)
- **Real-time Countdown:** Implemented `CountDownTimer` logic to display live D/H/M/S until the next race session.
- **Dynamic Leader Card:** Linked the "Championship Leader" card to live `/standings` API data.
- **Smart Navigation:** Clicking "Next Race" or "Leader" cards now deep-links to the specific Calendar or Standings tabs.

### 🏁 Added (Calendar & Race Center)
- **Grouped Calendar:** Implemented logic to merge separate API entries (Sprint/Feature) into a single "Race Weekend" card per round.
- **Race Details View:** Created a new Activity with `TabLayout` + `ViewPager2` to display results for Qualifying, Sprint, and Feature races separately.
- **Visual Formatting:** Implemented conditional UI rendering to show Tyre compounds in Qualifying vs. Laps/Points in Races.

### 🏆 Added (Championship Standings)
- **Multi-View Tables:** Created a unified `StandingsFragment` supporting Drivers, Constructors, and Manufacturers tabs.
- **Polymorphic Adapter:** Implemented a smart `RecyclerView` adapter capable of rendering different row types (Driver, Team, Manufacturer) from a single list.
- **Privateer Logic:** Added business logic to detect `type: "privateer"` teams and highlight their rows with a distinct blue background (`#1A2B3C`).

### 🛠️ Backend & Network
- **API Integration:** Connected and mapped endpoints for:
    - `GET /calendar` (Season schedule)
    - `GET /rounds/{id}` (Detailed session results)
    - `GET /standings` (Drivers, Teams, Manufacturers)
- **Data Modeling:** Created robust Java POJOs (`RaceEvent`, `ResultRow`, `TeamStanding`) to handle complex nested JSON responses from Laravel.

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