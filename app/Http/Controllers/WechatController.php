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
        $token = lovephpforweixin;
        $signature = $request['signature'];
        $arr = [$timestamp, $nonce, $signature];
        sort($arr);
        $info = explode('',$arr);
        dd($info);
        // 将排序后的三个参数拼接之后用sha1加密

        //将加密后的字符串与signature进行对比,判断该请求是否来自微信
    }
}
