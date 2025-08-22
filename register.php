<?php
    include 'admin/netting/baglan.php';

    if($_POST) {
        $NAME = $_POST['name'] ?? '';
        $EMAIL = $_POST['email'] ?? '';
        $PHONE = $_POST['phone'] ?? '';
        $PASSWORD = $_POST['password'] ?? '';
        $CONFIRM_PASSWORD = $_POST['confirm_password'] ?? '';
        $KAYIT_OLABILIR = true;
        $MESAJ = '';

        if($PASSWORD === $CONFIRM_PASSWORD) {
            if(strlen($PASSWORD) < 6){
                $MESAJ = 'The password must be at least 6 characters long.';
                $KAYIT_OLABILIR = false;
            } else if(strlen($PASSWORD) > 16){
                $MESAJ = 'The password must be no more than 16 characters long.';
                $KAYIT_OLABILIR = false;
            } 
        } else {
            $MESAJ = 'Passwords do not match';
            $KAYIT_OLABILIR = false;
        }

        if($KAYIT_OLABILIR) {
            $HASHED_PASSWORD = password_hash($PASSWORD, PASSWORD_DEFAULT);

            $sonuc = $db->prepare("INSERT INTO kullanici(kullanici_ad,kullanici_mail,kullanici_gsm,kullanici_password) 
                                VALUES(:name, :email, :phone, :password)");
            $sonuc->execute(array(
                'name' => $NAME,
                'email' => $EMAIL,
                'phone' => $PHONE,
                'password' => $HASHED_PASSWORD
            ));
            session_start();
            $_SESSION['status'] = 'success';
            header("Location: login?email=$EMAIL");
            exit;
        }
    }
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Partsix - Account Page</title>
  <meta name="description" content="Morden Bootstrap HTML5 Template">
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
        <div class="login__section section--padding py-auto">
            <div class="container">
                <form action="" method="post">
                    <div class="login__section--inner">
                        
                        <div class="d-flex justify-content-center align-items-center my-auto">
                            <div>
                                <div class="account__login register">
                                    <?php if(isset($MESAJ)): ?>
                                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                            <?= $MESAJ ?>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    <?php endif; ?>
                                    <div class="account__login--header mb-25">
                                        <h2 class="account__login--header__title mb-10">Create an Account</h2>
                                        <p class="account__login--header__desc">Register here if you are a new customer</p>
                                    </div>
                                    <div class="account__login--inner">
                                        <label>
                                            <input class="account__login--input" placeholder="Name" type="text" name="name">
                                        </label>
                                        <label>
                                            <input class="account__login--input" placeholder="Email Addres" type="email" name="email">
                                        </label>
                                        <label>
                                            <input class="account__login--input" type="text" id="phone" placeholder="(5xx) xxx xx xx" name="phone">
                                        </label>
                                        <label>
                                            <input class="account__login--input" placeholder="Password" type="password" name="password">
                                        </label>
                                        <label>
                                            <input class="account__login--input" placeholder="Confirm Password" type="password" name="confirm_password">
                                        </label>
                                        <button class="account__login--btn primary__btn mb-10" type="submit">Submit & Register</button>
                                        <div class="account__login--remember position__relative">
                                            <input class="checkout__checkbox--input" id="check2" type="checkbox">
                                            <span class="checkout__checkbox--checkmark"></span>
                                            <label class="checkout__checkbox--label login__remember--label" for="check2">
                                                I have read and agree to the terms & conditions</label>
                                        </div>
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
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- jQuery Mask Plugin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

   <script src="assets/js/vendor/popper.js" defer="defer"></script>
   <script src="assets/js/vendor/bootstrap.min.js" defer="defer"></script>
   <script src="assets/js/plugins/swiper-bundle.min.js"></script>
   <script src="assets/js/plugins/glightbox.min.js"></script>
 
  <!-- Customscript js -->
  <script src="assets/js/script.js"></script>
    <script>
        $(document).ready(function(){
            $('#phone').mask('(000) 000 00 00');
        });
    </script>
</body>
</html>