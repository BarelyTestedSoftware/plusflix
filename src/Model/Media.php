<?php

namespace App\Model;

use App\Service\Config;

class Media
{
    private ?int $id = null;
    private ?string $src = null;
    private ?string $alt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Media
    {
        $this->id = $id;

        return $this;
    }

    public function getSrc(): ?string
    {
        return $this->src;
    }

    public function setSrc(?string $src): Media
    {
        $this->src = $src;

        return $this;
    }

    public function getAlt(): ?string
    {
        return $this->alt;
    }

    public function setAlt(?string $alt): Media
    {
        $this->alt = $alt;

        return $this;
    }

    public static function fromArray($array): Media
    {
        $media = new self();
        $media->fill($array);

        return $media;
    }

    public function fill($array): Media
    {
        if (isset($array['id']) && !$this->getId()) {
            $this->setId($array['id']);
        }
        if (isset($array['src'])) {
            $this->setSrc($array['src']);
        }
        if (isset($array['alt'])) {
            $this->setAlt($array['alt']);
        }
        return $this;
    }

    public static function findAll(): array
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT * FROM media';
        $statement = $pdo->prepare($sql);
        $statement->execute();
        $medias = [];
        $mediaData = $statement->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($mediaData as $mediaArray) {
            $medias[] = self::fromArray($mediaArray);
        }
        
        return $medias;
    }

    public static function find(int $id): ?Media
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT * FROM media WHERE id = :id';
        $statement = $pdo->prepare($sql);
        $statement->execute(['id' => $id]);
        $mediaArray = $statement->fetch(\PDO::FETCH_ASSOC);
        if (!$mediaArray) {
            return null;
        }
        $media = Media::fromArray($mediaArray);
        return $media;
    }

    public function save(): void
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        if ($this->getId()) {
            $sql = 'UPDATE media SET src = :src, alt = :alt WHERE id = :id';
            $statement = $pdo->prepare($sql);
            $statement->execute([
                'src' => $this->getSrc(),
                'alt' => $this->getAlt(),
                'id' => $this->getId(),
            ]);
        } else {
            $sql = 'INSERT INTO media (src, alt) VALUES (:src, :alt)';
            $statement = $pdo->prepare($sql);
            $statement->execute([
                'src' => $this->getSrc(),
                'alt' => $this->getAlt(),
            ]);
            $this->setId((int)$pdo->lastInsertId());
        }
    }

    public function delete(): void
    {
        if (!$this->getId()) {
            return;
        }
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'DELETE FROM media WHERE id = :id';
        $statement = $pdo->prepare($sql);
        $statement->execute(['id' => $this->getId()]);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'src' => $this->src,
            'alt' => $this->alt,
        ];
    }
}
