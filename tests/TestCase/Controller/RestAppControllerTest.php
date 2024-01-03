<?php

namespace CakeDDD\Test\TestCase\Controller;

use Cake\Http\ServerRequest;
use CakeDDD\Test\Fixture\Controller\FakeRestController;
use CakeDDD\Test\Fixture\Domain\Entity\FakeEntity;
use CakeDDD\Test\Fixture\View\FakeRests\json\FakeCustomResponseDto;
use PHPUnit\Framework\TestCase;

class RestAppControllerTest extends TestCase
{
    /**
     * Test one can override serialize variable name
     * @return void
     */
    public function testOverrideVarName()
    {
        $expectedValue = new FakeEntity('1', 'Some entity');

        $controller = $this->getController();
        $controller->set('someVarName', $expectedValue);
        $controller->components()->get('RestHandler')->setVarName('someVarName');

        $response = $controller->render();
        $response = json_decode((string)$response->getBody());

        self::assertEquals($expectedValue->getName(), $response->someVarName->some);
    }

    /**
     * Test response is empty when no action or settings defined
     * @return void
     */
    public function testBeforeRenderNoVars()
    {
        $controller = $this->getController();
        $response = $controller->render();
        $response = json_decode((string)$response->getBody());

        self::assertEmpty($response);
    }

    /**
     * Test response contains other serialized vars
     * @return void
     */
    public function testBeforeRenderWithExistingSerializedVars()
    {
        $expectedValue = 'Some serialized value';

        $controller = $this->getController();
        $controller->set('someSerializedVar', $expectedValue);
        $controller->viewBuilder()->setOption('serialize', ['someSerializedVar']);
        $response = $controller->render();

        $response = json_decode((string)$response->getBody());

        self::assertEquals($expectedValue, $response->someSerializedVar);
    }

    /**
     * Test response contains serialize var when assigned
     * @return void
     */
    public function testBeforeRenderWithDefaultVarName()
    {
        $expectedProperty = 'fakeRest';

        $controller = $this->getController();
        $controller->set($expectedProperty, new FakeEntity('1', 'Some entity'));

        $response = $controller->render();
        $response = json_decode((string)$response->getBody());

        self::assertObjectHasProperty($expectedProperty, $response);
    }

    /**
     * Test response contains plural serialize var when action is index
     * @return void
     */
    public function testBeforeRenderWithPluralizedName()
    {
        $expectedProperty = 'fakeRests';

        $controller = $this->getController();
        $controller->getRequest()->method('getParam')->willReturn('index');

        $controller->set($expectedProperty, new FakeEntity('1', 'Some entity'));

        $response = $controller->render();
        $response = json_decode((string)$response->getBody());

        self::assertObjectHasProperty($expectedProperty, $response);
    }

    /**
     * Test shorthand in RestAppController
     * @return void
     */
    public function testBeforeRenderShorthandSerializeValue()
    {
        $expectedValue = new FakeEntity('1', 'Some entity');

        $controller = $this->getController();
        $controller->exposeSetSerializeValue($expectedValue);

        $response = $controller->render();
        $response = json_decode((string)$response->getBody());

        self::assertEquals($expectedValue->getName(), $response->fakeRest->some);
    }

    /**
     * Test custom response DTO
     * @return void
     */
    public function testBeforeRenderWithCustomResponseDto()
    {
        $expectedValue = new \stdClass();
        $expectedValue->id = '1';
        $expectedValue->some = 'Some entity';

        $controller = $this->getController();
        $controller->components()->get('RestHandler')->setConfig('jsonResponseDto', FakeCustomResponseDto::class);

        $controller->index();

        $response = $controller->render();
        $response = json_decode((string)$response->getBody());

        self::assertEquals($expectedValue, $response->fakeRest[0]);
    }

    /**
     * Test default response DTO
     * @return void
     */
    public function testBeforeRenderWithResponseDto()
    {
        $expectedValue = new \stdClass();
        $expectedValue->id = '1';
        $expectedValue->some = 'Some entity';

        $controller = $this->getController();
        $controller->index();

        $response = $controller->render();
        $response = json_decode((string)$response->getBody());

        self::assertEquals($expectedValue, $response->fakeRest[0]);
    }

    /**
     * Get controller (subject under test)
     * @return FakeRestController
     */
    private function getController(): FakeRestController
    {
        return new FakeRestController(
            $this->createMock(ServerRequest::class)
        );
    }
}
