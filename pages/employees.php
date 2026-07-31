<?php
$page = "employees";
session_start();

if(!isset($_SESSION["user"])){
    header("Location: ../login.php");
    exit;
}

require_once "../db.php";
//***********personl silme
if(isset($_GET["delete"])){

    $sql = "DELETE FROM employees WHERE id = :id";

    $query = $db->prepare($sql);

    $query->execute([
        "id" => $_GET["delete"]
    ]);

   header("Location: employees.php?success=delete");
    exit;
}
//**************************** */

//personel düzenlee
$edit = null;

if(isset($_GET["edit"])){

    $sql = "SELECT * FROM employees WHERE id = :id";

    $query = $db->prepare($sql);

    $query->execute([
        "id" => $_GET["edit"]
    ]);

    $edit = $query->fetch(PDO::FETCH_ASSOC);
}

//personel ekleme ve guncelleme
if(isset($_POST["save"])){

    if(!empty($_POST["id"])){

        $sql = "UPDATE employees SET
                name = :name,
                surname = :surname,
                phone = :phone,
                email = :email,
                position=:position
                WHERE id = :id";

        $query = $db->prepare($sql);

        $query->execute([

            "id" => $_POST["id"],
            "name" => $_POST["name"],
            "surname" => $_POST["surname"],
            "phone" => $_POST["phone"],
            "email" => $_POST["email"],
            "position" => $_POST["position"]

        ]);
        header("Location: employees.php?success=update");
        exit;

    }else{

        $sql = "INSERT INTO employees
        (name,surname,phone,email,position)
        VALUES
        (:name,:surname,:phone,:email,:position)";

        $query = $db->prepare($sql);

        $query->execute([

            "name" => $_POST["name"],
            "surname" => $_POST["surname"],
            "phone" => $_POST["phone"],
            "email" => $_POST["email"],
            "position" => $_POST["position"]

        ]);

    }

    header("Location: employees.php?success=add");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personeller</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">

    <link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>

body{
    background:#f4f6f9;
    font-family:Segoe UI, sans-serif;
}

.card{
    border:none;
    border-radius:18px;
    box-shadow:0 10px 30px rgba(0,0,0,.08);
    overflow:hidden;
}

.card-header{
    font-weight:600;
    font-size:18px;
}
.form-control,
.form-select{

    border-radius:12px;
    border:1px solid #dee2e6;
    padding:12px;

}

.form-control:focus{

    border-color:#198754;
    box-shadow:0 0 10px rgba(25,135,84,.2);

}
textarea{

    min-height:120px;

}
.btn{

    border-radius:10px;

}

</style>
</head>


<body>
<?php include "../includes/sidebar.php"; ?><div class="main-content">
<div class="container">

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h2 class="fw-bold text-success mb-1">
            <i class="bi bi-person-badge-fill"></i>
            Personel Yönetimi
        </h2>

        <small class="text-muted">
            Teknik Servis Yönetim Sistemi
        </small>

    </div>

</div>
<?php if(isset($_GET["success"])){ ?>

    <?php if($_GET["success"]=="add"){ ?>

        <div class="alert alert-success shadow-sm rounded-3">
            Personel başarıyla eklendi.
        </div>

    <?php } ?>

    <?php if($_GET["success"]=="update"){ ?>

        <div class="alert alert-success shadow-sm rounded-3">
            Personel başarıyla güncellendi.
        </div>

    <?php } ?>

    <?php if($_GET["success"]=="delete"){ ?>

        <div class="alert alert-success shadow-sm rounded-3">
            Personel başarıyla silindi.
        </div>

    <?php } ?>

<?php } ?>

<nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a class="text-secondary" href="../dashboard.php">
        <i class="bi bi-house"></i>
        Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Personel Yönetimi</li>
  </ol>
</nav>
<!-- //*********yeni personel */ -->
<div class="card mb-4">

<div class="card-header bg-success text-white">

<?= $edit ? "Personel Düzenle" : "Yeni Personel" ?>

</div>

<div class="card-body">

<form method="post">

<div class="row">

<div class="col-md-6 mb-3">

<input
type="hidden"
name="id"
value="<?= $edit["id"] ?? "" ?>">

<input
class="form-control"
name="name"
placeholder="Ad"
value="<?= $edit["name"] ?? "" ?>"
required
>

</div>

<div class="col-md-6 mb-3">

<input
class="form-control"
name="surname"
placeholder="Soyad"
value="<?= $edit["surname"] ?? "" ?>"
required

>

</div>

<div class="col-md-6 mb-3">

<input
class="form-control"
name="phone"
placeholder="Telefon"
value="<?= $edit["phone"] ?? "" ?>">

</div>

<div class="col-md-6 mb-3">

<input
class="form-control"
name="email"
placeholder="E-Mail"
value="<?= $edit["email"] ?? "" ?>">

</div>

<div class="col-md-6 mb-3">

    <select
    class="form-select"
    name="position"
    required>

        <option value="">Görev Seçiniz</option>

        <option
        value="Teknisyen"
        <?= isset($edit) && $edit["position"]=="Teknisyen" ? "selected" : "" ?>>
        Teknisyen
        </option>

        <option
        value="Danışman"
        <?= isset($edit) && $edit["position"]=="Danışman" ? "selected" : "" ?>>
        Danışman
        </option>

        <option
        value="Yönetici"
        <?= isset($edit) && $edit["position"]=="Yönetici" ? "selected" : "" ?>>
        Yönetici
        </option>

    </select>

</div>


<div class="col-12">

<button
name="save"
class="btn btn-success px-4">
<i class="bi bi-check-circle"></i>

<?= isset($edit) && $edit ? "Güncelle" : "Kaydet" ?>

</button>

</div>

</div>

</form>
</div> <!-- card-body -->

</div>
<?php

if(isset($_GET["search"]) && $_GET["search"] != ""){

    $search = "%".$_GET["search"]."%";

    $sql = "SELECT * FROM employees
            WHERE name ILIKE :search
               OR surname ILIKE :search
               OR phone ILIKE :search
               OR position ILIKE :search
            ORDER BY id ASC";

    $query = $db->prepare($sql);

    $query->execute([
        "search" => $search
    ]);

    $list = $query->fetchAll(PDO::FETCH_ASSOC);

}else{

    $list = $db->query("SELECT * FROM employees ORDER BY id ASC");

}

?>

<!-- *****************personel listesi -->
<div class="card mb-5">

    <div class="card-header bg-primary text-white">
        Personel Listesi
    </div>
<form method="GET">

<div class="input-group mt-3 px-3">

    <span class="input-group-text">
        <i class="bi bi-search"></i>
    </span>

    <input
        type="text"
        class="form-control"
        name="search"
        placeholder="Ad, Soyad veya Telefon ile ara..."
        value="<?= $_GET["search"] ?? "" ?>">

    <button class="btn btn-primary">
        Ara
    </button>

</div>

</form>
<!-- ************tablo oluşturma -->
    <div class="card-body">
        <div class="table-responsive" style="max-height:500px; overflow-y:auto;">
<table class="table table-hover align-middle">
<thead class="table-primary sticky-top">

<tr class>

<th>ID</th>

<th>Ad</th>

<th>Soyad</th>

<th>Telefon</th>

<th>Email</th>

<th>Görev</th>

<th>İşlemler</th>

</tr>

</thead>

<tbody>

<?php foreach($list as $row){ ?>

<tr>

<td><?= $row["id"] ?></td>

<td><?= $row["name"] ?></td>

<td><?= $row["surname"] ?></td>

<td><?= $row["phone"] ?></td>

<td><?= $row["email"] ?></td>

<td><?= $row["position"] ?></td>

<td>

<a href="?edit=<?= $row["id"] ?>"
class="btn btn-warning btn-sm"
title="Düzenle">

    <i class="bi bi-pencil-square"></i>

</a>

<a href="?delete=<?= $row["id"] ?>"
class="btn btn-danger btn-sm"
title="Sil"
onclick="return confirm('Bu personeli silmek istediğinize emin misiniz?')">

    <i class="bi bi-trash"></i>

</a>

</td>

</tr>

<?php } ?>

</tbody>

</table>
</div> <!-- table-responsive -->
</div>

</div>

</div>

</div>
</div>

<script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>