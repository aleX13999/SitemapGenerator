<?php

namespace App\Application\SitemapGeneration\Enum;

enum ChangeFreqEnum: string
{
    case HOURLY  = 'hourly';
    case DAILY   = 'daily';
    case WEEKLY  = 'weekly';
    case MONTHLY = 'monthly';
    case YEARLY  = 'yearly';
    case NEVER   = 'never';
}