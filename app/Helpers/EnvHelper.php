<?php

namespace App\Helpers;

class EnvHelper
{

    public static function createEnv(string $path, array $data) {
        $lines = [];

        foreach ($data as $key => $value) {
            if (preg_match('/\s|[#\$]/', $value)) {
                $value = '"' . addcslashes($value, '"') . '"';
            }

            $lines[] = "{$key}={$value}";
        }
        $content = implode(PHP_EOL, $lines) . PHP_EOL;

        file_put_contents($path, $content);
    }
    public static function parseEnvFile(string $file): array
    {
        if (!file_exists($file)) {
            throw new \InvalidArgumentException("Can not find file: {$file}");
        }
        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $data = [];

        foreach ($lines as $line) {
            $line = trim($line);

            // Пропускаємо коментарі
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            // Парсимо перший "="
            [$key, $value] = array_pad(explode('=', $line, 2), 2, null);

            if ($key !== null && $value !== null) {
                $key = trim($key);
                $value = trim($value);
                if (
                (str_starts_with($value, '"') && str_ends_with($value, '"')) ||
                (str_starts_with($value, "'") && str_ends_with($value, "'"))
                ) {
                    $value = substr($value, 1, -1);
                }
                $data[$key] = $value;
            }
        }

        return $data;
    }
}
