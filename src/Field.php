<?php declare(strict_types=1);

namespace Acelot\AutoMapper;

final class Field
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var DefinitionInterface
     */
    protected $definition;

    /**
     * @param string              $name
     * @param DefinitionInterface $definition
     *
     * @return Field
     */
    public static function create(string $name, DefinitionInterface $definition): Field
    {
        return new Field($name, $definition);
    }

    /**
     * @param string              $name
     * @param DefinitionInterface $definition
     */
    public function __construct(string $name, DefinitionInterface $definition)
    {
        $this->name = $name;
        $this->definition = $definition;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Field
     */
    public function withName(string $name): Field
    {
        $clone = clone $this;
        $clone->name = $name;
        return $clone;
    }

    /**
     * @return DefinitionInterface
     */
    public function getDefinition(): DefinitionInterface
    {
        return $this->definition;
    }

    /**
     * @param DefinitionInterface $definition
     *
     * @return Field
     */
    public function withDefinition(DefinitionInterface $definition): Field
    {
        $clone = clone $this;
        $clone->definition = $definition;
        return $clone;
    }
}