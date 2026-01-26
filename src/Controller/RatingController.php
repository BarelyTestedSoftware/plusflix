<?php

namespace App\Controller;

use App\Exception\NotFoundException;
use App\Model\Rating;
use App\Service\Router;

class RatingController
{
    public function getAll(): array
    {
        return ['ratings' => Rating::findAll()];
    }

    public function get(int $id): array
    {
        $rating = Rating::find($id);
        if (!$rating) {
            throw new NotFoundException("Ocena o ID $id nie istnieje");
        }
        return ['rating' => $rating];
    }

    public function store(array $data, Router $router): int
    {
        if (!isset($data['value']) || !is_numeric($data['value']) || $data['value'] < 1 || $data['value'] > 5) {
            throw new \InvalidArgumentException("Wartość oceny musi być liczbą od 1 do 5.");
        }

        if (!isset($data['show_id']) || !is_numeric($data['show_id'])) {
            throw new \InvalidArgumentException("Nieprawidłowy identyfikator serialu.");
        }

        $rating = Rating::fromArray($data);
        $rating->save();
        $router->redirect('/admin/rating');

        return $rating->getId();
    }

    public function edit(int $id): array
    {
        $rating = Rating::find($id);
        if (!$rating) {
            throw new NotFoundException("Ocena o ID $id nie istnieje");
        }
        return ['rating' => $rating];
    }

    public function update(int $id, array $data, Router $router): void
    {
        $rating = Rating::find($id);
        if (!$rating) {
            throw new NotFoundException("Ocena o ID $id nie istnieje");
        }

        if (isset($data['value']) && (!is_numeric($data['value']) || $data['value'] < 1 || $data['value'] > 5)) {
        throw new \InvalidArgumentException("Wartość oceny musi być liczbą od 1 do 5.");
        }

        $rating->fill($data);
        $rating->save();
        $router->redirect('/admin/rating');
    }

    public function delete(int $id, Router $router): void
    {
        $rating = Rating::find($id);
        if (!$rating) {
            throw new NotFoundException("Ocena o ID $id nie istnieje");
        }
        $rating->delete();
        $router->redirect('/admin/rating');
    }
}