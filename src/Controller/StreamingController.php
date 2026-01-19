<?php

namespace App\Controller;

use App\Exception\NotFoundException;
use App\Model\Streaming;
use App\Model\Media;
use App\Service\Router;

class StreamingController
{
    public function getAll(): array
    {
        return ['streamings' => Streaming::findAll()];
    }

    public function get(int $id): array
    {
        $streaming = Streaming::find($id);
        if (!$streaming) {
            throw new NotFoundException("Streaming o ID $id nie istnieje");
        }
        return ['streaming' => $streaming];
    }

    public function store(array $data, Router $router): int
    {
        if (!isset($data['name']) || empty(trim($data['name']))) {
            throw new \InvalidArgumentException("Nazwa serwisu nie może być pusta.");
        }

        if (isset($data['logo_image']['src']) && isset($data['logo_image']['alt'])) {
            $media = Media::fromArray($data['logo_image']);
            $media->save();

            $data['logo_image'] = ['id' => $media->getId()];
        }

        $streaming = Streaming::fromArray($data);
        $streaming->save();
        $router->redirect('/admin/streaming');

        return $streaming->getId();
    }

    public function edit(int $id): array
    {
        $streaming = Streaming::find($id);
        if (!$streaming) {
            throw new NotFoundException("Streaming o ID $id nie istnieje");
        }
        return ['streaming' => $streaming];
    }

    public function update(int $id, array $data, Router $router): void
    {
        $streaming = Streaming::find($id);
        if (!$streaming) {
            throw new NotFoundException("Streaming o ID $id nie istnieje");
        }
        $streaming->fill($data);
        $streaming->save();
        $router->redirect('/admin/streaming');
    }

    public function delete(int $id, Router $router): void
    {
        $streaming = Streaming::find($id);
        if (!$streaming) {
            throw new NotFoundException("Streaming o ID $id nie istnieje");
        }
        $streaming->delete();
        $router->redirect('/admin/streaming');
    }
}
