<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;

class UserApiService
{
	private string $apiUrl;

	public function __construct(string $apiUrl)
	{
		$this->apiUrl = $apiUrl . "/users/";
	}

	public function getAPIUrl(): string
	{
		return $this->apiUrl;
	}

	public function register(string $username, string $email, string $password): array
	{
		$client = HttpClient::create();
		$response = $client->request('POST', $this->apiUrl . 'auth/sign-up', [
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
			$json = $response->toArray(false);
			if (gettype($json['message']) === 'array') {
				throw new \Exception(implode("ERR", $json['message']));
			} else {
				throw new \Exception($json['message']);
			}
		}

		return $response->toArray();
	}

	public function login(string $email, string $password): array
	{
		$client = HttpClient::create();
		$response = $client->request('POST', $this->apiUrl . 'auth/login', [
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
			$json = $response->toArray(false);
			throw new \Exception($json['message']);
		}

		return $response->toArray();
	}

	public function getUser(string $token, int $id): array
	{
		$client = HttpClient::create();
		$response = $client->request('GET', $this->apiUrl . $id, [
			'headers' => [
				'Content-Type' => 'application/json',
				'Authorization' => 'Bearer ' . $token,
			],
		]);

		$statusCode = $response->getStatusCode();
		if ($statusCode !== 200) {
			$json = $response->toArray(false);
			throw new \Exception($json['message'][0]);
		}

		return $response->toArray();
	}

	public function getAllUser(string $token): array
	{
		$client = HttpClient::create();
		$response = $client->request('GET', $this->apiUrl, [
			'headers' => [
				'Content-Type' => 'application/json',
				'Authorization' => 'Bearer ' . $token,
			],
		]);

		$statusCode = $response->getStatusCode();
		if ($statusCode !== 200) {
			$json = $response->toArray(false);
			throw new \Exception($json['message'][0]);
		}

		return $response->toArray();
	}

	public function deleteUser(string $token, int $id): array
	{
		$client = HttpClient::create();
		$response = $client->request('POST', $this->apiUrl . 'delete', [
			'headers' => [
				'Content-Type' => 'application/json',
				'Authorization' => 'Bearer ' . $token,
			],
			'json' => [
				'id' => $id,
			],
		]);

		$statusCode = $response->getStatusCode();
		if ($statusCode !== 200) {
			$json = $response->toArray(false);
			throw new \Exception($json['message'][0]);
		}

		return $response->toArray();
	}
}
