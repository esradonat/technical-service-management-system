<?php
$page = "devices";
session_start();

if(!isset($_SESSION["user"])){
    header("Location: ../login.php");
    exit;
}

require_once "../db.php";
//***********cihaz silme
if(isset($_GET["delete"])){

    $sql = "DELETE FROM devices WHERE id = :id";

    $query = $db->prepare($sql);

    $query->execute([
        "id" => $_GET["delete"]
    ]);

   header("Location: devices.php?success=delete");
    exit;
}
//**************************** */

//cihaz düzenlee
$edit = null;

if(isset($_GET["edit"])){

    $sql = "SELECT * FROM devices WHERE id = :id";

    $query = $db->prepare($sql);

    $query->execute([
        "id" => $_GET["edit"]
    ]);

    $edit = $query->fetch(PDO::FETCH_ASSOC);
}

//cihaz ekleme ve guncelleme
if(isset($_POST["save"])){

    if(!empty($_POST["id"])){

        $sql = "UPDATE devices SET
                customer_id=:customer_id,
                brand=:brand,
                model=:model,
                serial_no=:serial_no,
                device_type=:device_type,
                complaint=:complaint
                WHERE id = :id";

        $query = $db->prepare($sql);

        $query->execute([

        "id"=>$_POST["id"],
        "customer_id"=>$_POST["customer_id"],
        "brand"=>$_POST["brand"],
        "model"=>$_POST["model"],
        "serial_no"=>$_POST["serial_no"],
        "device_type"=>$_POST["device_type"],
        "complaint"=>$_POST["complaint"]

         ]);
        header("Location: devices.php?success=update");
        exit;

    }else{

        $sql = "INSERT INTO devices
        (customer_id,brand,model,serial_no,device_type,complaint)
        VALUES
        (:customer_id,:brand,:model,:serial_no,:device_type,:complaint)";

        $query = $db->prepare($sql);

        $query->execute([

    "customer_id" => $_POST["customer_id"],
    "brand"       => $_POST["brand"],
    "model"       => $_POST["model"],
    "serial_no"   => $_POST["serial_no"],
    "device_type" => $_POST["device_type"],
    "complaint"   => $_POST["complaint"]

]);

    }

    header("Location: devices.php?success=add");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cihaz Yönetimi</title>
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

        <h2 class="fw-bold text-success">
        <i class="bi bi-laptop"></i>
        Cihaz Yönetimi
        </h2>

        <small class="text-muted">
            Teknik Servis Yönetim Sistemi
        </small>

    </div>

</div>
<?php if(isset($_GET["success"])){ ?>

    <?php if($_GET["success"]=="add"){ ?>

        <div class="alert alert-success shadow-sm rounded-3">
            Cihaz başarıyla eklendi.
        </div>

    <?php } ?>

    <?php if($_GET["success"]=="update"){ ?>

        <div class="alert alert-primary shadow-sm rounded-3">
            Cihaz başarıyla güncellendi.
        </div>

    <?php } ?>

    <?php if($_GET["success"]=="delete"){ ?>

        <div class="alert alert-danger shadow-sm rounded-3">
            Cihaz başarıyla silindi.
        </div>

    <?php } ?>

<?php } ?>

<nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a class="text-secondary" href="../dashboard.php">
        <i class="bi bi-house"></i>
        Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Cihaz Yönetimi</li>
  </ol>
</nav>
<!-- //*********yeni cihaz */ -->
<div class="card mb-4">

<div class="card-header bg-success text-white">

<?= $edit ? "Cihaz Düzenle" : "Yeni Cihaz" ?>

</div>

<div class="card-body">

<form method="post">

<div class="row">

<div class="col-md-6 mb-3">

<input
type="hidden"
name="id"
value="<?= $edit["id"] ?? "" ?>">

<select
class="form-select"
name="customer_id"
required>

<option value="">
Müşteri Seçiniz
</option>

<?php

$customers = $db->query("
SELECT id,name,surname
FROM customers
ORDER BY name
");

foreach($customers as $customer){

?>

<option
value="<?= $customer["id"] ?>"
<?= isset($edit) && $edit["customer_id"] == $customer["id"] ? "selected" : "" ?>>

<?= $customer["name"] ?>

<?= $customer["surname"] ?>

</option>
<?php } ?>
</select>
</div>

<div class="col-md-6 mb-3">
<input
class="form-control"
name="brand"
placeholder="Marka"
value="<?= $edit["brand"] ?? "" ?>"
required>
</div>

<div class="col-md-6 mb-3">
<input
class="form-control"
name="model"
placeholder="Model"
value="<?= $edit["model"] ?? "" ?>">
</div>

<div class="col-md-6 mb-3">
<input
class="form-control"
name="serial_no"
placeholder="Seri No"
value="<?= $edit["serial_no"] ?? "" ?>">
</div>

<div class="col-md-6 mb-3">

<select
class="form-select"
name="device_type"
required>

<option value="Laptop"
<?= isset($edit) && $edit["device_type"]=="Laptop" ? "selected" : "" ?>>
Laptop
</option>

<option value="Masaüstü"
<?= isset($edit) && $edit["device_type"]=="Masaüstü" ? "selected" : "" ?>>
Masaüstü
</option>

<option value="Telefon"
<?= isset($edit) && $edit["device_type"]=="Telefon" ? "selected" : "" ?>>
Telefon
</option>

<option value="Tablet"
<?= isset($edit) && $edit["device_type"]=="Tablet" ? "selected" : "" ?>>
Tablet
</option>

<option value="Yazıcı"
<?= isset($edit) && $edit["device_type"]=="Yazıcı" ? "selected" : "" ?>>
Yazıcı
</option>

<option value="Monitör"
<?= isset($edit) && $edit["device_type"]=="Monitör" ? "selected" : "" ?>>
Monitör
</option>

<option value="Diğer"
<?= isset($edit) && $edit["device_type"]=="Diğer" ? "selected" : "" ?>>
Diğer
</option>

</select>

</div>

<div class="col-12 mb-3">
<textarea
class="form-control"
name="complaint"
placeholder="Arıza Şikayeti"><?= $edit["complaint"] ?? "" ?></textarea>
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

    $sql = "
SELECT
devices.*,
customers.name,
customers.surname

FROM devices

INNER JOIN customers
ON customers.id=devices.customer_id

WHERE
customers.name ILIKE :search
OR customers.surname ILIKE :search
OR devices.brand ILIKE :search
OR devices.model ILIKE :search

ORDER BY devices.id DESC
";

    $query = $db->prepare($sql);

    $query->execute([
        "search" => $search
    ]);

    $list = $query->fetchAll(PDO::FETCH_ASSOC);
}
else{

    $list=$db->query("SELECT devices.*, customers.name, customers.surname FROM devices INNER JOIN customers 
    ON customers.id=devices.customer_id
    ORDER BY devices.id DESC");
}

?>

<!-- *****************cihaz listesi -->
<div class="card mb-5">

    <div class="card-header bg-primary text-white">
       Cihaz Listesi
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
    placeholder="Müşteri, Marka veya Model ile ara..."
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

<th>Müşteri</th>

<th>Marka</th>

<th>Model</th>

<th>Seri No</th>

<th>Cihaz Türü</th>

<th>Arıza</th>

<th>İşlemler</th>

</tr>

</thead>

<tbody>

<?php foreach($list as $row){ ?>

<tr>

<td><?= $row["id"] ?></td>

<td><?= $row["name"] ?> <?= $row["surname"] ?></td>

<td><?= $row["brand"] ?></td>

<td><?= $row["model"] ?></td>

<td><?= $row["serial_no"] ?></td>

<td><?= $row["device_type"] ?></td>

<td><?= $row["complaint"] ?></td>

<td>

<a href="?edit=<?= $row["id"] ?>"
class="btn btn-warning btn-sm"
title="Düzenle">

<i class="bi bi-pencil-square"></i>

</a>

<a href="?delete=<?= $row["id"] ?>"
class="btn btn-danger btn-sm"
title="Sil"
onclick="return confirm('Bu müşteriyi silmek istediğinize emin misiniz?')">

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