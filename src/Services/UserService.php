<?php

namespace Aakif\ReqRes\Services;

use Aakif\ReqRes\Client;
use Aakif\ReqRes\Exceptions\NameNotValidException;
use Aakif\ReqRes\Exceptions\UserNotCreatedException;
use Aakif\ReqRes\Exceptions\UserNotFoundException;
use Aakif\ReqRes\Models\User;

class UserService
{
	protected ?Client $client = null;

	public function __construct()
	{
		$this->client = new Client();
	}

	public function getById(int $id): ?User
	{
		$response = $this->client->get("users/$id");

		if (!$response)
		{
			throw new UserNotFoundException();
		}

		return new User($response['id'], $this->getFormattedName($response));
	}

	public function getUsers(int $page = 1): ?array
	{
		$response = $this->client->get('users', ['query' => ['page' => $page]]);

		if (!$response)
		{
			return null;
		}

		return \array_map(function ($user) {
			return new User($user['id'], $this->getFormattedName($user));
		}, $response);
	}

	public function create(string $name, string $job): int
	{
		if (strlen($name) < 2 || strlen($name) > 25)
		{
			throw new NameNotValidException('Name must be between 2 and 25 characters');
		}

		$response = $this->client->post("users", [
			'name' => $name,
			'job' => $job
		]);

		if (!$response)
		{
			throw new UserNotCreatedException('Failed to create user.');
		}

		return (new User($response['id'], $response['name']))->getId();
	}

	protected function getFormattedName(array $response)
	{
		$name = $response['first_name'];

		if (isset($response['last_name']))
		{
			$name .= ' ' . $response['last_name'];
		}

		return $name;
	}
}