# Usage

- `composer require doctrine/mongodb-odm-bundle`

```yaml
doctrine_mongodb:
    connections:
        default:
            server: "mongodb://localhost:27017"
            options: {}
    default_database: someDB
    document_managers:
        default:
            auto_mapping: true
            mappings:
                # ...
                wucdbm_http_logger:
                    type: annotation
                    dir: "%kernel.project_dir%/vendor/wucdbm/http-logger/src/Document"
                    prefix: Wucdbm\Component\MongoDBHttpLogger\Document\
```

Create a Document

```php
<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Wucdbm\Component\MongoDBHttpLogger\Document\Log;

/**
 * @ODM\Document(collection="some_collection")
 */
class SomeLog extends Log {

    // Feel free to add any custom fields

}
```

Create a logger

```php
<?php

namespace App\Logger;

use App\Document\SomeLog;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Wucdbm\Component\MongoDBHttpLogger\Logger\Logger;

class SomeLogger extends Logger {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, SomeLog::class);
    }

}
```

## Example

```php
<?php

/** @var \Wucdbm\Component\MongoDBHttpLogger\Logger\Logger $logger */
$logger = $this->container->get(SomeLogger::class);
$log = new \Wucdbm\Component\MongoDBHttpLogger\Document\Log();
$log->setExtraData('whatever');

try {
    $client = new \GuzzleHttp\Client();
    $request = new \GuzzleHttp\Psr7\Request('GET', 'http://some-website.com/');

    $logger->logRequest($log, $request, \Wucdbm\Component\MongoDBHttpLogger\Document\HttpMessage::ID_TEXT_PLAIN);
    // Logs are not saved automatically. You can override logRequest, response and Exception methods and implement it yourself.
    $logger->save($log);

    $response = $client->send($request);

    $logger->logResponse($log, $response, \Wucdbm\Component\MongoDBHttpLogger\Document\HttpMessage::ID_HTML);
    $logger->save($log);

    $ex = new \Exception('First Exception');

    throw new \Exception('Second Exception', 0, $ex);
} catch (\Throwable $e) {
    $logger->logException($log, $e);
    $logger->save($log);
}
```

## Guzzle Middleware

```php
class MiddlewareFactory {
    /** @var \ArrayIterator|\Wucdbm\Component\MongoDBHttpLogger\Document\Log[] */
    private $logCollection;

    /** @var \Wucdbm\Component\MongoDBHttpLogger\Logger\Logger */
    private $logger;

    public function createSearchLogger() {
        return function (callable $handler) {
            return function (
                \Psr\Http\Message\RequestInterface $request,
                array $options
            ) use ($handler) {
                $log = new \Wucdbm\Component\MongoDBHttpLogger\Document\Log();
                // Those may be your extra fields
    //                $log->setMethod($options['method']);
    //                $log->setSomeFieldId($this->someUnrelatedEntity->getId());
                $this->logCollection->append($log);
    
                $this->logger->logRequest(
                    $log, $request,
                    $options['requestMessageType'] ?? \Wucdbm\Component\MongoDBHttpLogger\Document\HttpMessage::ID_TEXT_PLAIN
                );
    
                /** @var \GuzzleHttp\Promise\PromiseInterface $promise */
                $promise = $handler($request, $options);
    
                return $promise->then(
                    function (\Psr\Http\Message\ResponseInterface $response) use ($log, $options) {
                        $this->logger->logResponse(
                            $log, $response,
                            $options['responseMessageType'] ?? \Wucdbm\Component\MongoDBHttpLogger\Document\HttpMessage::ID_TEXT_PLAIN
                        );
    
                        return $response;
                    }
                )->otherwise(function ($e) use ($log, $options) {
                    if ($e instanceof \GuzzleHttp\Exception\RequestException) {
                        $response = $e->getResponse();
    
                        if ($response) {
                            $this->logger->logResponse(
                                $log, $response,
                                $options['responseMessageType'] ?? \Wucdbm\Component\MongoDBHttpLogger\Document\HttpMessage::ID_TEXT_PLAIN
                            );
                        }
                    }
    
                    $this->logger->logException($log, $e);
    
                    throw $e;
                });
            };
        };
    }
}
```
