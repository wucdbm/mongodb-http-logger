<?php


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