/**
 * Created by v2 on 2018/5/31.
 */
var wxOAuth = function (redirect_uri ,appid ) {    // 跳转到微信授权接口
    var authPre = "https://open.weixin.qq.com/connect/oauth2/authorize?appid="+appid;
    console.log(" 去授权"  + authPre);
    window.location.href = authPre + "&redirect_uri="+redirect_uri + '&response_type=code&scope=snsapi_base&state=123#wechat_redirect';
};

function getCookie(c_name) {
    if (document.cookie.length > 0) {
        c_start = document.cookie.indexOf(c_name + "=")
        if (c_start != -1) {
            c_start = c_start + c_name.length + 1
            c_end = document.cookie.indexOf(";", c_start)
            if (c_end == -1) c_end = document.cookie.length
            return unescape(document.cookie.substring(c_start, c_end))
        }
    }
    return ""
}

var openid = localStorage.getItem("openid");
// var  redirect_uri = "http://192.168.1.88:8091/slim/songkao/public/index.php/auth";
if ( !openid || openid === 'undefined' || openid === "") {
    //本地没有获取openid
    console.log("本地没有openid跳到join");
    window.location.href = window.location.href + "join";
}else{
    var redirect_uri = window.location.href + "show";
    window.location.href = redirect_uri + "?openid=" + openid;
    console.log("本地openid>>>" + openid);
}

