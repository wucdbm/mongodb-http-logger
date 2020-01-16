<?php

/*
 * This file is part of the Wucdbm MongoDBHttpLogger package.
 *
 * Copyright (c) Martin Kirilov.
 *
 * Author Martin Kirilov <wucdbm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Wucdbm\Component\MongoDBHttpLogger\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\MappedSuperclass
 */
class Log {

    /**
     * @var string
     * @ODM\Id
     */
    private $id;

    /**
     * @var \DateTime
     * @ODM\Field(type="date")
     */
    private $date;

    /**
     * @var string|null
     * @ODM\Field(type="string")
     */
    private $extraData;

    /**
     * @var string|null
     * @ODM\Field(type="string")
     */
    private $debug;

    /**
     * @var Request|null
     * @ODM\EmbedOne(targetDocument=Request::class)
     */
    private $request;

    /**
     * @var Response|null
     * @ODM\EmbedOne(targetDocument=Response::class)
     */
    private $response;

    /**
     * @var Exception|null
     * @ODM\EmbedOne(targetDocument=Exception::class)
     */
    private $exception;

    public function __construct() {
        $this->date = new \DateTime();
    }

    public function getId(): ?string {
        return $this->id;
    }

    public function setId(string $id) {
        $this->id = $id;

        return $this;
    }

    public function getDate(): \DateTime {
        return $this->date;
    }

    public function setDate(\DateTime $date): Log {
        $this->date = $date;

        return $this;
    }

    public function getExtraData(): ?string {
        return $this->extraData;
    }

    public function setExtraData(?string $extraData): self {
        $this->extraData = $extraData;

        return $this;
    }

    public function getDebug(): ?string {
        return $this->debug;
    }

    public function setDebug(?string $debug): self {
        $this->debug = $debug;

        return $this;
    }

    public function getRequest(): ?Request {
        return $this->request;
    }

    public function setRequest(?Request $request): self {
        $this->request = $request;

        return $this;
    }

    public function getResponse(): ?Response {
        return $this->response;
    }

    public function setResponse(?Response $response): self {
        $this->response = $response;

        return $this;
    }

    public function getException(): ?Exception {
        return $this->exception;
    }

    public function setException(?Exception $exception): self {
        $this->exception = $exception;

        return $this;
    }
}
