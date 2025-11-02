# Caesar Challenge Workshop Competition

A Laravel-based web application for running a competitive Caesar cipher decoding workshop. Teams compete to decrypt Caesar-encrypted messages in real-time with a live leaderboard.

## 🎯 Features

- **Team Management**: Each team gets a unique URL to access their portal
- **Team Portal**: Teams can set their name, mark themselves as "Ready", and solve Caesar cipher challenges
- **Admin Panel**: Full control to prepare teams, set challenge text, start/stop/reset the game
- **Live Ranking**: Real-time leaderboard showing team progress and completion times
- **Caesar Cipher Service**: Built-in encryption/decryption using Caesar cipher with configurable shifts
- **Realtime Updates**: Automatic polling for live updates (SSE-compatible with polling fallback)
- **Shared Hosting Ready**: Works on standard shared hosting with SQLite or MySQL

## 🚀 Quick Start

### Requirements

- PHP 8.2 or higher
- Composer
- SQLite or MySQL database
- Apache web server (with mod_rewrite enabled)

### Installation

1. **Upload to Server**
   
   Upload all files to your web hosting (e.g., via FTP, cPanel File Manager, or Git)

2. **Set Permissions**
   
   ```bash
   chmod -R 775 storage bootstrap/cache
   ```

3. **Install Dependencies**
   
   ```bash
   composer install --optimize-autoloader --no-dev
   ```

4. **Configure Environment**
   
   Copy `.env.example` to `.env`:
   ```bash
   cp .env.example .env
   ```
   
   Edit `.env` and set your configuration:
   ```env
   APP_NAME="Caesar Challenge"
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=http://your-domain.com
   
   DB_CONNECTION=sqlite
   # For MySQL, uncomment and configure:
   # DB_CONNECTION=mysql
   # DB_HOST=127.0.0.1
   # DB_PORT=3306
   # DB_DATABASE=your_database
   # DB_USERNAME=your_username
   # DB_PASSWORD=your_password
   ```

5. **Generate Application Key**
   
   ```bash
   php artisan key:generate
   ```

6. **Create Database**
   
   For SQLite (default):
   ```bash
   touch database/database.sqlite
   ```
   
   For MySQL: Create an empty database using phpMyAdmin or command line

7. **Run Migrations**
   
   ```bash
   php artisan migrate --force
   php artisan db:seed --force
   ```

8. **Set Up URL Rewriting**
   
   The `.htaccess` files are already included. Make sure mod_rewrite is enabled on Apache.

9. **Access the Application**
   
   Navigate to: `http://your-domain.com`

## 📖 Usage Guide

### For Administrators

1. **Access Admin Panel**
   - Navigate to `/admin` (or just the root URL `/`)

2. **Create Teams**
   - Enter the number of teams you want to create
   - Click "Create Teams"
   - Each team gets a unique 8-character slug/URL

3. **Set Challenge Text**
   - Enter the text that teams will need to decrypt
   - This text will be encrypted with different Caesar shifts for each team
   - Example: "The quick brown fox jumps over the lazy dog"

4. **Start the Game**
   - Once teams have marked themselves as ready and you've set the challenge text
   - Click "🚀 Start Game"
   - The system automatically encrypts the challenge text with a random shift (1-25) for each ready team

5. **Monitor Progress**
   - Watch teams' progress in the admin panel
   - View the live ranking page at `/ranking`

6. **Control the Game**
   - **Stop Game**: Pause the competition
   - **Reset Game**: Clear all progress and start fresh

### For Teams

1. **Access Your Team Portal**
   - Admin provides your unique team URL: `/team/{your-slug}`

2. **Set Team Name**
   - Enter your team name when you first access your portal

3. **Mark as Ready**
   - Click "Mark as Ready" when your team is prepared to start
   - You can toggle this before the game starts

4. **Solve the Challenge**
   - Once the admin starts the game, you'll see your encrypted text
   - Decrypt the Caesar cipher
   - Submit your solution
   - The system checks if your answer matches the original text

5. **Real-time Updates**
   - Your page automatically updates to show game status changes
   - See when the game starts, stops, or if you've solved the challenge

### For Spectators

- Visit `/ranking` to see the live leaderboard
- Automatically refreshes to show latest rankings
- Shows which teams have solved the challenge and their completion times

## 🔐 Caesar Cipher Service

The application includes a `CaesarService` class with the following methods:

```php
// Encrypt text with a specific shift
$encrypted = $caesarService->encrypt('Hello World', 3);

// Decrypt text with a specific shift
$decrypted = $caesarService->decrypt('Khoor Zruog', 3);

// Brute force (try all 26 possible shifts)
$allPossibilities = $caesarService->bruteForce('Khoor Zruog');
```

## 🏗️ Architecture

### Database Schema

**teams**
- `slug`: Unique 8-character identifier for team URL
- `name`: Team name (set by team)
- `is_ready`: Boolean indicating if team is ready to start
- `cipher_text`: The encrypted challenge text for this team
- `solution`: Team's submitted solution
- `shift`: The Caesar shift used for this team's encryption
- `is_correct`: Boolean indicating if solution is correct
- `completed_at`: Timestamp when team completed the challenge

**game_state**
- `state`: Current game state (preparing/running/stopped)
- `challenge_text`: The original text to be encrypted

### Routes

**Admin Routes** (`/admin`)
- `GET /admin` - Admin dashboard
- `POST /admin/teams/create` - Create new teams
- `DELETE /admin/teams/{team}` - Delete a team
- `POST /admin/challenge-text` - Set challenge text
- `POST /admin/game/start` - Start the game
- `POST /admin/game/stop` - Stop the game
- `POST /admin/game/reset` - Reset the game

**Team Routes** (`/team/{slug}`)
- `GET /team/{slug}` - Team portal
- `POST /team/{slug}/name` - Set team name
- `POST /team/{slug}/ready` - Toggle ready status
- `POST /team/{slug}/solution` - Submit solution
- `GET /team/{slug}/updates` - Get real-time updates (JSON)

**Ranking Routes**
- `GET /ranking` - Live leaderboard
- `GET /ranking/updates` - Get ranking updates (JSON)

### Technologies Used

- **Backend**: Laravel 10 (PHP 8.2+)
- **Frontend**: Blade Templates
- **Styling**: Tailwind CSS (CDN)
- **JavaScript**: Alpine.js (CDN)
- **Database**: SQLite (default) or MySQL
- **Realtime**: AJAX polling every 2-5 seconds

## 🧪 Testing

Run the test suite:

```bash
php artisan test
```

Tests include:
- Caesar cipher encryption/decryption logic
- Team creation and management
- Game flow (start, stop, reset)
- Solution submission and validation
- Ranking display

## 🔧 Troubleshooting

### Issue: White screen or 500 error
- Check file permissions on `storage/` and `bootstrap/cache/`
- Enable debug mode temporarily: `APP_DEBUG=true` in `.env`
- Check error logs in `storage/logs/laravel.log`

### Issue: Database connection error
- Verify database credentials in `.env`
- For SQLite: ensure `database/database.sqlite` exists and is writable
- For MySQL: ensure database exists and credentials are correct

### Issue: Routes not working (404 errors)
- Ensure `.htaccess` files are present in root and `public/` directories
- Verify mod_rewrite is enabled on Apache
- Check that AllowOverride is set to All in Apache config

### Issue: CSS/JS not loading
- Check that `APP_URL` in `.env` matches your actual domain
- Clear browser cache
- Verify files are accessible in `public/` directory

## 🎮 Game Flow Example

1. Admin creates 10 teams → Each gets a URL like `/team/a7b3c9d2`
2. Teams access their URLs and set names: "Team Alpha", "Team Beta", etc.
3. Teams click "Mark as Ready"
4. Admin sets challenge text: "Meet me at the old oak tree at midnight"
5. Admin clicks "Start Game"
6. Each team receives the same text encrypted with a different random shift:
   - Team Alpha gets: "Phhw ph dw wkh rog rdn wuhh dw plgqljkw" (shift 3)
   - Team Beta gets: "Tlls tl ha aol vsk vhr ayll ha tpkupnoa" (shift 7)
7. Teams decrypt their text and submit solutions
8. First team to submit the correct answer appears at the top of the ranking
9. Spectators watch the live ranking page as teams complete the challenge

## 📝 License

This project is open-source software licensed under the MIT license.

## 👥 Credits

Created for workshop competitions to teach Caesar cipher cryptography in an engaging, competitive format.
