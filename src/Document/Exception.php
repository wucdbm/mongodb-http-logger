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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\EmbeddedDocument
 */
class Exception {

    /**
     * @var string
     * @ODM\Id
     */
    protected $id;

    /**
     * @var string
     * @ODM\Field(type="string")
     */
    protected $message;

    /**
     * @var string
     * @ODM\Field(type="string")
     */
    protected $code;

    /**
     * @var string
     * @ODM\Field(type="string")
     */
    protected $file;

    /**
     * @var int
     * @ODM\Field(type="int")
     */
    protected $line;

    /**
     * @var string
     * @ODM\Field(type="string")
     */
    protected $stackTraceString;

    /**
     * @var Trace[]
     * @ODM\EmbedMany(targetDocument=Trace::class)
     */
    protected $trace = [];

    /**
     * @var string|null
     * @ODM\Field(type="string")
     */
    protected $extraData;

    /**
     * @var \DateTime
     * @ODM\Field(type="date")
     */
    protected $date;

    /**
     * @var Exception|null
     * @ODM\EmbedOne(targetDocument=Exception::class)
     */
    protected $previous;

    /**
     * @var string
     * @ODM\Field(type="string")
     */
    protected $class;

    /**
     * @return Collection|Trace[]
     */
    public function getTrace(): Collection {
        return $this->trace;
    }

    public function addTrace(Trace $trace) {
        $this->trace->add($trace);
    }

    public function __construct() {
        $this->trace = new ArrayCollection();
        $this->date = new \DateTime();
    }

    public function getId(): string {
        return $this->id;
    }

    public function setId(string $id): self {
        $this->id = $id;

        return $this;
    }

    public function getMessage(): string {
        return $this->message;
    }

    public function setMessage(string $message): self {
        $this->message = $message;

        return $this;
    }

    public function getCode(): string {
        return $this->code;
    }

    public function setCode(string $code): self {
        $this->code = $code;

        return $this;
    }

    public function getFile(): string {
        return $this->file;
    }

    public function setFile(string $file): self {
        $this->file = $file;

        return $this;
    }

    public function getLine(): int {
        return $this->line;
    }

    public function setLine(int $line): self {
        $this->line = $line;

        return $this;
    }

    public function getStackTraceString(): string {
        return $this->stackTraceString;
    }

    public function setStackTraceString(string $stackTraceString): self {
        $this->stackTraceString = $stackTraceString;

        return $this;
    }

    public function getExtraData(): ?string {
        return $this->extraData;
    }

    public function setExtraData(?string $extraData): self {
        $this->extraData = $extraData;

        return $this;
    }

    public function getDate(): ?\DateTime {
        return $this->date;
    }

    public function setDate(\DateTime $date): self {
        $this->date = $date;

        return $this;
    }

    public function getPrevious(): ?self {
        return $this->previous;
    }

    public function setPrevious(?self $previous): self {
        $this->previous = $previous;

        return $this;
    }

    public function getClass(): string {
        return $this->class;
    }

    public function setClass(string $class): self {
        $this->class = $class;

        return $this;
    }
}
