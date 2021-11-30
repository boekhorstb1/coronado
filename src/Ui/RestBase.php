<?php

/**
 * Coronado REST
 *
 * This is the base class for REST calls
 */

declare(strict_types=1);

namespace Horde\Coronado\Ui;

use Horde\Injector\Injector;

/**
 * The standard PSR-7/PSR-15/PSR-17 fare.
 */

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Horde\Coronado\CoronadoException;
use Horde_Session;
use Horde_Registry;
use Horde\Log\Logger;

/**
 * Abstract Controller class for REST
 * Implement rest class by inheriting from this (DRY)
 */
abstract class RestBase implements MiddlewareInterface
{
    protected ResponseFactoryInterface $responseFactory;
    protected StreamFactoryInterface $streamFactory;
    protected Injector $injector;
    protected Horde_Session $session;
    protected Horde_Registry $registry;
    protected Logger $logger;

    /**
     * Constructor.
     *
     * This should be reusable for a lot of UI cases.
     * Maybe it could be factored out to a trait or base class.
     *
     * @param ResponseFactoryInterface $responseFactory
     * @param StreamFactoryInterface $streamFactory
     * @param Injector $injector
     * @param Horde_Registry $registry
     * @param Horde_Session $session
     * @param Logger $logger
     */
    public function __construct(
        ResponseFactoryInterface $responseFactory,
        StreamFactoryInterface $streamFactory,
        Injector $injector,
        Horde_Registry $registry,
        Horde_Session $session,
        Logger $logger
    ) {
        $this->responseFactory = $responseFactory;
        $this->streamFactory = $streamFactory;
        $this->injector = $injector;
        $this->registry = $registry;
        $this->session = $session;
        $this->page_output = $page_output;
        $this->view = $view;
        $this->logger = $logger;
        $this->notification = $notification;
    }

    /**
     * Process the incoming request.
     *
     *
     *
     * @see \Psr\Http\Server\MiddlewareInterface for concept
     * @param ServerRequestInterface $request The request might be amended or modified
     *                                        by previous middlewares
     * @param RequestHandlerInterface $handler We might refuse to deliver and ask the handler
     *                                         to either process the request himself or delegate
     *                                         to the next middleware (or a child handler).
     *                                         This might be useful to connect multiple presentations
     *                                         to the same route and let the presentation decide if it is
     *                                         responsible or not (Traditional/Dynamic/Mobile)
     *
     * @return ResponseInterface The response will bubble up through the chain
     *                           of previous middleware before being sent
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /* Naive approach. We will always return either a stream and return code 200,
         * an application-native exception and returncode 500 (this may be customized further)
         * or let other exceptions pass for other layers to process
         *
         */
        $returnCode = 200;
        $stream = null;

        try {
            $stream = $this->buildResponseStream($request);
        } catch (CoronadoException $e) {
            $stream = $this->handleNativeException($e, $request);
            // Manipulate return code and body for application-level exceptions
            // Everything else will just bubble up to an error handler middleware
            // and need not be handled here.
            $returnCode = 500;
        } finally {
            // Stop the output buffer
            ob_end_clean();
            if (!$stream) {
                return $handler->handle($request);
            }
        }
        return $this->responseFactory->createResponse($returnCode)
            ->withBody($stream)
            ->withHeader('Content-Type', 'application/json')
            ->withHeader('Accept', 'application/json');
    }

    /**
     * Overload this method for actually implementing stuff
     *
     * Quick & Dirty: Use $this->injector to get application services
     * Proper: Overload and amend constructor
     */
    protected function buildResponseStream(ServerRequestInterface $request): ?StreamInterface
    {
        $content = json_encode([
            'dummy' => 'data'
        ]);
        if ($content) {
            return $this->streamFactory->createStream($content);
        }
        throw new CoronadoException('Could not render rest output');
    }

    protected function handleNativeException(CoronadoException $e, $request): ?StreamInterface
    {
        return $this->streamFactory->createStream("An error occured: $e");
    }
}
