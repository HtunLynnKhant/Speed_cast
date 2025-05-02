# SpeedCast Backend

This project is for speedcast backend include the following.
- Client API
- Admin panel


## Setup ( Local )

Requirements:
- Docker
- Docker Compose
- Dev Containers (VSCode extension)

**1. Clone the repository**
```bash
git clone https://github.com/HtunLynnKhant/Speed_cast.git
```

**2. Copy `.env.example`**
```bash
cd speedcast-backend
cp .env.example .env
```

**3. Lunch Dev Container**

Keyboard Shortcut: `Ctrl` + `Shift` + `P`  
Select: `Dev Containers: Reopen in Container`

**4. Install dependencies**
Open terminal and install dependencies
- `composer install`
- `npm install`

## Code Style

**1. Check code style**  
*By running the following command, pint will show the issue*
```bash
./vendor/bin/pint --test
```

**2. Fix code style**  
*You can tell pint to fix code style by running the following command*
```bash
./vendor/bin/pint
```

## Test
- *to be continue*

## Note
Before open merge request, please check and fix code style.
