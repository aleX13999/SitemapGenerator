<?php

namespace App\Application\SitemapGeneration\Validator;

use App\Application\SitemapGeneration\Enum\ChangeFreqEnum;
use App\Application\SitemapGeneration\Exception\ChangeFreqValidationException;
use App\Application\SitemapGeneration\Exception\LastmodValidationException;
use App\Application\SitemapGeneration\Exception\LocValidationException;
use App\Application\SitemapGeneration\Exception\PriorityValidationException;
use App\Application\SitemapGeneration\Exception\ValidationException;

class SitemapGenerationValidator
{
    /**
     * @throws ChangeFreqValidationException
     * @throws LastmodValidationException
     * @throws LocValidationException
     * @throws PriorityValidationException
     * @throws ValidationException
     */
    public static function validatePages(array $pages): void
    {
        foreach ($pages as $index => $page) {
            if (!is_array($page)) {
                throw new ValidationException("Page at index $index must be an array");
            }

            self::validatePage($page, $index);
        }
    }

    /**
     * @throws ChangeFreqValidationException
     * @throws LastmodValidationException
     * @throws LocValidationException
     * @throws PriorityValidationException
     */
    private static function validatePage(array $page, int $index): void
    {
        // Валидация loc
        if (!isset($page['loc'])) {
            throw new LocValidationException("Missing parameter 'loc' in page $index");
        }

        if (!is_string($page['loc'])) {
            throw new LocValidationException("Invalid 'loc' field in page $index");
        }

        if (!filter_var($page['loc'], FILTER_VALIDATE_URL)) {
            throw new LocValidationException("Invalid URL format in 'loc' field for page $index");
        }

        // Валидация lastmod
        if (!isset($page['lastmod'])) {
            throw new LastmodValidationException("Missed parameter 'lastmod' in page $index");
        }

        if (!is_string($page['lastmod'])) {
            throw new LastmodValidationException("'lastmod' must be string in page $index");
        }

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $page['lastmod'])) {
            throw new LastmodValidationException("Invalid date format in 'lastmod' for page $index. Use YYYY-MM-DD");
        }

        // Валидация priority
        if (!isset($page['priority'])) {
            throw new PriorityValidationException("Missed parameter 'priority' in page $index");
        }

        $priority = $page['priority'];

        if (!is_float($priority)) {
            throw new PriorityValidationException("'priority' must be float in page $index");
        }

        if ($priority < 0.0 || $priority > 1.0) {
            throw new PriorityValidationException("'priority' must be between 0.0 and 1.0 in page $index");
        }

        // Валидация changefreq
        if (!isset($page['changefreq'])) {
            throw new ChangeFreqValidationException("Missed parameter 'changefreq' in page $index");
        }

        if (!is_string($page['changefreq'])) {
            throw new ChangeFreqValidationException("'changefreq' must be string in page $index");
        }

        if (!ChangeFreqEnum::tryFrom($page['changefreq'])) {
            $validValues = implode(', ', array_column(ChangeFreqEnum::cases(), 'value'));
            throw new ChangeFreqValidationException(
                "Invalid 'changefreq' value in page $index. Valid values: $validValues",
            );
        }
    }
}