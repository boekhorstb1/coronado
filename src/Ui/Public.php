<?php
/**
 * Coronado Public UI
 *
 * This is the page where the anonymous user adds data to generate his ticket.
 */
declare(strict_types=1);

namespace Horde\Coronado\Ui;

use Horde\Injector\Injector;
/**
 * The standard PSR-7/PSR-15/PSR-17 fare.
 */
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Horde\Coronado\CoronadoException;
use Horde_PageOutput;
use Horde_Registry;
use Horde_Session;
use Horde_View;
use Horde_View_Base;
use Horde\Log\Logger;
use Horde_Notification;
use Horde_Notification_Handler;
use Psr\Http\Message\StreamInterface;

/**
 * Controller class for Public UI
 */
class Public implements MiddlewareInterface
{
    protected ResponseFactoryInterface $responseFactory;
    protected StreamFactoryInterface $streamFactory;
    protected Injector $injector;
    protected Horde_Session $session;
    protected Horde_Registry $registry;
    protected Horde_PageOutput $page_output;
    protected Horde_View $view;
    protected Horde_Notification_Handler $notification;
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
     * @param Horde_PageOutput $page_output
     * @param Horde_View $view
     * @param Logger $logger
     */
    public function __construct(
        ResponseFactoryInterface $responseFactory,
        StreamFactoryInterface $streamFactory,
        Injector $injector,
        Horde_Registry $registry,
        Horde_Session $session,
        Horde_PageOutput $page_output,
        Horde_View_Base $view,
        Horde_Notification_Handler $notification,
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
        return $this->responseFactory->createResponse($returnCode)->withBody($stream);
    }

    protected function buildResponseStream(ServerRequestInterface $request): ?StreamInterface
    {
        // This is literally the list.php content, only adopted as needed

        // Capture any output. This allows us to use the legacy Page_Output
        // Also, we prevent leaking any unintended output before the headers are sent
        ob_start();
        // This is literally the list.php content, only adopted as needed
        $page_output = $this->page_output;
        $view = $this->view;
        $view->header = _("Header");
        $view->content = _("Some Content");
        $view->list = [
            ['One', 'Foo'],
            ['Two', 'Bar'],
        ];

        /* Load JavaScript for sortable table. */
        $page_output->addScriptFile('tables.js', 'horde');

        /* Here starts the actual page output. First we output the complete HTML
            * header, CSS files, the topbar menu, and the sidebar menu. */
        $page_output->header([
            'title' => _("List"),
        ]);

        /* Next we output any notification messages. This is not done automatically
            * because on some pages you might not want to have notifications. */
        $this->notification->notify(['listeners' => 'status']);

        /* Here goes the actual content of your application's page. This could be
            * Horde_View output, a rendered Horde_Form, or any other arbitrary HTML
            * output. */
        echo $view->render('list');

        /* Finally the HTML content is closed and JavaScript files are loaded. */
        $page_output->footer();
        $content = ob_get_contents();
        if ($content) {
            return $this->streamFactory->createStream($content);
        }
        throw new CoronadoException('Could not render page');
    }

    protected function handleNativeException(CoronadoException $e, $request): ?StreamInterface
    {
        return $this->streamFactory->createStream('An error occured: ');
    }
}
