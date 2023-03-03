<?php

namespace app\models;

use app\exceptions\UserApiException;
use yii\base\ExitException;
use yii\helpers\Json;
use yii\httpclient\Client;
use yii\httpclient\Exception;
use yii\httpclient\Response;
use yii\web\HttpException;

class User
{
    const _BASE_URL_ = 'https://random-data-api.com';
    private $httpClient;

    public function __construct()
    {
        $this->httpClient = new Client(
            [
                'baseUrl'       => self::_BASE_URL_,
                'requestConfig' => [
                    'format' => Client::FORMAT_JSON
                ],
                'responseConfig' => [
                    'format' => Client::FORMAT_JSON
                ]
            ]
        );
    }

    /**
     * Получение пользователей
     * @param int $limit
     * @return array
     */
    public function userList(int $limit): array
    {
        $params = [
            'size' => $limit,
        ];

        $users = $this->request('GET', '/api/v2/users', $params);

        return $users;
    }

    /**
     * Используем httpclient для выполнения запросов
     *
     * @param string $method
     * @param string $url
     * @param array  $data
     *
     * @return array
     */
    private function send(string $method, string $url, array $data): Response
    {
        $request = $this->httpClient
            ->createRequest()
            ->setUrl($url)
            ->setMethod($method)
            ->setOptions([
                'timeout' => 3,
            ])
            ->setData($data);

        return $request->send();
    }

    /**
     * Получение данных по API
     *
     * @param string $method
     * @param string $url
     * @param array  $data
     *
     * @return array
     */
    private function request(string $method, string $url, array $data): array
    {
        $response = $this->send($method, $url, $data);

        if (!$response->isOk) {
            throw new UserApiException('Произошла ошибка.');
        }

        return $response->getData();
    }
}