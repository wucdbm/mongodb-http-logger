<?php

/*
 * This file is part of the Wucdbm HttpLogger package.
 *
 * Copyright (c) Martin Kirilov.
 *
 * Author Martin Kirilov <martin@forci.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Wucdbm\Component\MongoDBHttpLogger\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\EmbeddedDocument
 */
class Trace {

    /**
     * @var string
     * @ODM\Id
     */
    protected $id;

    /**
     * @var string|null
     * @ODM\Field(type="string")
     */
    protected $namespace;

    /**
     * @var string|null
     * @ODM\Field(type="string")
     */
    protected $shortClass;

    /**
     * @var string|null
     * @ODM\Field(type="string")
     */
    protected $class;

    /**
     * @var string|null
     * @ODM\Field(type="string")
     */
    protected $type;

    /**
     * @var string|null
     * @ODM\Field(type="string")
     */
    protected $function;

    /**
     * @var string|null
     * @ODM\Field(type="string")
     */
    protected $file;

    /**
     * @var string|null
     * @ODM\Field(type="string")
     */
    protected $line;

    /**
     * @var array|null
     * @ODM\Field(type="hash")
     */
    protected $args;

    public function getId(): string {
        return $this->id;
    }

    public function setId(string $id): self {
        $this->id = $id;

        return $this;
    }

    public function getNamespace(): ?string {
        return $this->namespace;
    }

    public function setNamespace(?string $namespace): self {
        $this->namespace = $namespace;

        return $this;
    }

    public function getShortClass(): ?string {
        return $this->shortClass;
    }

    public function setShortClass(?string $shortClass): self {
        $this->shortClass = $shortClass;

        return $this;
    }

    public function getClass(): ?string {
        return $this->class;
    }

    public function setClass(?string $class): self {
        $this->class = $class;

        return $this;
    }

    public function getType(): ?string {
        return $this->type;
    }

    public function setType(?string $type): self {
        $this->type = $type;

        return $this;
    }

    public function getFunction(): ?string {
        return $this->function;
    }

    public function setFunction(?string $function): self {
        $this->function = $function;

        return $this;
    }

    public function getFile(): ?string {
        return $this->file;
    }

    public function setFile(?string $file): self {
        $this->file = $file;

        return $this;
    }

    public function getLine(): ?string {
        return $this->line;
    }

    public function setLine(?string $line): self {
        $this->line = $line;

        return $this;
    }

    public function getArgs(): ?string {
        return $this->args;
    }

    public function setArgs(?array $args): self {
        $this->args = $args;

        return $this;
    }
}
