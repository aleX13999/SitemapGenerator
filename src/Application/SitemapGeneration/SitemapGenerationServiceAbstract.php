<?php

namespace App\Application\SitemapGeneration;

use App\Application\SitemapGeneration\Exception\DirectoryException;

abstract class SitemapGenerationServiceAbstract implements SitemapGenerationServiceInterface
{
    /**
     * @throws DirectoryException
     */
    protected function ensureDirectoryExists(string $path): void
    {
        $directory = dirname($path);

        if (is_dir($directory)) {
            return;
        }

        if (!@mkdir($directory, 0777, true)) {
            // Проверяем race condition
            if (!is_dir($directory)) {
                throw new DirectoryException("Не удалось создать папку: " . $directory);
            }
        }

        @chmod($directory, 0777);
    }

    abstract public function generate(array $pages, string $generatePath): void;
}
