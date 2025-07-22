<?php

namespace App\Infrastructure\SitemapGenerator\Json;

use App\Application\SitemapGeneration\SitemapGenerationServiceAbstract;
use App\Infrastructure\SitemapGenerator\Exception\SitemapException;
use App\Infrastructure\SitemapGenerator\Json\Exception\JsonDataException;

class JsonSitemapGenerator extends SitemapGenerationServiceAbstract
{
    /**
     * @throws JsonDataException
     * @throws SitemapException
     */
    public function generate(array $pages, string $generatePath): void
    {
        $pages = json_encode($pages, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        if ($pages === false) {
            throw new JsonDataException('Data could not be encoded to JSON');
        }

        if (file_put_contents($generatePath, $pages) === false) {
            throw new SitemapException('Error writing to ' . $generatePath);
        }
    }
}