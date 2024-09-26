<?php

namespace Aakif\ReqResTests;

use Aakif\ReqRes\Models\User;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(User::class)]

final class UserTest extends TestCase
{
	public function testCanBeInstantiated(): void
	{
		$user = new User(1, 'John Doe');
		$this->assertInstanceOf(User::class, $user);
	}

	public function testJsonSerialize(): void
	{
		$user = new User(1, 'John Doe');

		$json = json_encode($user);

		$this->assertEquals(
			$json,
			'{"id":1,"name":"John Doe"}'
		);
	}

	public function testToArray(): void
	{
		$user = new User(1, 'John Doe');

		$array = (array) $user;

		$this->assertIsArray($array);
	}
}