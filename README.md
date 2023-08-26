
# Laravel Movie Project

This project uses the Laravel framework to consume The Movie DB API to fetch and display trending movies and their details. The data can also be saved to a local database through an artisan command.

## Requirements
- PHP >= 8.0
- Composer
- Laravel CLI
- Database (MySQL, PostgreSQL, SQLite, etc.)

## Installation

### 1. Clone the repository:

```bash
git clone https://github.com/yourusername/movie_project.git
cd movie_project
```

### 2. Install the dependencies:

```bash
composer install
```

### 3. Setup environment variables:

Copy `.env.example` to `.env`:

```bash
cp .env.example .env
```

Update the database configurations and add your The Movie DB API key:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password

TMDB_API_KEY=your_api_key_here
```

### 4. Generate application key:

```bash
php artisan key:generate
```

### 5. Run migrations:

This will create the necessary tables in your database.

```bash
php artisan migrate
```

### 6. Serve the application:

```bash
php artisan serve
```

Your application should now be running at [http://127.0.0.1:8000](http://127.0.0.1:8000).

## Usage

### View Trending Movies:

Visit `http://127.0.0.1:8000/movies/trending`

#### With Pagination

Visit `http://127.0.0.1:8000/movies/trending?page=1`

### View Movie Details:

Click on a specific movie from the trending list or visit `http://127.0.0.1:8000/movies/{movieId}`

### command Kernel:

Run the artisan command to get and save the trending movies from the API to database.

```Bath
php artisan fetch:movies
```

## Testing

Make sure you have the required configurations in `.env.testing` or any specific environment file for testing.

```bash
php artisan test
```

(Note: Be sure to add tests to your project if they don't exist.)

## Contributing

1. Fork the repository.
2. Create a new branch for each feature or improvement.
3. Send a pull request from each feature branch to the `develop` branch.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).