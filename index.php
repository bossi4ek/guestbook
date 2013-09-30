<?php
    require_once $_SERVER['DOCUMENT_ROOT']."/libs/GuestBook.php";

    if (!isset($_REQUEST['action']) || $_REQUEST['action'] == '') {
        $_REQUEST['action'] = 'showMain';
    }

    $guest_book = new GuestBook($_REQUEST);
    $guest_book->actionIndex($_REQUEST);
