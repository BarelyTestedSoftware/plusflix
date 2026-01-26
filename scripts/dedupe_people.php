<?php
use App\Service\Config;

require_once __DIR__ . '/../autoload.php';

try {
    $pdo = new PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch all people
    $stmt = $pdo->query('SELECT id, name, type FROM person');
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Group by lower(name)+type
    $groups = [];
    foreach ($rows as $row) {
        $key = strtolower(trim($row['name'])) . '|' . (int)$row['type'];
        if (!isset($groups[$key])) { $groups[$key] = []; }
        $groups[$key][] = [
            'id' => (int)$row['id'],
            'name' => $row['name'],
            'type' => (int)$row['type'],
        ];
    }

    $mergedCount = 0;
    foreach ($groups as $key => $people) {
        if (count($people) <= 1) { continue; }

        // Choose canonical as the smallest id
        usort($people, fn($a, $b) => $a['id'] <=> $b['id']);
        $canonical = $people[0];
        $duplicates = array_slice($people, 1);

        $pdo->beginTransaction();
        try {
            // Move references in show (director_id) and show_actor
            $updateDirector = $pdo->prepare('UPDATE show SET director_id = :canonical WHERE director_id = :dup');
            $updateActor = $pdo->prepare('UPDATE show_actor SET person_id = :canonical WHERE person_id = :dup');
            $deletePerson = $pdo->prepare('DELETE FROM person WHERE id = :dup');

            foreach ($duplicates as $dup) {
                $updateDirector->execute(['canonical' => $canonical['id'], 'dup' => $dup['id']]);
                $updateActor->execute(['canonical' => $canonical['id'], 'dup' => $dup['id']]);
                $deletePerson->execute(['dup' => $dup['id']]);
            }

            $pdo->commit();
            $mergedCount += count($duplicates);
            echo "Merged " . count($duplicates) . " duplicates into person ID " . $canonical['id'] . " (" . $canonical['name'] . ", type=" . $canonical['type'] . ")\n";
        } catch (Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    if ($mergedCount === 0) {
        echo "No duplicates found.\n";
    } else {
        echo "\nDeduplication complete. Total merged: $mergedCount\n";
    }
    exit(0);
} catch (Throwable $e) {
    fwrite(STDERR, "Deduplication failed: " . $e->getMessage() . "\n");
    exit(1);
}
