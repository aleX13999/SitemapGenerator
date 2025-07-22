<?php

namespace App\Infrastructure\SitemapGenerator;

use App\Application\Directory\DirectoryManager;
use App\Application\Directory\Exception\DirectoryException;
use App\Application\SitemapGeneration\Exception\DataException;
use App\Application\SitemapGeneration\Exception\FormatException;
use App\Application\SitemapGeneration\Exception\ValidationException;
use App\Application\SitemapGeneration\Model\SitemapGenerationFormat;
use App\Application\SitemapGeneration\SitemapGenerationServiceInterface;
use App\Application\SitemapGeneration\Validator\SitemapGenerationValidator;
use App\Infrastructure\SitemapGenerator\Csv\CsvSitemapGenerator;
use App\Infrastructure\SitemapGenerator\Exception\SitemapException;
use App\Infrastructure\SitemapGenerator\Json\JsonSitemapGenerator;
use App\Infrastructure\SitemapGenerator\Xml\XmlSitemapGenerator;

class SitemapGenerator
{
    private SitemapGenerationServiceInterface $generator;

    /** @var array<string, class-string<SitemapGenerationServiceInterface>> */
    private array $formatMap = [
        SitemapGenerationFormat::JSON => JsonSitemapGenerator::class,
        SitemapGenerationFormat::CSV => CsvSitemapGenerator::class,
        SitemapGenerationFormat::XML => XmlSitemapGenerator::class,
    ];

    /**
     * @throws FormatException
     */
    public function __construct(
        private readonly array  $pages,
        private readonly string $format,
        private readonly string $fullFilePath,
    ) {
        if (!isset($this->formatMap[$this->format])) {
            throw new FormatException("Wrong generate file format: " . $this->format);
        }

        $fileNameParts = explode('.', $this->fullFilePath);
        if (strtolower(end($fileNameParts)) !== strtolower($this->format)) {
            throw new FormatException("The file format does not match the generation format");
        }

        $generatorClass = $this->formatMap[$this->format];
        $this->generator = new $generatorClass(new DirectoryManager());
    }

    /**
     * @throws SitemapException
     */
    public function generate(): void
    {
        try {
            SitemapGenerationValidator::validatePages($this->pages);

            $this->generator->prepareOutputPath($this->fullFilePath);
            $this->generator->generate($this->pages, $this->fullFilePath);

        } catch (ValidationException|DataException|DirectoryException $e) {
            throw new SitemapException($e->getMessage(), $e->getCode());
        }
    }
}