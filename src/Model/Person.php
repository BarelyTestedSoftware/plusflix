<?php

namespace App\Model;

use App\Service\Config;

class Person
{
    private ?int $id = null;
    private ?string $name = null;
    private ?int $type = null; // 0 for actor, 1 for director

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Person
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): Person
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(?int $type): Person
    {
        $this->type = $type;

        return $this;
    }

    public static function fromArray($array): Person
    {
        $person = new self();
        $person->fill($array);

        return $person;
    }

    public function fill($array): Person
    {
        if (isset($array['id']) && ! $this->getId()) {
            $this->setId($array['id']);
        }
        if (isset($array['name'])) {
            $this->setName($array['name']);
        }
        if (isset($array['type'])) {
            $this->setType($array['type']);
        }
        return $this;
    }

    public static function findAll(): array
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT * FROM person';
        $statement = $pdo->prepare($sql);
        $statement->execute();

        $people = [];
        $peopleArray = $statement->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($peopleArray as $personRow) {
            $people[] = self::fromArray($personRow);
        }

        return $people;
    }

    public static function find(int $id): ?Person
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT * FROM person WHERE id = :id';
        $statement = $pdo->prepare($sql);
        $statement->execute(['id' => $id]);

        $personData = $statement->fetch(\PDO::FETCH_ASSOC);
        if (!$personData) {
            return null;
        }
        $person = Person::fromArray($personData);
        return $person;
    }

    public function save(): void
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        if ($this->getId()) {
            $sql = 'UPDATE person SET name = :name, type = :type WHERE id = :id';
            $statement = $pdo->prepare($sql);
            $statement->execute([
                'name' => $this->getName(),
                'type' => $this->getType(),
                'id' => $this->getId(),
            ]);
        } else {
            $sql = 'INSERT INTO person (name, type) VALUES (:name, :type)';
            $statement = $pdo->prepare($sql);
            $statement->execute([
                'name' => $this->getName(),
                'type' => $this->getType(),
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
        $sql = 'DELETE FROM person WHERE id = :id';
        $statement = $pdo->prepare($sql);
        $statement->execute(['id' => $this->getId()]);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
        ];
    }
}
