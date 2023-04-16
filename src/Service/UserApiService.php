<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;

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

	public function register(FormDataPart $formData): array
	{
		$client = HttpClient::create();
		$response = $client->request('POST', $this->apiUrl . 'auth/sign-up', [
			'headers' => $formData->getPreparedHeaders()->toArray(),
			'body' => $formData->bodyToIterable(),
		]);

		$statusCode = $response->getStatusCode();
		if ($statusCode !== 201) {
			$json = $response->toArray(false);
			// $errStr = "";
			// foreach ($json as $k => $v) {
			// 	$errStr .= $k . " => " . $v . "kwak";
			// }
			// throw new \Exception($errStr);
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

	public function getUser(string $jwt, int $id): User
	{
		$client = HttpClient::create();
		$response = $client->request('GET', $this->apiUrl . $id, [
			'headers' => [
				'Authorization' => 'Bearer ' . $jwt,
			]
		]);

		$statusCode = $response->getStatusCode();
		if ($statusCode !== 200) {
			$json = $response->toArray(false);
			throw new \Exception($json['message'][0]);
		}

		$user = $response->toArray();
		if (isset($user['userId'])) {
			$img = "/local-files/" . $user['userId'];
		} else {
			$img = null;
		}

		return new User($user['id'], $user['username'], $user['email'], $jwt, $img);
	}

	public function UpdateUser(string $jwt, FormDataPart $formData)
	{
		$client = HttpClient::create();
		$headers = $formData->getPreparedHeaders()->toArray();
		$headers[] = 'Authorization: Bearer ' . $jwt;
		$response = $client->request('POST', $this->apiUrl . "update", [
			'headers' => $headers,
			'body' => $formData->bodyToIterable(),
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
}
