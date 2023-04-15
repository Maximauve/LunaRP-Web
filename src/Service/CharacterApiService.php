<?php

namespace App\Service;

use App\Exception\ApiException;
use Symfony\Component\HttpClient\HttpClient;

class CharacterApiService
{
	private string $apiUrl;

	public function __construct(string $apiUrl)
	{
		$this->apiUrl = $apiUrl;
	}

	public function getAPIUrl(): string
	{
		return $this->apiUrl;
	}

	public function getAllCharacter(string $token): array
    {
        $client = HttpClient::create();
        $response = $client->request('GET', $this->apiUrl . '/characters', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $token,
            ],
        ]);

        $statusCode = $response->getStatusCode();
        if ($statusCode !== 200) {
            $json = $response->toArray(false);
            if (gettype($json['message']) === 'array') {
                throw new \Exception(implode("ERR", $json['message']));
            } else {
                throw new \Exception($json['message']);
            }
        }

        return $response->toArray();
    }
}
