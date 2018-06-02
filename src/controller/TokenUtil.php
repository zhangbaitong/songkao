<?php
/**
 * Created by PhpStorm.
 * User: v2
 * Date: 2018/5/30
 * Time: 下午11:46
 */
use Psr\Container\ContainerInterface;

class TokenUtil {
    protected $app;

    public function __construct(ContainerInterface $ci)
    {
        $this->app = $ci;
    }
    function getToken(){
        $appid = $this ->app ->get("wechat")['appid'];
        $appsecret = $this ->app ->get("wechat")['appsecret'];
        $file = file_get_contents("/logs/access_token.json",true);
        $result = json_decode($file,true);
        if (time() > $result['expires']){
            $data = array();
            $data['access_token'] = getNewToken($appid,$appsecret);
            $data['expires']=time()+7000;
            $jsonStr =  json_encode($data);
            $fp = fopen("./access_token.json", "w");
            fwrite($fp, $jsonStr);
            fclose($fp);
            return $data['access_token'];
        }else{
            return $result['access_token'];
        }
    }
    function getNewToken($appid,$appsecret){
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$appsecret}";
        $access_token_Arr =  https_request($url);
        return $access_token_Arr['access_token'];
    }
    function getAssetsToken($code){
        $appid = $this ->app ->get("wechat")['appid'];
        $appsecret = $this ->app ->get("wechat")['appsecret'];
        $url = "hhttps://api.weixin.qq.com/sns/oauth2/access_token?grant_type=authorization_code&code={$code}&appid={$appid}&secret={$appsecret}";
        $result =  https_request($url);
        if($result->openid){
            setcookie("openid",$result->openid);
        }
        return $result->openid;

    }
    function https_request ($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $out = curl_exec($ch);
        curl_close($ch);
        $this->app-logger-info("https_request>>>url".$url ." result>>>>".$out);
        return  json_decode($out,true);
    }
}