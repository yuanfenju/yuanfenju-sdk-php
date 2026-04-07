# 缘份居 API PHP SDK（八字排盘 / 西方占星 / 紫微斗数 / 算命接口）

> 八字排盘 API / 西方占星 API / 紫微斗数 API / 算命接口（PHP SDK）
> 支持：八字分析、合婚配对、每日运势、星盘排盘等能力

---

## 🚀 项目简介

本 SDK 是「[缘份居](https://doc.yuanfenju.com)」提供的官方 PHP 开发工具包，用于快速接入传统文化相关的数据分析与内容生成能力。

通过本 SDK，你可以在几分钟内完成接口调用，无需关心复杂的签名算法、HTTP 请求封装等底层细节。

**适用场景：**

* 🛠️ 工具类网站（信息查询 / 娱乐应用）
* 📝 内容生成（文章 / 解读文案）
* 📱 小程序 / Web 应用快速集成

---

## ✨ 功能特性（v1）

* 生辰信息分析（基础排盘）
* 合盘关系分析（契合度参考）
* 每日运势参考（趋势信息）
* 基础排盘数据（紫微 / 星盘等）

> 💡 更多高级能力与完整参数说明，请参考
> 👉 https://doc.yuanfenju.com

---

## 📦 安装

推荐使用 Composer 安装：

```bash
composer require yuanfenju/sdk
```

---

## ⚡ 快速开始（3分钟跑通）

```php
<?php

require 'vendor/autoload.php';

use Yuanfenju\Sdk\Client;
use Yuanfenju\Sdk\Exception\ApiException;

// 1. 初始化 SDK
$client = new Client('你的_API_KEY');

try {
    // 2. 调用生辰分析接口
    $result = $client->getBaziPaipan([
        'name'   => '张三',
        'sex'    => 1,           // 1女 0男
        'type'   => 1,           // 0农历 1公历
        'year'   => 1990,
        'month'  => 1,
        'day'    => 1,
        'hours'  => 8,
        'minute' => 0
    ]);

    print_r($result);

} catch (ApiException $e) {
    echo "请求失败: " . $e->getMessage();
}
```

---

## 📁 示例代码

项目中提供完整示例，修改 API_KEY 即可运行：

```
examples/
├── bazi_paipan.php    # 八字排盘
├── bazi_cesuan.php    # 八字测算
├── bazi_yunshi.php    # 八字运势
```

---

## 📌 核心接口说明

| 功能   | 方法                               |
| ---- | ------------------------------     |
| 八字排盘 | `$client->getBaziPaipan($params)`|
| 八字测算 | `$client->getBaziCesuan($params)`|
| 八字运势 | `$client->getBaziYunshi($params)`|

---

## 🧩 通用调用（支持全部 API）

对于 SDK 尚未封装的接口，可使用通用方法：

```php
$result = $client->request('/api/v1/Xxx/xxx', $params, 'POST');
```

---

## 🔑 获取 API Key（每日赠送调用额度）

1. 访问：https://doc.yuanfenju.com
2. 注册开发者账号
3. 在控制台获取 API Key

---

## ⚠️ 使用说明（免责声明）

本 SDK 及缘份居 API 提供的是基于传统文化模型的数据分析结果，仅供技术研究、娱乐与参考使用。

不构成任何现实决策建议（包括但不限于投资、医疗、法律等场景）。

请合理、合规地使用相关数据内容。

---

## 📈 商业支持

如需以下支持，请访问官网联系：

* 🚀 高并发调用额度
* 🛡️ 商业授权 / SLA 支持
* 🛠️ 定制接口 / 私有化部署

---

## 📄 License

MIT License

---

## 🌐 关于我们

缘份居专注于传统文化数据的结构化与技术化，致力于为开发者提供稳定、易用、可扩展的接口服务能力。

👉 https://doc.yuanfenju.com
