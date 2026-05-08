# SIMONEL — Phase 1 Development Plan

## Sistem Monitoring Panel (SIMONEL)

---

# 1. Overview

SIMONEL (Sistem Monitoring Panel) adalah aplikasi dashboard monitoring berbasis IoT untuk memantau data panel listrik dan perangkat monitoring secara realtime.

Phase 1 difokuskan untuk membangun MVP (Minimum Viable Product) yang stabil, ringan, dan siap digunakan untuk monitoring dasar.

---

# 2. Technology Stack

## Backend
- Native PHP
- MVC Architecture
- MySQL / MariaDB

## Frontend
- Tailwind CSS
- Alpine.js
- Chart.js
- Font: Outfit
- Icon: Boxicons

## IoT Device
- ESP8266
- JSON API Communication

---

# 3. Phase 1 Goals

Target utama Phase 1:

- Device IoT dapat mengirim data ke server
- Data tersimpan ke database
- Dashboard dapat menampilkan monitoring realtime
- User dapat melihat history data
- Admin dapat mengelola device
- Sistem memiliki fondasi yang scalable

---

# 4. Core Features

## 4.1 Authentication System

### Features
- Login
- Logout
- Session Management
- Password Hashing

### Database Table

```sql
users
```

### Fields
- id
- name
- email
- password
- role
- created_at

---

## 4.2 Dashboard Realtime

### Dashboard Components

#### Summary Cards
- Total Device
- Device Online
- Device Offline
- Total Power
- Total Energy

#### Monitoring Cards
- Voltage
- Current
- Power
- Energy
- Frequency
- Power Factor
- Last Update

#### Realtime Charts
- Voltage Chart
- Current Chart
- Power Chart

### Technology
- Chart.js
- AJAX Polling

---

## 4.3 Device Management

### Features
- Add Device
- Edit Device
- Delete Device
- Activate / Deactivate Device

### Database Table

```sql
devices
```

### Fields
- id
- device_code
- device_name
- location
- status
- api_key
- created_at

---

## 4.4 API Data Receiver

### Endpoint

```txt
/api/v1/device/send
```

### JSON Payload Example

```json
{
  "device_id": "PANEL_01",
  "api_key": "xxxxx",
  "voltage": 220,
  "current": 1.5,
  "power": 330,
  "energy": 12.1,
  "frequency": 50,
  "pf": 0.98
}
```

### Backend Flow

1. Validate API Key
2. Validate Device
3. Validate Numeric Data
4. Save Data to Database
5. Return JSON Response

---

## 4.5 Data Logging System

### Database Table

```sql
sensor_logs
```

### Fields
- id
- device_id
- voltage
- current
- power
- energy
- frequency
- pf
- created_at

---

## 4.6 Device Detail Page

### Features
- Device Information
- Realtime Status
- Historical Chart
- Latest Logs
- Online / Offline Status

---

## 4.7 History & Filter

### Filter Options
- Today
- Last 24 Hours
- Last 7 Days
- Custom Date Range

---

## 4.8 Online / Offline Detection

### Logic
Jika device tidak mengirim data lebih dari 30 detik:

```txt
STATUS = OFFLINE
```

---

## 4.9 Responsive UI

### Supported Devices
- Desktop
- Tablet
- Mobile

---

## 4.10 Basic Settings

### Features
- Profile Settings
- Change Password
- Refresh Interval Settings

---

# 5. Dashboard Menu Structure

```txt
Dashboard
Devices
Logs
Reports
Settings
Profile
Logout
```

---

# 6. MVC Folder Structure

```txt
/app
    /controllers
        DashboardController.php
        DeviceController.php
        ApiController.php
        AuthController.php

    /models
        Device.php
        SensorLog.php
        User.php

    /views
        /dashboard
        /devices
        /auth
        /layouts

    /core
        App.php
        Controller.php
        Database.php
        Router.php

/public
    /assets
        /css
        /js
        /img

/routes
/config
/storage
```

---

# 7. System Flow

## IoT Flow

```txt
ESP8266
→ POST JSON
→ API SIMONEL
→ Database
→ Dashboard Realtime
```

---

## User Flow

```txt
Login
→ Dashboard
→ Monitoring
→ View History
→ Export Data
```

---

# 8. UI / UX Direction

## Design Theme
- Industrial Modern
- Dark Mode Default
- Blue / Cyan Accent
- Responsive Layout
- Focus on Readability

## UI Components
- Fixed Sidebar
- Dashboard Cards
- Realtime Charts
- Status Badge
- Notification Area

---

# 9. Realtime Strategy

## Recommended Method
Gunakan AJAX Polling setiap 3–5 detik.

### Reason
- Simpler Implementation
- Easier Debugging
- Stable for MVP
- No WebSocket Complexity

---

# 10. Security Plan

## Authentication
- Session Login
- Password Hashing

## API Security
- API Key Validation
- Device Validation
- Input Sanitization

---

# 11. Database Plan

## Main Tables

### users
Menyimpan data user sistem.

### devices
Menyimpan data device IoT.

### sensor_logs
Menyimpan histori data monitoring.

---

# 12. Development Roadmap

## STEP 1
Setup:
- MVC Structure
- Routing
- Database Connection

---

## STEP 2
Build:
- Authentication System

---

## STEP 3
Build:
- Device CRUD

---

## STEP 4
Build:
- API Receiver

---

## STEP 5
Testing:
- ESP8266 → API

---

## STEP 6
Build:
- Realtime Dashboard

---

## STEP 7
Build:
- Historical Chart

---

## STEP 8
Build:
- Online / Offline Detection

---

## STEP 9
Polish:
- UI / UX
- Responsive Layout
- Performance Optimization

---

# 13. Features Not Included in Phase 1

Fitur berikut akan dikerjakan pada phase berikutnya:

- MQTT
- WebSocket
- AI Analytics
- Predictive Maintenance
- WhatsApp Notification
- Advanced Reporting
- Multi Tenant System
- Live Map Monitoring
- Maintenance Scheduler

---

# 14. Final Target of Phase 1

Jika Phase 1 selesai, maka SIMONEL sudah mampu:

- menerima data dari device IoT
- menyimpan data monitoring
- monitoring realtime
- menampilkan grafik histori
- mendeteksi online/offline device
- mengelola device
- digunakan sebagai prototype production-ready

---

# 15. UI Design Direction

## Design Style

SIMONEL menggunakan konsep UI:

- Modern Industrial Dashboard
- Soft Glassmorphism
- Clean Monitoring Interface
- Realtime Analytics Dashboard
- Minimalist Professional Layout

---

## UI Inspiration

Konsep dashboard mengadopsi tampilan modern seperti:

- financial dashboard
- analytics dashboard
- SaaS monitoring panel
- industrial monitoring system

Dengan penyesuaian untuk kebutuhan monitoring panel listrik dan IoT.

---

# 16. Dashboard Layout Structure

## Main Layout

```txt
+------------------------------------------------+
| Sidebar | Topbar                               |
|         |--------------------------------------|
|         | Summary Cards                        |
|         |--------------------------------------|
|         | Realtime Chart      | Device Status  |
|         |--------------------------------------|
|         | Parameter Summary   | Alerts         |
|         |--------------------------------------|
```

---

## Sidebar Menu

### Main Menu

```txt
Dashboard
Devices
Monitoring
Reports
Alerts
Analytics
Settings
Users
Logs
```

---

## Topbar Components

### Topbar Features
- Search Bar
- Notification Icon
- User Profile
- Realtime Indicator

---

# 17. Dashboard Components

## 17.1 Summary Cards

### Components
- Total Device
- Online Device
- Total Power
- Energy Today
- Active Alerts

### UI Style
- Rounded Card
- Soft Shadow
- Icon Circle
- Gradient Accent
- Minimal Text

---

## 17.2 Realtime Power Chart

### Chart Parameters
- Daya Nyata (W)
- Daya Semu (VA)
- Daya Reaktif (VAR)

### Technology
- Chart.js
- AJAX Polling

### Chart Style
- Smooth Curve
- Gradient Line
- Soft Background
- Interactive Tooltip

---

## 17.3 Device Status Card

### Device Information
- Device Name
- Location
- Status
- Last Update
- Current Power

### Status Color

| Status | Color |
|---|---|
| Online | Green |
| Warning | Yellow |
| Critical | Red |
| Offline | Gray |

---

## 17.4 Parameter Summary Table

### Parameters
- Tegangan
- Arus
- Daya Semu
- Daya Nyata
- Daya Reaktif

### Statistics
- Average
- Minimum
- Maximum

---

## 17.5 Alert Section

### Features
- Alert Title
- Device Name
- Alert Time
- Alert Severity

### Severity Level
- High
- Medium
- Info

---

# 18. UI Theme Configuration

## Color Palette

### Primary Color
```txt
#5B5FEF
```

### Secondary Color
```txt
#7B61FF
```

### Background Color
```txt
#F5F7FB
```

### Card Color
```txt
#FFFFFF
```

### Text Color
```txt
#1E1E2D
```

---

## Status Colors

### Success
```txt
#22C55E
```

### Warning
```txt
#FACC15
```

### Danger
```txt
#EF4444
```

### Info
```txt
#3B82F6
```

---

# 19. Typography

## Font Family

```txt
Outfit
```

---

## Font Weight Usage

| Component | Weight |
|---|---|
| Main Title | Bold |
| Section Title | Semibold |
| Card Value | Bold |
| Label Text | Medium |
| Secondary Text | Regular |

---

# 20. UI Component Style

## Card Style

### Properties
- rounded-3xl
- soft-shadow
- border-transparent
- clean spacing
- light gradient accent

---

## Button Style

### Primary Button
- rounded-full
- gradient background
- soft shadow
- hover transition

---

## Sidebar Style

### Properties
- fixed sidebar
- icon + text navigation
- active menu highlight
- soft background

---

## Chart Container

### Properties
- rounded-xl
- light background
- smooth interaction
- responsive width

---

# 21. Recommended Tailwind Style

## Main Layout

```html
class="bg-[#F5F7FB] min-h-screen flex"
```

---

## Sidebar

```html
class="w-72 bg-white rounded-r-3xl shadow-sm"
```

---

## Dashboard Card

```html
class="bg-white rounded-3xl shadow-sm p-6"
```

---

## Primary Button

```html
class="bg-gradient-to-r from-indigo-500 to-violet-500 text-white rounded-full px-5 py-2"
```

---

# 22. Realtime Data Structure

## Monitoring Parameters

### Stored Parameters

| Parameter | Unit |
|---|---|
| Voltage | V AC |
| Current | A |
| Apparent Power | VA |
| Active Power | W |
| Reactive Power | VAR |
| Frequency | Hz |
| Power Factor | PF |
| Energy | kWh |

---

# 23. Database Structure

## Table: devices

```sql
CREATE TABLE devices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    device_code VARCHAR(50),
    device_name VARCHAR(100),
    location VARCHAR(100),
    api_key VARCHAR(255),
    status ENUM('online','offline'),
    last_seen DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

## Table: sensor_logs

```sql
CREATE TABLE sensor_logs (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    device_id INT,
    voltage FLOAT,
    current FLOAT,
    apparent_power FLOAT,
    active_power FLOAT,
    reactive_power FLOAT,
    frequency FLOAT,
    power_factor FLOAT,
    energy_kwh FLOAT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

# 24. API Payload Structure

## JSON Example

```json
{
  "device_id": "MICRO_A",
  "api_key": "xxxxx",

  "voltage": 220,
  "current": 1.2,

  "apparent_power": 264,
  "active_power": 250,
  "reactive_power": 80,

  "frequency": 50,
  "power_factor": 0.95,
  "energy_kwh": 12.5
}
```

---

# 25. Future Direction

Setelah Phase 1 stabil, SIMONEL dapat dikembangkan menjadi:

- Monitoring PLTS
- Monitoring Panel Industri
- Monitoring Genset
- Monitoring Energi Gedung
- Monitoring Mesin Produksi
- SaaS Monitoring System

---

# END OF DOCUMENT

