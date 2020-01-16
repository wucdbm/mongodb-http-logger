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

namespace Wucdbm\Component\MongoDBHttpLogger\Logger;

use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\ODM\MongoDB\DocumentManager;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Throwable;
use Wucdbm\Component\MongoDBHttpLogger\Document\Exception;
use Wucdbm\Component\MongoDBHttpLogger\Document\Log;
use Wucdbm\Component\MongoDBHttpLogger\Document\Request;
use Wucdbm\Component\MongoDBHttpLogger\Document\Response;
use Wucdbm\Component\MongoDBHttpLogger\Document\Trace;

class Logger {

    /** @var DocumentManager */
    private $dm;

    public function __construct(ManagerRegistry $registry, string $class) {
        $dm = $registry->getManagerForClass($class);
        $this->dm = $dm;
    }

    public function save(Log $log): void {
        $this->persist($log);
        $this->dm->flush();
    }

    protected function persist(Log $log): void {
        $this->dm->persist($log);

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
        $body = $request->getBody();
        $body->rewind();
        $content = $body->getContents();
        $body->rewind();

        $message = new Request();
        $message->setContent($content);
        $message->setHeaders($request->getHeaders());
        $message->setContentType($contentType);

        $log->setRequest($message);

        if ($url = $request->getUri()) {
            $message->setUrl($url);
            $message->setUrlHash(md5($url));
        }

        $message->setMethod($request->getMethod());
    }

    public function logResponse(Log $log, ResponseInterface $response, int $contentType): void {
        $body = $response->getBody();
        $body->rewind();
        $content = $body->getContents();
        $body->rewind();

        // Sometimes, as it appears, content is not in UTF-8, regardless of what server reports
        // Other times, headers say UTF-8, HTML content says iso-8859-1 via meta http-equiv="Content-Type"
        // So, always convert
        $encoding = mb_detect_encoding($content);

        if (false === $encoding) {
            $log->setDebug(sprintf('Could not convert content to UTF-8'));
            $content = 'Could not convert content to UTF-8';
        } else {
            $content = mb_convert_encoding($content, 'UTF-8', $encoding);
        }

        $message = new Response();
        $message->setContent($content);
        $message->setHeaders($response->getHeaders());
        $message->setContentType($contentType);

        $log->setResponse($message);

        $message->setStatusCode($response->getStatusCode());
    }

    public function logGuzzleException(Log $log, RequestException $exception, int $contentType): void {
        if ($response = $exception->getResponse()) {
            $this->logResponse($log, $response, $contentType);
        }

        $this->logException($log, $exception);
    }

    public function logException(Log $log, Throwable $exception, $extraData = null): void {
        $ex = $this->createEntity($exception);
        $ex->setExtraData($extraData);

        $log->setException($ex);
    }

    private function createEntity(Throwable $e): Exception {
        $catchable = $this->createEntityForFlatten(FlattenException::createFromThrowable($e));
        $this->setStackTraceAsString($catchable, $e);

        return $catchable;
    }

    private function setStackTraceAsString(Exception $catchable, Throwable $e): void {
        do {
            $catchable->setStackTraceString($e->getTraceAsString());
            $catchable = $catchable->getPrevious();
            $e = $e->getPrevious();
        } while ($catchable && $e);
    }

    private function createEntityForFlatten(FlattenException $flatten): Exception {
        $catchable = new Exception();
        $catchable->setMessage($flatten->getMessage());
        $catchable->setCode($flatten->getCode());
        foreach ($flatten->getTrace() as $line) {
            $trace = new Trace();
            $trace->setNamespace($line['namespace'] ?? null);
            $trace->setShortClass($line['short_class'] ?? null);
            $trace->setClass($line['class'] ?? null);
            $trace->setType($line['type'] ?? null);
            $trace->setFunction($line['function'] ?? null);
            $trace->setFile($line['file'] ?? null);
            $trace->setLine($line['line'] ?? null);
            $trace->setArgs($line['args'] ?? null);

            $catchable->addTrace($trace);
        }
        $catchable->setClass($flatten->getClass());
        $catchable->setFile($flatten->getFile());
        $catchable->setLine($flatten->getLine());

        if ($previous = $flatten->getPrevious()) {
            $previous = $this->createEntityForFlatten($previous);
            $catchable->setPrevious($previous);
        }

        return $catchable;
    }
}
