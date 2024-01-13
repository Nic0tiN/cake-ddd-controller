<?php
declare(strict_types=1);

namespace CakeDDD\Controller\Component;

use Cake\Controller\Component;
use Cake\Event\EventInterface;
use Cake\Utility\Inflector;
use Cake\View\JsonView;

/**
 * REST Handler component
 * Turn your controller into a REST compatible controller with this component is attached.
 */
class RestHandlerComponent extends Component
{
    /**
     * @var array|null[] Configurations
     * - responseDto (string|null): Fully qualified namespace of response DTO class or null if auto.
     */
    private array $config = [
        'jsonResponseDto' => null
    ];

    /**
     * @var string|null Variable name serialized. Default: Controller name
     */
    private ?string $varName = null;

    /**
     * JsonView Option name
     */
    private const OPTION_SERIALIZE = 'serialize';

    /**
     * @inheritDoc
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->getController()->addViewClasses([JsonView::class]);
        $this->getController()->viewBuilder()->setClassName('Json');
    }

    /**
     * Serialize vars and send to DTO (if exists)
     * @param EventInterface $event
     * @return void
     */
    public function beforeRender(EventInterface $event): void
    {
        $defaultVarName = $this->getVarName();

        $serializedVars = $this->getController()->viewBuilder()->getOption(self::OPTION_SERIALIZE) ?? [];
        if ($this->getController()->viewBuilder()->hasVar($defaultVarName)) {
            $responseDto = $this->getResponseDtoPath();
            if ($responseDto !== null) {
                $values = $this->getController()->viewBuilder()->getVar($defaultVarName);

                if (is_array($values)) {
                    $values = array_map(fn ($entity) => new $responseDto($entity), $values);
                } else {
                    $values = new $responseDto($values);
                }
                // Transform through DTO
                $this->getController()->viewBuilder()->setVar($defaultVarName, $values);
            }

            if (!in_array($defaultVarName, $serializedVars)) {
                $serializedVars[] = $defaultVarName;
            }
        }

        $this->getController()->viewBuilder()->setOption(self::OPTION_SERIALIZE, $serializedVars);
    }

    /**
     * @return string|null Variable name to serialize. Defaults : Controller name (plural if action is index)
     */
    public function getVarName(): ?string
    {
        if (isset($this->varName)) {
            return $this->varName;
        }

        $variable = Inflector::variable($this->getController()->getName());

        if ($this->getController()->getRequest()->getParam('action') !== 'index') {
            $variable = Inflector::singularize($variable);
        }

        return $variable;
    }

    /**
     * @return string|null Response DTO path.
     */
    private function getResponseDtoPath(): ?string {
        if ($this->getConfig('jsonResponseDto') !== null) {
            return $this->getConfig('jsonResponseDto');
        }

        $name = Inflector::camelize($this->getController()->getName());

        $responseDtoClassName = explode('\\', get_class($this->getController()), -2);
        $responseDtoClassName = array_merge($responseDtoClassName, [
            'View',
            Inflector::pluralize($name),
            'json',
            Inflector::singularize($name) . 'ResponseDto'
        ]);
        $responseDtoClassName = implode('\\', $responseDtoClassName);

        if (class_exists($responseDtoClassName)) {
            return $responseDtoClassName;
        }

        return null;
    }

    /**
     * @param string|null $name Override default serialize variable name.
     * @return void
     */
    public function setVarName(?string $name): void
    {
        $this->varName = $name;
    }
}