<?php

namespace App\Controller;

use App\Exception\NotFoundException;
use App\Model\Media;
use App\Service\Router;

class MediaController
{
    public function getAll(): array
    {
        return ['medias' => Media::findAll()];
    }

    public function get(int $id): array
    {
        $media = Media::find($id);
        if (!$media) {
            throw new NotFoundException("Media o ID $id nie istnieje");
        }
        return ['media' => $media];
    }

    public function store(array $data, Router $router): int
    {
        if (!isset($data['src']) || empty(trim($data['src']))) {
            throw new \InvalidArgumentException("Ścieżka do pliku nie może być pusta.");
        }

        if (!isset($data['alt']) || empty(trim($data['alt']))) {
            throw new \InvalidArgumentException("Tekst alternatywny nie może być pusty.");
        }

        $media = Media::fromArray($data);
        $media->save();
        $router->redirect('/admin/media');

        return $media->getId();
    }

    public function edit(int $id): array
    {
        $media = Media::find($id);
        if (!$media) {
            throw new NotFoundException("Media o ID $id nie istnieje");
        }
        return ['media' => $media];
    }

    public function update(int $id, array $data, Router $router): void
    {
        $media = Media::find($id);
        if (!$media) {
            throw new NotFoundException("Media o ID $id nie istnieje");
        }
        $media->fill($data);
        $media->save();
        $router->redirect('/admin/media');
    }

    public function delete(int $id, Router $router): void
    {
        $media = Media::find($id);
        if (!$media) {
            throw new NotFoundException("Media o ID $id nie istnieje");
        }
        $media->delete();
        $router->redirect('/admin/media');
    }
}
