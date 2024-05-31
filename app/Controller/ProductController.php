<?php

namespace Razan\belajar\php\mvc\Controller;

class ProductController
{
    public function categories(string $productId, string $categoryId): void
    {
        echo "PRODUCT $productId, CATEGORY $categoryId";
    }
}