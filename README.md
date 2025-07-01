# Sitemap Generator Library

Библиотека для генерации карты сайта в различных форматах с поддержкой валидации данных.
---

## Поддерживаемые форматы

- **XML** (`xml`)
- **JSON** (`json`)
- **CSV** (`csv`)

---

## Особенности

- **Валидация данных каждой страницы**
- **Проверка соответствия формата файла и генерации**
- **Поддержка различных форматов даты**
- **Гибкая настройка путей сохранения**
- **Автоматическое создание папок при необходимости**

---

## Установка

Установите библиотеку с помощью Composer:

```bash
composer require leama/sitemap-generator
```

---

## Использование

### 1. Генерация карты сайта

```php
use App\Application\SitemapGeneration\Enum\FormatEnum;
use App\Application\SitemapGeneration\Exception\FormatException;
use App\Infrastructure\SitemapGenerator\Exception\SitemapException;
use App\Infrastructure\SitemapGenerator\SitemapGenerator;

$pages = [
    [
        'loc' => 'https://site.com/',
        'lastmod' => '2024-07-23',
        'priority' => 1.0,
        'changefreq' => 'daily'
    ],
];

try {
    $sitemapGenerator = new SitemapGenerator($pages, SitemapGenerationFormat::CSV, 'path/to/file.csv');
    $sitemapGenerator->generate();

    echo 'File was generate successfully!';

} catch (FormatException|SitemapException $e) {
    echo "Ошибка: " . $e->getMessage();
}
```

---

### 2. Проверка валидации данных

- ### *loc*

```php
use App\Application\SitemapGeneration\Enum\FormatEnum;
use App\Application\SitemapGeneration\Exception\FormatException;
use App\Infrastructure\SitemapGenerator\Exception\SitemapException;
use App\Infrastructure\SitemapGenerator\SitemapGenerator;

$pages = [
    [
        'loc' => 'site', // Неверный формат
        'lastmod' => '2024-06-29',
        'priority' => 1.0,
        'changefreq' => 'tomorrow'
    ]
];

// Инициализация...
```

### Результат:

```text
Invalid URL format in 'loc' field for page 0
```

---

- ### *lastmod*

```php
use App\Application\SitemapGeneration\Enum\FormatEnum;
use App\Application\SitemapGeneration\Exception\FormatException;
use App\Infrastructure\SitemapGenerator\Exception\SitemapException;
use App\Infrastructure\SitemapGenerator\SitemapGenerator;

$pages = [
    [
        'loc' => 'https://site.com/',
        'lastmod' => '23.07.2024', // Неверный формат
        'priority' => 1.0,
        'changefreq' => 'tomorrow'
    ]
];

// Инициализация...
```

### Результат:

```text
Invalid date format in 'lastmod' for page 0. Use YYYY-MM-DD
```

---

 - ### *priority*

```php
use App\Application\SitemapGeneration\Enum\FormatEnum;
use App\Application\SitemapGeneration\Exception\FormatException;
use App\Infrastructure\SitemapGenerator\Exception\SitemapException;
use App\Infrastructure\SitemapGenerator\SitemapGenerator;

$pages = [
    [
        'loc' => 'https://site.com/',
        'lastmod' => '2024-07-23',
        'priority' => 1.2, // Ошибка: priority должен быть от 0.0 до 1.0
        'changefreq' => 'daily'
    ]
];

// Инициализация...
```

### Результат:

```text
'priority' must be between 0.0 and 1.0 in page 0
```
---

- ### *changefreq*

Доступны следующие варианты частоты обновления:

- hourly
- daily
- weekly
- monthly
- yearly
- never

Указаны в [ChangeFreqEnum.php](src%2FApplication%2FSitemapGeneration%2FEnum%2FChangeFreqEnum.php).

```php
use App\Application\SitemapGeneration\Enum\FormatEnum;
use App\Application\SitemapGeneration\Exception\FormatException;
use App\Infrastructure\SitemapGenerator\Exception\SitemapException;
use App\Infrastructure\SitemapGenerator\SitemapGenerator;

$pages = [
    [
        'loc' => 'https://site.com/',
        'lastmod' => '2024-07-23',
        'priority' => 1.0,
        'changefreq' => 'tomorrow' // Ошибка
    ]
];

// Инициализация...
```

### Результат:

```text
Invalid 'changefreq' value in page 0. Valid values: hourly, daily, weekly, monthly, yearly, never
```

---

- ### Несоответствие указанного типа и типа в пути файла

```php
use App\Application\SitemapGeneration\Enum\FormatEnum;
use App\Application\SitemapGeneration\Exception\FormatException;
use App\Infrastructure\SitemapGenerator\Exception\SitemapException;
use App\Infrastructure\SitemapGenerator\SitemapGenerator;

$pages = [];

try {
    $sitemapGenerator = new SitemapGenerator($pages, SitemapGenerationFormat::CSV, 'path/to/file.xml');
    $sitemapGenerator->generate();

    print 'File was generate successfully!';

} catch (FormatException|SitemapException $e) {
    print $e->getMessage();
}
```

### Результат:

```text
The file format does not match the generation format
```
