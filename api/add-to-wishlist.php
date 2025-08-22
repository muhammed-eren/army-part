<?php
    require_once __DIR__ . '/../admin/netting/baglan.php';

    $USER_ID = $_POST['user_id'];
    $URUN_ID = $_POST['product_id'];

    $kontrol = $db->query('SELECT * FROM wishlist WHERE kullanici_id = ' . $USER_ID . ' AND urun_id = ' . $URUN_ID);
    if ($kontrol->rowCount()) {
        $db->query('DELETE FROM wishlist WHERE kullanici_id = ' . $USER_ID . ' AND urun_id = ' . $URUN_ID);
    }
    else
    {
        $query = $db->prepare("INSERT INTO wishlist (kullanici_id,urun_id,wishlist_durum) VALUES (?,?,1)");
        $insert = $query->execute([$USER_ID,$URUN_ID]);
    }

    echo $db->query('SELECT COUNT(*) as count FROM wishlist WHERE kullanici_id = ' . $USER_ID)->fetch(PDO::FETCH_ASSOC)['count'];
?>