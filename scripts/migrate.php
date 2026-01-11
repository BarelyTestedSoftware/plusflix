<?php
use App\Service\Config;

require_once __DIR__ . '/../autoload.php';

function runMigrationFile(PDO $pdo, string $filePath): void {
    if (!file_exists($filePath)) {
        throw new RuntimeException("Migration file not found: $filePath");
    }
    $sql = file_get_contents($filePath);
    if ($sql === false) {
        throw new RuntimeException("Failed to read file: $filePath");
    }
    // Wrap each file in a transaction for atomicity
    $pdo->beginTransaction();
    try {
        $pdo->exec($sql);
        $pdo->commit();
        echo "Applied: $filePath\n";
    } catch (Throwable $e) {
        $pdo->rollBack();
        throw $e;
    }
}

function tableHasData(PDO $pdo, string $table): bool {
    try {
        $stmt = $pdo->query("SELECT COUNT(*) FROM " . $table);
        $count = (int) $stmt->fetchColumn();
        return $count > 0;
    } catch (Throwable $e) {
        // If table doesn't exist yet, treat as empty
        return false;
    }
}

try {
    $pdo = new PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $root = realpath(__DIR__ . '/..');
    $schema = $root . DIRECTORY_SEPARATOR . 'sql' . DIRECTORY_SEPARATOR . 'schema.sql';
    $content = $root . DIRECTORY_SEPARATOR . 'sql' . DIRECTORY_SEPARATOR . 'content.sql';

    // Always apply schema
    runMigrationFile($pdo, $schema);

    // Seed only if database is empty to avoid UNIQUE conflicts
    if (!tableHasData($pdo, 'media') && !tableHasData($pdo, 'show')) {
        runMigrationFile($pdo, $content);
    } else {
        echo "Skipped seed (content.sql): existing data detected.\n";
    }

    echo "\nMigrations completed successfully.\n";
    exit(0);
} catch (Throwable $e) {
    fwrite(STDERR, "Migration failed: " . $e->getMessage() . "\n");
    exit(1);
}
