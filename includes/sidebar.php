<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>

.sidebar{
    position: fixed;
    left: 0;
    top: 0;
    width: 250px;
    height: 100vh;
    background: #343a40;
    overflow-y: auto;
}

.sidebar h3{
    color: white;
    text-align: center;
    padding: 20px 0;
    border-bottom:1px solid rgba(255,255,255,.15);
}

.sidebar a{
    display:block;
    padding:15px 20px;
    color:white;
    text-decoration:none;
    transition:.3s;
}

.sidebar a:hover{
    background:#198754;
    padding-left:28px;
}

.sidebar i{
    margin-right:10px;
}

.main-content{
    margin-left:250px;
    padding:25px;
}
.sidebar a.active{
    background:#198754;
    color:#fff;
    padding-left:28px;
}

.sidebar a.active:hover{
    background:#198754;
}
</style>

<div class="sidebar">

    <h3>
        <i class="bi bi-tools"></i>
        Teknik Servis
    </h3>

   <a href="/STAJ_TEKNIK/dashboard.php"
   class="<?= ($page=="dashboard") ? "active" : "" ?>">
    <i class="bi bi-speedometer2"></i>
    Dashboard
</a>

<a href="/STAJ_TEKNIK/pages/customers.php"
   class="<?= ($page=="customers") ? "active" : "" ?>">
    <i class="bi bi-people"></i>
    Müşteriler
</a>

<a href="/STAJ_TEKNIK/pages/employees.php"
   class="<?= ($page=="employees") ? "active" : "" ?>">
    <i class="bi bi-person-badge"></i>
    Personeller
</a>

<a href="/STAJ_TEKNIK/pages/devices.php"
   class="<?= ($page=="devices") ? "active" : "" ?>">
    <i class="bi bi-laptop"></i>
    Cihazlar
</a>

<a href="/STAJ_TEKNIK/pages/services.php"
   class="<?= ($page=="services") ? "active" : "" ?>">
    <i class="bi bi-tools"></i>
    Servisler
</a>



<a href="/STAJ_TEKNIK/logout.php"
   onclick="return confirm('Çıkış yapmak istediğinize emin misiniz?');">
    <i class="bi bi-box-arrow-right"></i>
    Çıkış Yap
</a>

</div>