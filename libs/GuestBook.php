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
        return new Response("data", $this->showAllPost($REQUEST), "main.tpl.php");
    }

    public function showAllPost($REQUEST)
    {
        $data = array();
        //Разделение на страницы
        $query = "SELECT count(id) AS count
                  FROM post";
        if (!$this->mysql->execute($query)) {
            echo $this->mysql->getError();
        }
        $result['page'] = isset($REQUEST['page']) ? $REQUEST['page'] : 0;
        $result['count'] = $this->mysql->getValue('count');
        $result['page'] = $this->getPages($result['page'], $this->mysql->getValue('count'));
        $result['condition'] = " LIMIT ".$result['page']['from'].", ".$result['page']['element_per_page'];
        $result['page']['javaFunction'] = "showAllPost";
        $data['page'] = $result['page'];

        //Выборка всех постов
        $query = "SELECT id,
                         user_name,
                         user_email,
                         text,
                         date_create
                  FROM post
                  ORDER BY date_create DESC
                  ".$result['condition'];
        if (!$this->mysql->execute($query)) {
            echo $this->mysql->getError();
        }
        $data['post'] = $this->mysql->getQueryTable();

        return $data;
    }

    public function showAllPostAjax($REQUEST)
    {
        return new Response("data", $this->showAllPost($REQUEST), "allPost.tpl.php");
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
        }

        echo "1";
    }

    public function delPost($REQUEST)
    {
        $query = "DELETE
                  FROM post
                  WHERE id = ".$this->mysql->prepare($REQUEST['id']);
        if (!$this->mysql->execute($query)) {
            echo $this->mysql->getError();
        }

        echo 1;
    }

//==============================================================================
    public function getPages($page_index, $count, $elements_on_page = ELEMENTS_ON_PAGE, $visible_pages = VISIBLE_PAGES) {
        $page = array();
        $page['count_element'] = $count;
        $page['element_per_page'] = $elements_on_page;
        $page['count_page'] =  ceil($page['count_element'] / $page['element_per_page']);


        if (($page['count_page'] - 1) < $page_index) {
            $page['index'] = $page['count_page'] - 1;
            if ($page['index'] == -1) {
                $page['index'] = 0;
            }
        }
        else {
            $page['index'] = $page_index;
        }
        $page['from'] = $page['element_per_page'] * $page['index'];
        $page['to'] = $page['from'] + $page['element_per_page'];

        if ($page['count_page'] < $visible_pages) {
            $visible_pages = $page['count_page'];
        }
        if ($page['index'] == 0) {
            for ($index = 0; $index < $visible_pages; $index++) {
                $page['array'][] = $index;
            }
        }
        else {
            if ($page['index'] == ($page['count_page'] - 1)) {
                for ($index = $page['count_page'] - $visible_pages; $index < $page['count_page']; $index++) {
                    $page['array'][] = $index;
                }
            }
            else {
                $half = ceil($visible_pages / 2) - 1;
                if (($page['index'] - $half) < 0) {
                    $tmp = 0;
                }
                else {
                    if (($page['index'] + $half) > ($page['count_page'] - 1)) {
                        $tmp = $page['count_page'] - $visible_pages;
                    }
                    else {
                        $tmp = $page['index'] - $half;
                    }
                }
                for ($index = 0; $index < $visible_pages; $index++) {
                    $page['array'][] = $tmp;
                    $tmp++;
                }
            }
        }
        return $page;
    }

}