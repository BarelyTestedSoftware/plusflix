<?php

namespace App\Controller;

use App\Exception\NotFoundException;
use App\Model\Show;
use App\Model\Media;
use App\Service\Router;

class ShowController
{
    public function getAll(): array {
        return ['shows' => Show::findAll()];
    }

    public function get(int $id): array {
        $show = Show::find($id);
        if (!$show) {
            throw new NotFoundException("Produkcja o ID $id nie istnieje");
        }
        return ['show' => $show];
    }

    public function store(array $data, Router $router): void {
        if (empty($data['title'])) {
            throw new \InvalidArgumentException("Tytuł nie może być pusty.");
        }

        if (isset($data['type']) && !in_array((int)$data['type'], [1, 2])) {
            throw new \InvalidArgumentException("Nieprawidłowy typ produkcji.");
        }

        $show = Show::fromArray($data);
        
        // Obsługa zdjęć
        if (!empty($data['coverImage'])) {
            $media = new Media();
            $media->src = $data['coverImage'];
            $media->alt = $data['title'] . ' - Cover Image';
            // TODO: Zmień na rzeczywisty zapis do bazy przez MediaController::store()
            $media->id = 1; // mockowany ID
            $show->setCoverImage($media);
        }
        
        if (!empty($data['backgroundImage'])) {
            $media = new Media();
            $media->src = $data['backgroundImage'];
            $media->alt = ''; // zdjęcia dekoracyjne powinny mieć pusty alt
            // TODO: Zmień na rzeczywisty zapis do bazy przez MediaController::store()
            $media->id = 1; // mockowany ID
            $show->setBackgroundImage($media);
        }
        
        $show->save();
        $router->redirect('/admin/show/');
    }

    public function edit (int $id): array {
        $show = Show::find($id);
        if (!$show) {
            throw new NotFoundException("Produkcja o ID $id nie istnieje");
        }
        return ['show' => $show];
    }

    public function update (int $id, array $data, Router $router): void {
        $show = Show::find($id);
        if (!$show) {
            throw new NotFoundException("Produkcja o ID $id nie istnieje");
        }
        
        $show->fill($data);
        // @todo: walidacja
        if (isset($data['title']) && empty($data['title'])) {
            throw new \InvalidArgumentException("Podczas edycji tytuł nie może byc pusty.");
        }
        
        // Obsługa zdjęć
        if (!empty($data['coverImage'])) {
            $media = new Media();
            $media->src = $data['coverImage'];
            $media->alt = $data['title'] . ' - Cover Image';
            // TODO: Zmień na rzeczywisty zapis do bazy przez MediaController::store()
            $media->id = 1; // mockowany ID
            $show->setCoverImage($media);
        }
        
        if (!empty($data['backgroundImage'])) {
            $media = new Media();
            $media->src = $data['backgroundImage'];
            $media->alt = ''; // zdjęcia dekoracyjne powinny mieć pusty alt
            // TODO: Zmień na rzeczywisty zapis do bazy przez MediaController::store()
            $media->id = 1; // mockowany ID
            $show->setBackgroundImage($media);
        }

        $show->save();
        $router->redirect('/admin/show/');
    }

    public function delete (int $id, Router $router): void {
        $show = Show::find($id);
        if (!$show) {
            throw new NotFoundException("Produkcja o ID $id nie istnieje");
        }
        $show->delete();
        $router->redirect('/admin/show/');
    }
}