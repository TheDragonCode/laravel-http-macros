<?php

declare(strict_types=1);

namespace Tests\Fixtures\Logging;

use GuzzleHttp\MessageFormatterInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class MessageFormater implements MessageFormatterInterface
{
    public function __construct(
        protected string $template = 'REQUEST: {request} RESPONSE: {response}'
    ) {}

    public function format(
        RequestInterface $request,
        ?ResponseInterface $response = null,
        ?Throwable $error = null
    ): string {
        return str_replace(['{request}', '{response}'], [
            $request->getUri(),
            $response->getBody()->getContents(),
        ], $this->template);
    }
}
