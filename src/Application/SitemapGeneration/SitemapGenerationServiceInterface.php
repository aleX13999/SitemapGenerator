<?php

namespace App\Application\SitemapGeneration;

use App\Application\Directory\Exception\DirectoryException;
use App\Application\SitemapGeneration\Exception\DataException;

interface SitemapGenerationServiceInterface
{
    /**
     * @throws DirectoryException
     */
    public function prepareOutputPath(string $outputPath): void;

    /**
     * @throws DataException
     */
    public function generate(array $pages, string $generatePath): void;
}