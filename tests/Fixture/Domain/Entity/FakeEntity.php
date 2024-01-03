<?php

namespace CakeDDD\Test\Fixture\Domain\Entity;

/**
 * Fake entity
 */
class FakeEntity
{
    public function __construct(
        private readonly string $id,
        private readonly string $name
    ) {}

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}