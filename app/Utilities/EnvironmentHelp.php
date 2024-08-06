<?php


namespace App\Utilities;

use Illuminate\Support\Facades\Artisan;

class EnvironmentHelp
{
    public function putPermanentEnv($key, $value): void
    {
        $path = app()->environmentFilePath();

        $oldValue = env($key);
        $oldValue = preg_match('/\s/', $oldValue) ? "\"{$oldValue}\""
            : $oldValue;
        $escaped = preg_quote('=' . $oldValue, '/');
        $value = preg_match('/\s/', $value) ? "\"{$value}\"" : $value;

        file_put_contents($path, preg_replace(
            "/^{$key}{$escaped}/m",
            "{$key}={$value}",
            file_get_contents($path)
        ));
    }

    public function updateAllEnv($installerFormConfig, $environment): void
    {
        $env = ['app.name' => env('APP_NAME'), 'app.url' => env('APP_URL'), 'database.host' => env('DB_HOST'), 'database.port' => env('DB_PORT'), 'database.name' => env('DB_DATABASE'), 'database.username' => env('DB_USERNAME'), 'database.password' => env('DB_PASSWORD')];
        // dd($installerFormConfig, $environment, $env);
        $update = false;
        foreach ($installerFormConfig as $key => $config) {
            $newValue = array_get($environment, $key);
            // dd($config, $newValue, $env[$key], $key, $environment);
            if ($newValue != $env[$key]) {
                $this->putPermanentEnv($config['env_key'], $newValue);
                $update = true;
            }
        }

        if ($update == true) {
            // dd('update new env');
            Artisan::call('config:clear');
        }
        // dd('no update new env');
    }
}
