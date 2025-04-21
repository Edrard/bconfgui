<?php

namespace App\Logging;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\RotatingFileHandler;
use Symfony\Component\Console\Output\ConsoleOutput;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class GroupLogger
{
    protected static string $mode = 'file'; // file, console, html
    protected static string $group = 'default';
    protected static bool $debug = True;
    protected static string $mark = '';
    protected static int $rotate = 30;

    public static function init(string $group, string $mode = 'file', int $rotate = 30, $debug = True): void
    {
        static::$group = $group;
        static::$mode = $mode;
        static::$debug = $debug;
        static::initMark();
        static::$rotate = $rotate;

    }
    public static function initMark(string $mark = ''): void
    {
        static::$mark = $mark ? $mark : now()->format('Y-m-d-H-i');
    }
    public static function getMark(): string
    {
        return static::$mark;
    }
    public static function log(string $message, string $level = 'info'): void
    {
        switch (static::$mode) {
            case 'console':
            $output = new ConsoleOutput();
            $color = match ($level) {
                'debug'     => '32', // зелений
                'info'      => '36', // блакитний
                'notice'    => '34', // синій
                'warning'   => '33', // жовтий
                'error'     => '31', // червоний
                'critical'  => '35', // фіолетовий
                'alert'     => '97', // білий яскравий
                'emergency' => '41', // червоний фон
                default     => '0',  // без кольору
            };
            $coloredMessage = "\033[{$color}m[{$level}]: {$message}\033[0m";
            $output->writeln($coloredMessage);
            break;

            case 'html':
            $color = match ($level) {
                'debug'     => '#3c763d',
                'info'      => '#31708f',
                'notice'    => '#31708f',
                'warning'   => '#8a6d3b',
                'error'     => '#a94442',
                'critical'  => '#a94442',
                'alert'     => '#000000',
                'emergency' => '#ffffff;background:#a94442',
                default     => '#333',
            };
            echo "<pre style='margin:0;color:{$color};background:#f0f0f0;padding:5px;border-left:3px solid #888;'>[{$level}]: {$message}</pre>";
            break;

            case 'file':
            default:
                static::logToFile($message, $level);
                break;
        }
    }

    protected static function logToFile(string $message, string $level): void
    {
        if(!static::$mark)
        {
            static::initMark();
        }

        $logger = new Logger("Bconf");

        $logDir = storage_path("logs/".static::$group);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0775, true);
        }
        static::rotateFiles($logDir);
        // Загальний лог
        $mainLogPath = "{$logDir}/info_".static::$mark.".log";
        if(static::$debug)
        {
            $logger->pushHandler(new StreamHandler($mainLogPath, Logger::DEBUG));
        }else{
            $logger->pushHandler(new StreamHandler($mainLogPath, Logger::INFO));
        }
        // Лог тільки з WARNING і вище
        $warningLogPath = "{$logDir}/error_".static::$mark.".log";
        $logger->pushHandler(new StreamHandler($warningLogPath, Logger::WARNING));

        $logger->{$level}($message);
    }
    protected static function rotateFiles(string $path): void
    {
        $files = collect(File::files($path))
        ->sortBy(fn($file) => $file->getMTime())
        ->filter(fn($file) => Str::endsWith($file->getFilename(), '.log'))
        ->values();
        foreach($files as $file){
            if(now()->createFromTimestamp($file->getCTime())->diffInDays(now()) > static::$rotate){
                File::delete($file->getRealPath());
            }
        }
    }
    public static function emergency(string $message): void
    {
        static::log($message,'emergency');
    }
    public static function alert(string $message): void
    {
        static::log($message,'alert');
    }
    public static function critical(string $message): void
    {
        static::log($message,'critical');
    }
    public static function error(string $message): void
    {
        static::log($message,'error');
    }
    public static function warning(string $message): void
    {
        static::log($message,'warning');
    }
    public static function notice(string $message): void
    {
        static::log($message,'notice');
    }
    public static function info(string $message): void
    {
        static::log($message,'info');
    }
    public static function debug(string $message): void
    {
        static::log($message,'debug');
    }
}
