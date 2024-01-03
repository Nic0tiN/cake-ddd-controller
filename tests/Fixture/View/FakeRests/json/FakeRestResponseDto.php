<?php

namespace CakeDDD\Test\Fixture\View\FakeRests\json;

use CakeDDD\Test\Fixture\Domain\Entity\FakeEntity;
use JsonSerializable;

/**
 * Response DTO compliant with naming convention
 */
class FakeRestResponseDto implements JsonSerializable
{
    public function __construct(
        private readonly FakeEntity $entity
    ){}

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->entity->getId(),
            'some' => $this->entity->getName()
        ];
    }
}