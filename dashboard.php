<?php
$page = "dashboard";//menude active seyi için
session_start();

if(!isset($_SESSION["user"])){
    header("Location: login.php");
    exit;
}
require_once "db.php";

// İstatistikler - kayıt sayıları
$totalCustomers = $db->query("SELECT COUNT(*) FROM customers")->fetchColumn();
$totalEmployees = $db->query("SELECT COUNT(*) FROM employees")->fetchColumn();
$totalDevices = $db->query("SELECT COUNT(*) FROM devices")->fetchColumn();
$totalServices = $db->query("SELECT COUNT(*) FROM services")->fetchColumn();
//fetchColumn-> sorgunun ilk sütununu dondurur
// istatistik bitiş 


//son 5 servis kaydı
$lastServices = $db->query("SELECT services.id, customers.name, customers.surname, devices.brand, devices.model, services.status, services.price
                        FROM services INNER JOIN devices
                        ON devices.id=services.device_id
                            INNER JOIN customers
                        ON customers.id=devices.customer_id
                        ORDER BY services.id DESC LIMIT 5 ");

$waiting = $db->query("SELECT COUNT(*) FROM services WHERE status='Bekliyor'")->fetchColumn();
$working = $db->query("SELECT COUNT(*) FROM services WHERE status='İşlemde'")->fetchColumn();
$finished = $db->query("SELECT COUNT(*) FROM services WHERE status='Tamamlandı'")->fetchColumn();
$delivered = $db->query("SELECT COUNT(*) FROM services WHERE status='Teslim Edildi'")->fetchColumn();
//
?>

<!DOCTYPE html>
<html lang="tr">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Dashboard</title>


<link rel="stylesheet" href="assets/css/bootstrap.min.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>

body{
    background:linear-gradient(135deg,#eef2f7,#d9e4f5);
}

.navbar{
    box-shadow:0 2px 10px rgba(0,0,0,.15);
}

.card{
    transition: .35s;
    border-radius:18px;
}

/* kart yukarı */
.card:hover{ 
    transform:translateY(-8px);
    box-shadow:0 20px 35px rgba(0,0,0,.18);
}

.card i{
    font-size:45px;
    margin-bottom:15px;
}
.small-box{
    position:relative;
    color:#fff;
    border-radius:6px;
    overflow:hidden;
    min-height:120px;
    transition:.3s;
}

.small-box:hover{
    transform:translateY(-4px);
    box-shadow:0 15px 25px rgba(0,0,0,.20);
}

.small-box .inner{
    padding:15px;
}

.small-box h3{
    font-size:38px;
    font-weight:700;
    margin:0;
}

.small-box p{
    margin-top:5px;
    font-size:18px;
}

.small-box .icon{
    position:absolute;
    top:10px;
    right:15px;
    font-size:70px;
    opacity:.20;
}

.small-box .footer{
    display:block;
    background:rgba(0,0,0,.18);
    color:#fff;
    text-align:center;
    padding:8px;
    text-decoration:none;
    transition:.3s;
}

.small-box .footer:hover{
    background:rgba(0,0,0,.28);
    color:#fff;
}

/* renkler */
.bg-info{
    background:#17a2b8;
}

.bg-success{
    background:#28a745;
}

.bg-warning{
    background:#ffc107;
    color:#212529 !important;
}

.bg-danger{
    background:#dc3545;
}



</style>

</head>

<body>
    <?php include "includes/sidebar.php"; ?>
<div class="main-content">
<div class="container mt-5">

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">

        <h3 class="fw-bold">
            Hoş Geldin,
            <span class="text-primary">
                <?= $_SESSION["user"]["username"] ?>
            </span>
        </h3>

        <p class="text-muted mb-0">
            Teknik Servis Yönetim Paneline hoş geldiniz.
        </p>

    </div>
</div>

<!-- //********************slider */ -->
<div id="slider" class="carousel slide mb-4" data-bs-ride="carousel" data-bs-interval="3000">

    <div class="carousel-indicators">
        <button type="button" data-bs-target="#slider" data-bs-slide-to="0" class="active"></button>
        <button type="button" data-bs-target="#slider" data-bs-slide-to="1"></button>
        <button type="button" data-bs-target="#slider" data-bs-slide-to="2"></button>
    </div>

    <div class="carousel-inner rounded shadow">

        <div class="carousel-item active">

            <img src="assets/img/slider1.jpg"
                 class="d-block w-100"
                 style="height:400px;object-fit:cover;">

            <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded">

                <h3>Teknik Servis Yönetim Sistemi</h3>

                <p>Müşteri, cihaz ve servis süreçlerini tek panelden yönetin.</p>

            </div>

        </div>

        <div class="carousel-item">

            <img src="assets/img/slider2.jpg"
                 class="d-block w-100"
                 style="height:400px;object-fit:cover;">

            <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded">

                <h3>Müşteri Yönetimi</h3>

                <p>Yeni müşteri ekleyin, düzenleyin ve arama yapın.</p>

            </div>

        </div>

        <div class="carousel-item">

            <img src="assets/img/slider3.jpg"
                 class="d-block w-100"
                 style="height:400px;object-fit:cover;">

            <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded">

                <h3>Arıza Takibi</h3>

                <p>Arızaları ve onarım süreçlerini tek ekrandan yönetin.</p>

            </div>

        </div>

    </div>

    <button class="carousel-control-prev"
            type="button"
            data-bs-target="#slider"
            data-bs-slide="prev">

        <span class="carousel-control-prev-icon"></span>

    </button>

    <button class="carousel-control-next"
            type="button"
            data-bs-target="#slider"
            data-bs-slide="next">

        <span class="carousel-control-next-icon"></span>

    </button>

</div>
<!-- //*****slider bitiş */ -->

<!-- istatistik kartı -->
<div class="row g-3 mb-5">

    <div class="col-lg-3 col-md-6">

        <div class="small-box bg-info">

            <div class="inner">

                <h3><?= $totalCustomers ?></h3>

                <p>Toplam Müşteri</p>

            </div>

            <div class="icon">
                <i class="bi bi-people-fill"></i>
            </div>

            <a href="pages/customers.php" class="footer">
                Detaylar
                <i class="bi bi-arrow-right-circle"></i>
            </a>

        </div>

    </div>

    <div class="col-lg-3 col-md-6">

        <div class="small-box bg-success">

            <div class="inner">

                <h3><?= $totalEmployees ?></h3>

                <p>Toplam Personel</p>

            </div>

            <div class="icon">
                <i class="bi bi-person-badge-fill"></i>
            </div>

            <a href="pages/employees.php" class="footer">
                Detaylar
                <i class="bi bi-arrow-right-circle"></i>
            </a>

        </div>

    </div>

    <div class="col-lg-3 col-md-6">

        <div class="small-box bg-warning">

            <div class="inner">

                <h3><?= $totalDevices ?></h3>

                <p>Toplam Cihaz</p>

            </div>

            <div class="icon">
                <i class="bi bi-laptop-fill"></i>
            </div>

            <a href="pages/devices.php" class="footer">
                Detaylar
                <i class="bi bi-arrow-right-circle"></i>
            </a>

        </div>

    </div>

    <div class="col-lg-3 col-md-6">

        <div class="small-box bg-danger">

            <div class="inner">

                <h3><?= $totalServices ?></h3>

                <p>Servis Kaydı</p>

            </div>

            <div class="icon">
                <i class="bi bi-tools"></i>
            </div>

            <a href="pages/services.php" class="footer">
                Detaylar
                <i class="bi bi-arrow-right-circle"></i>
            </a>

        </div>

    </div>

</div>
<!-- istatistik bitiş -->
 

<!-- grafik -->
<div class="row g-4 mb-5 ">

    <div class="col-lg-8 mb-4">

        <div class="card shadow border-0">

            <div class="card-header bg-primary text-white">

                <i class="bi bi-bar-chart-line"></i>

                Servis Durumları

            </div>

            <div class="card-body">

                <canvas id="serviceChart" height="110"></canvas>  
                <!-- grafik cizliecek chart js ile-->

            </div>
        </div>
    </div>

    <!-- harita  -->
    <div class="col-lg-4">

    <div class="card chart-card h-100">

        <div class="card-header bg-success text-white">
            <i class="bi bi-geo-alt-fill me-2"></i>
            Konum
        </div>

        <div class="card-body p-0">

            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12478.971269281275!2d43.27745319342489!3d38.56273886465415!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40127cc9e502490b%3A0xb407aecbf4d71ad8!2zUmVrdMO2cmzDvGs!5e0!3m2!1str!2str!4v1785153463200!5m2!1str!2str"
                width="100%"
                height="350"
                style="border:0;"
                loading="lazy"
                allowfullscreen>
            </iframe>
            

        </div>

    </div>

</div>
<!-- grafik bitiş  -->

<div class="card shadow border-0 mb-4">

    <div class="card-header bg-dark text-white">

        <i class="bi bi-clock-history"></i>

        Son 5 Servis Kaydı

    </div>

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead class="table-light">

                    <tr>

                        <th>ID</th>

                        <th>Müşteri</th>

                        <th>Cihaz</th>

                        <th>Durum</th>

                        <th>Ücret</th>

                    </tr>

                </thead>

                <tbody>

                <?php foreach($lastServices as $row){ ?>
                <!-- veritabaındaki her kayıt tek tek doalsıyor ve rowa kaydediliyor -->

                    <tr>

                        <td><?= $row["id"] ?></td>

                        <td>
                            <?= $row["name"] ?>
                            <?= $row["surname"] ?>
                        </td>

                        <td>
                            <?= $row["brand"] ?>
                            <?= $row["model"] ?>
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
                       <?= $row["price"]!=null
                        ? number_format($row["price"],2,",",".")." ₺"
                        : "-" ?>
                        </td>

                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div> <!-- container -->
</div> <!-- main-content -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script>

new Chart(document.getElementById('serviceChart'),{
// grafik olusturuluyor
type:'bar',

data:{

labels:[
'Bekliyor',
'İşlemde',
'Tamamlandı',
'Teslim'
],

datasets:[{

label:'Servis Sayısı',

data:[
<?= $waiting ?>,
<?= $working ?>,
<?= $finished ?>,
<?= $delivered ?>
],

backgroundColor:[
'#6c757d',
'#ffc107',
'#28a745',
'#0d6efd'
]

}]

},

options:{
responsive:true,
plugins:{
legend:{
display:false
}
}
}

});


</script>


</body>
</html>