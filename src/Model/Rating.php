<?php

namespace App\Model;

use App\Service\Config;

class Rating
{
    private ?int $id = null;
    private ?float $value = null;
    private ?int $showId = null;
    private ?Show $show = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Rating
    {
        $this->id = $id;

        return $this;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(?float $value): Rating
    {
        $this->value = $value;

        return $this;
    }

    public function getShowId(): ?int
    {
        return $this->showId;
    }

    public function setShowId(?int $showId): Rating
    {
        $this->showId = $showId;

        return $this;
    }

    public function getShow(): ?Show
    {
        return $this->show;
    }

    public function setShow(?Show $show): Rating
    {
        $this->show = $show;

        return $this;
    }

    public static function fromArray($array): Rating
    {
        $rating = new self();
        $rating->fill($array);

        return $rating;
    }

    public function fill($array): Rating
    {
        if (isset($array['id']) && !$this->getId()) {
            $this->setId($array['id']);
        }
        if (isset($array['value'])) {
            $this->setValue($array['value'] !== null ? (float) $array['value'] : null);
        }
        if (isset($array['show_id'])) {
            $this->setShowId($array['show_id']);
        }
        return $this;
    }

    public static function findAll(): array
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT * FROM rating';
        $statement = $pdo->prepare($sql);
        $statement->execute();

        $ratings = [];
        $ratingsArray = $statement->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($ratingsArray as $row) {
            $ratings[] = self::fromArray($row);
        }

        return $ratings;
    }

    public static function find(int $id): ?Rating
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT * FROM rating WHERE id = :id';
        $statement = $pdo->prepare($sql);
        $statement->execute(['id' => $id]);
        $data = $statement->fetch(\PDO::FETCH_ASSOC);
        if (!$data) {
            return null;
        }
        $rating = Rating::fromArray($data);
        return $rating;
    }

    public function save(): void
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        if ($this->getId()) {
            $sql = 'UPDATE rating SET value = :value, show_id = :show_id WHERE id = :id';
            $statement = $pdo->prepare($sql);
            $statement->execute([
                'value' => $this->getValue(),
                'show_id' => $this->getShowId(),
                'id' => $this->getId(),
            ]);
        } else {
            $sql = 'INSERT INTO rating (value, show_id) VALUES (:value, :show_id)';
            $statement = $pdo->prepare($sql);
            $statement->execute([
                'value' => $this->getValue(),
                'show_id' => $this->getShowId(),
            ]);
            $this->setId((int)$pdo->lastInsertId());
        }
    }

    public function delete(): void
    {
        if (! $this->getId()) {
            return;
        }

        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'DELETE FROM rating WHERE id = :id';
        $statement = $pdo->prepare($sql);
        $statement->execute(['id' => $this->getId()]);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'value' => $this->value,
            'show_id' => $this->show->getId(),
            // Może lepiej tak:
            //'show_id' => $this->getShowId(),
        ];
    }
}
