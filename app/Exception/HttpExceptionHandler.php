<?php
declare(strict_types=1);

namespace app\Exception;

use App\Utils\Response\ResponseUtil;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\ExceptionHandler\Annotation\ExceptionHandler;
use Hyperf\ExceptionHandler\ExceptionHandler as HyperfExceptionHandler;
use Hyperf\Validation\ValidationException;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;
use Swow\Psr7\Message\ResponsePlusInterface;
use Throwable;

/**
 * @AppExceptionHandler
 * @\app\Exception\AppExceptionHandler
 */
#[
    ExceptionHandler,
]
class HttpExceptionHandler extends HyperfExceptionHandler
{

    /**
     * @var ResponseUtil $responseUtil
     */
    #[Inject]
    protected ResponseUtil $responseUtil;

    public function __construct(protected StdoutLoggerInterface $logger)
    {
    }

    public function handle(Throwable $throwable, ResponsePlusInterface $response): MessageInterface|ResponseInterface
    {
        $this->logger->error(sprintf('%s[%s] in %s', $throwable->getMessage(), $throwable->getLine(), $throwable->getFile()));
        $this->logger->error($throwable->getTraceAsString());

        if ($throwable instanceof ValidationException) {
            return $this->handleValidator($throwable);
        }

        return $this->responseUtil->serverError('Internal Server Error.');
    }

    /**
     * The method is a validator exception handler
     * @param ValidationException $validationException
     * @return MessageInterface
     */
    public function handleValidator(ValidationException $validationException): MessageInterface
    {
        $this->stopPropagation();
        $message = $validationException->validator->errors()->first();

        return $this->responseUtil->validatorError($message);
    }

    public function isValid(Throwable $throwable): bool
    {
        return true;
    }
}
