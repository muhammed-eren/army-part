<?php
    require_once __DIR__ . '/../admin/netting/baglan.php';
    $urun_id = $_POST['product_id'];
    $urun = $db->prepare("DELETE FROM wishlist WHERE kullanici_id = :kullanici_id AND wishlist_id = :urun_id");
    $urun->execute([':urun_id' => $urun_id, ':kullanici_id' => $_POST['user_id']]);
?>