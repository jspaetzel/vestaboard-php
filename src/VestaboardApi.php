<?php

namespace Vestaboard;

/**
 * Wrapper for the Vestaboard api
 * https://swagger.vestaboard.com/docs/vestaboard
 */
class VestaboardApi
{
    private const BASE_URL = 'https://platform.vestaboard.com/v2.0/';
    private const API_KEY_HEADER_NAME = 'X-Vestaboard-Api-Key';
    private const API_SECRET_HEADER_NAME = 'X-Vestaboard-Api-Secret';

    public const CHAR_MAP = [
        ' ' => 0,
        'A' => 1,
        'B' => 2,
        'C' => 3,
        'D' => 4,
        'E' => 5,
        'F' => 6,
        'G' => 7,
        'H' => 8,
        'I' => 9,
        'J' => 10,
        'K' => 11,
        'L' => 12,
        'M' => 13,
        'N' => 14,
        'O' => 15,
        'P' => 16,
        'Q' => 17,
        'R' => 18,
        'S' => 19,
        'T' => 20,
        'U' => 21,
        'V' => 22,
        'W' => 23,
        'X' => 24,
        'Y' => 25,
        'Z' => 26,
        '1' => 27,
        '2' => 28,
        '3' => 29,
        '4' => 30,
        '5' => 31,
        '6' => 32,
        '7' => 33,
        '8' => 34,
        '9' => 35,
        '0' => 36,
        '!' => 37,
        '@' => 38,
        '#' => 39,
        '$' => 40,
        '(' => 41,
        ')' => 42,
        '-' => 44,
        '+' => 46,
        '&' => 47,
        '=' => 48,
        ';' => 49,
        ':' => 50,
        '\'' => 52,
        '"' => 53,
        '%' => 54,
        ',' => 55,
        '.' => 56,
        '/' => 59,
        '?' => 60,
        '°' => 62,
        '🟥' => 63,
        '🟧' => 64,
        '🟨' => 65,
        '🟩' => 66,
        '🟦' => 67,
        '🟪' => 68,
        '⬜' => 69,
    ];

    private string $api_key;
    private string $secret;
    private string $subscription_id;

    public function __construct(string $api_key, string $secret, string $subscription_id)
    {
        $this->api_key = $api_key;
        $this->secret = $secret;
        $this->subscription_id = $subscription_id;
    }

    public static function linesToCharacters(array $lines): array
    {
        $characters = [];
        foreach($lines as $line) {
            $line = strtoupper($line);
            $line_of_characters = [];
            foreach (str_split($line) as $character) {
                $char = self::CHAR_MAP[$character];
                $line_of_characters[] = $char;
            }
            $characters[] = $line_of_characters;
        }
        return $characters;
    }

    private function request(string $uri, string $method = 'POST', ?array $body = null): bool|string
    {
        $c = curl_init();

        curl_setopt($c, CURLOPT_TIMEOUT, 4);
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($c, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            self::API_KEY_HEADER_NAME . ':' . $this->api_key,
            self::API_SECRET_HEADER_NAME . ':' . $this->secret,
        ]);

        if ('POST' === $method && isset($body)) {
            $payload = json_encode($body);
            curl_setopt($c, CURLOPT_POSTFIELDS, $payload);
        }

        $curl_url = self::BASE_URL . $uri;
        curl_setopt($c, CURLOPT_URL, $curl_url);

        $info = curl_getinfo($c);
        $response = curl_exec($c);
        curl_close($c);
        var_dump($response);
        return $response;
    }

    public function updateWithText(string $text): void
    {
        $this->postMessage([
            'text' => $text,
        ]);
    }

    public function updateWithCharacters(array $characters): void
    {
        $this->postMessage([
            'characters' => $characters
        ]);
    }

    private function postMessage(array $body): void
    {
        $subscription_id = $this->subscription_id;
        $uri = "subscriptions/{$subscription_id}/message";

        $this->request($uri, 'POST', $body);
    }
}
