<?php

namespace App\Application\SitemapGeneration;

use App\Application\Directory\DirectoryManager;
use App\Application\Directory\Exception\DirectoryException;

abstract class SitemapGenerationServiceAbstract implements SitemapGenerationServiceInterface
{
    public function __construct(
        private readonly DirectoryManager $directoryManager,
    ) {
    }

    /**
     * @throws DirectoryException
     */
    public function prepareOutputPath(string $outputPath): void
    {
        $this->directoryManager->ensureDirectoryExists($outputPath);
    }

    abstract public function generate(array $pages, string $generatePath): void;
}
