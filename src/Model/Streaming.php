<?php

namespace App\Model;

use App\Service\Config;

class Streaming
{
    private ?int $id = null;
    private ?string $name = null;
    private ?Media $logoImage = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Streaming
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): Streaming
    {
        $this->name = $name;

        return $this;
    }

    public function getLogoImage(): ?Media
    {
        return $this->logoImage;
    }

    public function setLogoImage(?Media $logoImage): Streaming
    {
        $this->logoImage = $logoImage;

        return $this;
    }

    public static function fromArray($array): Streaming
    {
        $streaming = new self();
        $streaming->fill($array);

        return $streaming;
    }

    public function fill($array): Streaming
    {
        if (isset($array['id']) && ! $this->getId()) {
            $this->setId($array['id']);
        }
        if (isset($array['name'])) {
            $this->setName($array['name']);
        }
        if (isset($array['logo_image']) && is_array($array['logo_image'])) {
            $media = Media::fromArray($array['logo_image']);
            $this->setLogoImage($media);
        }
        return $this;
    }

    public static function findAll(): array
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT s.*, m.src AS media_src, m.alt AS media_alt 
            FROM streaming s 
            LEFT JOIN media m ON s.logo_image_id = m.id';
        $statement = $pdo->prepare($sql);
        $statement->execute();

        $streamings = [];
        $streamingData = $statement->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($streamingData as $row) {
            $streaming = self::fromArray($row);

            if ($row['logo_image_id']) {
                $media = new Media();
                $media->setId($row['logo_image_id']);
                $media->setSrc($row['media_src']);
                $media->setAlt($row['media_alt']);
                $streaming->setLogoImage($media);
            }
            $streamings[] = $streaming;
        }
        return $streamings;
    }

    public static function find(int $id): ?Streaming
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT * FROM streaming WHERE id = :id';
        $statement = $pdo->prepare($sql);
        $statement->execute(['id' => $id]);

        $streamingArray = $statement->fetch(\PDO::FETCH_ASSOC);
        if (!$streamingArray) {
            return null;
        }
        $streaming = Streaming::fromArray($streamingArray);
        return $streaming;
    }

    public function save(): void
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));

        if ($this->getId()) {
            $sql = 'UPDATE streaming SET name = :name, logo_image_id = :logo_image_id WHERE id = :id';
            $statement = $pdo->prepare($sql);
            $statement->execute([
                'name' => $this->getName(),
                'logo_image_id' => $this->getLogoImage() ? $this->getLogoImage()->getId() : null,
                'id' => $this->getId(),
            ]);
        } else {
            $sql = 'INSERT INTO streaming (name, logo_image_id) VALUES (:name, :logo_image_id)';
            $statement = $pdo->prepare($sql);
            $statement->execute([
                'name' => $this->getName(),
                'logo_image_id' => $this->getLogoImage() ? $this->getLogoImage()->getId() : null,
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
        $sql = 'DELETE FROM streaming WHERE id = :id';
        $statement = $pdo->prepare($sql);
        $statement->execute(['id' => $this->getId()]);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'logo_image' => $this->logoImage ? $this->logoImage->toArray() : null,
        ];
    }
}
