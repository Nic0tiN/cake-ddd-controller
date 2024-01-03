<?php

namespace CakeDDD\Controller;

use Cake\Controller\Controller;
use Cake\View\JsonView;

/**
 * Controller REST for JSON views.
 * Extends this class to turn your controller REST compatible and ease JSON response generation
 */
abstract class RestAppController extends Controller
{
    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('CakeDDD.RestHandler');
    }

    /**
     * Shorthand for sending value to serialization.
     * It keeps you from having to give the variable name through set: `$this->set('varName', 'value')`
     * @param mixed $value Value to serialize
     * @return void
     */
    protected function serialize(mixed $value): void
    {
        $varName = $this->components()->get('RestHandler')->getVarName();

        $this->set($varName, $value);
    }
}