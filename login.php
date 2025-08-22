<?php
include 'admin/netting/baglan.php';
session_start();

if($_POST) {
    $EMAIL = $_POST['email'] ?? '';
    $PASSWORD = $_POST['password'] ?? '';
    $MESAJ;

    $query = $db->prepare("SELECT * FROM kullanici WHERE kullanici_mail = :mail");
    $query->execute(['mail' => $EMAIL]);
    $USER = $query->fetch(PDO::FETCH_ASSOC);

    if(!$USER) {
        $MESAJ = 'Email address not found.';
    } elseif(!password_verify($PASSWORD, $USER['kullanici_password'])) {
        $MESAJ = 'Incorrect password.';
    } else {
        $_SESSION['USER'] = $USER;

        if(isset($_POST['remember']) && $_POST['remember'] == 'on') {
            $token = hash('sha256',$USER['kullanici_id'] . bin2hex(random_bytes(16)) . time());
            setcookie('remember', $token, time() + (86400*30), "/", "", true, true);
            $db->prepare("UPDATE kullanici SET remember_token = :token WHERE kullanici_id = :id")
               ->execute(['token'=>$token, 'id'=>$USER['kullanici_id']]);
        }

        header('Location: shop');
        exit;
    }
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Partsix - Account Page</title>
  <meta name="description" content="army part login army part sign in ARMY PART">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico">
    
  <!-- ======= All CSS Plugins here ======== -->
  <link rel="stylesheet" href="assets/css/plugins/swiper-bundle.min.css">
  <link rel="stylesheet" href="assets/css/plugins/glightbox.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700&family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500&display=swap" rel="stylesheet">

  <!-- Plugin css -->
  <link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">

  <!-- Custom Style CSS -->
  <link rel="stylesheet" href="assets/css/style.css">

</head>

<body class="h-100">
    <main class="main__content_wrapper">
        <!-- Start login section  -->
        <div class="login__section section--padding">
            <div class="container">
                <form action="" method="post">
                    <div class="login__section--inner">
                        <div class="d-flex justify-content-center align-items-center my-auto">
                            <div>
                                <div class="account__login">
                                    <?php if(isset($_SESSION['status']) && $_SESSION['status'] == 'success'): ?>
                                        <?php unset($_SESSION['status']); ?>
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            Kaydınız oluşturuldu giriş yapabilirsiniz
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    <?php endif; ?>
                                    <?php if(isset($MESAJ)): ?>
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            <?=$MESAJ?>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    <?php endif; ?>
                                    <div class="account__login--header mb-25">
                                        <h2 class="account__login--header__title mb-10">Login</h2>
                                        <p class="account__login--header__desc">Login if you area a returning customer.</p>
                                    </div>
                                    <div class="account__login--inner">
                                        <label>
                                            <input class="account__login--input" placeholder="Email Addres" type="email" name="email" value="<?=@$_GET['email']?>">
                                        </label>
                                        <label>
                                            <input class="account__login--input" placeholder="Password" name="password" type="password">
                                        </label>
                                        <div class="account__login--remember__forgot mb-15 d-flex justify-content-between align-items-center">
                                            <div class="account__login--remember position__relative">
                                                <input class="checkout__checkbox--input" id="check1" type="checkbox" name="remember">
                                                <span class="checkout__checkbox--checkmark"></span>
                                                <label class="checkout__checkbox--label login__remember--label" for="check1">
                                                    Remember me</label>
                                            </div>
                                            <button class="account__login--forgot"  type="submit">Forgot Your Password?</button>
                                        </div>
                                        <button class="account__login--btn primary__btn" type="submit">Login</button>
                                        <div class="account__login--divide">
                                            <span class="account__login--divide__text">OR</span>
                                        </div>
                                        <div class="account__social d-flex justify-content-center mb-15">
                                            <a class="account__social--link facebook" target="_blank" href="https://www.facebook.com">Facebook</a>
                                            <a class="account__social--link google" target="_blank" href="https://www.google.com">Google</a>
                                            <a class="account__social--link twitter" target="_blank" href="https://twitter.com">Twitter</a>
                                        </div>
                                        <p class="account__login--signup__text">Don,t Have an Account? <button type="submit">Sign up now</button></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>     
        </div>
        <!-- End login section  -->
    </main>

   <!-- All Script JS Plugins here  -->
   <script src="assets/js/vendor/popper.js" defer="defer"></script>
   <script src="assets/js/vendor/bootstrap.min.js" defer="defer"></script>
   <script src="assets/js/plugins/swiper-bundle.min.js"></script>
   <script src="assets/js/plugins/glightbox.min.js"></script>
 
  <!-- Customscript js -->
  <script src="assets/js/script.js"></script>
  
</body>
</html>