<?php

namespace Aakif\ReqResTests;

use Aakif\ReqRes\Exceptions\NameNotValidException;
use Aakif\ReqRes\Models\User;
use Aakif\ReqRes\Services\UserService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(UserService::class)]
#[UsesClass(User::class)]

final class UserServiceTest extends TestCase
{
	public function testGetById(): void
	{
		$userService = new UserService();
		$user = $userService->getById(1);

		$this->assertEquals(1, $user->getId());
	}

	public function testUserNotFound(): void
	{
		$userService = new UserService();
		$user = $userService->getById(23);
		$this->assertNull($user);
	}

	public function testUserCreated(): void
	{
		$userService = new UserService();
		$userId = $userService->create('Bob', 'Builder');
		$this->assertIsInt($userId);
	}

	public function testNameTooShort(): void
	{
		$this->expectException(NameNotValidException::class);

		$userService = new UserService();
		$userService->create('B', 'Builder');
	}

	public function testNameTooLong(): void
	{
		$this->expectException(NameNotValidException::class);

		$userService = new UserService();
		$userService->create('This is too long of a name to have', 'Builder');
	}
}