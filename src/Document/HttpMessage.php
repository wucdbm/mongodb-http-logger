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
class HttpMessage {

    public const ID_URL_ENCODED = 1;
    public const ID_HTML = 2;
    public const ID_XML = 3;
    public const ID_JSON = 4;
    public const ID_TEXT_PLAIN = 5;

    /**
     * @var string
     * @ODM\Id
     */
    private $id;

    /**
     * @var string|null
     * @ODM\Field(type="string")
     */
    private $content;

    /**
     * @var array|null
     * @ODM\Field(type="hash")
     */
    private $headers = [];

    /**
     * @var int
     * @ODM\Field(type="int")
     */
    private $contentType = self::ID_TEXT_PLAIN;

    /**
     * @var \DateTime
     * @ODM\Field(type="date")
     */
    private $date;

    public function getContentTypeString(): string {
        switch ($this->contentType) {
            case self::ID_URL_ENCODED:
                return 'URL Encoded';
            case self::ID_HTML:
                return 'HTML';
            case self::ID_XML:
                return 'XML';
            case self::ID_JSON:
                return 'JSON';
            case self::ID_TEXT_PLAIN:
                return 'Plain Text';
            default:
                return 'Unknown';
        }
    }

    public function __construct() {
        $this->date = new \DateTime();
    }

    public function getId(): ?string {
        return $this->id;
    }

    public function setId(string $id): self {
        $this->id = $id;

        return $this;
    }

    public function getContent(): ?string {
        return $this->content;
    }

    public function setContent(?string $content): self {
        $this->content = $content;

        return $this;
    }

    public function getHeaders(): ?array {
        return $this->headers;
    }

    public function setHeaders(array $headers): self {
        $this->headers = $headers;

        return $this;
    }

    public function getContentType(): int {
        return $this->contentType;
    }

    public function setContentType(int $contentType): self {
        $this->contentType = $contentType;

        return $this;
    }

    public function getDate(): \DateTime {
        return $this->date;
    }

    public function setDate(\DateTime $date): self {
        $this->date = $date;

        return $this;
    }
}
