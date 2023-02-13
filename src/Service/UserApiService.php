<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;

class UserApiService
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

	public function getUser(int $id): array
	{
		$client = HttpClient::create();
		$response = $client->request('GET', $this->apiUrl . '/users/' . $id);

		$statusCode = $response->getStatusCode();
		if ($statusCode !== 200) {
			throw new \Exception('Error retrieving user data');
		}

		return $response->toArray();
	}
}
