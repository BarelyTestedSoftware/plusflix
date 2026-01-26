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

        $streaming = new Streaming();
        $streaming->setName($data['name']);

        // Obsługa logo
        if (!empty($data['logo_image']['src'])) {
            $logoMedia = new Media();
            $logoMedia->setSrc($data['logo_image']['src']);
            $logoMedia->setAlt($data['logo_image']['alt'] ?? $data['name'] . ' - Logo');
            $logoMedia->save();
            
            $streaming->setLogoImage($logoMedia);
        }

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
        
        if (isset($data['name'])) {
            $streaming->setName($data['name']);
        }

        // Obsługa logo
        if (!empty($data['logo_image']['src'])) {
            $logoMedia = new Media();
            $logoMedia->setSrc($data['logo_image']['src']);
            $logoMedia->setAlt($data['logo_image']['alt'] ?? $streaming->getName() . ' - Logo');
            $logoMedia->save();
            
            $streaming->setLogoImage($logoMedia);
        }

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
