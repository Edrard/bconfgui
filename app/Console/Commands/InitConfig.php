<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Helpers\EnvHelper;

class InitConfig extends Command
{
    /**
    * The name and signature of the console command.
    *
    * @var string
    */
    protected $signature = 'app:init-config';

    /**
    * The console command description.
    *
    * @var string
    */
    protected $description = 'Command description';

    /**
    * Execute the console command.
    */
    public function handle()
    {
        $env = EnvHelper::parseEnvFile(base_path('.env.example'));

        $this->info("Welcome to the configuration initialization!");

        // Запитуємо у користувача значення
        $env["APP_KEY"] = 'base64:' . base64_encode(random_bytes(32));

        $env["DB_HOST_GUI"] = $this->ask('Enter the database host for the GUI', env('DB_HOST_GUI', '127.0.0.1'));
        $env["DB_PORT_GUI"] = $this->ask('Enter the database port for the GUI', env('DB_PORT_GUI', '3306'));
        $env["DB_DATABASE_GUI"] = $this->ask('Enter the database name for the GUI', env('DB_DATABASE_GUI', 'bconfui'));
        $env["DB_USERNAME_GUI"] = $this->ask('Enter database user for GUI', env('DB_USERNAME_GUI', 'root'));
        $env["DB_PASSWORD_GUI"] = $this->secret('Enter the database password for the GUI');

        $env["DB_HOST_BCONF"] = $this->ask('Enter the database host for the Bconf App', env('DB_HOST_BCONF', '127.0.0.1'));
        $env["DB_PORT_BCONF"] = $this->ask('Enter the database port for the Bconf App', env('DB_PORT_BCONF', '3306'));
        $env["DB_DATABASE_BCONF"] = $this->ask('Enter the database name for the Bconf App', env('DB_DATABASE_BCONF', 'bconf'));
        $env["DB_USERNAME_BCONF"] = $this->ask('Enter database user for Bconf App', env('DB_USERNAME_BCONF', 'root'));
        $env["DB_PASSWORD_BCONF"] = $this->secret('Enter the database password for the Bconf App');

        $env["DOMAIN"] = $this->ask('Enter domain', env('DOMAIN', ''));

        $env["FOLDER"] = $this->ask('Enter Route prefix', env('FOLDER', ''));

        $env["HTTP"] = $this->choice(
            'Protocol HTTP or HTTPS?',
            ['http', 'https'],
            1
        );
        $env["APP_LOCALE"] = $this->choice(
            'Default Locale?',
            ['en', 'ua'],
            0
        );
        $env["APP_LOCALE_SWITCH"] = implode(',',
            $this->choice(
                'Default locale switch, can be multiple selected comma separated?',
                ['en', 'ua'],
                "0,1",
                $maxAttempts = 1,
                $allowMultipleSelections = TRUE
            )
        );
        // Записуємо у файл `.env`
        EnvHelper::createEnv(base_path('.env.test'),$env);
        dd($env);
        $this->info('.env file created successfully!');

        // Генеруємо кеш конфігурації
        $this->call('config:clear');
        $this->call('cache:clear');
        $this->info('Конфігурація оновлена!');
    }
}
