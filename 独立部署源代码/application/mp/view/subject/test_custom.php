<?php
use app\Defs;
?>
<?php
include APP_PATH . "mp" . DS . "view" . DS . "common" . DS . "header.php";
include APP_PATH . "mp/view/common/script.php";
?>
<ion-app>
<ion-header>
    <ion-toolbar color="bg">
        <ion-buttons slot="start" id="nav-top-buttons">
            <ion-button size="small" href="javascript:window.history.back();"><ion-icon slot="start" name="chevron-back-outline"></ion-icon>后退</ion-button>
        </ion-buttons>
        <ion-title color="action">量表测评答题</ion-title>
        <ion-buttons slot="end">
            <ion-button size="small" href="<?=$_home_url?>"><ion-icon slot="start" name="home-outline"></ion-icon>首页</ion-button>
        </ion-buttons>
    </ion-toolbar>
</ion-header>
<?=$testBody?>
</ion-app>
<?php
include APP_PATH . "mp/view/common/footer.php";
?>