<?php

namespace App\Model;

use App\Service\Config;
use PDO;

class Show
{
    private ?int $id = null;
    private ?string $title = null;
    private ?string $description = null;
    private ?int $type = null; // 1 - movie, 2 - series
    private ?string $productionDate = null;
    private ?int $numberOfEpisodes = null;
    private ?Media $coverImage = null;
    private ?Media $backgroundImage = null;
    private ?Person $director = null;
    /** @var Person[] */
    private array $actors = [];
    /** @var Streaming[] */
    private array $streamings = [];
    /** @var Category[] */
    private array $categories = [];
    // Average rating from reviews
    private ?float $rating = null;
    private ?int $numberOfRatings = null;


    public function getId(): ?int {
        return $this->id;
    }

    public function setId(?int $id): Show {
        $this->id = $id;
        return $this;
    }

    public function getTitle(): ?string {
        return $this->title;
    }

    public function setTitle(?string $title): Show {
        $this->title = $title;
        return $this;
    }

    public function getDescription(): ?string {
        return $this->description;
    }

    public function setDescription(?string $description): Show {
        $this->description = $description;
        return $this;
    }

    public function getType(): ?int {
        return $this->type;
    }

    public function setType(?int $type): Show {
        $this->type = $type;
        return $this;
    }

    public function getProductionDate(): ?string {
        return $this->productionDate;
    }

    public function setProductionDate(?string $productionDate): Show {
        $this->productionDate = $productionDate;
        return $this;
    }

    public function getNumberOfEpisodes(): ?int {
        return $this->numberOfEpisodes;
    }

    public function setNumberOfEpisodes(?int $numberOfEpisodes): Show {
        $this->numberOfEpisodes = $numberOfEpisodes;
        return $this;
    }

    public function getCoverImage(): ?Media {
        return $this->coverImage;
    }

    public function setCoverImage(?Media $coverImage): Show {
        $this->coverImage = $coverImage;
        return $this;
    }

    public function getBackgroundImage(): ?Media {
        return $this->backgroundImage;
    }

    public function setBackgroundImage(?Media $backgroundImage): Show {
        $this->backgroundImage = $backgroundImage;
        return $this;
    }

    public function getDirector(): ?Person {
        return $this->director;
    }

    public function setDirector(?Person $director): Show {
        $this->director = $director;
        return $this;
    }

    /**
     * @return Person[]
     */
    public function getActors(): array {
        return $this->actors;
    }

    /**
     * @param Person[] $actors
     */
    public function setActors(array $actors): Show {
        $this->actors = $actors;
        return $this;
    }

    /**
     * @return Streaming[]
     */
    public function getStreamings(): array {
        return $this->streamings;
    }

    /**
     * @param Streaming[] $streamings
     */
    public function setStreamings(array $streamings): Show {
        $this->streamings = $streamings;
        return $this;
    }

    /**
     * @return Category[]
     */
    public function getCategories(): array {
        return $this->categories;
    }

    /**
     * @param Category[] $categories
     */
    public function setCategories(array $categories): Show {
        $this->categories = $categories;
        return $this;
    }

    public function getRating(): ?float {
        return $this->rating;
    }

    public function setRating(?float $rating): Show {
        $this->rating = $rating;
        return $this;
    }

    public function getNumberOfRatings(): ?int {
        return $this->numberOfRatings;
    }

    public function setNumberOfRatings(?int $numberOfRatings): Show {
        $this->numberOfRatings = $numberOfRatings;
        return $this;
    }

    public static function fromArray($array): Show {
        $show = new self();
        $show->fill($array);
        return $show;
    }

    public function fill($array): Show {
        $id = $array['id'] ?? null;
        if ($id !== null && ! $this->getId()) {
            $this->setId((int) $id);
        }

        $this->setTitle($array['title'] ?? $this->getTitle());
        $this->setDescription($array['description'] ?? $this->getDescription());
        $this->setType(isset($array['type']) ? (int) $array['type'] : $this->getType());

        $productionDate = $array['production_date'] ?? null;
        $this->setProductionDate($productionDate ?? $this->getProductionDate());

        $numberOfEpisodes = $array['number_of_episodes'] ?? null;
        $this->setNumberOfEpisodes($numberOfEpisodes !== null ? (int) $numberOfEpisodes : $this->getNumberOfEpisodes());

        $directorId = $array['director'] ?? null;
        if ($directorId !== null) {
            // TODO: Załaduj pełny obiekt Person
            $director = new Person();
            $director->id = ((int) $directorId);
            $this->setDirector($director);
        }

        return $this;
    }

    public static function findAll(): array {
        $pdo = new PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT s.*, 
                cover.id AS cover_id, cover.src AS cover_src, cover.alt AS cover_alt,
                bg.id AS bg_id, bg.src AS bg_src, bg.alt AS bg_alt,
                d.id AS director_id, d.name AS director_name, d.type AS director_type,
                AVG(r.value) AS avg_rating, COUNT(r.id) AS rating_count
            FROM show s
            LEFT JOIN media cover ON cover.id = s.cover_image_id
            LEFT JOIN media bg ON bg.id = s.background_image_id
            LEFT JOIN person d ON d.id = s.director_id
            LEFT JOIN rating r ON r.show_id = s.id
            GROUP BY s.id';
        $statement = $pdo->prepare($sql);
        $statement->execute();

        $shows = [];
        $showsArray = $statement->fetchAll(PDO::FETCH_ASSOC);

        $showIds = [];
        foreach ($showsArray as $showArray) {
            $show = self::hydrateBaseWithJoins($showArray);
            $shows[$show->getId()] = $show;
            $showIds[] = $show->getId();
        }

        if (! empty($showIds)) {
            self::populateCategories($pdo, $shows, $showIds);
            self::populateActors($pdo, $shows, $showIds);
            self::populateStreamings($pdo, $shows, $showIds);
        }

        // Reindeksuj na tablicę bez kluczy ID
        $shows = array_values($shows);

        return $shows;
    }

    public static function find(int $id): ?Show {
        $pdo = new PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT s.*, 
                cover.id AS cover_id, cover.src AS cover_src, cover.alt AS cover_alt,
                bg.id AS bg_id, bg.src AS bg_src, bg.alt AS bg_alt,
                d.id AS director_id, d.name AS director_name, d.type AS director_type,
                AVG(r.value) AS avg_rating, COUNT(r.id) AS rating_count
            FROM show s
            LEFT JOIN media cover ON cover.id = s.cover_image_id
            LEFT JOIN media bg ON bg.id = s.background_image_id
            LEFT JOIN person d ON d.id = s.director_id
            LEFT JOIN rating r ON r.show_id = s.id
            WHERE s.id = :id
            GROUP BY s.id';
        $statement = $pdo->prepare($sql);
        $statement->execute(['id' => $id]);

        $showArray = $statement->fetch(PDO::FETCH_ASSOC);
        if (! $showArray) {
            return null;
        }

        $show = self::hydrateBaseWithJoins($showArray);

        // Use a referenced map so populate* can mutate the show instance
        $showMap = [$id => $show];
        self::populateCategories($pdo, $showMap, [$id]);
        self::populateActors($pdo, $showMap, [$id]);
        self::populateStreamings($pdo, $showMap, [$id]);

        return $show;
    }

    public function save(): void {
        $pdo = new PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        //echo "\nhej\n";
        //var_dump($this->toArray());
        
        // TODO: Obsługa nowych zdjęć - gdy będzie MediaController, zmień na rzeczywiste tworzenie Media
        // TODO: Jeśli cover lub background image zostały zmienione, stwórz nowy rekord w media
        $coverImageId = $this->coverImage?->id ?? null;
        $backgroundImageId = $this->backgroundImage?->id ?? null;
        $directorId = $this->director?->id ?? null;
        
        if (! $this->getId()) {
            $sql = "INSERT INTO show (title, description, type, production_date, number_of_episodes, cover_image_id, background_image_id, director_id)
            VALUES (:title, :description, :type, :production_date, :number_of_episodes, :cover_image_id, :background_image_id, :director_id)";
            $statement = $pdo->prepare($sql);
            $statement->execute([
                ':title' => $this->getTitle(),
                ':description' => $this->getDescription(),
                ':type' => $this->getType(),
                ':production_date' => $this->getProductionDate(),
                ':number_of_episodes' => (int)$this->getNumberOfEpisodes(),
                ':cover_image_id' => $coverImageId,
                ':background_image_id' => $backgroundImageId,
                ':director_id' => (int)$directorId,
            ]);

            $this->setId($pdo->lastInsertId());
        } else {
                    //echo "\nhej\n";
                    var_dump([
                ':title' => $this->getTitle(),
                ':description' => $this->getDescription(),
                ':type' => intval($this->getType()),
                ':production_date' => $this->getProductionDate(),
                ':number_of_episodes' => intval($this->getNumberOfEpisodes()),
                ':cover_image_id' => $coverImageId,
                ':background_image_id' => $backgroundImageId,
                ':director_id' => intval($directorId),
                ':id' => $this->getId(),
            ]);
            $sql = "UPDATE show SET title = :title, description = :description, type = :type,
                production_date = :production_date, number_of_episodes = :number_of_episodes,
                cover_image_id = :cover_image_id, background_image_id = :background_image_id,
                director_id = :director_id WHERE id = :id";
            $statement = $pdo->prepare($sql);
            $statement->execute([
                ':title' => $this->getTitle(),
                ':description' => $this->getDescription(),
                ':type' => $this->getType(),
                ':production_date' => $this->getProductionDate(),
                ':number_of_episodes' => (int)$this->getNumberOfEpisodes(),
                ':cover_image_id' => $coverImageId,
                ':background_image_id' => $backgroundImageId,
                ':director_id' => (int)$directorId,
                ':id' => $this->getId(),
            ]);
        }
    }

    public function delete(): void {
        $pdo = new PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'DELETE FROM show WHERE id = :id';
        $statement = $pdo->prepare($sql);
        $statement->execute([':id' => $this->getId()]);
    }

    private static function hydrateBaseWithJoins(array $row): Show {
        $show = self::fromArray($row);

        if (! empty($row['cover_id'])) {
            $media = new Media();
            $media->id = (int) $row['cover_id'];
            $media->src = $row['cover_src'] ?? '';
            $media->alt = $row['cover_alt'] ?? '';
            $show->setCoverImage($media);
        }

        if (! empty($row['bg_id'])) {
            $media = new Media();
            $media->id = (int) $row['bg_id'];
            $media->src = $row['bg_src'] ?? '';
            $media->alt = $row['bg_alt'] ?? '';
            $show->setBackgroundImage($media);
        }

        if (! empty($row['director_id'])) {
            $director = new Person();
            $director->id = (int) $row['director_id'];
            $director->name = $row['director_name'] ?? '';
            $director->type = isset($row['director_type']) ? (int) $row['director_type'] : 1;
            $show->setDirector($director);
        }

        if (isset($row['avg_rating'])) {
            $show->setRating($row['avg_rating'] !== null ? (float) $row['avg_rating'] : null);
        }
        if (isset($row['rating_count'])) {
            $show->setNumberOfRatings($row['rating_count'] !== null ? (int) $row['rating_count'] : null);
        }

        return $show;
    }

    /**
     * @param array<int,Show> $showsById
     * @param int[] $ids
     */
    private static function populateCategories(PDO $pdo, array &$showsById, array $ids): void {
        if (empty($ids)) {
            return;
        }

        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = "SELECT sc.show_id, c.id, c.name
            FROM show_category sc
            INNER JOIN category c ON c.id = sc.category_id
            WHERE sc.show_id IN ($placeholders)
            ORDER BY c.name";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($ids);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $showId = (int) $row['show_id'];
            if (! isset($showsById[$showId])) {
                continue;
            }
            $category = new Category();
            $category->setId((int) $row['id']);
            $category->setName($row['name']);

            $current = $showsById[$showId]->getCategories();
            $current[] = $category;
            $showsById[$showId]->setCategories($current);
        }
    }

    /**
     * @param array<int,Show> $showsById
     * @param int[] $ids
     */
    private static function populateActors(PDO $pdo, array &$showsById, array $ids): void {
        if (empty($ids)) {
            return;
        }

        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = "SELECT sa.show_id, p.id, p.name, p.type
            FROM show_actor sa
            INNER JOIN person p ON p.id = sa.person_id
            WHERE sa.show_id IN ($placeholders)
            ORDER BY p.name";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($ids);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $showId = (int) $row['show_id'];
            if (! isset($showsById[$showId])) {
                continue;
            }
            $actor = new Person();
            $actor->id = (int) $row['id'];
            $actor->name = $row['name'] ?? '';
            $actor->type = isset($row['type']) ? (int) $row['type'] : 0;

            $current = $showsById[$showId]->getActors();
            $current[] = $actor;
            $showsById[$showId]->setActors($current);
        }
    }

    /**
     * @param array<int,Show> $showsById
     * @param int[] $ids
     */
    private static function populateStreamings(PDO $pdo, array &$showsById, array $ids): void {
        if (empty($ids)) {
            return;
        }

        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = "SELECT ss.show_id, st.id, st.name,
                    m.id AS logo_id, m.src AS logo_src, m.alt AS logo_alt
            FROM show_streaming ss
            INNER JOIN streaming st ON st.id = ss.streaming_id
            LEFT JOIN media m ON m.id = st.logo_image_id
            WHERE ss.show_id IN ($placeholders)
            ORDER BY st.name";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($ids);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $showId = (int) $row['show_id'];
            if (! isset($showsById[$showId])) {
                continue;
            }

            $streaming = new Streaming();
            $streaming->id = (int) $row['id'];
            $streaming->name = $row['name'] ?? '';

            if (! empty($row['logo_id'])) {
                $logo = new Media();
                $logo->id = (int) $row['logo_id'];
                $logo->src = $row['logo_src'] ?? '';
                $logo->alt = $row['logo_alt'] ?? '';
                $streaming->logoImage = $logo;
            }

            $current = $showsById[$showId]->getStreamings();
            $current[] = $streaming;
            $showsById[$showId]->setStreamings($current);
        }
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'type' => ['id' => $this->getType(), 'name' => $this->getType() == 1 ? 'Film' : 'Serial'],
            'productionDate' => $this->getProductionDate(),
            'numberOfEpisodes' => $this->getNumberOfEpisodes(),
            'coverImage' => $this->getCoverImage() ? $this->getCoverImage()->toArray() : null,
            'backgroundImage' => $this->getBackgroundImage() ? $this->getBackgroundImage()->toArray() : null,
            'director' => $this->getDirector() ? $this->getDirector()->toArray() : null,
            'actors' => array_map(fn($actor) => $actor->toArray(), $this->getActors()),
            'streamings' =>  array_map(fn($streaming) => $streaming->toArray(), $this->getStreamings()),
            'categories' => array_map(fn($category) => $category->toArray(), $this->getCategories()),
            'rating' => $this->getRating(),
            'numberOfRatings' => $this->getNumberOfRatings(),
        ];
    }
}
