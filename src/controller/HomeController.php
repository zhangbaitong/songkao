<?php
/**
 * Created by PhpStorm.
 * User: v2
 * Date: 2018/5/29
 * Time: 上午8:48
 */
use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
class HomeController
{
    protected $app;
    public function __construct(ContainerInterface $ci)
    {
        $this->app = $ci;
    }
    public function __invoke($request, $response, $args) {
        // your code
        // to access items in the container... $this->container->get('');

        $wechat = $this->app->get('settings')['wechat'];
        return $this->app->renderer->render($response, 'home.phtml', [ 'wexin' => $wechat['appid']]);
    }

    public function auth(Request $request, Response $response, $args){
        //$c->get('settings')['logger']
       // $this->logger

        $this->app ->logger ->info("auth '/' code"+ $args['code'] +" status "+ $args['status']);
        if($args['code']){
            $openid = $this ->app ->tokenUtil->getAssetsToken($args['code']);
            if($openid){
                $this ->app ->redirect("/show?openid=".$openid ,301);
            }
        }
    }

    public function show(Request $request, Response $response, $args){
        //$c->get('settings')['logger']
        // $this->logger
//
//        $this->app ->logger ->info("auth '/' code"+ $args['code'] +" status "+ $args['status']);
//        if($args['code']){
//            $openid = $this ->app ->tokenUtil->getAssetsToken($args['code']);
//            if($openid){
//                $this ->app ->redirect("/show?openid=".$openid ,301);
//            }
//        }
        $queryParams = $request->getQueryParams();
        $user = $this->app -> user->getUserById($queryParams['openid']);
        //判断用户是否存在
        if(!isset($user['id']) || $user == 0){
            //不存在进入join页面
            return $response->withRedirect('/join', 301);
        }else{
            return $this->app->renderer->render($response, 'show.phtml', $user);
        }
    }

    private function log($info){
        $this->app->logger->info($info);
    }

    public function join(Request $request, Response $response, $args){
        //$c->get('settings')['logger']
        // $this->logger
//
//        $this->app ->logger ->info("auth '/' code"+ $args['code'] +" status "+ $args['status']);
//        if($args['code']){
//            $openid = $this ->app ->tokenUtil->getAssetsToken($args['code']);
//            if($openid){
//                $this ->app ->redirect("/show?openid=".$openid ,301);
//            }
//        }
        return $this->app->renderer->render($response, 'join.phtml', $args);

    }


    public function joinUser(Request $request, Response $response, $args){
        //$c->get('settings')['logger']
        // $this->logger
//
//        $this->app ->logger ->info("auth '/' code"+ $args['code'] +" status "+ $args['status']);
//        if($args['code']){
//            $openid = $this ->app ->tokenUtil->getAssetsToken($args['code']);
//            if($openid){
//                $this ->app ->redirect("/show?openid=".$openid ,301);
//            }
//        }t
//        }t
        $queryParams = $request->getQueryParams();
        $this->app->logger ->info("username::>>". json_encode( $queryParams['r_name']) );
        $data['openid'] = $queryParams['openid'];
        $data['r_name'] = $queryParams['r_name'];
       if( $this->app -> user ->insertUser($data)){
           //return $this->app->renderer->render($response, 'show.phtml', $args);
//           $this ->app ->redirect("/show?openid=" ,301);
           $this->app->logger->info('URL:'.'/show?openid='.$data['openid']);
           return $response->withRedirect('/show?openid='.$data['openid'], 301);
        }else{
            echo "加入失败";
       }
    }
}