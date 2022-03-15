<?php

namespace IOL\Generic\v1\Content;

use IOL\Generic\v1\DataSource\Environment;
use JsonException;

class Discord
{
    /**
     * @throws JsonException
     */
    public static function sendWebhook(array $data): void
    {
        $jsonData = json_encode($data, JSON_THROW_ON_ERROR);

        $discordRequest = curl_init(Environment::get('DISCORD_WEBHOOK_URL'));
        $headers = ['Content-Type: application/json'];

        curl_setopt($discordRequest, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($discordRequest, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($discordRequest, CURLOPT_POST, true);
        curl_setopt($discordRequest, CURLOPT_POSTFIELDS, $jsonData);
        curl_exec($discordRequest);
    }
}