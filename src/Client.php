<?php

namespace Yuanfenju\Sdk;

use Yuanfenju\Sdk\Exception\ApiException;

class Client
{
    /**
     * @var string 缘份居 API 密钥
     */
    private $apiKey;

    /**
     * @var string 接口根地址
     */
    private $baseUri = 'https://api.yuanfenju.com/index.php';

    /**
     * 构造函数，初始化 SDK
     *
     * @param string $apiKey 你的 API Key
     */
    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * 1. 专属封装：八字排盘接口
     *
     * @param array $params 包含出生年月日时等参数
     * @return array
     * @throws ApiException
     */
    public function getBaziPaipan(array $params): array
    {
        // 1. 基础必填参数校验
        $requiredKeys = ['name', 'sex', 'type', 'year', 'month', 'day', 'hours', 'minute'];
        foreach ($requiredKeys as $key) {
            if (!isset($params[$key])) {
                throw new ApiException("Missing required parameter: {$key}");
            }
        }

        // 2. 进阶校验：针对真太阳时 (zhen) 的条件必填参数校验
        if (isset($params['zhen'])) {
            if ($params['zhen'] == 1) {
                // 中国真太阳时：省和市必填
                if (empty($params['province']) || empty($params['city'])) {
                    throw new ApiException("When zhen=1, parameters 'province' and 'city' are required.");
                }
            } elseif ($params['zhen'] == 3) {
                // 全球真太阳时：经度和纬度必填
                if (!isset($params['longitude']) || !isset($params['latitude'])) {
                    throw new ApiException("When zhen=3, parameters 'longitude' and 'latitude' are required.");
                }
            }
        }

        // 调用具体的接口路由
        return $this->request('/v1/Bazi/paipan', $params, 'POST');
    }

    /**
     * 2. 专属封装：八字测算接口
     *
     * @param array $params 包含出生年月日时等参数
     * @return array
     * @throws ApiException
     */
    public function getBaziCesuan(array $params): array
    {
        // 1. 基础必填参数校验
        $requiredKeys = ['name', 'sex', 'type', 'year', 'month', 'day', 'hours', 'minute'];
        foreach ($requiredKeys as $key) {
            if (!isset($params[$key])) {
                throw new ApiException("Missing required parameter: {$key}");
            }
        }

        // 2. 进阶校验：针对真太阳时 (zhen) 的条件必填参数校验
        if (isset($params['zhen'])) {
            if ($params['zhen'] == 1) {
                // 中国真太阳时：省和市必填
                if (empty($params['province']) || empty($params['city'])) {
                    throw new ApiException("When zhen=1, parameters 'province' and 'city' are required.");
                }
            } elseif ($params['zhen'] == 3) {
                // 全球真太阳时：经度和纬度必填
                if (!isset($params['longitude']) || !isset($params['latitude'])) {
                    throw new ApiException("When zhen=3, parameters 'longitude' and 'latitude' are required.");
                }
            }
        }

        // 调用具体的接口路由
        return $this->request('/v1/Bazi/cesuan', $params, 'POST');
    }

    /**
     * 3. 专属封装：八字运势接口
     *
     * @param array $params 包含出生年月日时等参数
     * @return array
     * @throws ApiException
     */
    public function getBaziYunshi(array $params): array
    {
        // 1. 基础必填参数校验
        $requiredKeys = ['name', 'sex', 'type', 'year', 'month', 'day', 'hours', 'minute'];
        foreach ($requiredKeys as $key) {
            if (!isset($params[$key])) {
                throw new ApiException("Missing required parameter: {$key}");
            }
        }

        // 2. 进阶校验：针对真太阳时 (zhen) 的条件必填参数校验
        if (isset($params['zhen'])) {
            if ($params['zhen'] == 1) {
                // 中国真太阳时：省和市必填
                if (empty($params['province']) || empty($params['city'])) {
                    throw new ApiException("When zhen=1, parameters 'province' and 'city' are required.");
                }
            } elseif ($params['zhen'] == 3) {
                // 全球真太阳时：经度和纬度必填
                if (!isset($params['longitude']) || !isset($params['latitude'])) {
                    throw new ApiException("When zhen=3, parameters 'longitude' and 'latitude' are required.");
                }
            }
        }

        // 调用具体的接口路由
        return $this->request('/v1/Bazi/yunshi', $params, 'POST');
    }

    /**
     * 2. 万能请求方法（钩子）
     * 允许用户调用 SDK 中尚未封装的新接口
     *
     * @param string $endpoint 接口路径
     * @param array $data 请求参数
     * @param string $method 请求方法 (GET/POST)
     * @return array
     * @throws ApiException
     */
    public function request(string $endpoint, array $data = [], string $method = 'POST'): array
    {
        $url = rtrim($this->baseUri, '/') . '/' . ltrim($endpoint, '/');

        // 将 ApiKey 自动附加到参数中
        $data['api_key'] = $this->apiKey;

        $ch = curl_init();

        if (strtoupper($method) === 'GET') {
            $url .= '?' . http_build_query($data);
        } else {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10); // 设置超时时间

        // 统一 Header：补充文档要求的 Content-Type
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Content-Type: application/x-www-form-urlencoded'
        ]);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($response === false) {
            throw new ApiException("cURL Error: " . $error);
        }

        $result = json_decode($response, true);

        // 容错处理：如果接口返回的不是标准 JSON（比如网关报错 502 HTML）
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ApiException("Invalid JSON Response [HTTP {$httpCode}]: " . $response);
        }

        // 处理 API 返回的业务错误 (依据文档：errcode 0 成功，其它失败)
        // 注意：文档中 errcode 类型为 String，所以用 != 0 进行比较
        if ($httpCode !== 200 || (isset($result['errcode']) && $result['errcode'] != 0)) {
            $errorMsg = $result['errmsg'] ?? 'Unknown API Error';
            $errCode = $result['errcode'] ?? $httpCode;
            throw new ApiException("API Error [{$errCode}]: {$errorMsg}");
        }

        return $result;
    }
}