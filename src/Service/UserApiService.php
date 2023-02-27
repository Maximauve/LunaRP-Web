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

	public function register(string $username, string $email, string $password): array
	{
		$client = HttpClient::create();
		$response = $client->request('POST', $this->apiUrl . '/users/auth/sign-up', [
			'headers' => [
				'Content-Type' => 'application/json',
			],
			'json' => [
				'username' => $username,
				'email' => $email,
				'password' => $password,
			],
		]);

		$statusCode = $response->getStatusCode();
		if ($statusCode !== 201) {
			throw new \Exception($response->toArray()['message']);
		}

		return $response->toArray();
	}

	public function login(string $email, string $password): array
	{
		$client = HttpClient::create();
		$response = $client->request('POST', $this->apiUrl . '/users/auth/login', [
			'headers' => [
				'Content-Type' => 'application/json',
			],
			'json' => [
				'email' => $email,
				'password' => $password,
			],
		]);

		$statusCode = $response->getStatusCode();
		if ($statusCode !== 201) {
			throw new \Exception($response->toArray()['message']);
		}

		return $response->toArray();
	}

	public function getUser(int $id): array
	{
		$client = HttpClient::create();
		$response = $client->request('GET', $this->apiUrl . '/users/' . $id);

		$statusCode = $response->getStatusCode();
		if ($statusCode !== 200) {
			throw new \Exception($response->toArray()['message']);
		}

		return $response->toArray();
	}
}
