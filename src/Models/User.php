<?php

namespace Aakif\ReqRes\Models;

use Aakif\ReqRes\Exceptions\NameNotValidException;

class User implements \JsonSerializable, \ArrayAccess
{
	protected int $id;
	protected string $name;

	public function __construct(int $id, string $name)
	{
		$this->setId($id);
		$this->setName($name);
	}

	public function getId(): int
	{
		return $this->id;
	}

	public function setId(int $id): User
	{
		$this->id = $id;
		return $this;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function setName(string $name): User
	{
		if (strlen($name) < 2 || strlen($name) > 25)
		{
			throw new NameNotValidException('Name must be between 2 and 25 characters');
		}

		$this->name = $name;
		return $this;
	}

	public function jsonSerialize(): array
	{
		return [
			'id' => $this->id,
			'name' => $this->name,
		];
	}

	public function offsetExists(mixed $offset): bool
	{
		return $this->offsetGet($offset) !== null;
	}

	public function offsetGet(mixed $offset): string|int
	{
		switch ($offset)
		{
			case 'id': return $this->id;
			case 'name': return $this->name;
		}

		throw new \InvalidArgumentException("Offset '$offset' was not found");
	}

	public function offsetSet(mixed $offset, mixed $value): void
	{
		switch ($offset)
		{
			case 'id':
				$this->id = $value; break;
			case 'name':
				$this->name = $value; break;
		}

		throw new \InvalidArgumentException("Offset '$offset' was not found");
	}

	public function offsetUnset(mixed $offset): void
	{
		switch ($offset)
		{
			case 'id':
				$this->id = ''; break;
			case 'name':
				$this->name = ''; break;
		}

		throw new \InvalidArgumentException("Offset '$offset' was not found");

	}
}