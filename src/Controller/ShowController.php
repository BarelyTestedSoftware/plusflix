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

    public function store(array $data, Router $router): int {
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
            $media->setSrc($data['coverImage']);
            $media->setAlt($data['title'] . ' - Cover Image');
            // TODO: Zmień na rzeczywisty zapis do bazy przez MediaController::store()
            $media->setId(1); // mockowany ID
            $show->setCoverImage($media);
        }
        
        if (!empty($data['backgroundImage'])) {
            $media = new Media();
            $media->setSrc($data['backgroundImage']);
            $media->setAlt(''); // zdjęcia dekoracyjne powinny mieć pusty alt
            // TODO: Zmień na rzeczywisty zapis do bazy przez MediaController::store()
            $media->setId(1); // mockowany ID
            $show->setBackgroundImage($media);
        }
        
        $show->save();
        $router->redirect('/admin/show/');

        return $show->getId();
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
            $media->setSrc($data['coverImage']);
            $media->setAlt($data['title'] . ' - Cover Image');
            // TODO: Zmień na rzeczywisty zapis do bazy przez MediaController::store()
            $media->setId(1); // mockowany ID
            $show->setCoverImage($media);
        }
        
        if (!empty($data['backgroundImage'])) {
            $media = new Media();
            $media->setSrc($data['backgroundImage']);
            $media->setAlt(''); // zdjęcia dekoracyjne powinny mieć pusty alt
            // TODO: Zmień na rzeczywisty zapis do bazy przez MediaController::store()
            $media->setId(1); // mockowany ID
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

    /**
     * Wyszukuj i filtruj produkcje
     * 
     * @param array $filters Tablica z filtrami:
     *   - q: zapytanie wyszukiwania (szuka w tytule)
     *   - type: typ (1=film, 2=serial)
     *   - actor: ID aktora
     *   - director: ID reżysera
     *   - year: rok produkcji
     *   - rating: minimalna ocena
     *   - genre: ID gatunku/kategorii
     */
    public function search(array $filters = []): array {
        $shows = Show::findAll();
        
        // Filtruj po zapytaniu wyszukiwania (tytuł, opis)
        if (!empty($filters['q'])) {
            $query = strtolower($filters['q']);
            $shows = array_filter($shows, function($show) use ($query) {
                return strpos(strtolower($show->getTitle()), $query) !== false;
            });
        }
        
        // Filtruj po typie (film/serial)
        if (!empty($filters['type'])) {
            $type = (int)$filters['type'];
            $shows = array_filter($shows, fn($show) => $show->getType() === $type);
        }
        
        // Filtruj po roku produkcji
        if (!empty($filters['year'])) {
            $year = (int)$filters['year'];
            $shows = array_filter($shows, function($show) use ($year) {
                $productionYear = (int)substr($show->getProductionDate(), 0, 4);
                return $productionYear === $year;
            });
        }
        
        // Filtruj po ocenie minimalnej
        if (!empty($filters['rating'])) {
            $minRating = (float)$filters['rating'];
            $shows = array_filter($shows, fn($show) => ($show->getRating() ?? 0) >= $minRating);
        }
        
        // Filtruj po aktorze
        if (!empty($filters['actor'])) {
            $actorId = (int)$filters['actor'];
            $shows = array_filter($shows, function($show) use ($actorId) {
                $actors = $show->getActors();
                return !empty(array_filter($actors, fn($a) => $a->id === $actorId));
            });
        }
        
        // Filtruj po reżyserze
        if (!empty($filters['director'])) {
            $directorId = (int)$filters['director'];
            $shows = array_filter($shows, function($show) use ($directorId) {
                $director = $show->getDirector();
                return $director && $director->id === $directorId;
            });
        }
        
        // Filtruj po gatunku/kategorii
        if (!empty($filters['genre'])) {
            $genreId = (int)$filters['genre'];
            $shows = array_filter($shows, function($show) use ($genreId) {
                $categories = $show->getCategories();
                return !empty(array_filter($categories, fn($c) => $c->getId() === $genreId));
            });
        }
        
        // Reindeksuj tablicę
        $shows = array_values($shows);
        
        return ['shows' => $shows];
    }
}