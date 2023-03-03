<?php

namespace app\controllers;

use app\models\User;
use yii\helpers\Json;
use yii\web\Controller;

class SiteController extends Controller
{
    private $users;

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }


    /**
     * Список пользователей по API
     *
     * @param $limit
     * @return mixed
     */
    public function actionUsers(int $limit=1)
    {
        $data = (new User())->userList($limit);

        if (!empty($data["message"])) {
            return $data["message"];
        } else {
            $data = !empty($data[0]) ? $data : [$data];
            foreach ($data as $item) {
                $this->users[] = [
                    'id' => $item["id"],
                    'name' => "{$item["first_name"]} {$item["last_name"]} [{$item["username"]}]",
                ];
            }
        }

        return Json::encode($this->users);
    }

}
