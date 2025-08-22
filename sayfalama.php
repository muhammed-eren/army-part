<?php 


include 'admin/netting/baglan.php';
include 'admin/production/fonksiyon.php';

if (isset($_GET['sayfa']) and $_GET['sayfa'] > 1) {
	$sayfa=$_GET['sayfa'];
}else{
	$sayfa=1;
}
//$sayibul=$db->prepare("SELECT * FROM urunler where kategori_id =: kategori_id and urun_durum =: urun_durum ");
$sayibul=$db->prepare("SELECT * FROM urun ");
$sayibul->execute();
//$sayibul->execute(array(
//'kategori_id'=>$id
//));



$urunsayisi=$sayibul->rowCount();

$goruntuleme_sayisi=4;



$sayfa_katsayisi=($sayfa*$goruntuleme_sayisi)-$goruntuleme_sayisi;

//$urunler=$db->prepare("SELECT * FROM urunler where kategori_id =: kategori_id and urun_durum =: urun_durum order by urun_sira ASC limit $sayfa_katsayisi, $goruntuleme_sayisi");
$urunler=$db->prepare("SELECT * FROM urun  order by urun_id limit $sayfa_katsayisi,$goruntuleme_sayisi ");
$urunler->execute();

$sayfasayisi=ceil($urunsayisi/$goruntuleme_sayisi);
if ($sayfa > $sayfasayisi) { $sayfa=$sayfasayisi; }

    		echo "urunsayisi : ".$urunsayisi."</br>";
    		echo "goruntuleme_sayisi : ".$goruntuleme_sayisi."</br>";
    		echo "sayfa_katsayisi : ".$sayfa_katsayisi."</br>";
    		echo "sayfa_ADRESİ : ".$_SERVER['PHP_SELF']."</br>";

 echo "<pre>";
	print_r(get_included_files());
 echo "</pre>";
  echo "<pre>";
	print_r($_GET);
 echo "</pre>";
//  echo "<pre>";
//	print_r($_SERVER);
// echo "</pre>";
//  echo "<pre>";
//	print_r($_SESSION);
// echo "</pre>";




echo "<pre>";
//print_r(ini_get_all("session"));
echo "</pre>";

// PHP HATALARINI ON OLUP OLMADIĞINI VE KAPATMA AÇMA YÖNTEMLERİNİ GÖSTERİYOR

// ayarı görme
//echo ini_get("log_errors")."<br>";
//
//// ayarı gömme 0 hataları göster demek
//ini_set("log_errors",0);
//echo ini_get("log_errors")."<br>";
//
//// hataları fabrika ayarlarına alma
//ini_restore("log_errors");
//echo ini_get("log_errors")."<br>";

// strin içerisindeki php kodunu çalıştır
$text = 'echo "Hello php eval";';
eval($text);


?>




 ?>

 <!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ürün Listeleme Tablosu</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 20px;
      background-color: #f4f4f9;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
      background-color: #fff;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    th, td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }
    th {
      background-color: #4CAF50;
      color: white;
    }
    tr:hover {
      background-color: #f1f1f1;
    }
    .btn {
      padding: 8px 16px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      text-decoration: none;
    }
    .btn:hover {
      background-color: #45a049;
    }
  </style>
</head>
<body>

  <h2>Ürün Listeleme</h2>
  <p>Ürünlerinizi aşağıdaki tabloda görüntüleyebilirsiniz:</p>

  <table>
    <thead>
      <tr>
        <th>Ürün Adı</th>
        <th>Fiyat</th>
        <th>Stok</th>
        <th>Kategori</th>
        <th>İşlem</th>
      </tr>
    </thead>
    <tbody>
    	<?php 
    	if ($urunler) {
    		// code...
    	}
    		while ($urunlercek=$urunler->fetch(PDO::FETCH_ASSOC)) {
    			$urunsayisi = $urunler->rowCount();	
 		
    	 ?>
      <tr>
        <td><?php echo $urunlercek["urun_ad"]; ?></td>
        <td>2.500₺</td>
        <td>25</td>
        <td><?php echo $urunsayisi ?></td>
        <td><a href="#" class="btn">Detay</a></td>
      </tr>
<?php } ?>

    </tbody>
  </table>

  
  <nav aria-label="Page navigation example">
  <ul class="pagination justify-content-center">
    <li class="page-item disabled">
    	<?php if($sayfa-1 >= 1) { ?>
      <a class="page-link" href="?sayfa=<?php echo $sayfa-1; ?>" tabindex="-1">Previous</a>
      	<?php } ?>
    </li>
    <?php 	
    		//echo "sayfasayisi : ".$sayfasayisi."</br>";
    	$sirano=0;
    	while (	$sirano < $sayfasayisi ) {
    		$sirano	++;
     ?>
    <li class="page-item <?php 	if($sirano == $sayfa ) {echo "active"; } ?>"><a class="page-link" href="?sayfa= <?php echo $sirano; ?>"	> <?php echo $sirano; ?></a></li>
    <?php 	} ?>
 
    <li class="page-item">
    	<?php if($sayfa+1 <= $sayfasayisi) { ?>
      <a class="page-link" href="?sayfa=<?php echo $sayfa+1; ?>" >Next</a>
      	<?php } ?>
    </li>
  </ul>
</nav>

</body>
</html>
