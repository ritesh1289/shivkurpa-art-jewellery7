<?php

declare(strict_types=1);

function getCategories(PDO $database): array
{
    return $database->query('SELECT id, name, slug, description FROM categories ORDER BY name')->fetchAll();
}

function getProducts(PDO $database, ?string $categorySlug = null): array
{
    $sql = 'SELECT p.id, p.name, p.slug, p.description, p.price, p.stock_quantity, p.image_path, c.name AS category_name, c.slug AS category_slug
            FROM products p LEFT JOIN categories c ON c.id = p.category_id WHERE p.is_active = 1';
    $parameters = [];

    if ($categorySlug !== null && $categorySlug !== '') {
        $sql .= ' AND c.slug = :category_slug';
        $parameters['category_slug'] = $categorySlug;
    }
    $sql .= ' ORDER BY p.created_at DESC, p.name';
    $statement = $database->prepare($sql);
    $statement->execute($parameters);

    return $statement->fetchAll();
}

function getProductBySlug(PDO $database, string $slug): ?array
{
    $statement = $database->prepare(
        'SELECT p.id, p.name, p.slug, p.description, p.price, p.stock_quantity, p.image_path, c.name AS category_name, c.slug AS category_slug
         FROM products p LEFT JOIN categories c ON c.id = p.category_id
         WHERE p.slug = :slug AND p.is_active = 1 LIMIT 1'
    );
    $statement->execute(['slug' => $slug]);
    $product = $statement->fetch();

    return $product === false ? null : $product;
}
