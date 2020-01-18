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
 * @ODM\EmbeddedDocument
 */
class Request extends HttpMessage {

    /**
     * @var string|null
     * @ODM\Field(type="string")
     */
    private $url;

    /**
     * @var string|null
     * @ODM\Field(type="string")
     */
    private $urlHash;

    /**
     * @var string|null
     * @ODM\Field(type="string")
     */
    private $method;

    public function getUrl(): ?string {
        return $this->url;
    }

    public function setUrl(?string $url): self {
        $this->url = $url;

        return $this;
    }

    public function getUrlHash(): ?string {
        return $this->urlHash;
    }

    public function setUrlHash(?string $urlHash): self {
        $this->urlHash = $urlHash;

        return $this;
    }

    public function getMethod(): ?string {
        return $this->method;
    }

    public function setMethod(?string $method): self {
        $this->method = $method;

        return $this;
    }
}
