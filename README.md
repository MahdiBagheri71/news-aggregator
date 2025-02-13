# News Aggregator API

A Laravel-based REST API for aggregating and managing news articles.

## Requirements

- PHP >= 8.1
- Composer
- SQLite >= 3.8.8
- Git >= 2.0.0
- News API Key ([Get it here](https://newsapi.org/register))

## Installation & Setup

### 1. Clone the Repository
```bash
git clone https://github.com/MahdiBagheri71/news-aggregator.git
cd news-aggregator
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

Update the `.env` file with the following configurations:
```env
DB_CONNECTION=sqlite
# Comment out or remove other DB_* settings

# Add your News API key
NEWS_API_KEY=your_api_key_here
```

> **Important**: You need to register at [NewsAPI.org](https://newsapi.org/register) to get your API key. The free tier allows 100 requests per day.

### 4. Create SQLite Database
```bash
touch database/database.sqlite
```

### 5. Run Migrations and Seeders
```bash
php artisan migrate --seed
```

### 6. Start Development Server
```bash
php artisan serve
```

The API will be available at `http://localhost:8000`

### Running Tests
```bash
# Create testing database
touch database/testing.sqlite

# Add NEWS_API_KEY to .env.testing
cp .env .env.testing
# Update NEWS_API_KEY in .env.testing if you want to use a different key for testing

# Run tests
php artisan test
```

> **Note**: For testing purposes, you might want to use a different API key to avoid consuming your production API quota. Update the NEWS_API_KEY in `.env.testing` accordingly.
