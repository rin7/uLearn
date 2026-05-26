# ULearn — Local Development Setup

**APTECH School Student Information System (2005)**  
Dockerized for local development using PHP 5.6 + MySQL 5.7.

---

## Quick Start

```bash
# 1. Make sure Docker Desktop is running

# 2. Start the containers
docker compose up --build -d

# 3. Wait ~15 seconds for MySQL to initialize, then open:
#    http://localhost:8080/ulearn/index.php
```

## Default Login Credentials

### Student Login
| Field    | Value     |
|----------|-----------|
| Username | `student1` |
| Password | `pass123`  |

### Faculty Login (Admin)
| Field    | Value      |
|----------|------------|
| Username | `admin`    |
| Password | `admin123` |

Alternative faculty:
| Field    | Value     |
|----------|-----------|
| Username | `jsmith`  |
| Password | `pass123` |

## URLs

| Page | URL |
|------|-----|
| Student Login | http://localhost:8080/ulearn/index.php |
| Faculty Login | http://localhost:8080/ulearn/indexf.php |
| Registration | http://localhost:8080/ulearn/register.php |
| Schedule | http://localhost:8080/ulearn/schedule.php |

## Managing the Containers

```bash
# Stop
docker compose down

# Stop and delete database data
docker compose down -v

# View logs
docker compose logs -f web
docker compose logs -f db

# Restart
docker compose restart
```

## Project Structure

```
ulearn-local/
├── docker-compose.yml    # Docker services (PHP + MySQL)
├── Dockerfile            # PHP 5.6 + Apache image
├── init.sql              # Database schema + seed data
├── README.md             # This file
├── start.sh              # Quick start script
└── ulearn/               # PHP source files (modified copies)
    ├── Connections/
    │   └── connection.php
    ├── images/
    ├── index.php          # Student login (entry point)
    ├── indexf.php         # Faculty login
    ├── register.php       # Student registration
    └── ... (38 PHP files total)
```

## Changes from Original

1. **Database connection** (`Connections/connection.php`): hostname changed from `localhost` → `db` (Docker service name), credentials updated
2. **URL paths**: All hardcoded `/ulearn/` prefixes removed (now relative paths)
3. **Database**: Schema reverse-engineered and saved as `init.sql` with seed data
4. **No PHP code logic changes** — original Dreamweaver-generated code preserved as-is

## Database Tables

| Table | Purpose |
|-------|---------|
| `students` | Student accounts and profiles |
| `faculty` | Faculty accounts with authorization levels |
| `course` | Course catalog with faculty assignments |
| `studentscourse` | Student-course enrollment (many-to-many) |
| `studenthistory` | Academic history records |
