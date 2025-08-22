<?php 

include 'header.php'; 

$urunsor=$db->prepare("SELECT * FROM urun where urun_id=:id");
$urunsor->execute(array(
    'id' => $_GET['urun_id']
  ));

$uruncek=$urunsor->fetch(PDO::FETCH_ASSOC);

?>

<header>
  <title>Ürün Düzenle - <?php echo $_GET['urun_id']; ?> | SS Admin Paneli</title>
</header>

<!-- page content -->
<div class="right_col" role="main">
  <div class="">

    <div class="clearfix"></div>
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Ürün Düzenleme Paneli<small>

              <?php 

              if (isset($_GET['durum'])=="ok") {?>

              <b style="color:green;">İşlem Başarılı...</b>
              <a href="urun"><button class="btn btn-danger btn-xs">Geri Dön</button></a>

              <?php } elseif (isset($_GET['durum'])=="no") {?>

              <b style="color:red;">İşlem Başarısız...</b>

              <?php }

              ?>


            </small></h2>
            <ul class="nav navbar-right panel_toolbox">
              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
              </li>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
              </li>
              <li><a class="close-link"><i class="fa fa-close"></i></a>
              </li>
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <br/>


          

          











            <!-- / => en kök dizine çık ... ../ bir üst dizine çık -->
            <form action="../netting/islem.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">


          <!-- ARAÇ TİPİ SEÇME İŞLEMİ BAŞLANGIÇ -->
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Araç Tipi Seç<span class="required">*</span></label>           
                  <div class="col-md-6 col-sm-6 col-xs-6">  

                    <?php  
                      $arac_id=$uruncek['arac_tipi_id'];  
                      $arac_tipisor=$db->prepare("SELECT * FROM arac_tipi WHERE arac_tipi_ust=:arac_tipi_ust ORDER BY arac_tipi_sira");
                      $arac_tipisor->execute(array(
                        'arac_tipi_ust' => 0
                      ));
                    ?>

                      <select class="select2_multiple form-control" required="" name="arac_tipi_id" >
                        <?php 
                          while($arac_tipicek=$arac_tipisor->fetch(PDO::FETCH_ASSOC)) {
                            $arac_tipi_id=$arac_tipicek['arac_tipi_id'];
                        ?>

                          <option 
                            <?php 
                              if ($arac_tipi_id==$arac_id) 
                              { 
                                echo "selected='select'"; 
                              } 
                            ?> 
                              value="<?php echo $arac_tipicek['arac_tipi_id']; ?>"> <?php echo $arac_tipicek['arac_tipi_ad']; ?>                   
                          </option>

                          <?php } ?>

                      </select>
                  </div>
              </div>
          <!-- arac_tipi SEÇME İŞLEMİ BİTİŞ -->


          <!-- KATEGORİ SEÇME İŞLEMİ BAŞLANGIÇ -->
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Kategori Seç<span class="required">*</span></label>           
                  <div class="col-md-6 col-sm-6 col-xs-6">  

                    <?php  
                      $kat_id=$uruncek['kategori_id'];  
                      $kategorisor=$db->prepare("SELECT * FROM kategori WHERE kategori_ust=:kategori_ust ORDER BY kategori_sira");
                      $kategorisor->execute(array(
                        'kategori_ust' => 0
                      ));
                    ?>

                      <select class="select2_multiple form-control" required="" name="kategori_id" >
                        <?php 
                          while($kategoricek=$kategorisor->fetch(PDO::FETCH_ASSOC)) {
                            $kategori_id=$kategoricek['kategori_id'];
                        ?>

                          <option 
                            <?php 
                              if ($kategori_id==$kat_id) 
                              { 
                                echo "selected='select'"; 
                              } 
                            ?> 
                              value="<?php echo $kategoricek['kategori_id']; ?>"> <?php echo $kategoricek['kategori_ad']; ?>                   
                          </option>

                          <?php } ?>

                      </select>
                  </div>
              </div>
          <!-- KATEGORİ SEÇME İŞLEMİ BİTİŞ -->


              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Ürün Adı<span>*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" id="first-name" required="" name="urun_ad" value="<?php echo $uruncek['urun_ad'] ?>" class="form-control col-md-7 col-xs-12">
                </div>
              </div>
              
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Ürün Anahtar Kelimeleri<span>*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" id="first-name" name="urun_keyword" value="<?php echo $uruncek['urun_keyword'] ?>" class="form-control col-md-7 col-xs-12">
                </div>
              </div>
             
               <!-- Ck Editör Başlangıç -->
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Ürün Detay<span>*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <textarea  required="" id="editor" rows="8"  name="urun_detay"><?php echo $uruncek['urun_detay']; ?></textarea>
                </div>
              </div>

              <script>
              let instance;

              function initEditor() {
                const textarea = document.getElementById('editor');
                instance = sceditor.create(textarea, {
                  // 'html' da kullanılabilir. Bu örnekte BBCode tercih edildi.
                  format: 'bbcode',
                  // WYSIWYG içindeki içerik stilleri (content CSS)
                  style: 'https://cdn.jsdelivr.net/npm/sceditor@3/minified/themes/content/default.min.css',
                  icons: 'monocons',
                  // Sık kullanılan basit bir toolbar
                  toolbar: 'bold,italic,underline|left,center,right|bulletlist,orderedlist|quote,code|link,unlink,image|source',
                  emoticonsEnabled: false
                });
              }

              document.addEventListener('DOMContentLoaded', initEditor);

              const out = document.getElementById('output');

              // HTML içeriği al (ikinci parametre false => HTML döner)
              document.getElementById('getHtml').addEventListener('click', function () {
                if (!instance) return;
                out.textContent = instance.value(null, false);
              });


              // Editöre programatik içerik yükle
              document.getElementById('setContent').addEventListener('click', function () {
                if (!instance) return;
                instance.value('[b]Kalın[/b] ve [i]italik[/i] metin, bir [url=https://www.sceditor.com]bağlantı[/url] ve bir liste:\n\n[list]\n[*] Öğe 1\n[*] Öğe 2\n[/list]');
              });

            </script>
            <!-- Ck Editör Bitiş -->



              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Ürün Fiyat<span>*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" id="first-name" required="" name="urun_fiyat" value="<?php echo $uruncek['urun_fiyat'] ?>" class="form-control col-md-7 col-xs-12">
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Ürün Stok<span>*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" id="first-name" name="urun_stok" value="<?php echo $uruncek['urun_stok'] ?>" class="form-control col-md-7 col-xs-12">
                </div>
              </div>




              
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Ürün Durumu<span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select id="heard" class="form-control" name="urun_durum" required="">
                  
                  <?php 
                   if ($uruncek['urun_durum']==0) {?>
                   <option value="0">Pasif</option>
                   <option value="1">Aktif</option>

                   <?php } else {?>
                   <option value="1">Aktif</option>
                   <option value="0">Pasif</option>

                   <?php  } ?>
                  </select>
                </div>
               </div>     


              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Ürün Öne Çıkar<span class="required">*</span></label>               
                <div class="col-md-6 col-sm-6 col-xs-12">

                  <select id="heard" class="form-control" name="urun_onecikar" required="">                
                    <?php 
                    if ($uruncek['urun_onecikar']==0) {?>
                      <option value="0">Hayır</option>
                      <option value="1">Evet</option>

                    <?php } else {?>
                      <option value="1">Evet</option>
                      <option value="0">Hayır</option>

                    <?php  } ?>
                  </select>
                </div>
              </div>



              



              <input type="hidden" name="urun_id" value="<?php echo $uruncek['urun_id'] ?>">

              <div class="ln_solid"></div>
              <div class="form-group">
                <div class="col-md-9 col-sm-9 col-xs-12">
                <div align="right">
                  <button type="submit" name="urunduzenle" class="btn btn-success">Güncelle</button>               
                </div>
                </div>
              </div>

            </form>

            


          </div>
        </div>
      </div>
    </div>



    <hr>
    <hr>
    <hr>



  </div>
</div>
<!-- /page content -->

<?php include 'footer.php'; ?>
