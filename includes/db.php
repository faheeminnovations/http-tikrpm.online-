<?php
/**
 * TikRPM.com – PDO Database Connection (Singleton)
 * Usage: $pdo = db();
 */

declare(strict_types=1);

function db(): PDO {
    static $pdo = null;

    if ($pdo !== null) {
        return $pdo;
    }

    $dsn = sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=%s',
        DB_HOST,
        DB_PORT,
        DB_NAME,
        DB_CHARSET
    );

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
        PDO::ATTR_PERSISTENT         => false,
        PDO::MYSQL_ATTR_FOUND_ROWS   => true,
    ];

    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (PDOException $e) {
        // Log in development; show generic error in production
        if (ENVIRONMENT === 'development') {
            throw $e;
        }
        http_response_code(503);
        die('<h1 style="font-family:sans-serif;text-align:center;margin-top:4rem;">
              Service temporarily unavailable. Please try again later.
             </h1>');
    }

    return $pdo;
}

// ── Reusable Query Helpers ────────────────────────────────────

/**
 * Fetch a single row.
 * @param  string $sql
 * @param  array  $params
 * @return array|null
 */
function db_row(string $sql, array $params = []): ?array {
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    $row = $stmt->fetch();
    return $row ?: null;
}

/**
 * Fetch all rows.
 * @param  string $sql
 * @param  array  $params
 * @return array
 */
function db_rows(string $sql, array $params = []): array {
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

/**
 * Execute an INSERT / UPDATE / DELETE.
 * Returns last insert ID for INSERT, affected rows count otherwise.
 * @param  string $sql
 * @param  array  $params
 * @return int
 */
function db_exec(string $sql, array $params = []): int {
    $pdo  = db();
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    $verb = strtoupper(substr(ltrim($sql), 0, 6));
    return ($verb === 'INSERT') ? (int)$pdo->lastInsertId() : $stmt->rowCount();
}
