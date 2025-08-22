<?php
    require_once __DIR__ . '/../admin/netting/baglan.php';

    $USER_ID = $_POST['user_id'];

    $stmt = $db->prepare("
        SELECT 
        u.*,
        s.urun_adet,
        k.kategori_ad,
        f.urunfoto_resimyol
        FROM sepet AS s
        JOIN urun AS u           ON s.urun_id = u.urun_id
        LEFT JOIN kategori AS k  ON u.kategori_id = k.kategori_id
        LEFT JOIN urunfoto AS f  ON f.urun_id = u.urun_id
        AND f.urunfoto_id = (
            SELECT MIN(uf2.urunfoto_id)
            FROM urunfoto AS uf2
            WHERE uf2.urun_id = u.urun_id
        )
        WHERE s.kullanici_id = ?");

    $stmt->execute([$USER_ID]);
    $sepet = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($sepet);
?>
