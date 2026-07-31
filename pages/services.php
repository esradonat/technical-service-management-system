<?php
$page = "services";
session_start();

if(!isset($_SESSION["user"])){
    header("Location: ../login.php");
    exit;
}

require_once "../db.php";
//***********müşteri silme
if(isset($_GET["delete"])){

    $sql = "DELETE FROM services WHERE id = :id";

    $query = $db->prepare($sql);

    $query->execute([
        "id" => $_GET["delete"]
    ]);

   header("Location: services.php?success=delete");
    exit;
}
//**************************** */

//müsteri düzenlee
$edit = null;

if(isset($_GET["edit"])){

    $sql = "SELECT * FROM services WHERE id = :id";

    $query = $db->prepare($sql);

    $query->execute([
        "id" => $_GET["edit"]
    ]);

    $edit = $query->fetch(PDO::FETCH_ASSOC);
}

//müşteri ekleme ve guncelleme
if(isset($_POST["save"])){

    if(!empty($_POST["id"])){

        $sql = "UPDATE services SET
                device_id=:device_id,
                employee_id=:employee_id,
                status=:status,
                description=:description,
                price=:price
                WHERE id = :id";

        $query = $db->prepare($sql);

        $query->execute([

"id"=>$_POST["id"],

"device_id"=>$_POST["device_id"],

"employee_id"=>$_POST["employee_id"],

"status"=>$_POST["status"],

"description"=>$_POST["description"],

"price"=>$_POST["price"]=="" ? null : $_POST["price"]

]);
        header("Location: services.php?success=update");
        exit;

    }else{

        $sql="INSERT INTO services
        (device_id,employee_id,status,description,price)
         VALUES
        (:device_id,:employee_id,:status,:description,:price)";
        $query = $db->prepare($sql);

        $query->execute([
        "device_id"=>$_POST["device_id"],
        "employee_id"=>$_POST["employee_id"],
        "status"=>$_POST["status"],
        "description"=>$_POST["description"],
        "price"=>$_POST["price"]=="" ? null : $_POST["price"]
        ]);

    }

    header("Location: services.php?success=add");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servis Kayıtları</title>
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
        Servis Kayıtları
        </h2>

        <small class="text-muted">
            Teknik Servis Yönetim Sistemi
        </small>

    </div>

</div>
<?php if(isset($_GET["success"])){ ?>

    <?php if($_GET["success"]=="add"){ ?>

        <div class="alert alert-success shadow-sm rounded-3">
            Servis kaydı başarıyla eklendi.
        </div>

    <?php } ?>

    <?php if($_GET["success"]=="update"){ ?>

        <div class="alert alert-primary shadow-sm rounded-3">
            Servis kaydı başarıyla güncellendi.
        </div>

    <?php } ?>

    <?php if($_GET["success"]=="delete"){ ?>

        <div class="alert alert-danger shadow-sm rounded-3">
            Servis kaydı başarıyla silindi.
        </div>

    <?php } ?>

<?php } ?>

<nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a class="text-secondary" href="../dashboard.php">
        <i class="bi bi-house"></i>
        Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Servis Kayıtları</li>
  </ol>
</nav>
<!-- //*********yeni müsteri */ -->
<div class="card mb-4">

<div class="card-header bg-success text-white">

<?= $edit ? "Kayıt Düzenle" : "Yeni Kayıt" ?>

</div>

<div class="card-body">

<form method="post">

<div class="row">
<!-- cihaz  -->
<div class="col-md-6 mb-3">
    
<input
type="hidden"
name="id"
value="<?= $edit["id"] ?? "" ?>">

<label class="form-label">Cihaz</label>

<select
class="form-select"
name="device_id"
required>

<option value="">Cihaz Seçiniz</option>

<?php

$devices = $db->query("
SELECT
devices.id,
customers.name,
customers.surname,
devices.brand,
devices.model

FROM devices

INNER JOIN customers
ON customers.id = devices.customer_id

ORDER BY customers.name
");

foreach($devices as $device){

?>

<option
value="<?= $device["id"] ?>"
<?= isset($edit) && $edit["device_id"]==$device["id"] ? "selected" : "" ?>>

<?= $device["name"] ?>

<?= $device["surname"] ?>

-

<?= $device["brand"] ?>

<?= $device["model"] ?>

</option>

<?php } ?>

</select>

</div>

<!-- Personel Seciçimi -->
<div class="col-md-6 mb-3">
    <label class="form-label">Personel</label>

<select
class="form-select"
name="employee_id"
required>
<option value="">Personel Seçiniz</option>

<?php
$employees = $db->query("
SELECT id, name, surname
FROM employees
ORDER BY name
");
foreach($employees as $employee){
?>
<option
value="<?= $employee["id"] ?>"
<?= isset($edit) && $edit["employee_id"] == $employee["id"] ? "selected" : "" ?>>
<?= $employee["name"] ?>

<?= $employee["surname"] ?>
</option>
<?php } ?>
</select>
</div>


<!-- durum -->
<div class="col-md-6 mb-3">

<label class="form-label">Durum</label>

<select
class="form-select"
name="status"
required>

<option value="">Durum Seçiniz</option>

<option value="Bekliyor"
<?= isset($edit) && $edit["status"]=="Bekliyor" ? "selected" : "" ?>>
Bekliyor
</option>

<option value="İşlemde"
<?= isset($edit) && $edit["status"]=="İşlemde" ? "selected" : "" ?>>
İşlemde
</option>

<option value="Tamamlandı"
<?= isset($edit) && $edit["status"]=="Tamamlandı" ? "selected" : "" ?>>
Tamamlandı
</option>

<option value="Teslim Edildi"
<?= isset($edit) && $edit["status"]=="Teslim Edildi" ? "selected" : "" ?>>
Teslim Edildi
</option>
</select>
</div>

<!-- ücret -->
<div class="col-md-6 mb-3">

<label class="form-label">Ücret</label>

<input
type="number"
step="0.01"
class="form-control"
name="price"
placeholder="Servis Ücreti"
value="<?= $edit["price"] ?? "" ?>">
</div>


<!-- açıklama -->
<div class="col-12 mb-3">

<label class="form-label">Açıklama</label>

<textarea
class="form-control"
name="description"
rows="4"
placeholder="Arıza açıklaması veya yapılan işlem"><?= $edit["description"] ?? "" ?></textarea>

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

services.*,

customers.name,

customers.surname,

devices.brand,

devices.model,

employees.name AS employee_name,

employees.surname AS employee_surname

FROM services

INNER JOIN devices
ON devices.id = services.device_id

INNER JOIN customers
ON customers.id = devices.customer_id

INNER JOIN employees
ON employees.id = services.employee_id

WHERE

customers.name ILIKE :search

OR customers.surname ILIKE :search

OR devices.brand ILIKE :search

OR devices.model ILIKE :search

OR employees.name ILIKE :search

ORDER BY services.id ASC
";


    $query = $db->prepare($sql);

    $query->execute([
        "search" => $search
    ]);

    $list = $query->fetchAll(PDO::FETCH_ASSOC);
}
else{

    $list = $db->query("
SELECT

services.*,

customers.name,

customers.surname,

devices.brand,

devices.model,

employees.name AS employee_name,

employees.surname AS employee_surname

FROM services

INNER JOIN devices
ON devices.id = services.device_id

INNER JOIN customers
ON customers.id = devices.customer_id

INNER JOIN employees
ON employees.id = services.employee_id

ORDER BY services.id DESC
");
}

?>

<!-- *****************müsteri listesi -->
<div class="card mb-5">

    <div class="card-header bg-primary text-white">
       Servis Kayıtları
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
placeholder="Müşteri, Personel, Marka veya Model ara..."    value="<?= $_GET["search"] ?? "" ?>">

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

<th>Cihaz</th>

<th>Personel</th>

<th>Durum</th>

<th>Ücret</th>

<th>İşlemler</th>

</tr>

</thead>

<tbody>

<?php foreach($list as $row){ ?>

<tr>

<td><?= $row["id"] ?></td>

<td><?= $row["name"] ?> <?= $row["surname"] ?></td>

<td>

<?= $row["brand"] ?>

<?= $row["model"] ?>

</td>

<td>

<?= $row["employee_name"] ?>

<?= $row["employee_surname"] ?>

</td>

<td>

<?php

switch($row["status"]){

    case "Bekliyor":
        echo '<span class="badge bg-secondary">Bekliyor</span>';
        break;

    case "İşlemde":
        echo '<span class="badge bg-warning text-dark">İşlemde</span>';
        break;

    case "Tamamlandı":
        echo '<span class="badge bg-success">Tamamlandı</span>';
        break;

    case "Teslim Edildi":
        echo '<span class="badge bg-primary">Teslim Edildi</span>';
        break;

}

?>

</td>



<td>

<?= $row["price"] == null ? "-" : number_format($row["price"],2,",",".") ?> ₺

</td>

<td>

<a href="?edit=<?= $row["id"] ?>"
class="btn btn-warning btn-sm"
title="Düzenle">

<i class="bi bi-pencil-square"></i>

</a>

<a href="?delete=<?= $row["id"] ?>"
class="btn btn-danger btn-sm"
title="Sil"
onclick="return confirm('Bunu silmek istediğinize emin misiniz?')">

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