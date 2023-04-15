<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;

class ClassApiService
{
	private string $apiUrl;

	public function __construct(string $apiUrl)
	{
		$this->apiUrl = $apiUrl . "/class/";
	}

	public function getAPIUrl(): string
	{
		return $this->apiUrl;
	}

	public function createClass(string $token, array $class): array
	{
		$client = HttpClient::create();
		$response = $client->request('POST', $this->apiUrl . 'create', [
			'headers' => [
				'Content-Type' => 'application/json',
			],
			'Authorization' => 'Bearer ' . $token,
			'body' => $class,
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

	public function getClass(string $token, int $id): array
	{
		$client = HttpClient::create();
		$response = $client->request('GET', $this->apiUrl . $id, [
			'headers' => [
				'Content-Type' => 'application/json',
			],
			'Authorization' => 'Bearer ' . $token,
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

	public function getClasses(string $token): array
	{
		$client = HttpClient::create();
		$response = $client->request('GET', $this->apiUrl, [
			'headers' => [
				'Content-Type' => 'application/json',
			],
			'Authorization' => 'Bearer ' . $token,
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

	public function UpdateClass(string $token, array $class)
	{
		$client = HttpClient::create();
		$response = $client->request('POST', $this->apiUrl . "update", [
			'headers' => [
				'Content-Type' => 'application/json',
			],
			'Authorization' => 'Bearer ' . $token,
			'body' => $class,
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

	public function deleteClass(string $token, int $id): array
	{
		$client = HttpClient::create();
		$response = $client->request('POST', $this->apiUrl . "delete", [
			'headers' => [
				'Content-Type' => 'application/json',
			],
			'Authorization' => 'Bearer ' . $token,
			'body' => [
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
