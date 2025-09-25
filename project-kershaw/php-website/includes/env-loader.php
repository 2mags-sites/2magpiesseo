<?php
/**
 * Simple Environment Variable Loader
 * Loads .env file and makes variables available via getenv()
 */

class EnvLoader {
    private static $loaded = false;
    private static $env_path = null;

    /**
     * Load environment variables from .env file
     * @param string $path Path to .env file (optional)
     * @return bool Success status
     */
    public static function load($path = null) {
        // Prevent multiple loads
        if (self::$loaded) {
            return true;
        }

        // Determine path to .env file
        if ($path === null) {
            // Default to .env in website root (one level up from includes)
            $path = dirname(__DIR__) . '/.env';
        }

        self::$env_path = $path;

        // Check if file exists
        if (!file_exists($path)) {
            // Not an error - .env might not exist in all environments
            return false;
        }

        // Read the file
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if ($lines === false) {
            return false;
        }

        foreach ($lines as $line) {
            // Skip comments
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            // Skip lines without = sign
            if (strpos($line, '=') === false) {
                continue;
            }

            // Parse the line
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            // Remove quotes if present
            if ((substr($value, 0, 1) === '"' && substr($value, -1) === '"') ||
                (substr($value, 0, 1) === "'" && substr($value, -1) === "'")) {
                $value = substr($value, 1, -1);
            }

            // Set environment variable
            putenv("$name=$value");
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }

        self::$loaded = true;
        return true;
    }

    /**
     * Get an environment variable with optional default
     * @param string $key Variable name
     * @param mixed $default Default value if not found
     * @return mixed
     */
    public static function get($key, $default = null) {
        $value = getenv($key);

        if ($value === false) {
            return $default;
        }

        // Convert string booleans to actual booleans
        if (strtolower($value) === 'true') return true;
        if (strtolower($value) === 'false') return false;

        return $value;
    }

    /**
     * Check if an environment variable exists
     * @param string $key Variable name
     * @return bool
     */
    public static function has($key) {
        return getenv($key) !== false;
    }

    /**
     * Get all loaded environment variables
     * @return array
     */
    public static function all() {
        return $_ENV;
    }
}

// Auto-load on include
EnvLoader::load();
?>