
<?php 
require_once 'header.php';


if (isset($_GET['sayfa']) and $_GET['sayfa'] > 1) {
    $sayfa=(int)$_GET['sayfa'];
}else{
    $sayfa=1;
}


?>
<?php 

@$KEYWORDS = '%'.$_GET['q'].'%';
@$KATEGORI = $_GET['kategori'];
@$ARAC_TIPI = $_GET['arac_tipi'];
@$SHOR_BY = $_POST['shor_by'] ?? 'urun_id DESC';

if(isset($_GET['v'])){
    $sayibul = $db->prepare("SELECT * FROM urun 
        WHERE 1=1
        AND (kategori_id = :kategori AND arac_tipi_id = :arac_tipi)
        ORDER BY $SHOR_BY");
    
    $sayibul->bindParam(':kategori', $KATEGORI, PDO::PARAM_STR);
    $sayibul->bindParam(':arac_tipi', $ARAC_TIPI, PDO::PARAM_STR);
    $sayibul->execute();
}
else if(isset($_GET['s'])){
    if($KATEGORI)
    {
        $sayibul = $db->prepare("SELECT * FROM urun 
            WHERE 1=1
            AND (kategori_id = :kategori AND arac_tipi_id = :arac_tipi)
            ORDER BY $SHOR_BY");
        
        $sayibul->bindParam(':kategori', $KATEGORI, PDO::PARAM_STR);
        $sayibul->bindParam(':arac_tipi', $ARAC_TIPI, PDO::PARAM_STR);
        $sayibul->execute();
    }
    else
    {
        $sayibul = $db->prepare("SELECT * FROM urun 
            WHERE 1=1
            AND arac_tipi_id = :arac_tipi
            ORDER BY $SHOR_BY");
        
        $sayibul->bindParam(':arac_tipi', $ARAC_TIPI, PDO::PARAM_STR);
        $sayibul->execute();
    }
}
else{
    $sayibul = $db->prepare("SELECT * FROM urun 
        WHERE urun_ad LIKE :keywords
        OR urun_detay LIKE :keywords
        OR urun_keyword LIKE :keywords 
        OR urun_seourl LIKE :keywords
        ORDER BY $SHOR_BY");

    $sayibul->bindParam(':keywords', $KEYWORDS, PDO::PARAM_STR);
    $sayibul->execute();
}

$urunsayisi=$sayibul->rowCount();

$goruntuleme_sayisi=25;

$sayfa_katsayisi=($sayfa*$goruntuleme_sayisi)-$goruntuleme_sayisi;

//$urunler=$db->prepare("SELECT * FROM urunler where kategori_id =: kategori_id and urun_durum =: urun_durum order by urun_sira ASC limit $sayfa_katsayisi, $goruntuleme_sayisi");

// Ürün Arama
if(isset($_GET['v'])){
    $urunler = $db->prepare("SELECT * FROM urun 
        WHERE 1=1
        AND (kategori_id = :kategori AND arac_tipi_id = :arac_tipi)
        ORDER BY $SHOR_BY
        LIMIT :basla, :adet");
    
    $urunler->bindParam(':kategori', $KATEGORI, PDO::PARAM_STR);
    $urunler->bindParam(':arac_tipi', $ARAC_TIPI, PDO::PARAM_STR);
    $urunler->bindParam(':basla', $sayfa_katsayisi, PDO::PARAM_INT);
    $urunler->bindParam(':adet', $goruntuleme_sayisi, PDO::PARAM_INT);
    $urunler->execute();
}
else if(isset($_GET['s'])){
    if($KATEGORI)
    {
        $urunler = $db->prepare("SELECT * FROM urun 
            WHERE 1=1
            AND (kategori_id = :kategori AND arac_tipi_id = :arac_tipi)
            ORDER BY $SHOR_BY
            LIMIT :basla, :adet");
        
        $urunler->bindParam(':kategori', $KATEGORI, PDO::PARAM_STR);
        $urunler->bindParam(':arac_tipi', $ARAC_TIPI, PDO::PARAM_STR);
        $urunler->bindParam(':basla', $sayfa_katsayisi, PDO::PARAM_INT);
        $urunler->bindParam(':adet', $goruntuleme_sayisi, PDO::PARAM_INT);
        $urunler->execute();
    }
    else
    {
        $urunler = $db->prepare("SELECT * FROM urun 
            WHERE 1=1
            AND arac_tipi_id = :arac_tipi
            ORDER BY $SHOR_BY
            LIMIT :basla, :adet");
        
        $urunler->bindParam(':arac_tipi', $ARAC_TIPI, PDO::PARAM_STR);
        $urunler->bindParam(':basla', $sayfa_katsayisi, PDO::PARAM_INT);
        $urunler->bindParam(':adet', $goruntuleme_sayisi, PDO::PARAM_INT);
        $urunler->execute();
    }
}
else{
    $urunler = $db->prepare("SELECT * FROM urun 
        WHERE urun_ad LIKE :keywords
        OR urun_detay LIKE :keywords
        OR urun_keyword LIKE :keywords 
        OR urun_seourl LIKE :keywords
        ORDER BY $SHOR_BY 
        LIMIT :basla, :adet");

    $urunler->bindParam(':keywords', $KEYWORDS, PDO::PARAM_STR);
    $urunler->bindParam(':basla', $sayfa_katsayisi, PDO::PARAM_INT);
    $urunler->bindParam(':adet', $goruntuleme_sayisi, PDO::PARAM_INT);
    $urunler->execute();
}


$sayfasayisi=ceil($urunsayisi/$goruntuleme_sayisi);
if ($sayfa > $sayfasayisi) { $sayfa=$sayfasayisi; }

           // echo "urunsayisi : ".$urunsayisi."</br>";
           // echo "goruntuleme_sayisi : ".$goruntuleme_sayisi."</br>";
           // echo "sayfa_ADRESİ : ".$_SERVER['PHP_SELF']."</br>";
           // echo "sayfa_katsayisi : ".$sayfa_katsayisi."</br>";

        if ($urunler) {
            // code...
        }
    if ($sayfa_katsayisi+$goruntuleme_sayisi > $urunsayisi) {
        $sayfagoruntulenenson = $urunsayisi;
    } else {
        $sayfagoruntulenenson =  $sayfa_katsayisi+$goruntuleme_sayisi;
    }
 ?>



    <main class="main__content_wrapper">
        
        <!-- Start breadcrumb section -->
        <section class="breadcrumb__section breadcrumb__bg">
            <div class="container">
                <div class="row row-cols-1">
                    <div class="col">
                        <div class="breadcrumb__content text-center">
                            <ul class="breadcrumb__content--menu d-flex justify-content-center">
                                <li class="breadcrumb__content--menu__items"><a href="index">Home</a></li>
                                <li class="breadcrumb__content--menu__items"><span>Shop</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End breadcrumb section -->

        <!-- Start shop section -->
        <div class="shop__section section--padding">
            <div class="container">
                <?php
                    $filtreler = [];

                    if(@$_GET['arac_tipi']) {
                        $arac_tipi = $_GET['arac_tipi'];
                        $arac_tipi_ad = $db->query("SELECT arac_tipi_ad FROM arac_tipi WHERE arac_tipi_id = '$arac_tipi'")->fetch(PDO::FETCH_ASSOC);
                        if($arac_tipi_ad) {
                            $filtreler[] = $arac_tipi_ad['arac_tipi_ad'];
                        }
                    }

                    if(@$_GET['kategori']) {
                        $kategori = $_GET['kategori'];
                        $kategori_ad = $db->query("SELECT kategori_ad FROM kategori WHERE kategori_id = '$kategori'")->fetch(PDO::FETCH_ASSOC);
                        if($kategori_ad) {
                            $filtreler[] = $kategori_ad['kategori_ad'];
                        }
                    }
                    ?>

                    <?php if(!empty($filtreler)): ?>

                    <div class="container my-4">
                        <div class="card shadow-sm border-0">
                            <div class="card-body d-flex flex-wrap align-items-center justify-content-between">
                                <div>
                                    <h5 class="mb-2">Uygulanan Filtreler</h5>
                                    <?php foreach($filtreler as $filtre): ?>
                                        <span class="badge bg-primary me-2 mb-2 p-2 px-3 fs-6">
                                            <?= htmlspecialchars($filtre) ?>
                                        </span>

                                    <?php endforeach; ?>
                                </div>
                                <div>
                                    <a href="shop" class="btn btn-outline-danger">
                                        <i class="fa-solid fa-xmark"></i> Filtreyi Temizle
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-xl-3 col-lg-4 shop-col-width-lg-4">
                        <div class="shop__sidebar--widget widget__area d-none d-lg-block">
                            <div class="single__widget widget__bg">
                                <h2 class="widget__title h3">Vehicle Type</h2>
                                <ul class="widget__categories--menu">
                                    <?php
                                        $arac_tipleri = $db->query("SELECT * FROM arac_tipi");
                                        while ($arac_tipi = $arac_tipleri->fetch(PDO::FETCH_ASSOC)) {
                                        ?>
                                            <li class="widget__categories--menu__list">
                                                <label class="widget__categories--menu__label d-flex align-items-center">
                                                    <img class="widget__categories--menu__img" src="assets/img/car/unimog.png" alt="categories-img">
                                                    <span class="widget__categories--menu__text"><?=$arac_tipi['arac_tipi_ad']?></span>
                                                    <svg class="widget__categories--menu__arrowdown--icon" xmlns="http://www.w3.org/2000/svg" width="12.355" height="8.394">
                                                        <path  d="M15.138,8.59l-3.961,3.952L7.217,8.59,6,9.807l5.178,5.178,5.178-5.178Z" transform="translate(-6 -8.59)" fill="currentColor"></path>
                                                    </svg>
                                                </label>
                                                <ul class="widget__categories--sub__menu">
                                                    <?php
                                                        $kategoriler = $db->query("SELECT * FROM kategori");
                                                        while ($kategori = $kategoriler->fetch(PDO::FETCH_ASSOC)) {
                                                    ?>
                                                    <li class="widget__categories--sub__menu--list">
                                                        <a class="widget__categories--sub__menu--link d-flex align-items-center" href="shop.php?kategori=<?= $kategori["kategori_id"] ?>&arac_tipi=<?= $arac_tipi["arac_tipi_id"] ?>&v">
                                                            <img class="widget__categories--sub__menu--img" src="assets/img/product/main-product/product2.webp" alt="categories-img">
                                                            <span class="widget__categories--sub__menu--text"><?= $kategori["kategori_ad"] ?></span>
                                                        </a>
                                                    </li>
                                                    <?php } ?>
                                                </ul>
                                            </li>
                                        <?php }
                                    ?>
                                </ul>
                            </div>
                            
                            <!--
                            <div class="single__widget widget__bg">
                                <h2 class="widget__title h3">Dietary Needs</h2>
                                <ul class="widget__form--check">
                                    <li class="widget__form--check__list">
                                        <label class="widget__form--check__label" for="check1">Body Parts</label>
                                        <input class="widget__form--check__input" id="check1" type="checkbox">
                                        <span class="widget__form--checkmark"></span>
                                    </li>
                                    <li class="widget__form--check__list">
                                        <label class="widget__form--check__label" for="check2">Oiles Fluids</label>
                                        <input class="widget__form--check__input" id="check2" type="checkbox">
                                        <span class="widget__form--checkmark"></span>
                                    </li>
                                    <li class="widget__form--check__list">
                                        <label class="widget__form--check__label" for="check3">Car care kits</label>
                                        <input class="widget__form--check__input" id="check3" type="checkbox">
                                        <span class="widget__form--checkmark"></span>
                                    </li>
                                    <li class="widget__form--check__list">
                                        <label class="widget__form--check__label" for="check4">Brake disks</label>
                                        <input class="widget__form--check__input" id="check4" type="checkbox">
                                        <span class="widget__form--checkmark"></span>
                                    </li>
                                    <li class="widget__form--check__list">
                                        <label class="widget__form--check__label" for="check5">Repair Parts</label>
                                        <input class="widget__form--check__input" id="check5" type="checkbox">
                                        <span class="widget__form--checkmark"></span>
                                    </li>
                                </ul>
                            </div>
                            <div class="single__widget price__filter widget__bg">
                                <h2 class="widget__title h3">Filter By Price</h2>
                                <form class="price__filter--form" action="#"> 
                                    <div class="price__filter--form__inner mb-15 d-flex align-items-center">
                                        <div class="price__filter--group">
                                            <label class="price__filter--label" for="Filter-Price-GTE2">From</label>
                                            <div class="price__filter--input border-radius-5 d-flex align-items-center">
                                                <span class="price__filter--currency">$</span>
                                                <input class="price__filter--input__field border-0" name="filter.v.price.gte" id="Filter-Price-GTE2" type="number" placeholder="0" min="0" max="250.00">
                                            </div>
                                        </div>
                                        <div class="price__divider">
                                            <span>-</span>
                                        </div>
                                        <div class="price__filter--group">
                                            <label class="price__filter--label" for="Filter-Price-LTE2">To</label>
                                            <div class="price__filter--input border-radius-5 d-flex align-items-center">
                                                <span class="price__filter--currency">$</span>
                                                <input class="price__filter--input__field border-0" name="filter.v.price.lte" id="Filter-Price-LTE2" type="number" min="0" placeholder="250.00" max="250.00"> 
                                            </div>	
                                        </div>
                                    </div>
                                    <button class="primary__btn price__filter--btn" type="submit">Filter</button>
                                </form>
                            </div>
                        -->
                            <div class="single__widget widget__bg">
                                <h2 class="widget__title h3">Most Wanted Product</h2>
                                <div class="shop__sidebar--product">
                                    <?php
                                        $mostQuery = $db->query("
                                            SELECT u.*, k.kategori_ad, COUNT(w.wishlist_id) as wishlist_count
                                            FROM wishlist as w
                                            LEFT JOIN urun as u ON w.urun_id = u.urun_id
                                            LEFT JOIN kategori as k ON u.kategori_id = k.kategori_id
                                            GROUP BY w.urun_id
                                            ORDER BY wishlist_count DESC
                                            LIMIT 5
                                        ");
                                        while ($most = $mostQuery->fetch(PDO::FETCH_ASSOC)) {
                                    ?>
                                        <div class="small__product--card d-flex">
                                            <div class="small__product--thumbnail">
                                                <a class="display-block" href="product-details.html"><img src="assets/img/product/small-product/product6.webp" alt="product-img"></a>
                                            </div>
                                            <div class="small__product--content">
                                                <h3 class="small__product--card__title"><a href="product-details.html"><?php echo $most['urun_ad']; ?></a></h3>
                                                <p class="small__product--card__title text-muted"><?php echo $most['kategori_ad']; ?></h3>

                                                <!-- <ul class="rating small__product--rating d-flex">
                                                    <li class="rating__list">
                                                        <span class="rating__icon">
                                                            <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M6.08398 0.921875L4.56055 4.03906L1.11523 4.53125C0.505859 4.625 0.271484 5.375 0.716797 5.82031L3.17773 8.23438L2.5918 11.6328C2.49805 12.2422 3.1543 12.7109 3.69336 12.4297L6.76367 10.8125L9.81055 12.4297C10.3496 12.7109 11.0059 12.2422 10.9121 11.6328L10.3262 8.23438L12.7871 5.82031C13.2324 5.375 12.998 4.625 12.3887 4.53125L8.9668 4.03906L7.41992 0.921875C7.16211 0.382812 6.36523 0.359375 6.08398 0.921875Z" fill="currentColor"/>
                                                            </svg>
                                                        </span>
                                                    </li>
                                                    <li class="rating__list">
                                                        <span class="rating__icon">
                                                            <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M6.08398 0.921875L4.56055 4.03906L1.11523 4.53125C0.505859 4.625 0.271484 5.375 0.716797 5.82031L3.17773 8.23438L2.5918 11.6328C2.49805 12.2422 3.1543 12.7109 3.69336 12.4297L6.76367 10.8125L9.81055 12.4297C10.3496 12.7109 11.0059 12.2422 10.9121 11.6328L10.3262 8.23438L12.7871 5.82031C13.2324 5.375 12.998 4.625 12.3887 4.53125L8.9668 4.03906L7.41992 0.921875C7.16211 0.382812 6.36523 0.359375 6.08398 0.921875Z" fill="currentColor"/>
                                                            </svg>
                                                        </span>
                                                    </li>
                                                    <li class="rating__list">
                                                        <span class="rating__icon">
                                                            <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M6.08398 0.921875L4.56055 4.03906L1.11523 4.53125C0.505859 4.625 0.271484 5.375 0.716797 5.82031L3.17773 8.23438L2.5918 11.6328C2.49805 12.2422 3.1543 12.7109 3.69336 12.4297L6.76367 10.8125L9.81055 12.4297C10.3496 12.7109 11.0059 12.2422 10.9121 11.6328L10.3262 8.23438L12.7871 5.82031C13.2324 5.375 12.998 4.625 12.3887 4.53125L8.9668 4.03906L7.41992 0.921875C7.16211 0.382812 6.36523 0.359375 6.08398 0.921875Z" fill="currentColor"/>
                                                            </svg>
                                                        </span>
                                                    </li>
                                                    <li class="rating__list">
                                                        <span class="rating__icon">
                                                            <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M6.08398 0.921875L4.56055 4.03906L1.11523 4.53125C0.505859 4.625 0.271484 5.375 0.716797 5.82031L3.17773 8.23438L2.5918 11.6328C2.49805 12.2422 3.1543 12.7109 3.69336 12.4297L6.76367 10.8125L9.81055 12.4297C10.3496 12.7109 11.0059 12.2422 10.9121 11.6328L10.3262 8.23438L12.7871 5.82031C13.2324 5.375 12.998 4.625 12.3887 4.53125L8.9668 4.03906L7.41992 0.921875C7.16211 0.382812 6.36523 0.359375 6.08398 0.921875Z" fill="currentColor"/>
                                                            </svg>
                                                        </span>
                                                    </li>
                                                    <li class="rating__list">
                                                        <span class="rating__icon"> 
                                                            <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M12.4141 4.53125L8.99219 4.03906L7.44531 0.921875C7.1875 0.382812 6.39062 0.359375 6.10938 0.921875L4.58594 4.03906L1.14062 4.53125C0.53125 4.625 0.296875 5.375 0.742188 5.82031L3.20312 8.23438L2.61719 11.6328C2.52344 12.2422 3.17969 12.7109 3.71875 12.4297L6.78906 10.8125L9.83594 12.4297C10.375 12.7109 11.0312 12.2422 10.9375 11.6328L10.3516 8.23438L12.8125 5.82031C13.2578 5.375 13.0234 4.625 12.4141 4.53125ZM9.53125 7.95312L10.1875 11.75L6.78906 9.96875L3.36719 11.75L4.02344 7.95312L1.25781 5.28125L5.07812 4.71875L6.78906 1.25L8.47656 4.71875L12.2969 5.28125L9.53125 7.95312Z" fill="currentColor"/>
                                                            </svg>
                                                        </span>
                                                    </li>
                                                </ul> -->
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="single__widget widget__bg">
                                <h2 class="widget__title h3">Recently Viewed</h2>
                                <div class="shop__sidebar--product">
                                    <?php
                                        $mostQuery = $db->query("SELECT * FROM urun as u LEFT JOIN kategori as k ON u.kategori_id = k.kategori_id ORDER BY urun_id DESC LIMIT 5");
                                        while ($most = $mostQuery->fetch(PDO::FETCH_ASSOC)) {
                                    ?>
                                        <div class="small__product--card d-flex">
                                            <div class="small__product--thumbnail">
                                                <a class="display-block" href="product-details.html"><img src="assets/img/product/small-product/product6.webp" alt="product-img"></a>
                                            </div>
                                            <div class="small__product--content">
                                                <h3 class="small__product--card__title"><a href="product-details.html"><?php echo $most['urun_ad']; ?></a></h3>
                                                <p class="small__product--card__title text-muted"><?php echo $most['kategori_ad']; ?></h3>

                                                <!-- <ul class="rating small__product--rating d-flex">
                                                    <li class="rating__list">
                                                        <span class="rating__icon">
                                                            <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M6.08398 0.921875L4.56055 4.03906L1.11523 4.53125C0.505859 4.625 0.271484 5.375 0.716797 5.82031L3.17773 8.23438L2.5918 11.6328C2.49805 12.2422 3.1543 12.7109 3.69336 12.4297L6.76367 10.8125L9.81055 12.4297C10.3496 12.7109 11.0059 12.2422 10.9121 11.6328L10.3262 8.23438L12.7871 5.82031C13.2324 5.375 12.998 4.625 12.3887 4.53125L8.9668 4.03906L7.41992 0.921875C7.16211 0.382812 6.36523 0.359375 6.08398 0.921875Z" fill="currentColor"/>
                                                            </svg>
                                                        </span>
                                                    </li>
                                                    <li class="rating__list">
                                                        <span class="rating__icon">
                                                            <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M6.08398 0.921875L4.56055 4.03906L1.11523 4.53125C0.505859 4.625 0.271484 5.375 0.716797 5.82031L3.17773 8.23438L2.5918 11.6328C2.49805 12.2422 3.1543 12.7109 3.69336 12.4297L6.76367 10.8125L9.81055 12.4297C10.3496 12.7109 11.0059 12.2422 10.9121 11.6328L10.3262 8.23438L12.7871 5.82031C13.2324 5.375 12.998 4.625 12.3887 4.53125L8.9668 4.03906L7.41992 0.921875C7.16211 0.382812 6.36523 0.359375 6.08398 0.921875Z" fill="currentColor"/>
                                                            </svg>
                                                        </span>
                                                    </li>
                                                    <li class="rating__list">
                                                        <span class="rating__icon">
                                                            <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M6.08398 0.921875L4.56055 4.03906L1.11523 4.53125C0.505859 4.625 0.271484 5.375 0.716797 5.82031L3.17773 8.23438L2.5918 11.6328C2.49805 12.2422 3.1543 12.7109 3.69336 12.4297L6.76367 10.8125L9.81055 12.4297C10.3496 12.7109 11.0059 12.2422 10.9121 11.6328L10.3262 8.23438L12.7871 5.82031C13.2324 5.375 12.998 4.625 12.3887 4.53125L8.9668 4.03906L7.41992 0.921875C7.16211 0.382812 6.36523 0.359375 6.08398 0.921875Z" fill="currentColor"/>
                                                            </svg>
                                                        </span>
                                                    </li>
                                                    <li class="rating__list">
                                                        <span class="rating__icon">
                                                            <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M6.08398 0.921875L4.56055 4.03906L1.11523 4.53125C0.505859 4.625 0.271484 5.375 0.716797 5.82031L3.17773 8.23438L2.5918 11.6328C2.49805 12.2422 3.1543 12.7109 3.69336 12.4297L6.76367 10.8125L9.81055 12.4297C10.3496 12.7109 11.0059 12.2422 10.9121 11.6328L10.3262 8.23438L12.7871 5.82031C13.2324 5.375 12.998 4.625 12.3887 4.53125L8.9668 4.03906L7.41992 0.921875C7.16211 0.382812 6.36523 0.359375 6.08398 0.921875Z" fill="currentColor"/>
                                                            </svg>
                                                        </span>
                                                    </li>
                                                    <li class="rating__list">
                                                        <span class="rating__icon"> 
                                                            <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M12.4141 4.53125L8.99219 4.03906L7.44531 0.921875C7.1875 0.382812 6.39062 0.359375 6.10938 0.921875L4.58594 4.03906L1.14062 4.53125C0.53125 4.625 0.296875 5.375 0.742188 5.82031L3.20312 8.23438L2.61719 11.6328C2.52344 12.2422 3.17969 12.7109 3.71875 12.4297L6.78906 10.8125L9.83594 12.4297C10.375 12.7109 11.0312 12.2422 10.9375 11.6328L10.3516 8.23438L12.8125 5.82031C13.2578 5.375 13.0234 4.625 12.4141 4.53125ZM9.53125 7.95312L10.1875 11.75L6.78906 9.96875L3.36719 11.75L4.02344 7.95312L1.25781 5.28125L5.07812 4.71875L6.78906 1.25L8.47656 4.71875L12.2969 5.28125L9.53125 7.95312Z" fill="currentColor"/>
                                                            </svg>
                                                        </span>
                                                    </li>
                                                </ul> -->
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="single__widget widget__bg">
                                <h2 class="widget__title h3">Brands</h2>
                                <ul class="widget__tagcloud">
                                    <li class="widget__tagcloud--list"><a class="widget__tagcloud--link" href="shop.html"> Unimog
                                    </a></li>
                                    <li class="widget__tagcloud--list"><a class="widget__tagcloud--link" href="shop.html">Kirpi</a></li>
                                    <li class="widget__tagcloud--list"><a class="widget__tagcloud--link" href="shop.html">ZPT</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>








<!-- kategori başlangıç -->

                    <div class="col-xl-9 col-lg-8 shop-col-width-lg-8">
                        <div class="shop__right--sidebar">            
                            <div class="search__filter--inner style mb-3">
                                <form class="search__filter--form__style2 d-flex" method="get" action="shop.php">
                                    <input type="hidden" name="s">
                                    <div class="search__filter--select w-50 select search__filter--width">
                                        <select class="search__filter--select__field" name="arac_tipi" required>
                                            <option selected value="">Select Vehicle</option>
                                            <?php
                                                $arac_tipleri = $db->query("SELECT * FROM arac_tipi");
                                                while ($arac_tipi = $arac_tipleri->fetch(PDO::FETCH_ASSOC)) {
                                                ?>
                                                    <option <?=$arac_tipi['arac_tipi_id'] == @$_GET['arac_tipi'] ? 'selected' : ''?> value="<?php echo $arac_tipi['arac_tipi_id']; ?>"><?php echo $arac_tipi['arac_tipi_ad']; ?></option>
                                                <?php }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="search__filter--select w-50 select search__filter--width">
                                        <select class="search__filter--select__field" name="kategori">
                                            <option selected value="">Select Unit</option>
                                            <?php
                                                $kategoriler = $db->query("SELECT * FROM kategori");
                                                while ($kategori = $kategoriler->fetch(PDO::FETCH_ASSOC)) {
                                            ?>
                                                <option <?=$kategori['kategori_id'] == @$_GET['kategori'] ? 'selected' : ''?> value="<?php echo $kategori['kategori_id']; ?>"><?php echo $kategori['kategori_ad']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="search__filter--width">
                                        <button class="search__filter--btn primary__btn" type="submit">Search</button>
                                    </div>
                                </form>
                            </div>

                            <div class="shop__product--wrapper">
                                <div class="shop__header d-flex align-items-center justify-content-between mb-30">
                                    <div class="product__view--mode d-flex align-items-center">
                                        <button class="widget__filter--btn d-flex d-lg-none align-items-center" data-offcanvas>
                                            <svg  class="widget__filter--btn__icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="28" d="M368 128h80M64 128h240M368 384h80M64 384h240M208 256h240M64 256h80"/><circle cx="336" cy="128" r="28" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="28"/><circle cx="176" cy="256" r="28" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="28"/><circle cx="336" cy="384" r="28" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="28"/></svg> 
                                            <span class="widget__filter--btn__text">Filter</span>
                                        </button>
                                        <div class="product__view--mode__list product__short--by align-items-center d-flex ">
                                            <label class="product__view--label">Prev Page :</label>
                                            <div class="select shop__header--select">
                                                <select class="product__view--select" onchange="selectPage(this.value)">
                                                    <?php  
                                                        $sirano=0;
                                                        while ( $sirano < $sayfasayisi ) {
                                                                $sirano ++;
                                                            if($sirano == $sayfa) { 
                                                                echo '<option selected value="'.$sirano.'&q='.$_GET['q'].'">'.$sirano.'</option>';
                                                            }
                                                            else {
                                                                echo '<option value="'.$sirano.'&q='.$_GET['q'].'">'.$sirano.'</option>';
                                                            }
                                                        }
                                                    ?> 
                                                </select>
                                            </div>
                                        </div>
                                        <div class="product__view--mode__list product__short--by align-items-center d-flex">
                                            <label class="product__view--label">Sort By :</label>
                                            <div class="select shop__header--select">
                                                <form action="" method="post">
                                                    <select class="product__view--select" name="shor_by" onchange="this.form.submit()">
                                                        <option <?=$SHOR_BY == 'urun_id DESC' ? 'selected' : ''?> value="urun_id DESC">Sort by latest</option>
                                                        <!-- <option value="2">Sort by popularity</option> -->
                                                        <option <?=$SHOR_BY == 'urun_zaman DESC' ? 'selected' : ''?> value="urun_zaman DESC">Sort by newness</option>
                                                        <!-- <option value="4">Sort by  rating </option> -->
                                                    </select>
                                                </form>
                                            </div>
                                             
                                        </div>

                                        <!--
                                        <div class="product__view--mode__list">
                                            <div class="product__tab--one product__grid--column__buttons d-flex justify-content-center">
                                                <button class="product__grid--column__buttons--icons active" aria-label="grid btn" data-toggle="tab" data-target="#product_grid">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 9 9">
                                                        <g  transform="translate(-1360 -479)">
                                                          <rect id="Rectangle_5725" data-name="Rectangle 5725" width="4" height="4" transform="translate(1360 479)" fill="currentColor"/>
                                                          <rect id="Rectangle_5727" data-name="Rectangle 5727" width="4" height="4" transform="translate(1360 484)" fill="currentColor"/>
                                                          <rect id="Rectangle_5726" data-name="Rectangle 5726" width="4" height="4" transform="translate(1365 479)" fill="currentColor"/>
                                                          <rect id="Rectangle_5728" data-name="Rectangle 5728" width="4" height="4" transform="translate(1365 484)" fill="currentColor"/>
                                                        </g>
                                                    </svg>
                                                </button>
                                                <button class="product__grid--column__buttons--icons" aria-label="list btn" data-toggle="tab" data-target="#product_list">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="16" viewBox="0 0 13 8">
                                                        <g id="Group_14700" data-name="Group 14700" transform="translate(-1376 -478)">
                                                          <g  transform="translate(12 -2)">
                                                            <g id="Group_1326" data-name="Group 1326">
                                                              <rect id="Rectangle_5729" data-name="Rectangle 5729" width="3" height="2" transform="translate(1364 483)" fill="currentColor"/>
                                                              <rect id="Rectangle_5730" data-name="Rectangle 5730" width="9" height="2" transform="translate(1368 483)" fill="currentColor"/>
                                                            </g>
                                                            <g id="Group_1328" data-name="Group 1328" transform="translate(0 -3)">
                                                              <rect id="Rectangle_5729-2" data-name="Rectangle 5729" width="3" height="2" transform="translate(1364 483)" fill="currentColor"/>
                                                              <rect id="Rectangle_5730-2" data-name="Rectangle 5730" width="9" height="2" transform="translate(1368 483)" fill="currentColor"/>
                                                            </g>
                                                            <g id="Group_1327" data-name="Group 1327" transform="translate(0 -1)">
                                                              <rect id="Rectangle_5731" data-name="Rectangle 5731" width="3" height="2" transform="translate(1364 487)" fill="currentColor"/>
                                                              <rect id="Rectangle_5732" data-name="Rectangle 5732" width="9" height="2" transform="translate(1368 487)" fill="currentColor"/>
                                                            </g>
                                                          </g>
                                                        </g>
                                                      </svg>
                                                </button>
                                            </div>
                                        </div>
                                            -->
                                    </div>

                                    <p class="product__showing--count">Showing <?php echo $sayfa_katsayisi+1 ?> – <?php echo $sayfagoruntulenenson ; ?>  of <?php echo $urunsayisi ?> results</p>
                                </div>
                                <div class="tab_content">
                                    <div id="product_grid" class="tab_pane active show">
                                        <div class="product__section--inner">
                                            <div class="row mb--n30">




                                                <!-- kart tekrar başlangıç -->      
                                                <?php       
                                                    $urunsayisi = $urunler->rowCount();
                                                    if($urunsayisi <= 0)
                                                    {
                                                        echo "<center>Ürün bulunamadı</center>";
                                                    }
                                                    else
                                                    {
                                                    while ($urunlercek = $urunler->fetch(PDO::FETCH_ASSOC)) {

                                                ?>
                                                        <div class="col-lg-4 col-md-4 col-sm-6 col-6 custom-col mb-30">
                                                            <article class="product__card">
                                                                <div class="product__card--thumbnail">
                                                                    <a href="/product-details/<?= rawurlencode($urunlercek['urun_seourl']) ?>/<?= (int)$urunlercek['urun_id'] ?>">
                                                                        <img class="product__card--thumbnail__img product__primary--img" src="assets/img/product/main-product/product5.webp" alt="product-img">
                                                                        <img class="product__card--thumbnail__img product__secondary--img" src="assets/img/product/main-product/product6.webp" alt="product-img">
                                                                    </a>
                                                                    <!-- <span class="product__badge">-14%</span> -->
                                                                    <ul class="product__card--action d-flex align-items-center justify-content-center">
                                                                        <li class="product__card--action__list">
                                                                            <a class="product__card--action__btn" title="Quick View" data-open="modal1" href="javascript:void(0)">
                                                                                <svg class="product__card--action__btn--svg" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                    <path d="M15.6952 14.4991L11.7663 10.5588C12.7765 9.4008 13.33 7.94381 13.33 6.42703C13.33 2.88322 10.34 0 6.66499 0C2.98997 0 0 2.88322 0 6.42703C0 9.97085 2.98997 12.8541 6.66499 12.8541C8.04464 12.8541 9.35938 12.4528 10.4834 11.6911L14.4422 15.6613C14.6076 15.827 14.8302 15.9184 15.0687 15.9184C15.2944 15.9184 15.5086 15.8354 15.6711 15.6845C16.0166 15.364 16.0276 14.8325 15.6952 14.4991ZM6.66499 1.67662C9.38141 1.67662 11.5913 3.8076 11.5913 6.42703C11.5913 9.04647 9.38141 11.1775 6.66499 11.1775C3.94857 11.1775 1.73869 9.04647 1.73869 6.42703C1.73869 3.8076 3.94857 1.67662 6.66499 1.67662Z" fill="currentColor"></path>
                                                                                </svg>
                                                                                <span class="visually-hidden">Quick View</span>  
                                                                            </a>
                                                                        </li>
                                                                        <li class="product__card--action__list">
                                                                            <button class="product__card--action__btn add-to-wishlist" data-id="<?= $urunlercek['urun_id'] ?>">
                                                                                <?php
                                                                                    $user_id = $_SESSION['USER']['kullanici_id'];
                                                                                    $urun_id = $urunlercek['urun_id'];

                                                                                    $kontrol = $db->prepare("SELECT * FROM wishlist WHERE kullanici_id = ? AND urun_id = ?");
                                                                                    $kontrol->execute([$user_id, $urun_id]);

                                                                                    if ($kontrol->rowCount()) {
                                                                                        echo '<i class="fa-solid fa-heart"></i>';
                                                                                    }else{
                                                                                        echo '<i class="fa-regular fa-heart"></i>';
                                                                                    }
                                                                                ?>
                                                                            </button>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                                <div class="product__card--content">
                                                                    <ul class="rating product__card--rating d-flex">
                                                                        <li class="rating__list">
                                                                            <span class="rating__icon">
                                                                                <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                <path d="M6.08398 0.921875L4.56055 4.03906L1.11523 4.53125C0.505859 4.625 0.271484 5.375 0.716797 5.82031L3.17773 8.23438L2.5918 11.6328C2.49805 12.2422 3.1543 12.7109 3.69336 12.4297L6.76367 10.8125L9.81055 12.4297C10.3496 12.7109 11.0059 12.2422 10.9121 11.6328L10.3262 8.23438L12.7871 5.82031C13.2324 5.375 12.998 4.625 12.3887 4.53125L8.9668 4.03906L7.41992 0.921875C7.16211 0.382812 6.36523 0.359375 6.08398 0.921875Z" fill="currentColor"/>
                                                                                </svg>
                                                                            </span>
                                                                        </li>
                                                                        <li class="rating__list">
                                                                            <span class="rating__icon">
                                                                                <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                <path d="M6.08398 0.921875L4.56055 4.03906L1.11523 4.53125C0.505859 4.625 0.271484 5.375 0.716797 5.82031L3.17773 8.23438L2.5918 11.6328C2.49805 12.2422 3.1543 12.7109 3.69336 12.4297L6.76367 10.8125L9.81055 12.4297C10.3496 12.7109 11.0059 12.2422 10.9121 11.6328L10.3262 8.23438L12.7871 5.82031C13.2324 5.375 12.998 4.625 12.3887 4.53125L8.9668 4.03906L7.41992 0.921875C7.16211 0.382812 6.36523 0.359375 6.08398 0.921875Z" fill="currentColor"/>
                                                                                </svg>
                                                                            </span>
                                                                        </li>
                                                                        <li class="rating__list">
                                                                            <span class="rating__icon">
                                                                                <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                <path d="M6.08398 0.921875L4.56055 4.03906L1.11523 4.53125C0.505859 4.625 0.271484 5.375 0.716797 5.82031L3.17773 8.23438L2.5918 11.6328C2.49805 12.2422 3.1543 12.7109 3.69336 12.4297L6.76367 10.8125L9.81055 12.4297C10.3496 12.7109 11.0059 12.2422 10.9121 11.6328L10.3262 8.23438L12.7871 5.82031C13.2324 5.375 12.998 4.625 12.3887 4.53125L8.9668 4.03906L7.41992 0.921875C7.16211 0.382812 6.36523 0.359375 6.08398 0.921875Z" fill="currentColor"/>
                                                                                </svg>
                                                                            </span>
                                                                        </li>
                                                                        <li class="rating__list">
                                                                            <span class="rating__icon"> 
                                                                                <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                    <path d="M12.4141 4.53125L8.99219 4.03906L7.44531 0.921875C7.1875 0.382812 6.39062 0.359375 6.10938 0.921875L4.58594 4.03906L1.14062 4.53125C0.53125 4.625 0.296875 5.375 0.742188 5.82031L3.20312 8.23438L2.61719 11.6328C2.52344 12.2422 3.17969 12.7109 3.71875 12.4297L6.78906 10.8125L9.83594 12.4297C10.375 12.7109 11.0312 12.2422 10.9375 11.6328L10.3516 8.23438L12.8125 5.82031C13.2578 5.375 13.0234 4.625 12.4141 4.53125ZM9.53125 7.95312L10.1875 11.75L6.78906 9.96875L3.36719 11.75L4.02344 7.95312L1.25781 5.28125L5.07812 4.71875L6.78906 1.25L8.47656 4.71875L12.2969 5.28125L9.53125 7.95312Z" fill="currentColor"/>
                                                                                    </svg>
                                                                            </span>
                                                                        </li>
                                                                        <li class="rating__list">
                                                                            <span class="rating__icon"> 
                                                                                <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                    <path d="M12.4141 4.53125L8.99219 4.03906L7.44531 0.921875C7.1875 0.382812 6.39062 0.359375 6.10938 0.921875L4.58594 4.03906L1.14062 4.53125C0.53125 4.625 0.296875 5.375 0.742188 5.82031L3.20312 8.23438L2.61719 11.6328C2.52344 12.2422 3.17969 12.7109 3.71875 12.4297L6.78906 10.8125L9.83594 12.4297C10.375 12.7109 11.0312 12.2422 10.9375 11.6328L10.3516 8.23438L12.8125 5.82031C13.2578 5.375 13.0234 4.625 12.4141 4.53125ZM9.53125 7.95312L10.1875 11.75L6.78906 9.96875L3.36719 11.75L4.02344 7.95312L1.25781 5.28125L5.07812 4.71875L6.78906 1.25L8.47656 4.71875L12.2969 5.28125L9.53125 7.95312Z" fill="currentColor"/>
                                                                                    </svg>
                                                                            </span>
                                                                        </li>
                                                                        <li>
                                                                            <span class="rating__review--text">(126) Review</span>
                                                                        </li>
                                                                    </ul>
                                                                    <h3 class="product__card--title"><a href="/product-details/<?= rawurlencode($urunlercek['urun_seourl']) ?>/<?= (int)$urunlercek['urun_id'] ?>"><?php echo $urunlercek["urun_ad"]; ?></a></h3>
                                                                    <!-- <div class="product__card--price">
                                                                        <span class="current__price">$239.52</span>
                                                                        <span class="old__price"> $362.00</span>
                                                                    </div> -->
                                                                    <div class="product__card--footer">
                                                                        <button class="product__card--btn primary__btn add-to-cart" data-id="<?= (int)$urunlercek['urun_id'] ?>">
                                                                            <svg width="14" height="11" viewBox="0 0 14 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                            <path d="M13.2371 4H11.5261L8.5027 0.460938C8.29176 0.226562 7.9402 0.203125 7.70582 0.390625C7.47145 0.601562 7.44801 0.953125 7.63551 1.1875L10.0496 4H3.46364L5.8777 1.1875C6.0652 0.953125 6.04176 0.601562 5.80739 0.390625C5.57301 0.203125 5.22145 0.226562 5.01051 0.460938L1.98707 4H0.299574C0.135511 4 0.0183239 4.14062 0.0183239 4.28125V4.84375C0.0183239 5.00781 0.135511 5.125 0.299574 5.125H0.721449L1.3777 9.78906C1.44801 10.3516 1.91676 10.75 2.47926 10.75H11.0339C11.5964 10.75 12.0652 10.3516 12.1355 9.78906L12.7918 5.125H13.2371C13.3777 5.125 13.5183 5.00781 13.5183 4.84375V4.28125C13.5183 4.14062 13.3777 4 13.2371 4ZM11.0339 9.625H2.47926L1.86989 5.125H11.6433L11.0339 9.625ZM7.33082 6.4375C7.33082 6.13281 7.07301 5.875 6.76832 5.875C6.4402 5.875 6.20582 6.13281 6.20582 6.4375V8.3125C6.20582 8.64062 6.4402 8.875 6.76832 8.875C7.07301 8.875 7.33082 8.64062 7.33082 8.3125V6.4375ZM9.95582 6.4375C9.95582 6.13281 9.69801 5.875 9.39332 5.875C9.0652 5.875 8.83082 6.13281 8.83082 6.4375V8.3125C8.83082 8.64062 9.0652 8.875 9.39332 8.875C9.69801 8.875 9.95582 8.64062 9.95582 8.3125V6.4375ZM4.70582 6.4375C4.70582 6.13281 4.44801 5.875 4.14332 5.875C3.8152 5.875 3.58082 6.13281 3.58082 6.4375V8.3125C3.58082 8.64062 3.8152 8.875 4.14332 8.875C4.44801 8.875 4.70582 8.64062 4.70582 8.3125V6.4375Z" fill="currentColor"/>
                                                                            </svg>
                                                                            Add to cart
                                                                        </button>
                                                                    </div>   
                                                                </div>
                                                            </article>
                                                        </div>                                 
                                                <?php 
                                                        }
                                                    }
                                                ?>                      
                                                <!-- kart tekrar son -->
                                            </div>
                                        </div>
                                    </div>
                                    <div id="product_list" class="tab_pane">
                                        <div class="product__section--inner product__section--style3__inner">
                                            <div class="row row-cols-1 mb--n30">
                                                <div class="col mb-30">
                                                    <div class="product__card product__list d-flex align-items-center">
                                                        <div class="product__card--thumbnail product__list--thumbnail">
                                                            <a class="product__card--thumbnail__link display-block" href="product-details.html">
                                                                <img class="product__card--thumbnail__img product__primary--img" src="assets/img/product/main-product/product10.webp" alt="product-img">
                                                                <img class="product__card--thumbnail__img product__secondary--img" src="assets/img/product/main-product/product9.webp" alt="product-img">
                                                            </a>
                                                            <span class="product__badge">-20%</span>
                                                            <ul class="product__card--action d-flex align-items-center justify-content-center">
                                                                <li class="product__card--action__list">
                                                                    <a class="product__card--action__btn" title="Quick View" data-open="modal1" href="javascript:void(0)">
                                                                        <svg class="product__card--action__btn--svg" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                            <path d="M15.6952 14.4991L11.7663 10.5588C12.7765 9.4008 13.33 7.94381 13.33 6.42703C13.33 2.88322 10.34 0 6.66499 0C2.98997 0 0 2.88322 0 6.42703C0 9.97085 2.98997 12.8541 6.66499 12.8541C8.04464 12.8541 9.35938 12.4528 10.4834 11.6911L14.4422 15.6613C14.6076 15.827 14.8302 15.9184 15.0687 15.9184C15.2944 15.9184 15.5086 15.8354 15.6711 15.6845C16.0166 15.364 16.0276 14.8325 15.6952 14.4991ZM6.66499 1.67662C9.38141 1.67662 11.5913 3.8076 11.5913 6.42703C11.5913 9.04647 9.38141 11.1775 6.66499 11.1775C3.94857 11.1775 1.73869 9.04647 1.73869 6.42703C1.73869 3.8076 3.94857 1.67662 6.66499 1.67662Z" fill="currentColor"></path>
                                                                        </svg>
                                                                        <span class="visually-hidden">Quick View</span>  
                                                                    </a>
                                                                </li>
                                                                <li class="product__card--action__list">
                                                                    <a class="product__card--action__btn" title="Wishlist" href="wishlist.html">
                                                                        <svg class="product__card--action__btn--svg" width="18" height="18" viewBox="0 0 16 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M13.5379 1.52734C11.9519 0.1875 9.51832 0.378906 8.01442 1.9375C6.48317 0.378906 4.04957 0.1875 2.46364 1.52734C0.412855 3.25 0.713636 6.06641 2.1902 7.57031L6.97536 12.4648C7.24879 12.7383 7.60426 12.9023 8.01442 12.9023C8.39723 12.9023 8.7527 12.7383 9.02614 12.4648L13.8386 7.57031C15.2879 6.06641 15.5886 3.25 13.5379 1.52734ZM12.8816 6.64062L8.09645 11.5352C8.04176 11.5898 7.98707 11.5898 7.90504 11.5352L3.11989 6.64062C2.10817 5.62891 1.91676 3.71484 3.31129 2.53906C4.3777 1.63672 6.01832 1.77344 7.05739 2.8125L8.01442 3.79688L8.97145 2.8125C9.98317 1.77344 11.6238 1.63672 12.6902 2.51172C14.0847 3.71484 13.8933 5.62891 12.8816 6.64062Z" fill="currentColor"/>
                                                                        </svg>
                                                                        <span class="visually-hidden">Wishlist</span> 
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="product__card--content product__list--content">
                                                            <h3 class="product__card--title"><a href="product-details.html">Eius doloribus dicta labore magni nulla! </a></h3>
                                                            <ul class="rating product__card--rating d-flex">
                                                                <li class="rating__list">
                                                                    <span class="rating__icon">
                                                                        <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M6.08398 0.921875L4.56055 4.03906L1.11523 4.53125C0.505859 4.625 0.271484 5.375 0.716797 5.82031L3.17773 8.23438L2.5918 11.6328C2.49805 12.2422 3.1543 12.7109 3.69336 12.4297L6.76367 10.8125L9.81055 12.4297C10.3496 12.7109 11.0059 12.2422 10.9121 11.6328L10.3262 8.23438L12.7871 5.82031C13.2324 5.375 12.998 4.625 12.3887 4.53125L8.9668 4.03906L7.41992 0.921875C7.16211 0.382812 6.36523 0.359375 6.08398 0.921875Z" fill="currentColor"/>
                                                                        </svg>
                                                                    </span>
                                                                </li>
                                                                <li class="rating__list">
                                                                    <span class="rating__icon">
                                                                        <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M6.08398 0.921875L4.56055 4.03906L1.11523 4.53125C0.505859 4.625 0.271484 5.375 0.716797 5.82031L3.17773 8.23438L2.5918 11.6328C2.49805 12.2422 3.1543 12.7109 3.69336 12.4297L6.76367 10.8125L9.81055 12.4297C10.3496 12.7109 11.0059 12.2422 10.9121 11.6328L10.3262 8.23438L12.7871 5.82031C13.2324 5.375 12.998 4.625 12.3887 4.53125L8.9668 4.03906L7.41992 0.921875C7.16211 0.382812 6.36523 0.359375 6.08398 0.921875Z" fill="currentColor"/>
                                                                        </svg>
                                                                    </span>
                                                                </li>
                                                                <li class="rating__list">
                                                                    <span class="rating__icon">
                                                                        <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M6.08398 0.921875L4.56055 4.03906L1.11523 4.53125C0.505859 4.625 0.271484 5.375 0.716797 5.82031L3.17773 8.23438L2.5918 11.6328C2.49805 12.2422 3.1543 12.7109 3.69336 12.4297L6.76367 10.8125L9.81055 12.4297C10.3496 12.7109 11.0059 12.2422 10.9121 11.6328L10.3262 8.23438L12.7871 5.82031C13.2324 5.375 12.998 4.625 12.3887 4.53125L8.9668 4.03906L7.41992 0.921875C7.16211 0.382812 6.36523 0.359375 6.08398 0.921875Z" fill="currentColor"/>
                                                                        </svg>
                                                                    </span>
                                                                </li>
                                                                <li class="rating__list">
                                                                    <span class="rating__icon"> 
                                                                        <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                            <path d="M12.4141 4.53125L8.99219 4.03906L7.44531 0.921875C7.1875 0.382812 6.39062 0.359375 6.10938 0.921875L4.58594 4.03906L1.14062 4.53125C0.53125 4.625 0.296875 5.375 0.742188 5.82031L3.20312 8.23438L2.61719 11.6328C2.52344 12.2422 3.17969 12.7109 3.71875 12.4297L6.78906 10.8125L9.83594 12.4297C10.375 12.7109 11.0312 12.2422 10.9375 11.6328L10.3516 8.23438L12.8125 5.82031C13.2578 5.375 13.0234 4.625 12.4141 4.53125ZM9.53125 7.95312L10.1875 11.75L6.78906 9.96875L3.36719 11.75L4.02344 7.95312L1.25781 5.28125L5.07812 4.71875L6.78906 1.25L8.47656 4.71875L12.2969 5.28125L9.53125 7.95312Z" fill="currentColor"/>
                                                                            </svg>
                                                                    </span>
                                                                </li>
                                                                <li class="rating__list">
                                                                    <span class="rating__icon"> 
                                                                        <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                            <path d="M12.4141 4.53125L8.99219 4.03906L7.44531 0.921875C7.1875 0.382812 6.39062 0.359375 6.10938 0.921875L4.58594 4.03906L1.14062 4.53125C0.53125 4.625 0.296875 5.375 0.742188 5.82031L3.20312 8.23438L2.61719 11.6328C2.52344 12.2422 3.17969 12.7109 3.71875 12.4297L6.78906 10.8125L9.83594 12.4297C10.375 12.7109 11.0312 12.2422 10.9375 11.6328L10.3516 8.23438L12.8125 5.82031C13.2578 5.375 13.0234 4.625 12.4141 4.53125ZM9.53125 7.95312L10.1875 11.75L6.78906 9.96875L3.36719 11.75L4.02344 7.95312L1.25781 5.28125L5.07812 4.71875L6.78906 1.25L8.47656 4.71875L12.2969 5.28125L9.53125 7.95312Z" fill="currentColor"/>
                                                                            </svg>
                                                                    </span>
                                                                </li>
                                                                <li>
                                                                    <span class="rating__review--text">(106) Review</span>
                                                                </li>
                                                            </ul>
                                                            <div class="product__list--price">
                                                                <span class="current__price">$188.52</span>
                                                                <span class="old__price"> $268.00</span>
                                                            </div>
                                                            <p class="product__card--content__desc mb-20">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quia voluptas dolore doloribus architecto sequi corporis deleniti officia culpa dolor esse there consectetur eligendi, natus at rem ab quae amet molestiae quod voluptates.</p>
                                                            <a class="product__card--btn primary__btn" href="cart.html">+ Add to cart</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col mb-30">
                                                    <div class="product__card product__list d-flex align-items-center">
                                                        <div class="product__card--thumbnail product__list--thumbnail">
                                                            <a class="product__card--thumbnail__link display-block" href="product-details.html">
                                                                <img class="product__card--thumbnail__img product__primary--img" src="assets/img/product/main-product/product1.webp" alt="product-img">
                                                                <img class="product__card--thumbnail__img product__secondary--img" src="assets/img/product/main-product/product2.webp" alt="product-img">
                                                            </a>
                                                            <span class="product__badge">-17%</span>
                                                            <ul class="product__card--action d-flex align-items-center justify-content-center">
                                                                <li class="product__card--action__list">
                                                                    <a class="product__card--action__btn" title="Quick View" data-open="modal1" href="javascript:void(0)">
                                                                        <svg class="product__card--action__btn--svg" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                            <path d="M15.6952 14.4991L11.7663 10.5588C12.7765 9.4008 13.33 7.94381 13.33 6.42703C13.33 2.88322 10.34 0 6.66499 0C2.98997 0 0 2.88322 0 6.42703C0 9.97085 2.98997 12.8541 6.66499 12.8541C8.04464 12.8541 9.35938 12.4528 10.4834 11.6911L14.4422 15.6613C14.6076 15.827 14.8302 15.9184 15.0687 15.9184C15.2944 15.9184 15.5086 15.8354 15.6711 15.6845C16.0166 15.364 16.0276 14.8325 15.6952 14.4991ZM6.66499 1.67662C9.38141 1.67662 11.5913 3.8076 11.5913 6.42703C11.5913 9.04647 9.38141 11.1775 6.66499 11.1775C3.94857 11.1775 1.73869 9.04647 1.73869 6.42703C1.73869 3.8076 3.94857 1.67662 6.66499 1.67662Z" fill="currentColor"></path>
                                                                        </svg>
                                                                        <span class="visually-hidden">Quick View</span>  
                                                                    </a>
                                                                </li>
                                                                <li class="product__card--action__list">
                                                                    <a class="product__card--action__btn" title="Wishlist" href="wishlist.html">
                                                                        <svg class="product__card--action__btn--svg" width="18" height="18" viewBox="0 0 16 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M13.5379 1.52734C11.9519 0.1875 9.51832 0.378906 8.01442 1.9375C6.48317 0.378906 4.04957 0.1875 2.46364 1.52734C0.412855 3.25 0.713636 6.06641 2.1902 7.57031L6.97536 12.4648C7.24879 12.7383 7.60426 12.9023 8.01442 12.9023C8.39723 12.9023 8.7527 12.7383 9.02614 12.4648L13.8386 7.57031C15.2879 6.06641 15.5886 3.25 13.5379 1.52734ZM12.8816 6.64062L8.09645 11.5352C8.04176 11.5898 7.98707 11.5898 7.90504 11.5352L3.11989 6.64062C2.10817 5.62891 1.91676 3.71484 3.31129 2.53906C4.3777 1.63672 6.01832 1.77344 7.05739 2.8125L8.01442 3.79688L8.97145 2.8125C9.98317 1.77344 11.6238 1.63672 12.6902 2.51172C14.0847 3.71484 13.8933 5.62891 12.8816 6.64062Z" fill="currentColor"/>
                                                                        </svg>
                                                                        <span class="visually-hidden">Wishlist</span> 
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>


                                                        <div class="product__card--content product__list--content">
                                                            <h3 class="product__card--title"><a href="product-details.html">Amazon Cloud Cam Security
                                                                Camera  844</a></h3>
                                                            <ul class="rating product__card--rating d-flex">
                                                                <li class="rating__list">
                                                                    <span class="rating__icon">
                                                                        <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M6.08398 0.921875L4.56055 4.03906L1.11523 4.53125C0.505859 4.625 0.271484 5.375 0.716797 5.82031L3.17773 8.23438L2.5918 11.6328C2.49805 12.2422 3.1543 12.7109 3.69336 12.4297L6.76367 10.8125L9.81055 12.4297C10.3496 12.7109 11.0059 12.2422 10.9121 11.6328L10.3262 8.23438L12.7871 5.82031C13.2324 5.375 12.998 4.625 12.3887 4.53125L8.9668 4.03906L7.41992 0.921875C7.16211 0.382812 6.36523 0.359375 6.08398 0.921875Z" fill="currentColor"/>
                                                                        </svg>
                                                                    </span>
                                                                </li>
                                                                <li class="rating__list">
                                                                    <span class="rating__icon">
                                                                        <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M6.08398 0.921875L4.56055 4.03906L1.11523 4.53125C0.505859 4.625 0.271484 5.375 0.716797 5.82031L3.17773 8.23438L2.5918 11.6328C2.49805 12.2422 3.1543 12.7109 3.69336 12.4297L6.76367 10.8125L9.81055 12.4297C10.3496 12.7109 11.0059 12.2422 10.9121 11.6328L10.3262 8.23438L12.7871 5.82031C13.2324 5.375 12.998 4.625 12.3887 4.53125L8.9668 4.03906L7.41992 0.921875C7.16211 0.382812 6.36523 0.359375 6.08398 0.921875Z" fill="currentColor"/>
                                                                        </svg>
                                                                    </span>
                                                                </li>
                                                                <li class="rating__list">
                                                                    <span class="rating__icon">
                                                                        <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M6.08398 0.921875L4.56055 4.03906L1.11523 4.53125C0.505859 4.625 0.271484 5.375 0.716797 5.82031L3.17773 8.23438L2.5918 11.6328C2.49805 12.2422 3.1543 12.7109 3.69336 12.4297L6.76367 10.8125L9.81055 12.4297C10.3496 12.7109 11.0059 12.2422 10.9121 11.6328L10.3262 8.23438L12.7871 5.82031C13.2324 5.375 12.998 4.625 12.3887 4.53125L8.9668 4.03906L7.41992 0.921875C7.16211 0.382812 6.36523 0.359375 6.08398 0.921875Z" fill="currentColor"/>
                                                                        </svg>
                                                                    </span>
                                                                </li>
                                                                <li class="rating__list">
                                                                    <span class="rating__icon"> 
                                                                        <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                            <path d="M12.4141 4.53125L8.99219 4.03906L7.44531 0.921875C7.1875 0.382812 6.39062 0.359375 6.10938 0.921875L4.58594 4.03906L1.14062 4.53125C0.53125 4.625 0.296875 5.375 0.742188 5.82031L3.20312 8.23438L2.61719 11.6328C2.52344 12.2422 3.17969 12.7109 3.71875 12.4297L6.78906 10.8125L9.83594 12.4297C10.375 12.7109 11.0312 12.2422 10.9375 11.6328L10.3516 8.23438L12.8125 5.82031C13.2578 5.375 13.0234 4.625 12.4141 4.53125ZM9.53125 7.95312L10.1875 11.75L6.78906 9.96875L3.36719 11.75L4.02344 7.95312L1.25781 5.28125L5.07812 4.71875L6.78906 1.25L8.47656 4.71875L12.2969 5.28125L9.53125 7.95312Z" fill="currentColor"/>
                                                                            </svg>
                                                                    </span>
                                                                </li>
                                                                <li class="rating__list">
                                                                    <span class="rating__icon"> 
                                                                        <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                            <path d="M12.4141 4.53125L8.99219 4.03906L7.44531 0.921875C7.1875 0.382812 6.39062 0.359375 6.10938 0.921875L4.58594 4.03906L1.14062 4.53125C0.53125 4.625 0.296875 5.375 0.742188 5.82031L3.20312 8.23438L2.61719 11.6328C2.52344 12.2422 3.17969 12.7109 3.71875 12.4297L6.78906 10.8125L9.83594 12.4297C10.375 12.7109 11.0312 12.2422 10.9375 11.6328L10.3516 8.23438L12.8125 5.82031C13.2578 5.375 13.0234 4.625 12.4141 4.53125ZM9.53125 7.95312L10.1875 11.75L6.78906 9.96875L3.36719 11.75L4.02344 7.95312L1.25781 5.28125L5.07812 4.71875L6.78906 1.25L8.47656 4.71875L12.2969 5.28125L9.53125 7.95312Z" fill="currentColor"/>
                                                                            </svg>
                                                                    </span>
                                                                </li>
                                                                <li>
                                                                    <span class="rating__review--text">(106) Review</span>
                                                                </li>
                                                            </ul>
                                                            <div class="product__list--price">
                                                                <span class="current__price">$155.52</span>
                                                                <span class="old__price"> $219.00</span>
                                                            </div>
                                                            <p class="product__card--content__desc mb-20">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quia voluptas dolore doloribus architecto sequi corporis deleniti officia culpa dolor esse there consectetur eligendi, natus at rem ab quae amet molestiae quod voluptates.</p>
                                                            <a class="product__card--btn primary__btn" href="cart.html">+ Add to cart</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col mb-30">
                                                    <div class="product__card product__list d-flex align-items-center">
                                                        <div class="product__card--thumbnail product__list--thumbnail">
                                                            <a class="product__card--thumbnail__link display-block" href="product-details.html">
                                                                <img class="product__card--thumbnail__img product__primary--img" src="assets/img/product/main-product/product3.webp" alt="product-img">
                                                                <img class="product__card--thumbnail__img product__secondary--img" src="assets/img/product/main-product/product4.webp" alt="product-img">
                                                            </a>
                                                            <span class="product__badge">-16%</span>
                                                            <ul class="product__card--action d-flex align-items-center justify-content-center">
                                                                <li class="product__card--action__list">
                                                                    <a class="product__card--action__btn" title="Quick View" data-open="modal1" href="javascript:void(0)">
                                                                        <svg class="product__card--action__btn--svg" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                            <path d="M15.6952 14.4991L11.7663 10.5588C12.7765 9.4008 13.33 7.94381 13.33 6.42703C13.33 2.88322 10.34 0 6.66499 0C2.98997 0 0 2.88322 0 6.42703C0 9.97085 2.98997 12.8541 6.66499 12.8541C8.04464 12.8541 9.35938 12.4528 10.4834 11.6911L14.4422 15.6613C14.6076 15.827 14.8302 15.9184 15.0687 15.9184C15.2944 15.9184 15.5086 15.8354 15.6711 15.6845C16.0166 15.364 16.0276 14.8325 15.6952 14.4991ZM6.66499 1.67662C9.38141 1.67662 11.5913 3.8076 11.5913 6.42703C11.5913 9.04647 9.38141 11.1775 6.66499 11.1775C3.94857 11.1775 1.73869 9.04647 1.73869 6.42703C1.73869 3.8076 3.94857 1.67662 6.66499 1.67662Z" fill="currentColor"></path>
                                                                        </svg>
                                                                        <span class="visually-hidden">Quick View</span>  
                                                                    </a>
                                                                </li>
                                                                <li class="product__card--action__list">
                                                                    <a class="product__card--action__btn" title="Wishlist" href="wishlist.html">
                                                                        <svg class="product__card--action__btn--svg" width="18" height="18" viewBox="0 0 16 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M13.5379 1.52734C11.9519 0.1875 9.51832 0.378906 8.01442 1.9375C6.48317 0.378906 4.04957 0.1875 2.46364 1.52734C0.412855 3.25 0.713636 6.06641 2.1902 7.57031L6.97536 12.4648C7.24879 12.7383 7.60426 12.9023 8.01442 12.9023C8.39723 12.9023 8.7527 12.7383 9.02614 12.4648L13.8386 7.57031C15.2879 6.06641 15.5886 3.25 13.5379 1.52734ZM12.8816 6.64062L8.09645 11.5352C8.04176 11.5898 7.98707 11.5898 7.90504 11.5352L3.11989 6.64062C2.10817 5.62891 1.91676 3.71484 3.31129 2.53906C4.3777 1.63672 6.01832 1.77344 7.05739 2.8125L8.01442 3.79688L8.97145 2.8125C9.98317 1.77344 11.6238 1.63672 12.6902 2.51172C14.0847 3.71484 13.8933 5.62891 12.8816 6.64062Z" fill="currentColor"/>
                                                                        </svg>
                                                                        <span class="visually-hidden">Wishlist</span> 
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="product__card--content product__list--content">
                                                            <h3 class="product__card--title"><a href="product-details.html">Taboriosam asnda et itaque expcabo. </a></h3>
                                                            <ul class="rating product__card--rating d-flex">
                                                                <li class="rating__list">
                                                                    <span class="rating__icon">
                                                                        <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M6.08398 0.921875L4.56055 4.03906L1.11523 4.53125C0.505859 4.625 0.271484 5.375 0.716797 5.82031L3.17773 8.23438L2.5918 11.6328C2.49805 12.2422 3.1543 12.7109 3.69336 12.4297L6.76367 10.8125L9.81055 12.4297C10.3496 12.7109 11.0059 12.2422 10.9121 11.6328L10.3262 8.23438L12.7871 5.82031C13.2324 5.375 12.998 4.625 12.3887 4.53125L8.9668 4.03906L7.41992 0.921875C7.16211 0.382812 6.36523 0.359375 6.08398 0.921875Z" fill="currentColor"/>
                                                                        </svg>
                                                                    </span>
                                                                </li>
                                                                <li class="rating__list">
                                                                    <span class="rating__icon">
                                                                        <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M6.08398 0.921875L4.56055 4.03906L1.11523 4.53125C0.505859 4.625 0.271484 5.375 0.716797 5.82031L3.17773 8.23438L2.5918 11.6328C2.49805 12.2422 3.1543 12.7109 3.69336 12.4297L6.76367 10.8125L9.81055 12.4297C10.3496 12.7109 11.0059 12.2422 10.9121 11.6328L10.3262 8.23438L12.7871 5.82031C13.2324 5.375 12.998 4.625 12.3887 4.53125L8.9668 4.03906L7.41992 0.921875C7.16211 0.382812 6.36523 0.359375 6.08398 0.921875Z" fill="currentColor"/>
                                                                        </svg>
                                                                    </span>
                                                                </li>
                                                                <li class="rating__list">
                                                                    <span class="rating__icon">
                                                                        <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M6.08398 0.921875L4.56055 4.03906L1.11523 4.53125C0.505859 4.625 0.271484 5.375 0.716797 5.82031L3.17773 8.23438L2.5918 11.6328C2.49805 12.2422 3.1543 12.7109 3.69336 12.4297L6.76367 10.8125L9.81055 12.4297C10.3496 12.7109 11.0059 12.2422 10.9121 11.6328L10.3262 8.23438L12.7871 5.82031C13.2324 5.375 12.998 4.625 12.3887 4.53125L8.9668 4.03906L7.41992 0.921875C7.16211 0.382812 6.36523 0.359375 6.08398 0.921875Z" fill="currentColor"/>
                                                                        </svg>
                                                                    </span>
                                                                </li>
                                                                <li class="rating__list">
                                                                    <span class="rating__icon"> 
                                                                        <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                            <path d="M12.4141 4.53125L8.99219 4.03906L7.44531 0.921875C7.1875 0.382812 6.39062 0.359375 6.10938 0.921875L4.58594 4.03906L1.14062 4.53125C0.53125 4.625 0.296875 5.375 0.742188 5.82031L3.20312 8.23438L2.61719 11.6328C2.52344 12.2422 3.17969 12.7109 3.71875 12.4297L6.78906 10.8125L9.83594 12.4297C10.375 12.7109 11.0312 12.2422 10.9375 11.6328L10.3516 8.23438L12.8125 5.82031C13.2578 5.375 13.0234 4.625 12.4141 4.53125ZM9.53125 7.95312L10.1875 11.75L6.78906 9.96875L3.36719 11.75L4.02344 7.95312L1.25781 5.28125L5.07812 4.71875L6.78906 1.25L8.47656 4.71875L12.2969 5.28125L9.53125 7.95312Z" fill="currentColor"/>
                                                                            </svg>
                                                                    </span>
                                                                </li>
                                                                <li class="rating__list">
                                                                    <span class="rating__icon"> 
                                                                        <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                            <path d="M12.4141 4.53125L8.99219 4.03906L7.44531 0.921875C7.1875 0.382812 6.39062 0.359375 6.10938 0.921875L4.58594 4.03906L1.14062 4.53125C0.53125 4.625 0.296875 5.375 0.742188 5.82031L3.20312 8.23438L2.61719 11.6328C2.52344 12.2422 3.17969 12.7109 3.71875 12.4297L6.78906 10.8125L9.83594 12.4297C10.375 12.7109 11.0312 12.2422 10.9375 11.6328L10.3516 8.23438L12.8125 5.82031C13.2578 5.375 13.0234 4.625 12.4141 4.53125ZM9.53125 7.95312L10.1875 11.75L6.78906 9.96875L3.36719 11.75L4.02344 7.95312L1.25781 5.28125L5.07812 4.71875L6.78906 1.25L8.47656 4.71875L12.2969 5.28125L9.53125 7.95312Z" fill="currentColor"/>
                                                                            </svg>
                                                                    </span>
                                                                </li>
                                                                <li>
                                                                    <span class="rating__review--text">(106) Review</span>
                                                                </li>
                                                            </ul>
                                                            <div class="product__list--price">
                                                                <span class="current__price">$144.52</span>
                                                                <span class="old__price"> $234.00</span>
                                                            </div>
                                                            <p class="product__card--content__desc mb-20">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quia voluptas dolore doloribus architecto sequi corporis deleniti officia culpa dolor esse there consectetur eligendi, natus at rem ab quae amet molestiae quod voluptates.</p>
                                                            <a class="product__card--btn primary__btn" href="cart.html">+ Add to cart</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col mb-30">
                                                    <div class="product__card product__list d-flex align-items-center">
                                                        <div class="product__card--thumbnail product__list--thumbnail">
                                                            <a class="product__card--thumbnail__link display-block" href="product-details.html">
                                                                <img class="product__card--thumbnail__img product__primary--img" src="assets/img/product/main-product/product5.webp" alt="product-img">
                                                                <img class="product__card--thumbnail__img product__secondary--img" src="assets/img/product/main-product/product6.webp" alt="product-img">
                                                            </a>
                                                            <span class="product__badge">-22%</span>
                                                            <ul class="product__card--action d-flex align-items-center justify-content-center">
                                                                <li class="product__card--action__list">
                                                                    <a class="product__card--action__btn" title="Quick View" data-open="modal1" href="javascript:void(0)">
                                                                        <svg class="product__card--action__btn--svg" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                            <path d="M15.6952 14.4991L11.7663 10.5588C12.7765 9.4008 13.33 7.94381 13.33 6.42703C13.33 2.88322 10.34 0 6.66499 0C2.98997 0 0 2.88322 0 6.42703C0 9.97085 2.98997 12.8541 6.66499 12.8541C8.04464 12.8541 9.35938 12.4528 10.4834 11.6911L14.4422 15.6613C14.6076 15.827 14.8302 15.9184 15.0687 15.9184C15.2944 15.9184 15.5086 15.8354 15.6711 15.6845C16.0166 15.364 16.0276 14.8325 15.6952 14.4991ZM6.66499 1.67662C9.38141 1.67662 11.5913 3.8076 11.5913 6.42703C11.5913 9.04647 9.38141 11.1775 6.66499 11.1775C3.94857 11.1775 1.73869 9.04647 1.73869 6.42703C1.73869 3.8076 3.94857 1.67662 6.66499 1.67662Z" fill="currentColor"></path>
                                                                        </svg>
                                                                        <span class="visually-hidden">Quick View</span>  
                                                                    </a>
                                                                </li>
                                                                <li class="product__card--action__list">
                                                                    <a class="product__card--action__btn" title="Wishlist" href="wishlist.html">
                                                                        <svg class="product__card--action__btn--svg" width="18" height="18" viewBox="0 0 16 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M13.5379 1.52734C11.9519 0.1875 9.51832 0.378906 8.01442 1.9375C6.48317 0.378906 4.04957 0.1875 2.46364 1.52734C0.412855 3.25 0.713636 6.06641 2.1902 7.57031L6.97536 12.4648C7.24879 12.7383 7.60426 12.9023 8.01442 12.9023C8.39723 12.9023 8.7527 12.7383 9.02614 12.4648L13.8386 7.57031C15.2879 6.06641 15.5886 3.25 13.5379 1.52734ZM12.8816 6.64062L8.09645 11.5352C8.04176 11.5898 7.98707 11.5898 7.90504 11.5352L3.11989 6.64062C2.10817 5.62891 1.91676 3.71484 3.31129 2.53906C4.3777 1.63672 6.01832 1.77344 7.05739 2.8125L8.01442 3.79688L8.97145 2.8125C9.98317 1.77344 11.6238 1.63672 12.6902 2.51172C14.0847 3.71484 13.8933 5.62891 12.8816 6.64062Z" fill="currentColor"/>
                                                                        </svg>
                                                                        <span class="visually-hidden">Wishlist</span> 
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="product__card--content product__list--content">
                                                            <h3 class="product__card--title"><a href="product-details.html">Eius doloribus dicta labore magni nulla! </a></h3>
                                                            <ul class="rating product__card--rating d-flex">
                                                                <li class="rating__list">
                                                                    <span class="rating__icon">
                                                                        <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M6.08398 0.921875L4.56055 4.03906L1.11523 4.53125C0.505859 4.625 0.271484 5.375 0.716797 5.82031L3.17773 8.23438L2.5918 11.6328C2.49805 12.2422 3.1543 12.7109 3.69336 12.4297L6.76367 10.8125L9.81055 12.4297C10.3496 12.7109 11.0059 12.2422 10.9121 11.6328L10.3262 8.23438L12.7871 5.82031C13.2324 5.375 12.998 4.625 12.3887 4.53125L8.9668 4.03906L7.41992 0.921875C7.16211 0.382812 6.36523 0.359375 6.08398 0.921875Z" fill="currentColor"/>
                                                                        </svg>
                                                                    </span>
                                                                </li>
                                                                <li class="rating__list">
                                                                    <span class="rating__icon">
                                                                        <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M6.08398 0.921875L4.56055 4.03906L1.11523 4.53125C0.505859 4.625 0.271484 5.375 0.716797 5.82031L3.17773 8.23438L2.5918 11.6328C2.49805 12.2422 3.1543 12.7109 3.69336 12.4297L6.76367 10.8125L9.81055 12.4297C10.3496 12.7109 11.0059 12.2422 10.9121 11.6328L10.3262 8.23438L12.7871 5.82031C13.2324 5.375 12.998 4.625 12.3887 4.53125L8.9668 4.03906L7.41992 0.921875C7.16211 0.382812 6.36523 0.359375 6.08398 0.921875Z" fill="currentColor"/>
                                                                        </svg>
                                                                    </span>
                                                                </li>
                                                                <li class="rating__list">
                                                                    <span class="rating__icon">
                                                                        <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M6.08398 0.921875L4.56055 4.03906L1.11523 4.53125C0.505859 4.625 0.271484 5.375 0.716797 5.82031L3.17773 8.23438L2.5918 11.6328C2.49805 12.2422 3.1543 12.7109 3.69336 12.4297L6.76367 10.8125L9.81055 12.4297C10.3496 12.7109 11.0059 12.2422 10.9121 11.6328L10.3262 8.23438L12.7871 5.82031C13.2324 5.375 12.998 4.625 12.3887 4.53125L8.9668 4.03906L7.41992 0.921875C7.16211 0.382812 6.36523 0.359375 6.08398 0.921875Z" fill="currentColor"/>
                                                                        </svg>
                                                                    </span>
                                                                </li>
                                                                <li class="rating__list">
                                                                    <span class="rating__icon"> 
                                                                        <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                            <path d="M12.4141 4.53125L8.99219 4.03906L7.44531 0.921875C7.1875 0.382812 6.39062 0.359375 6.10938 0.921875L4.58594 4.03906L1.14062 4.53125C0.53125 4.625 0.296875 5.375 0.742188 5.82031L3.20312 8.23438L2.61719 11.6328C2.52344 12.2422 3.17969 12.7109 3.71875 12.4297L6.78906 10.8125L9.83594 12.4297C10.375 12.7109 11.0312 12.2422 10.9375 11.6328L10.3516 8.23438L12.8125 5.82031C13.2578 5.375 13.0234 4.625 12.4141 4.53125ZM9.53125 7.95312L10.1875 11.75L6.78906 9.96875L3.36719 11.75L4.02344 7.95312L1.25781 5.28125L5.07812 4.71875L6.78906 1.25L8.47656 4.71875L12.2969 5.28125L9.53125 7.95312Z" fill="currentColor"/>
                                                                            </svg>
                                                                    </span>
                                                                </li>
                                                                <li class="rating__list">
                                                                    <span class="rating__icon"> 
                                                                        <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                            <path d="M12.4141 4.53125L8.99219 4.03906L7.44531 0.921875C7.1875 0.382812 6.39062 0.359375 6.10938 0.921875L4.58594 4.03906L1.14062 4.53125C0.53125 4.625 0.296875 5.375 0.742188 5.82031L3.20312 8.23438L2.61719 11.6328C2.52344 12.2422 3.17969 12.7109 3.71875 12.4297L6.78906 10.8125L9.83594 12.4297C10.375 12.7109 11.0312 12.2422 10.9375 11.6328L10.3516 8.23438L12.8125 5.82031C13.2578 5.375 13.0234 4.625 12.4141 4.53125ZM9.53125 7.95312L10.1875 11.75L6.78906 9.96875L3.36719 11.75L4.02344 7.95312L1.25781 5.28125L5.07812 4.71875L6.78906 1.25L8.47656 4.71875L12.2969 5.28125L9.53125 7.95312Z" fill="currentColor"/>
                                                                            </svg>
                                                                    </span>
                                                                </li>
                                                                <li>
                                                                    <span class="rating__review--text">(106) Review</span>
                                                                </li>
                                                            </ul>
                                                            <div class="product__list--price">
                                                                <span class="current__price">$123.52</span>
                                                                <span class="old__price"> $222.00</span>
                                                            </div>
                                                            <p class="product__card--content__desc mb-20">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quia voluptas dolore doloribus architecto sequi corporis deleniti officia culpa dolor esse there consectetur eligendi, natus at rem ab quae amet molestiae quod voluptates.</p>
                                                            <a class="product__card--btn primary__btn" href="cart.html">+ Add to cart</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col mb-30">
                                                    <div class="product__card product__list d-flex align-items-center">
                                                        <div class="product__card--thumbnail product__list--thumbnail">
                                                            <a class="product__card--thumbnail__link display-block" href="product-details.html">
                                                                <img class="product__card--thumbnail__img product__primary--img" src="assets/img/product/main-product/product7.webp" alt="product-img">
                                                                <img class="product__card--thumbnail__img product__secondary--img" src="assets/img/product/main-product/product8.webp" alt="product-img">
                                                            </a>
                                                            <span class="product__badge">-16%</span>
                                                            <ul class="product__card--action d-flex align-items-center justify-content-center">
                                                                <li class="product__card--action__list">
                                                                    <a class="product__card--action__btn" title="Quick View" data-open="modal1" href="javascript:void(0)">
                                                                        <svg class="product__card--action__btn--svg" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                            <path d="M15.6952 14.4991L11.7663 10.5588C12.7765 9.4008 13.33 7.94381 13.33 6.42703C13.33 2.88322 10.34 0 6.66499 0C2.98997 0 0 2.88322 0 6.42703C0 9.97085 2.98997 12.8541 6.66499 12.8541C8.04464 12.8541 9.35938 12.4528 10.4834 11.6911L14.4422 15.6613C14.6076 15.827 14.8302 15.9184 15.0687 15.9184C15.2944 15.9184 15.5086 15.8354 15.6711 15.6845C16.0166 15.364 16.0276 14.8325 15.6952 14.4991ZM6.66499 1.67662C9.38141 1.67662 11.5913 3.8076 11.5913 6.42703C11.5913 9.04647 9.38141 11.1775 6.66499 11.1775C3.94857 11.1775 1.73869 9.04647 1.73869 6.42703C1.73869 3.8076 3.94857 1.67662 6.66499 1.67662Z" fill="currentColor"></path>
                                                                        </svg>
                                                                        <span class="visually-hidden">Quick View</span>  
                                                                    </a>
                                                                </li>
                                                                <li class="product__card--action__list">
                                                                    <a class="product__card--action__btn" title="Wishlist" href="wishlist.html">
                                                                        <svg class="product__card--action__btn--svg" width="18" height="18" viewBox="0 0 16 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M13.5379 1.52734C11.9519 0.1875 9.51832 0.378906 8.01442 1.9375C6.48317 0.378906 4.04957 0.1875 2.46364 1.52734C0.412855 3.25 0.713636 6.06641 2.1902 7.57031L6.97536 12.4648C7.24879 12.7383 7.60426 12.9023 8.01442 12.9023C8.39723 12.9023 8.7527 12.7383 9.02614 12.4648L13.8386 7.57031C15.2879 6.06641 15.5886 3.25 13.5379 1.52734ZM12.8816 6.64062L8.09645 11.5352C8.04176 11.5898 7.98707 11.5898 7.90504 11.5352L3.11989 6.64062C2.10817 5.62891 1.91676 3.71484 3.31129 2.53906C4.3777 1.63672 6.01832 1.77344 7.05739 2.8125L8.01442 3.79688L8.97145 2.8125C9.98317 1.77344 11.6238 1.63672 12.6902 2.51172C14.0847 3.71484 13.8933 5.62891 12.8816 6.64062Z" fill="currentColor"/>
                                                                        </svg>
                                                                        <span class="visually-hidden">Wishlist</span> 
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="product__card--content product__list--content">
                                                            <h3 class="product__card--title"><a href="product-details.html">Sequi eum saepe nisi repellat at fuga nemo.  </a></h3>
                                                            <ul class="rating product__card--rating d-flex">
                                                                <li class="rating__list">
                                                                    <span class="rating__icon">
                                                                        <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M6.08398 0.921875L4.56055 4.03906L1.11523 4.53125C0.505859 4.625 0.271484 5.375 0.716797 5.82031L3.17773 8.23438L2.5918 11.6328C2.49805 12.2422 3.1543 12.7109 3.69336 12.4297L6.76367 10.8125L9.81055 12.4297C10.3496 12.7109 11.0059 12.2422 10.9121 11.6328L10.3262 8.23438L12.7871 5.82031C13.2324 5.375 12.998 4.625 12.3887 4.53125L8.9668 4.03906L7.41992 0.921875C7.16211 0.382812 6.36523 0.359375 6.08398 0.921875Z" fill="currentColor"/>
                                                                        </svg>
                                                                    </span>
                                                                </li>
                                                                <li class="rating__list">
                                                                    <span class="rating__icon">
                                                                        <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M6.08398 0.921875L4.56055 4.03906L1.11523 4.53125C0.505859 4.625 0.271484 5.375 0.716797 5.82031L3.17773 8.23438L2.5918 11.6328C2.49805 12.2422 3.1543 12.7109 3.69336 12.4297L6.76367 10.8125L9.81055 12.4297C10.3496 12.7109 11.0059 12.2422 10.9121 11.6328L10.3262 8.23438L12.7871 5.82031C13.2324 5.375 12.998 4.625 12.3887 4.53125L8.9668 4.03906L7.41992 0.921875C7.16211 0.382812 6.36523 0.359375 6.08398 0.921875Z" fill="currentColor"/>
                                                                        </svg>
                                                                    </span>
                                                                </li>
                                                                <li class="rating__list">
                                                                    <span class="rating__icon">
                                                                        <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M6.08398 0.921875L4.56055 4.03906L1.11523 4.53125C0.505859 4.625 0.271484 5.375 0.716797 5.82031L3.17773 8.23438L2.5918 11.6328C2.49805 12.2422 3.1543 12.7109 3.69336 12.4297L6.76367 10.8125L9.81055 12.4297C10.3496 12.7109 11.0059 12.2422 10.9121 11.6328L10.3262 8.23438L12.7871 5.82031C13.2324 5.375 12.998 4.625 12.3887 4.53125L8.9668 4.03906L7.41992 0.921875C7.16211 0.382812 6.36523 0.359375 6.08398 0.921875Z" fill="currentColor"/>
                                                                        </svg>
                                                                    </span>
                                                                </li>
                                                                <li class="rating__list">
                                                                    <span class="rating__icon"> 
                                                                        <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                            <path d="M12.4141 4.53125L8.99219 4.03906L7.44531 0.921875C7.1875 0.382812 6.39062 0.359375 6.10938 0.921875L4.58594 4.03906L1.14062 4.53125C0.53125 4.625 0.296875 5.375 0.742188 5.82031L3.20312 8.23438L2.61719 11.6328C2.52344 12.2422 3.17969 12.7109 3.71875 12.4297L6.78906 10.8125L9.83594 12.4297C10.375 12.7109 11.0312 12.2422 10.9375 11.6328L10.3516 8.23438L12.8125 5.82031C13.2578 5.375 13.0234 4.625 12.4141 4.53125ZM9.53125 7.95312L10.1875 11.75L6.78906 9.96875L3.36719 11.75L4.02344 7.95312L1.25781 5.28125L5.07812 4.71875L6.78906 1.25L8.47656 4.71875L12.2969 5.28125L9.53125 7.95312Z" fill="currentColor"/>
                                                                            </svg>
                                                                    </span>
                                                                </li>
                                                                <li class="rating__list">
                                                                    <span class="rating__icon"> 
                                                                        <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                            <path d="M12.4141 4.53125L8.99219 4.03906L7.44531 0.921875C7.1875 0.382812 6.39062 0.359375 6.10938 0.921875L4.58594 4.03906L1.14062 4.53125C0.53125 4.625 0.296875 5.375 0.742188 5.82031L3.20312 8.23438L2.61719 11.6328C2.52344 12.2422 3.17969 12.7109 3.71875 12.4297L6.78906 10.8125L9.83594 12.4297C10.375 12.7109 11.0312 12.2422 10.9375 11.6328L10.3516 8.23438L12.8125 5.82031C13.2578 5.375 13.0234 4.625 12.4141 4.53125ZM9.53125 7.95312L10.1875 11.75L6.78906 9.96875L3.36719 11.75L4.02344 7.95312L1.25781 5.28125L5.07812 4.71875L6.78906 1.25L8.47656 4.71875L12.2969 5.28125L9.53125 7.95312Z" fill="currentColor"/>
                                                                            </svg>
                                                                    </span>
                                                                </li>
                                                                <li>
                                                                    <span class="rating__review--text">(106) Review</span>
                                                                </li>
                                                            </ul>
                                                            <div class="product__list--price">
                                                                <span class="current__price">$150.52</span>
                                                                <span class="old__price"> $240.00</span>
                                                            </div>
                                                            <p class="product__card--content__desc mb-20">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quia voluptas dolore doloribus architecto sequi corporis deleniti officia culpa dolor esse there consectetur eligendi, natus at rem ab quae amet molestiae quod voluptates.</p>
                                                            <a class="product__card--btn primary__btn" href="cart.html">+ Add to cart</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col mb-30">
                                                    <div class="product__card product__list d-flex align-items-center">
                                                        <div class="product__card--thumbnail product__list--thumbnail">
                                                            <a class="product__card--thumbnail__link display-block" href="product-details.html">
                                                                <img class="product__card--thumbnail__img product__primary--img" src="assets/img/product/main-product/product9.webp" alt="product-img">
                                                                <img class="product__card--thumbnail__img product__secondary--img" src="assets/img/product/main-product/product10.webp" alt="product-img">
                                                            </a>
                                                            <span class="product__badge">-24%</span>
                                                            <ul class="product__card--action d-flex align-items-center justify-content-center">
                                                                <li class="product__card--action__list">
                                                                    <a class="product__card--action__btn" title="Quick View" data-open="modal1" href="javascript:void(0)">
                                                                        <svg class="product__card--action__btn--svg" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                            <path d="M15.6952 14.4991L11.7663 10.5588C12.7765 9.4008 13.33 7.94381 13.33 6.42703C13.33 2.88322 10.34 0 6.66499 0C2.98997 0 0 2.88322 0 6.42703C0 9.97085 2.98997 12.8541 6.66499 12.8541C8.04464 12.8541 9.35938 12.4528 10.4834 11.6911L14.4422 15.6613C14.6076 15.827 14.8302 15.9184 15.0687 15.9184C15.2944 15.9184 15.5086 15.8354 15.6711 15.6845C16.0166 15.364 16.0276 14.8325 15.6952 14.4991ZM6.66499 1.67662C9.38141 1.67662 11.5913 3.8076 11.5913 6.42703C11.5913 9.04647 9.38141 11.1775 6.66499 11.1775C3.94857 11.1775 1.73869 9.04647 1.73869 6.42703C1.73869 3.8076 3.94857 1.67662 6.66499 1.67662Z" fill="currentColor"></path>
                                                                        </svg>
                                                                        <span class="visually-hidden">Quick View</span>  
                                                                    </a>
                                                                </li>
                                                                <li class="product__card--action__list">
                                                                    <a class="product__card--action__btn" title="Wishlist" href="wishlist.html">
                                                                        <svg class="product__card--action__btn--svg" width="18" height="18" viewBox="0 0 16 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M13.5379 1.52734C11.9519 0.1875 9.51832 0.378906 8.01442 1.9375C6.48317 0.378906 4.04957 0.1875 2.46364 1.52734C0.412855 3.25 0.713636 6.06641 2.1902 7.57031L6.97536 12.4648C7.24879 12.7383 7.60426 12.9023 8.01442 12.9023C8.39723 12.9023 8.7527 12.7383 9.02614 12.4648L13.8386 7.57031C15.2879 6.06641 15.5886 3.25 13.5379 1.52734ZM12.8816 6.64062L8.09645 11.5352C8.04176 11.5898 7.98707 11.5898 7.90504 11.5352L3.11989 6.64062C2.10817 5.62891 1.91676 3.71484 3.31129 2.53906C4.3777 1.63672 6.01832 1.77344 7.05739 2.8125L8.01442 3.79688L8.97145 2.8125C9.98317 1.77344 11.6238 1.63672 12.6902 2.51172C14.0847 3.71484 13.8933 5.62891 12.8816 6.64062Z" fill="currentColor"/>
                                                                        </svg>
                                                                        <span class="visually-hidden">Wishlist</span> 
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="product__card--content product__list--content">
                                                            <h3 class="product__card--title"><a href="product-details.html">Eius doloribus dicta labore magni nulla! </a></h3>
                                                            <ul class="rating product__card--rating d-flex">
                                                                <li class="rating__list">
                                                                    <span class="rating__icon">
                                                                        <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M6.08398 0.921875L4.56055 4.03906L1.11523 4.53125C0.505859 4.625 0.271484 5.375 0.716797 5.82031L3.17773 8.23438L2.5918 11.6328C2.49805 12.2422 3.1543 12.7109 3.69336 12.4297L6.76367 10.8125L9.81055 12.4297C10.3496 12.7109 11.0059 12.2422 10.9121 11.6328L10.3262 8.23438L12.7871 5.82031C13.2324 5.375 12.998 4.625 12.3887 4.53125L8.9668 4.03906L7.41992 0.921875C7.16211 0.382812 6.36523 0.359375 6.08398 0.921875Z" fill="currentColor"/>
                                                                        </svg>
                                                                    </span>
                                                                </li>
                                                                <li class="rating__list">
                                                                    <span class="rating__icon">
                                                                        <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M6.08398 0.921875L4.56055 4.03906L1.11523 4.53125C0.505859 4.625 0.271484 5.375 0.716797 5.82031L3.17773 8.23438L2.5918 11.6328C2.49805 12.2422 3.1543 12.7109 3.69336 12.4297L6.76367 10.8125L9.81055 12.4297C10.3496 12.7109 11.0059 12.2422 10.9121 11.6328L10.3262 8.23438L12.7871 5.82031C13.2324 5.375 12.998 4.625 12.3887 4.53125L8.9668 4.03906L7.41992 0.921875C7.16211 0.382812 6.36523 0.359375 6.08398 0.921875Z" fill="currentColor"/>
                                                                        </svg>
                                                                    </span>
                                                                </li>
                                                                <li class="rating__list">
                                                                    <span class="rating__icon">
                                                                        <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M6.08398 0.921875L4.56055 4.03906L1.11523 4.53125C0.505859 4.625 0.271484 5.375 0.716797 5.82031L3.17773 8.23438L2.5918 11.6328C2.49805 12.2422 3.1543 12.7109 3.69336 12.4297L6.76367 10.8125L9.81055 12.4297C10.3496 12.7109 11.0059 12.2422 10.9121 11.6328L10.3262 8.23438L12.7871 5.82031C13.2324 5.375 12.998 4.625 12.3887 4.53125L8.9668 4.03906L7.41992 0.921875C7.16211 0.382812 6.36523 0.359375 6.08398 0.921875Z" fill="currentColor"/>
                                                                        </svg>
                                                                    </span>
                                                                </li>
                                                                <li class="rating__list">
                                                                    <span class="rating__icon"> 
                                                                        <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                            <path d="M12.4141 4.53125L8.99219 4.03906L7.44531 0.921875C7.1875 0.382812 6.39062 0.359375 6.10938 0.921875L4.58594 4.03906L1.14062 4.53125C0.53125 4.625 0.296875 5.375 0.742188 5.82031L3.20312 8.23438L2.61719 11.6328C2.52344 12.2422 3.17969 12.7109 3.71875 12.4297L6.78906 10.8125L9.83594 12.4297C10.375 12.7109 11.0312 12.2422 10.9375 11.6328L10.3516 8.23438L12.8125 5.82031C13.2578 5.375 13.0234 4.625 12.4141 4.53125ZM9.53125 7.95312L10.1875 11.75L6.78906 9.96875L3.36719 11.75L4.02344 7.95312L1.25781 5.28125L5.07812 4.71875L6.78906 1.25L8.47656 4.71875L12.2969 5.28125L9.53125 7.95312Z" fill="currentColor"/>
                                                                            </svg>
                                                                    </span>
                                                                </li>
                                                                <li class="rating__list">
                                                                    <span class="rating__icon"> 
                                                                        <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                            <path d="M12.4141 4.53125L8.99219 4.03906L7.44531 0.921875C7.1875 0.382812 6.39062 0.359375 6.10938 0.921875L4.58594 4.03906L1.14062 4.53125C0.53125 4.625 0.296875 5.375 0.742188 5.82031L3.20312 8.23438L2.61719 11.6328C2.52344 12.2422 3.17969 12.7109 3.71875 12.4297L6.78906 10.8125L9.83594 12.4297C10.375 12.7109 11.0312 12.2422 10.9375 11.6328L10.3516 8.23438L12.8125 5.82031C13.2578 5.375 13.0234 4.625 12.4141 4.53125ZM9.53125 7.95312L10.1875 11.75L6.78906 9.96875L3.36719 11.75L4.02344 7.95312L1.25781 5.28125L5.07812 4.71875L6.78906 1.25L8.47656 4.71875L12.2969 5.28125L9.53125 7.95312Z" fill="currentColor"/>
                                                                            </svg>
                                                                    </span>
                                                                </li>
                                                                <li>
                                                                    <span class="rating__review--text">(106) Review</span>
                                                                </li>
                                                            </ul>
                                                            <div class="product__list--price">
                                                                <span class="current__price">$188.52</span>
                                                                <span class="old__price"> $268.00</span>
                                                            </div>
                                                            <p class="product__card--content__desc mb-20">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quia voluptas dolore doloribus architecto sequi corporis deleniti officia culpa dolor esse there consectetur eligendi, natus at rem ab quae amet molestiae quod voluptates.</p>
                                                            <a class="product__card--btn primary__btn" href="cart.html">+ Add to cart</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <div class="pagination__area">
                                    <nav class="pagination justify-content-center">
                                        <?php
                                            // Tüm GET parametrelerini kopyala
                                            $params = $_GET;

                                            // Önce sayfa parametresini kaldır
                                            unset($params['sayfa']);

                                            // Yeni link oluştururken diğer parametreleri taşı
                                            $queryString = http_build_query($params);
                                            ?>
                                            <ul class="pagination__wrapper d-flex align-items-center justify-content-center">

                                                <li class="pagination__list">
                                                    <?php if($sayfa-1 >= 1) { ?>
                                                    <a href="?sayfa=<?php echo $sayfa-1; ?>&<?php echo $queryString; ?>" class="pagination__item--arrow link">
                                                        ◀
                                                    </a>
                                                    <?php } ?>
                                                </li>

                                                <?php  
                                                    for ($sirano=1; $sirano <= $sayfasayisi; $sirano++) {
                                                        $active = ($sirano == $sayfa) ? "pagination__item pagination__item--current" : "pagination__item link";
                                                ?>
                                                    <li class="pagination__list">
                                                        <a href="?sayfa=<?php echo $sirano; ?>&<?php echo $queryString; ?>" class="<?php echo $active; ?>">
                                                            <?php echo $sirano; ?>
                                                        </a>
                                                    </li>
                                                <?php } ?>

                                                <li class="pagination__list">
                                                    <?php if($sayfa+1 <= $sayfasayisi) { ?>
                                                    <a href="?sayfa=<?php echo $sayfa+1; ?>&<?php echo $queryString; ?>" class="pagination__item--arrow link">
                                                        ▶
                                                    </a>
                                                    <?php } ?>
                                                </li>
                                            </ul>


                                    </nav>
                                </div>


                            </div>
                        </div>
                    </div>



                </div>
            </div>
        </div>
        <!-- End shop section -->

        <!-- Start shipping section -->
        <section class="shipping__section">
            <div class="container">
                <div class="shipping__inner style2 d-flex">
                    <div class="shipping__items style2 d-flex align-items-center">
                        <div class="shipping__icon">  
                            <img src="assets/img/other/shipping1.webp" alt="icon-img">
                        </div>
                        <div class="shipping__content">
                            <h2 class="shipping__content--title h3">Free Shipping</h2>
                            <p class="shipping__content--desc">Free shipping over $100</p>
                        </div>
                    </div>
                    <div class="shipping__items style2 d-flex align-items-center">
                        <div class="shipping__icon">  
                            <img src="assets/img/other/shipping2.webp" alt="icon-img">
                        </div>
                        <div class="shipping__content">
                            <h2 class="shipping__content--title h3">Support 24/7</h2>
                            <p class="shipping__content--desc">Contact us 24 hours a day</p>
                        </div>
                    </div>
                    <div class="shipping__items style2 d-flex align-items-center">
                        <div class="shipping__icon">  
                            <img src="assets/img/other/shipping3.webp" alt="icon-img">
                        </div>
                        <div class="shipping__content">
                            <h2 class="shipping__content--title h3">100% Money Back</h2>
                            <p class="shipping__content--desc">You have 30 days to Return</p>
                        </div>
                    </div>
                    <div class="shipping__items style2 d-flex align-items-center">
                        <div class="shipping__icon">  
                            <img src="assets/img/other/shipping4.webp" alt="icon-img">
                        </div>
                        <div class="shipping__content">
                            <h2 class="shipping__content--title h3">Payment Secure</h2>
                            <p class="shipping__content--desc">We ensure secure payment</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End shipping section -->

    </main>

<script>
    function selectPage(value) {
        window.location.href = 'shop.php?sayfa=' + value;
    }
</script>
<?php 
    include 'footer.php';
?>

    