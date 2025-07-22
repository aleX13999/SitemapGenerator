<?php

namespace App\Application\Directory;

use App\Application\Directory\Exception\DirectoryException;

class DirectoryManager
{
    private const PERMISSION = 0755;

    public function ensureDirectoryExists(string $path): void
    {
        $directory = dirname($path);

        if (is_dir($directory)) {
            return;
        }

        // Сохраняем старую umask
        $oldUmask = umask(0);

        if (!@mkdir($directory, self::PERMISSION, true)) {
            // Проверяем race condition
            if (!is_dir($directory)) {
                umask($oldUmask);
                throw new DirectoryException("Не удалось создать папку: " . $directory);
            }
        }

        // Восстанавливаем umask
        umask($oldUmask);

        @chmod($directory, self::PERMISSION);
    }
}