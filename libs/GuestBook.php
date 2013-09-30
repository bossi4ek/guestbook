<?php

require_once $_SERVER['DOCUMENT_ROOT']."/libs/Kernel.php";
require_once $_SERVER['DOCUMENT_ROOT']."/libs/Response.php";

class GuestBook extends Kernel {

    public function actionIndex($REQUEST)
    {
        switch ($REQUEST['action']){
            case "showMain":
                $result = $this->showMain($REQUEST);
                break;
            case "showAllPost":
                $result = $this->showAllPost($REQUEST);
                break;
            case "showAllPostAjax":
                $result = $this->showAllPostAjax($REQUEST);
                break;
            case "addPost":
                $result = $this->addPost($REQUEST);
                break;
            case "delPost":
                $result = $this->delPost($REQUEST);
                break;
        }
        return $result;
    }

    public function showMain($REQUEST)
    {
        return new Response("post", $this->showAllPost($REQUEST), "main.tpl.php");
    }

    public function showAllPost($REQUEST)
    {
        //Выборка всех постов
        $query = "SELECT id,
                         user_name,
                         user_email,
                         text,
                         date_create
                  FROM post
                  ORDER BY date_create DESC";
        if (!$this->mysql->execute($query)) {
            echo $this->mysql->getError();
            return 0;
        }
        $post = $this->mysql->getQueryTable();

        return $post;
    }

    public function showAllPostAjax($REQUEST)
    {
        return new Response("post", $this->showAllPost($REQUEST), "allPost.tpl.php");
    }

    public function addPost($REQUEST)
    {
        $query = "INSERT INTO post(
                    user_name,
                    user_email,
                    text,
                    date_create
                  )
                  VALUES(
                    ".$this->mysql->prepare($REQUEST['name']).",
                    ".$this->mysql->prepare($REQUEST['email']).",
                    ".$this->mysql->prepare($REQUEST['text']).",
                    ".time()."
                  )";
        if (!$this->mysql->execute($query)) {
            echo $this->mysql->getError();
            return 0;
        }

        echo "1";
    }

    public function delPost($REQUEST)
    {

    }
}