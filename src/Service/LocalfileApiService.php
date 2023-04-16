<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;

class LocalfileApiService
{
	private string $apiUrl;

	public function __construct(string $apiUrl)
	{
		$this->apiUrl = $apiUrl . "/local-files/";
	}

	public function getAPIUrl(): string
	{
		return $this->apiUrl;
	}

    public function getImage(int $id): string
    {
		return $this->apiUrl  . $id;
	}
}