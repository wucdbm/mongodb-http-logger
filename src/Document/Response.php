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
class Response extends HttpMessage {

    /**
     * @var int
     * @ODM\Field(type="int")
     */
    private $statusCode;

    public function getStatusCode(): int {
        return $this->statusCode;
    }

    public function setStatusCode(int $statusCode): self {
        $this->statusCode = $statusCode;

        return $this;
    }
}
