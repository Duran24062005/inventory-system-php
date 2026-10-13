<?php

declare(strict_types=1);

namespace AlexiDg\InventorySystem\Config;

use InvalidArgumentException;
use PDO;
use RuntimeException;

final class Database
{
    private static ?PDO $connection = null;

    public static function connection(): PDO
    {
        if (self::$connection instanceof PDO) {
            return self::$connection;
        }

        [$dsn, $username, $password] = self::connectionParameters();

        self::$connection = new PDO($dsn, $username, $password, [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);

        return self::$connection;
    }

    public static function isConfigured(): bool
    {
        foreach ([
            'DATABASE_URL',
            'DATABASE_URL_UNPOOLED',
            'POSTGRES_URL',
            'POSTGRES_URL_NON_POOLING',
        ] as $variable) {
            if (self::environmentValue($variable) !== null) {
                return true;
            }
        }

        return self::environmentValue('PGHOST') !== null
            && self::environmentValue('PGDATABASE') !== null;
    }

    /**
     * @return array{0: string, 1: string, 2: string}
     */
    private static function connectionParameters(): array
    {
        $url = self::firstEnvironmentValue([
            'DATABASE_URL',
            'DATABASE_URL_UNPOOLED',
            'POSTGRES_URL',
            'POSTGRES_URL_NON_POOLING',
        ]);

        if ($url !== null) {
            return self::parametersFromUrl($url);
        }

        $host = self::firstEnvironmentValue(['PGHOST', 'POSTGRES_HOST']);
        $database = self::firstEnvironmentValue(['PGDATABASE', 'POSTGRES_DATABASE']);
        $username = self::firstEnvironmentValue(['PGUSER', 'POSTGRES_USER']);
        $password = self::firstEnvironmentValue(['PGPASSWORD', 'POSTGRES_PASSWORD']) ?? '';
        $port = self::environmentValue('PGPORT') ?? '5432';

        if ($host === null || $database === null || $username === null) {
            throw new RuntimeException(
                'PostgreSQL is not configured. Set DATABASE_URL or the PG* connection variables.'
            );
        }

        return [
            sprintf(
                'pgsql:host=%s;port=%s;dbname=%s',
                self::dsnValue($host),
                self::dsnValue($port),
                self::dsnValue($database)
            ),
            $username,
            $password,
        ];
    }

    /**
     * @return array{0: string, 1: string, 2: string}
     */
    private static function parametersFromUrl(string $url): array
    {
        $parts = parse_url($url);

        if ($parts === false || empty($parts['host']) || empty($parts['user']) || empty($parts['path'])) {
            throw new InvalidArgumentException('DATABASE_URL must be a valid PostgreSQL connection URL.');
        }

        $dsn = [
            'pgsql:host=' . self::dsnValue($parts['host']),
        ];

        if (isset($parts['port'])) {
            $dsn[] = 'port=' . self::dsnValue((string) $parts['port']);
        }

        $dsn[] = 'dbname=' . self::dsnValue(ltrim($parts['path'], '/'));

        $query = [];
        if (!empty($parts['query'])) {
            parse_str($parts['query'], $query);
        }

        foreach (['sslmode', 'channel_binding', 'connect_timeout'] as $option) {
            if (isset($query[$option]) && is_scalar($query[$option])) {
                $dsn[] = $option . '=' . self::dsnValue((string) $query[$option]);
            }
        }

        return [
            implode(';', $dsn),
            rawurldecode((string) $parts['user']),
            rawurldecode((string) ($parts['pass'] ?? '')),
        ];
    }

    private static function firstEnvironmentValue(array $variables): ?string
    {
        foreach ($variables as $variable) {
            $value = self::environmentValue($variable);

            if ($value !== null) {
                return $value;
            }
        }

        return null;
    }

    private static function environmentValue(string $variable): ?string
    {
        $value = $_ENV[$variable] ?? $_SERVER[$variable] ?? getenv($variable);

        if ($value === false || $value === null || trim((string) $value) === '') {
            return null;
        }

        return trim((string) $value);
    }

    private static function dsnValue(string $value): string
    {
        return str_replace(['\\', ';'], ['\\\\', '\\;'], $value);
    }
}
