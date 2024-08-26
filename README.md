# Run a PHP Server without XAMPP or WAMP

This is a simple PHP server that can be run without XAMPP or WAMP. It is useful for testing PHP scripts on your local machine.

## Usage

Run the Docker Compose:
```bash
docker compose up -d
```

Run the PHP server:
```bash
php -S localhost:8000
```

Automatically, php will search for an `index.php` or `index.html` file in the root directory and serve it.
But if you want to serve an `index.php` or `index.html` in different directory, you can specify it as an argument:
```bash
php -S localhost:8000 -t /path/to/directory
```