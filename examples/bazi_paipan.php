<?php

// 引入 Composer 的自动加载
require_once __DIR__ . '/../vendor/autoload.php';

use Yuanfenju\Sdk\Client;
use Yuanfenju\Sdk\Exception\ApiException;

// 1. 初始化 SDK (请一定要换成你真实的 API Key)
$client = new Client('FsF1C******fSk');

// 准备测试数据（公共参数）
$baziParams = [
    'name'   => '张三',
    'sex'    => 1,           // 1女 0男 (根据 API 文档)
    'type'   => 1,           // 0农历 1公历 (必填项)
    'year'   => 1988,
    'month'  => 11,
    'day'    => 8,
    'hours'  => 12,
    'minute' => 20
];

try {
    echo "=== 方式一：使用专属封装方法调用 ===\n";
    echo "说明：自带参数校验，IDE 提示友好，推荐常用接口使用。\n";

    $result1 = $client->getBaziPaipan($baziParams);

    echo "调用成功！数据返回：\n";
    print_r($result1);


    echo "\n--------------------------------------------------\n\n";


    echo "=== 方式二：使用万能钩子方法调用 ===\n";
    echo "说明：直接传入路由地址，适用于调用 SDK 中尚未封装的任何新接口。\n";

    // 传入路由地址 '/v1/Bazi/paipan'、参数数组，以及请求方式 'POST'
    $result2 = $client->request('/v1/Bazi/paipan', $baziParams, 'POST');

    echo "调用成功！数据返回：\n";
    print_r($result2);

} catch (ApiException $e) {
    echo "请求失败: " . $e->getMessage() . "\n";
}