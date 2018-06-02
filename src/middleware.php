<?php
// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);
class IncomeMiddleware
{
    /**
     * Example middleware invokable class
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */

    protected $app;

    public function __construct(ContainerInterface $ci)
    {
        $this->app = $ci;
    }

    public function __invoke($request, $response, $next)
    {
        $this->app->logger->info("url:".$request->getUri() ." parme" .$request->getAttributes());

        return $response;
    }
}