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

namespace Wucdbm\Component\MongoDBHttpLogger;

use Wucdbm\Component\MongoDBHttpLogger\Document\Request;

class CurlCommand {

    public static function generate(Request $request): string {
        $method = $request->getMethod();

        $headers = [];
        foreach ($request->getHeaders() as $header => $values) {
            foreach ($values as $value) {
                $headers[] = sprintf('-H "%s: %s"', $header, $value);
            }
        }

        $pieces = [
            sprintf('-X %s', $method),
            implode(' ', $headers)
        ];

        if ($request->getContent()) {
            $pieces[] = sprintf('-d "%s"', str_replace('"', '\"', $request->getContent()));
        }

        return sprintf('curl %s %s', $request->getUrl(), implode(' ', $pieces));
    }
}
