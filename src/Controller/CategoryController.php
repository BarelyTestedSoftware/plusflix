<?php
namespace App\Controller;

use App\Exception\NotFoundException;
use App\Model\Category;
use App\Service\Router;

class CategoryController
{
    public function getAll(): array
    {
        return ['categories' => Category::findAll()];
    }

    public function get(int $id): array
    {
        $category = Category::find($id);
        if (!$category) {
            throw new NotFoundException("Kategoria o ID $id nie istnieje");
        }
        return ['category' => $category];
    }

    public function store(array $data, Router $router): void
    {
        $category = Category::fromArray($data);
        // @todo: walidacja
        $category->save();
        $router->redirect('/category');
    }

    public function edit(int $id): array
    {
        $category = Category::find($id);
        if (!$category) {
            throw new NotFoundException("Kategoria o ID $id nie istnieje");
        }
        return ['category' => $category];
    }

    public function update(int $id, array $data, Router $router): void
    {
        $category = Category::find($id);
        if (!$category) {
            throw new NotFoundException("Kategoria o ID $id nie istnieje");
        }
        $category->fill($data);
        // @todo: walidacja
        $category->save();
        $router->redirect('/category');
    }

    public function delete(int $id, Router $router): void
    {
        $category = Category::find($id);
        if (!$category) {
            throw new NotFoundException("Kategoria o ID $id nie istnieje");
        }
        $category->delete();
        $router->redirect('/category');
    }
}
