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

    public static function fromArray($array): Show {
        $show = new self();
        $show->fill($array);
        return $show;
    }

    public function fill($array): Show {
        if (isset($array['id']) && !$this->getId()) {
            $this->setId($array['id']);
        }
        if (isset($array['title'])) {
            $this->setTitle($array['title']);
        }
        if (isset($array['description'])) {
            $this->setDescription($array['description']);
        }
        if (isset($array['type'])) {
            $this->setType($array['type']);
        }
        if (isset($array['production_date'])) {
            $this->setProductionDate($array['production_date']);
        }
        if (isset($array['number_of_episodes'])) {
            $this->setNumberOfEpisodes($array['number_of_episodes']);
        }

        return $this;
    }

    public static function findAll(): array {
        $pdo = new PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT * FROM show';
        $statement = $pdo->prepare($sql);
        $statement->execute();

        $shows = [];
        $showsArray = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach ($showsArray as $showArray) {
            $shows[] = self::fromArray($showArray);
        }

        return $shows;
    }

    public static function find(int $id): ?Show {
        $pdo = new PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT * FROM show WHERE id = :id';
        $statement = $pdo->prepare($sql);
        $statement->execute(['id' => $id]);

        $showArray = $statement->fetch(PDO::FETCH_ASSOC);
        if (! $showArray) {
            return null;
        }
        $show = Show::fromArray($showArray);

        return $show;
    }

    public function save(): void {
        $pdo = new PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        if (! $this->getId()) {
            $sql = "INSERT INTO show (title, description, type, production_date, number_of_episodes)
            VALUES (:title, :description, :type, :production_date, :number_of_episodes)";
            $statement = $pdo->prepare($sql);
            $statement->execute([
                ':title' => $this->getTitle(),
                ':description' => $this->getDescription(),
                ':type' => $this->getType(),
                ':production_date' => $this->getProductionDate(),
                ':number_of_episodes' => $this->getNumberOfEpisodes(),
            ]);

            $this->setId($pdo->lastInsertId());
        } else {
            $sql = "UPDATE show SET title = :title, description = :description, type = :type,
                production_date = :production_date, number_of_episodes = :number_of_episodes WHERE id = :id";
            $statement = $pdo->prepare($sql);
            $statement->execute([
                ':title' => $this->getTitle(),
                ':description' => $this->getDescription(),
                ':type' => $this->getType(),
                ':production_date' => $this->getProductionDate(),
                ':number_of_episodes' => $this->getNumberOfEpisodes(),
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

    //----------------------------------

    public Media $coverImage;
    public Media $backgroundImage;
    public Person $director;
    /** @var Person[] */
    public array $actors;
    /** @var Streaming[] */
    public array $streamings;
    /** @var Category[] */
    public array $categories;
    // Average rating from reviews
    public float $rating;
    public int $numberOfRatings;
}
