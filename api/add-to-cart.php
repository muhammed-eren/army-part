<?php
require_once __DIR__ . '/../admin/netting/baglan.php';

$USER_ID = $_POST['user_id'] ?? null;
$URUN_ID = $_POST['product_id'] ?? null;
$miktar  = $_POST['miktar'] ?? 1;
$islem   = $_POST['islem'] ?? null;

switch ($islem) {
    case 'miktar_duzenle':
        $query = $db->prepare("UPDATE sepet SET urun_adet = ? WHERE kullanici_id = ? AND urun_id = ?");
        $query->execute([$miktar, $USER_ID, $URUN_ID]);
        echo json_encode(["status" => "success", "action" => "miktar_duzenle"]);
        break;

    case 'delete':
        $query = $db->prepare("DELETE FROM sepet WHERE kullanici_id = ? AND urun_id = ?");
        $query->execute([$USER_ID, $URUN_ID]);
        echo json_encode(["status" => "success", "action" => "delete"]);
        break;

    case 'add':
        $kontrol = $db->prepare("SELECT * FROM sepet WHERE kullanici_id = ? AND urun_id = ?");
        $kontrol->execute([$USER_ID, $URUN_ID]);

        if ($kontrol->rowCount()) {
            $query = $db->prepare("UPDATE sepet SET urun_adet = urun_adet + 1 WHERE kullanici_id = ? AND urun_id = ?");
            $query->execute([$USER_ID, $URUN_ID]);
        } else {
            $query = $db->prepare("INSERT INTO sepet (kullanici_id, urun_id, urun_adet) VALUES (?, ?, 1)");
            $query->execute([$USER_ID, $URUN_ID]);
        }
        echo json_encode(["status" => "success", "action" => "add"]);
        break;

    default:
        echo json_encode(["status" => "error", "message" => "Geçersiz işlem"]);
        break;
}
?>