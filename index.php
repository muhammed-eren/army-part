<?php
    include __DIR__ . '/admin/netting/baglan.php';
    session_start();
    if($_SESSION['USER'] == null){
        if($_COOKIE['remember'] != null){
            $token = $_COOKIE['remember'];
            $query = $db->query('SELECT * FROM kullanici WHERE remember_token = "' . $token . '"');
            $USER = $query->fetch(PDO::FETCH_ASSOC);
            $_SESSION['USER'] = $USER;
            header("Location: /shop");
            exit;
        }
        else
        {
            header("Location: /shop");
            exit;
        }
    }
    else
    {
        header("Location: /shop");
        exit;
    }
?>