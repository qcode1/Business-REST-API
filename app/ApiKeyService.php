<?php

namespace App;

use Illuminate\Support\Facades\Storage;

class ApiKeyService
{

    protected $appConfig;

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        try {
            $this->appConfig = json_decode(file_get_contents(config_path('json/config.auth.json')), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('JSON decoding error: ' . json_last_error_msg());
            }
        } catch (\Exception $e) {
            die('Failed to load app configuration: ' . $e->getMessage());
        }
        // print_r($this->appConfig);
    }

    public function validateKeys(string $client, string $mode, string $apiKey): bool
    {
        // Check if client exists in configuration
        // if (!isset($this->keys[$client])) {
        //     return false;
        // }

        // Search through all environments and keys
        // foreach ($this->apiKeys[$client]['API_KEY'] as $environment => $locations) {
        //     foreach ($locations as $location => $keys) {
        //         if (in_array($apiKey, array_values($keys))) {
        //             return true;
        //         }
        //     }
        // }

        return isset($this->appConfig[$client]) && $this->appConfig[$client]['API_KEY'][$mode] === $apiKey;
    }


    public function validateAppId(string $client, string $appId): bool
    {
        return isset($this->appConfig[$client]) && $this->appConfig[$client]['APP_ID'] === $appId;
    }

    // {
    //     return isset($this->keys[$client]['APP_ID']) &&
    //         $this->apiKeys[$client]['API_KEY'][$mode]['key'] === $appId;
    // }
}
