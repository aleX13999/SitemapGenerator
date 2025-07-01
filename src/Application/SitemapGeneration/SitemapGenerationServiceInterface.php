<?php

namespace App\Application\SitemapGeneration;

use App\Application\SitemapGeneration\Exception\DataException;
use App\Application\SitemapGeneration\Exception\DirectoryException;

interface SitemapGenerationServiceInterface
{
    /**
     * @throws DirectoryException
     * @throws DataException
     */
    public function generate(array $pages, string $generatePath): void;
}