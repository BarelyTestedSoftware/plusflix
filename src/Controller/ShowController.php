<?php

namespace App\Controller;

use App\Exception\NotFoundException;
use App\Model\Show;
use App\Model\Media;
use App\Model\Person;
use App\Service\Config;
use App\Service\Router;

class ShowController
{
    public function getAll(): array {
        return ['shows' => Show::findAll()];
    }

    public function get(int $id): array {
        $show = Show::find($id);
        if (!$show) {
            throw new NotFoundException("Produkcja o ID $id nie istnieje");
        }
        return ['show' => $show];
    }

    public function store(array $data, Router $router): int {
        if (empty($data['title'])) {
            throw new \InvalidArgumentException("Tytuł nie może być pusty.");
        }

        if (isset($data['type']) && !in_array((int)$data['type'], [1, 2])) {
            throw new \InvalidArgumentException("Nieprawidłowy typ produkcji.");
        }

        $show = Show::fromArray($data);
        
        // Obsługa zdjęć
        if (!empty($data['coverImage'])) {
            $coverMedia = $this->persistMedia($data['coverImage'], ($data['title'] ?? '') . ' - Cover Image');
            $show->setCoverImage($coverMedia);
        }
        
        if (!empty($data['backgroundImage'])) {
            $backgroundMedia = $this->persistMedia($data['backgroundImage'], ''); // zdjęcia dekoracyjne: pusty alt
            $show->setBackgroundImage($backgroundMedia);
        }

        // Obsługa reżysera i aktorów
        $directorValue = $data['director'] ?? null;
        if ($directorValue) {
            $director = $this->resolvePerson($directorValue, 2); // 2 = director
            $show->setDirector($director);
        }

        $actorValues = $data['actors'] ?? [];
        $actors = [];
        if (is_array($actorValues)) {
            foreach ($actorValues as $rawActor) {
                if ($rawActor === '' || $rawActor === null) {
                    continue;
                }
                $actors[] = $this->resolvePerson($rawActor, 1); // 1 = actor
            }
        }
        $show->setActors($actors);
        
        $show->save();

        $this->syncActors($show->getId(), $actors);
        // Kategorie i platformy streamingowe (pivot tables)
        $categoryIds = [];
        if (!empty($data['categories']) && is_array($data['categories'])) {
            foreach ($data['categories'] as $cid) {
                if ($cid === '' || $cid === null) { continue; }
                $categoryIds[] = (int)$cid;
            }
        }
        $this->syncCategories($show->getId(), $categoryIds);

        $streamingIds = [];
        if (!empty($data['streamings']) && is_array($data['streamings'])) {
            foreach ($data['streamings'] as $sid) {
                if ($sid === '' || $sid === null) { continue; }
                $streamingIds[] = (int)$sid;
            }
        }
        $this->syncStreamings($show->getId(), $streamingIds);
        $router->redirect('/admin/show/');

        return $show->getId();
    }

    public function edit (int $id): array {
        $show = Show::find($id);
        if (!$show) {
            throw new NotFoundException("Produkcja o ID $id nie istnieje");
        }
        return ['show' => $show];
    }

    public function update (int $id, array $data, Router $router): void {
        $show = Show::find($id);
        if (!$show) {
            throw new NotFoundException("Produkcja o ID $id nie istnieje");
        }
        
        $show->fill($data);
        // @todo: walidacja
        if (isset($data['title']) && empty($data['title'])) {
            throw new \InvalidArgumentException("Podczas edycji tytuł nie może byc pusty.");
        }
        
        // Obsługa zdjęć
        if (!empty($data['coverImage'])) {
            $coverMedia = $this->persistMedia($data['coverImage'], ($data['title'] ?? '') . ' - Cover Image');
            $show->setCoverImage($coverMedia);
        }
        
        if (!empty($data['backgroundImage'])) {
            $backgroundMedia = $this->persistMedia($data['backgroundImage'], '');
            $show->setBackgroundImage($backgroundMedia);
        }

        // Obsługa reżysera i aktorów
        $directorValue = $data['director'] ?? null;
        if ($directorValue) {
            $director = $this->resolvePerson($directorValue, 2);
            $show->setDirector($director);
        } else {
            $show->setDirector(null);
        }

        $actorValues = $data['actors'] ?? [];
        $actors = [];
        if (is_array($actorValues)) {
            foreach ($actorValues as $rawActor) {
                if ($rawActor === '' || $rawActor === null) {
                    continue;
                }
                $actors[] = $this->resolvePerson($rawActor, 1);
            }
        }
        $show->setActors($actors);

        $show->save();
        $this->syncActors($show->getId(), $actors);

        $categoryIds = [];
        if (!empty($data['categories']) && is_array($data['categories'])) {
            foreach ($data['categories'] as $cid) {
                if ($cid === '' || $cid === null) { continue; }
                $categoryIds[] = (int)$cid;
            }
        }
        $this->syncCategories($show->getId(), $categoryIds);

        $streamingIds = [];
        if (!empty($data['streamings']) && is_array($data['streamings'])) {
            foreach ($data['streamings'] as $sid) {
                if ($sid === '' || $sid === null) { continue; }
                $streamingIds[] = (int)$sid;
            }
        }
        $this->syncStreamings($show->getId(), $streamingIds);
        $router->redirect('/admin/show/');
    }

    public function delete (int $id, Router $router): void {
        $show = Show::find($id);
        if (!$show) {
            throw new NotFoundException("Produkcja o ID $id nie istnieje");
        }
        $show->delete();
        $router->redirect('/admin/show/');
    }

    /**
     * Wyszukuj i filtruj produkcje
     * 
     * @param array $filters Tablica z filtrami:
     *   - q: zapytanie wyszukiwania (szuka w tytule)
     *   - type: typ (1=film, 2=serial)
     *   - actor: ID aktora
     *   - director: ID reżysera
     *   - year: rok produkcji
     *   - rating: minimalna ocena
     *   - genre: ID gatunku/kategorii
     */
    public function search(array $filters = []): array {
        $shows = Show::findAll();
        
        // Filtruj po zapytaniu wyszukiwania (tytuł, opis)
        if (!empty($filters['q'])) {
            $query = strtolower($filters['q']);
            $shows = array_filter($shows, function($show) use ($query) {
                $title = strtolower($show->getTitle() ?? '');
                return strpos($title, $query) !== false;
            });
        }
        
        // Filtruj po typie (film/serial)
        if (!empty($filters['type'])) {
            $type = (int)$filters['type'];
            $shows = array_filter($shows, fn($show) => $show->getType() === $type);
        }
        
        // Filtruj po roku produkcji
        if (!empty($filters['year'])) {
            $year = (int)$filters['year'];
            $shows = array_filter($shows, function($show) use ($year) {
                $productionDate = $show->getProductionDate();
                if (! $productionDate) {
                    return false;
                }
                $productionYear = (int)substr($productionDate, 0, 4);
                return $productionYear === $year;
            });
        }
        
        // Filtruj po ocenie minimalnej
        if (!empty($filters['rating'])) {
            $minRating = (float)$filters['rating'];
            $shows = array_filter($shows, fn($show) => ($show->getRating() ?? 0) >= $minRating);
        }
        
        // Filtruj po aktorze
        if (!empty($filters['actor'])) {
            $actorId = (int)$filters['actor'];
            $shows = array_filter($shows, function($show) use ($actorId) {
                $actors = $show->getActors();
                return !empty(array_filter($actors, fn($a) => $a->getId() === $actorId));
            });
        }
        
        // Filtruj po reżyserze
        if (!empty($filters['director'])) {
            $directorId = (int)$filters['director'];
            $shows = array_filter($shows, function($show) use ($directorId) {
                $director = $show->getDirector();
                return $director && $director->getId() === $directorId;
            });
        }
        
        // Filtruj po gatunku/kategorii
        if (!empty($filters['genre'])) {
            $genreId = (int)$filters['genre'];
            $shows = array_filter($shows, function($show) use ($genreId) {
                $categories = $show->getCategories();
                return !empty(array_filter($categories, fn($c) => $c->getId() === $genreId));
            });
        }
        
        // Reindeksuj tablicę
        $shows = array_values($shows);
        
        return ['shows' => $shows];
    }

    private function persistMedia(string $src, string $alt): Media
    {
        $media = new Media();
        $media->setSrc(trim($src));
        $media->setAlt($alt);
        $media->save();

        return $media;
    }

    private function resolvePerson(mixed $value, int $type): Person
    {
        if (is_numeric($value)) {
            $existing = Person::find((int) $value);
            if ($existing) {
                return $existing;
            }
        }

        $name = is_string($value) ? trim($value) : '';
        if ($name === '') {
            throw new \InvalidArgumentException('Nazwa osoby nie może być pusta.');
        }

        // Try to find existing person by case-insensitive name and type
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $stmt = $pdo->prepare('SELECT * FROM person WHERE LOWER(name) = LOWER(:name) AND type = :type LIMIT 1');
        $stmt->execute(['name' => $name, 'type' => $type]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($row) {
            return Person::fromArray($row);
        }

        // Create new if not found
        $person = new Person();
        $person->setName($name);
        $person->setType($type);
        $person->save();

        return $person;
    }

    /**
     * @param Person[] $actors
     */
    private function syncActors(int $showId, array $actors): void
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));

        $delete = $pdo->prepare('DELETE FROM show_actor WHERE show_id = :show_id');
        $delete->execute(['show_id' => $showId]);

        if (empty($actors)) {
            return;
        }

        $insert = $pdo->prepare('INSERT INTO show_actor (show_id, person_id) VALUES (:show_id, :person_id)');
        foreach ($actors as $actor) {
            $insert->execute([
                'show_id' => $showId,
                'person_id' => $actor->getId(),
            ]);
        }
    }

    private function syncCategories(int $showId, array $categoryIds): void
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));

        $delete = $pdo->prepare('DELETE FROM show_category WHERE show_id = :show_id');
        $delete->execute(['show_id' => $showId]);

        if (empty($categoryIds)) {
            return;
        }

        $insert = $pdo->prepare('INSERT INTO show_category (show_id, category_id) VALUES (:show_id, :category_id)');
        foreach ($categoryIds as $cid) {
            $insert->execute([
                'show_id' => $showId,
                'category_id' => $cid,
            ]);
        }
    }

    private function syncStreamings(int $showId, array $streamingIds): void
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));

        $delete = $pdo->prepare('DELETE FROM show_streaming WHERE show_id = :show_id');
        $delete->execute(['show_id' => $showId]);

        if (empty($streamingIds)) {
            return;
        }

        $insert = $pdo->prepare('INSERT INTO show_streaming (show_id, streaming_id) VALUES (:show_id, :streaming_id)');
        foreach ($streamingIds as $sid) {
            $insert->execute([
                'show_id' => $showId,
                'streaming_id' => $sid,
            ]);
        }
    }
}