<?php // @formatter:off
declare(strict_types=1);

return [
    'handler'   =>  [
        'http'  =>  [
            Hyperf\HttpServer\Exception\Handler\HttpExceptionHandler::class,
        ],
    ],
];
