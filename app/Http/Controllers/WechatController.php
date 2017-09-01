<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WechatController extends Controller
{
    public function index(Request $request)
    {
        // 将timestamp, nonce, token按字典序排序
        $timestamp = $request['timestamp'];
        $nonce = $request['nonce'];
        $echostr = $request['echostr'];
        $token = 'lovephpforweixin';
        $signature = $request['signature'];
        $arr = [$timestamp, $nonce, $token];
        sort($arr);

        // 将排序后的三个参数拼接之后用sha1加密
        $str = sha1(implode('',$arr));

        //将加密后的字符串与signature进行对比,判断该请求是否来自微信
        if ($str == $signature && $echostr) {
            echo $echostr;
            exit;
        } else {
            $this->responseMsg();
        }
    }

    /**
     *  接收事件推送并回复
     */
    public function responseMsg()
    {
        // 1.获取微信推送过来的post数据(xml格式)
        $postArr = isset($GLOBALS['HTTP_RAW_POST_DATA']) ? $GLOBALS['HTTP_RAW_POST_DATA'] : file_get_contents("php://input");

        // 2. 处理消息类型,并设置回复类型和内容
        $postObj = simplexml_load_string($postArr);
        // 3. 判断该数据包是否是订阅事件推送
        if (strtolower($postObj->MyType) == 'event') {
            if (strtolower($postObj->Event) == 'subscribe') {
                $toUser = $postObj->FromUserName;
                $fromUser = $postObj->ToUserName;
                $time = time();
                $msgType = 'text';
                $content = "欢迎关注我们的公众号";
                $template = "<xml>
                                <ToUserName><![CDATA[%s]]></ToUserName>
                                <FromUserName><![CDATA[%s]]></FromUserName>
                                <CreateTime>%s</CreateTime>
                                <MsgType><![CDATA[%s]]></MsgType>
                                <Event><![CDATA[%s]]></Event>
                            </xml>";
                $info = sprintf($template, $toUser, $fromUser, $time, $msgType, $content);
                return $info;
            }
        }

    }
}
