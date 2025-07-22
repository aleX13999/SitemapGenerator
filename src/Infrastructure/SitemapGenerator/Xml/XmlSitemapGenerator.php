<?php

namespace App\Infrastructure\SitemapGenerator\Xml;

use App\Application\SitemapGeneration\SitemapGenerationServiceAbstract;
use App\Infrastructure\SitemapGenerator\Exception\SitemapException;
use DOMDocument;
use SimpleXMLElement;

class XmlSitemapGenerator extends SitemapGenerationServiceAbstract
{
    /**
     * @throws SitemapException
     */
    public function generate(array $pages, string $generatePath): void
    {
        try {
            $xml = new SimpleXMLElement(
                '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
</urlset>',
            );

            foreach ($pages as $page) {
                $url = $xml->addChild('url');
                $url->addChild('loc', htmlspecialchars($page['loc']));
                $url->addChild('lastmod', $page['lastmod']);
                $url->addChild('priority', $page['priority']);
                $url->addChild('changefreq', $page['changefreq']);
            }

            $dom = new DOMDocument('1.0', 'UTF-8');
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            $dom->loadXML($xml->asXML());
            $formattedXml = $dom->saveXML();

            if (!file_put_contents($generatePath, $formattedXml) !== false) {
                throw new SitemapException('Error writing to ' . $generatePath);
            }

        } catch (\Exception $e) {
            throw new SitemapException($e->getMessage());
        }
    }
}