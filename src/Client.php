<?php

namespace Aakif\ReqRes;

use GuzzleHttp\Utils;

class Client
{
	public const BASE_URI = 'https://reqres.in/api/';

	public const HTTP_GET = 'GET';
	public const HTTP_POST = 'POST';

	public function get(string $url, array $data = [])
	{
		return $this->request($url);
	}

	public function post(string $url, array $data = [])
	{
		return $this->request($url, $data, self::HTTP_POST);
	}

	protected function request($url, array $data = [], $method = self::HTTP_GET)
	{
		$client = new \GuzzleHttp\Client([
			'base_uri' => self::BASE_URI,
		]);

		$options = [
			'headers' => [
				'Content-Type' => 'application/json',
				'Accept' => 'application/json',
			],
		];

		if (!empty($data))
		{
			$options['body'] = \json_encode($data);
		}

		try
		{
			$response = $client->request($method, $url, $options);
		}
		catch (\Exception $e)
		{
			return false;
		}

		if ($response->getStatusCode() === 304 ||
			$response->getStatusCode() === 400 ||
			$response->getStatusCode() === 401 ||
			$response->getStatusCode() === 403
		)
		{
			return false;
		}

		$body = trim($response->getBody()->getContents());

		try
		{
			$body = Utils::jsonDecode($body, true, 512, JSON_THROW_ON_ERROR);
		}
		catch (\Exception $e)
		{
			return false;
		}

		return $body['data'] ?? $body;
	}
}