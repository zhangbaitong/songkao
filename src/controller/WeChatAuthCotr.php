<?php
/**
 * Created by PhpStorm.
 * User: v2
 * Date: 2018/5/30
 * Time: 下午6:42
 */
class WeChatAuthCotr
{
    protected $app;

    public function __construct(ContainerInterface $ci)
    {
        $this->app = $ci;
    }

    public function index(Request $request, Response $response, $args)
    {
        //$this->app->renderer->render($response, 'join.phtml', $args);
        echo "WeChatAuth";
    }
}