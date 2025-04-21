<?php

use Illuminate\Support\Facades\Route;
use App\Models\{DevicesConfig,Config,Connect,Group,Model,Type};
use App\Logging\GroupLogger;

Route::get('/1', function () {
    GroupLogger::init('Test', mode: 'html', debug : False);
    GroupLogger::debug('Test debug');
    GroupLogger::info('Test info');
    GroupLogger::critical('Test critical');
});
