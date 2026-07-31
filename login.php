<?php
session_start();
require_once "db.php";


if(isset($_POST["username"])){

    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    $sql = "SELECT * FROM users
            WHERE username = :username
            AND password = :password";

    $query = $db->prepare($sql);

    $query->execute([
        "username" => $username,
        "password" => $password
    ]);

    $user = $query->fetch(PDO::FETCH_ASSOC);

    if($user){

        $_SESSION["user"] = $user;

        header("Location: dashboard.php");
        exit;

    }else{

        $error = "Kullanıcı adı veya şifre yanlış.";

    }

}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teknik Servis Yönetim Sistemi</title>

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body class="bg-light">

<div class="container">

    <div class="row justify-content-center align-items-center vh-100">

        <div class="col-md-5">

            <div class="card shadow">

                <div class="card-body p-4">

                    <div class="text-center mb-4">

                        <i class="fas fa-tools fa-3x text-primary mb-3"></i>

                        <h3>Teknik Servis</h3>

                        <p class="text-muted">
                            Yönetim Sistemine Giriş
                        </p>

                    </div>
            <?php if(isset($error)){ ?>

            <div class="alert alert-danger">

            <?= $error ?>

</div>

<?php } ?>
                    <form method="post">

                        <div class="mb-3">

                            <label>Kullanıcı Adı</label>

                            <input
                                type="text"
                                name="username"
                                class="form-control"
                                required>

                        </div>

                        <div class="mb-3">

                            <label>Şifre</label>

                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                required>

                        </div>

                        <button class="btn btn-primary w-100">

                            Giriş Yap

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

<script src="assets/js/bootstrap.bundle.min.js"></script>

</body>
</html>