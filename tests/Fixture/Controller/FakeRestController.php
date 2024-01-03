<?php

namespace CakeDDD\Test\Fixture\Controller;

use CakeDDD\Controller\RestAppController;
use CakeDDD\Test\Fixture\Domain\Entity\FakeEntity;

/**
 * Fake controller implementing RestAppController
 */
class FakeRestController extends RestAppController {
    public function index(): void
    {
        $entities = [new FakeEntity('1', 'Some entity')];
        $this->serialize($entities);
    }

    /**
     * Publicly exposes `RestAppController::serialize`.
     * Needed for test purpose only
     * @param mixed $value
     * @return void
     */
    public function exposeSetSerializeValue(mixed $value): void
    {
        $this->serialize($value);
    }
}