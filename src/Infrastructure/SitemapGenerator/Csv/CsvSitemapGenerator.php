<?php

namespace App\Infrastructure\SitemapGenerator\Csv;

use App\Application\SitemapGeneration\SitemapGenerationServiceAbstract;
use App\Infrastructure\SitemapGenerator\Exception\SitemapException;

class CsvSitemapGenerator extends SitemapGenerationServiceAbstract
{
    /**
     * @throws SitemapException
     */
    public function generate(array $pages, string $generatePath): void
    {
        try {
            $fp = fopen($generatePath, 'w');
            if (!$fp) {
                throw new SitemapException("Unable to open sitemap file");
            }

            fputcsv($fp, ['loc', 'lastmod', 'priority', 'changefreq']);

            foreach ($pages as $page) {
                fputcsv(
                    $fp,
                    [
                        $page['loc'],
                        $page['lastmod'],
                        $page['priority'],
                        $page['changefreq'],
                    ],
                );
            }
        } finally {
            fclose($fp);
        }
    }
}