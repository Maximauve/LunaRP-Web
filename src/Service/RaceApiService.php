<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;

class RaceApiService
{
	private string $apiUrl;

	public function __construct(string $apiUrl)
	{
		$this->apiUrl = $apiUrl . "/races/";
	}

	public function getAPIUrl(): string
	{
		return $this->apiUrl;
	}

	public function create(string $token, array $race): array
	{
		$client = HttpClient::create();
		$response = $client->request('POST', $this->apiUrl . 'create', [
			'headers' => [
				'Content-Type' => 'application/json',
				'Authorization' => 'Bearer ' . $token,
			],
			'json' => $race,
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

	public function get(string $token, int $id): array
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
			if (gettype($json['message']) === 'array') {
				throw new \Exception(implode("ERR", $json['message']));
			} else {
				throw new \Exception($json['message']);
			}
		}

		return $response->toArray();
	}

	public function getAll(string $token): array
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
			if (gettype($json['message']) === 'array') {
				throw new \Exception(implode("ERR", $json['message']));
			} else {
				throw new \Exception($json['message']);
			}
		}

		return $response->toArray();
	}

	public function update(string $token, array $race)
	{
		$client = HttpClient::create();
		$response = $client->request('POST', $this->apiUrl . "update", [
			'headers' => [
				'Content-Type' => 'application/json',
				'Authorization' => 'Bearer ' . $token,
			],
			'json' => $race,
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

	public function delete(string $token, int $id): array
	{
		$client = HttpClient::create();
		$response = $client->request('POST', $this->apiUrl . "delete", [
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
			if (gettype($json['message']) === 'array') {
				throw new \Exception(implode("ERR", $json['message']));
			} else {
				throw new \Exception($json['message']);
			}
		}

		return $response->toArray();
	}
}
