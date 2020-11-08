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

namespace Wucdbm\Component\MongoDBHttpLogger\Logger;

use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\ODM\MongoDB\DocumentManager;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;
use Wucdbm\Component\MongoDBHttpLogger\Document\Log;

class Logger {

    /** @var DocumentManager */
    private $dm;

    /** @var LoggerHelper */
    private $helper;

    public function __construct(ManagerRegistry $registry, string $class) {
        $dm = $registry->getManagerForClass($class);
        $this->dm = $dm;
        $this->helper = new LoggerHelper();
    }

    public function save(Log $log): void {
        $this->persist($log);
        $this->dm->flush();
    }

    protected function persist(Log $log): void {
        $this->dm->persist($log);

        if ($request = $log->getRequest()) {
            $this->dm->persist($request);
        }

        if ($response = $log->getResponse()) {
            $this->dm->persist($response);
        }

        $exception = $log->getException();

        while ($exception) {
            $this->dm->persist($exception);
            $exception = $exception->getPrevious();
        }
    }

    public function logRequest(Log $log, RequestInterface $request, int $contentType): void {
        $this->helper->logRequest($log, $request, $contentType);
    }

    public function logResponse(Log $log, ResponseInterface $response, int $contentType): void {
        $this->helper->logResponse($log, $response, $contentType);
    }

    public function logGuzzleException(Log $log, RequestException $exception, int $contentType): void {
        $this->helper->logGuzzleException($log, $exception, $contentType);
    }

    public function logException(Log $log, Throwable $exception, $extraData = null): void {
        $this->helper->logException($log, $exception, $extraData);
    }
}
