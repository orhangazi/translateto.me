<?php
/**
 * Created by PhpStorm.
 * User: Orhan Gazi
 * Date: 10.09.2016
 * Time: 20:24
 */
include "php/baglan.php";

$cikis = $_GET['cikis'];
if(isset($cikis)){
    session_destroy();
	header("Location:index.php");
}

$eposta = $_SESSION["eposta"];
if($eposta!=""){
    $kullanici_bilgileri_sql = mysqli_query($baglan,"select * from uyeler where eposta='$eposta' limit 1");
    $kullanici_bilgileri = mysqli_fetch_object($kullanici_bilgileri_sql);

    $uye_id = $kullanici_bilgileri->id;
    $adi_soyadi = $kullanici_bilgileri->adi_soyadi;
    $profil_resmi = $kullanici_bilgileri->profil_resmi;
    $profil_resmi = $profil_resmi!=""?"$profil_resmi":"resimler/kullanici_resmi_50x50.png";
	$giris_yapilmis_mi = true;

	//yeni metin ekleme katmanında select option nesnesi olarak kullanmak için
	$diller_select= "";
	$diller_sql = mysqli_query($baglan,"select * from diller");
	while($dil_nesne = mysqli_fetch_object($diller_sql)){
		$dil_id = $dil_nesne->id;
		$dil = $dil_nesne->dil;

		$diller_select .= "<option value='$dil_id'>$dil</option>";
	}
}
else{
	$giris_yapilmis_mi = false;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
    <title>translate to me</title>

    <!-- CSS  -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="materialize/css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>
    <link href="materialize/css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>
</head>
<body>
<nav class="light-blue lighten-1" role="navigation">
    <div class="nav-wrapper container"><a id="logo-container" href="" class="brand-logo">Logo</a>
        <ul class="right hide-on-med-and-down">
			<li><a class="gorunumu-degistir tooltipped" data-tooltip="3lü sütun görünümü"><i class="small material-icons">view_week</i></a></li>
            <li>
				<?= $giris_yapilmis_mi == true?"<a>$adi_soyadi</a>":"<a class='giris-kayit-divini-ac'>Giriş Yap ya da Kayıt Ol</a>"; ?>
			</li>
			<li>
				<a class='dropdown-button' data-constrainwidth="false" data-beloworigin="true" data-alignment="right" data-activates="menu" href="#" class="menu"><i class="dropdown-button material-icons small">reorder</i></a>
				<!-- Dropdown Structure -->
				<ul id='menu' class='dropdown-content'>
					<li><a href="php/islemler.php?cevirdigim_metinler=true" class="cevirdigim-metinler">Çevirdiklerim</a></li>
					<li><a href="php/islemler.php?cevirttigim_metinler=true" class="cevirttigim-metinler">Çevirttiklerim</a></li>
					<li><a href="php/ayarlar.php" class="ayarlar">Ayarlar</a></li>
					<li><a href="index.php?cikis=true">Çıkış</a></li>
				</ul>
			</li>
        </ul>
    </div>
</nav>
<div class="section no-pad-bot" id="index-banner">
    <div class="container orta">
        <div class="row">
            <?
                $html="";
                $orijinal_metinler_sql = mysqli_query($baglan,"select *,uyeler.adi_soyadi,uyeler.profil_resmi,orijinal_metinler.id as orijinal_metin_id from orijinal_metinler,uyeler where uyeler.id=orijinal_metinler.metin_sahibi_id order by orijinal_metinler.id desc;");

                while($orijinal_metinler = mysqli_fetch_object($orijinal_metinler_sql)){
                    $orijinal_metin_id = $orijinal_metinler->orijinal_metin_id;
                    $orijinal_metin = $orijinal_metinler->orijinal_metin;
					$orijinal_kelime_sayisi = count(explode(' ',$orijinal_metin));
                    $baslik = $orijinal_metinler->baslik;
                    $metin_sahibi_id = $orijinal_metinler->metin_sahibi_id;
                    $metin_sahibi_notu = $orijinal_metinler->metin_sahibi_notu;
                    $guncellenme_tarihi = $orijinal_metinler->guncellenme_tarihi;
                    $adi_soyadi_kart = $orijinal_metinler->adi_soyadi;
                    $orijinal_dil_id = $orijinal_metinler->orijinal_dil_id;
                    $cevrilecek_dil_id = $orijinal_metinler->cevrilecek_dil_id;
                    $profil_resmi = $orijinal_metinler->profil_resmi != ""?"$orijinal_metinler->profil_resmi":"resimler/kullanici_resmi_50x50.png";
                    $orijinal_metin = substr($orijinal_metin,0,150);

                    $html.="<div class='col s4 kart' style='width:483px'>
                                <div class='card-panel hoverable teal lighten-5'>
                                	<!--<div class='kart-ust'></div>-->
                                    <h6><a href='php/islemler.php?orijinal_metin_id=$orijinal_metin_id' class='cevir' style='color: rgba(0, 0, 0, 0.87);'>$baslik</a></h6>
                                    <span class='kart-aciklama blue-text text-darken-2'>$orijinal_metin</span>
                                    <div class='kart-alt'>
                                        <div class='chip'>
                                            <img src='$profil_resmi' alt='Contact Person'>
                                            <a class='' href='javascript:void(0)'>$adi_soyadi_kart</a>
                                        </div>
                                        <div style='position: absolute; right: 0px; bottom: 0px;'>
                                        <a href='php/islemler.php?orijinal_metin_id=$orijinal_metin_id' class='tumunu-goster waves-effect waves-light btn'>Göster</a>
                                        <a href='php/islemler.php?orijinal_metin_id=$orijinal_metin_id&orijinal_kelime_sayisi=$orijinal_kelime_sayisi' class='cevir waves-effect waves-light btn'>Çevir</a>
										</div>
                                        
                                    </div>
                                </div>
                            </div>";
                }
                echo $html;
            ?>
        </div>
    </div>
</div>

<footer class="page-footer orange">
    <div class="container">
        <div class="row">
            <div class="col l6 s12">
                <h5 class="white-text">Company Bio</h5>
                <p class="grey-text text-lighten-4">We are a team of college students working on this project like it's our full time job. Any amount would help support and continue development on this project and is greatly appreciated.</p>
            </div>
            <div class="col l3 s12">
                <h5 class="white-text">Settings</h5>
                <ul>
                    <li><a class="white-text" href="#!">Link 1</a></li>
                    <li><a class="white-text" href="#!">Link 2</a></li>
                    <li><a class="white-text" href="#!">Link 3</a></li>
                    <li><a class="white-text" href="#!">Link 4</a></li>
                </ul>
            </div>
            <div class="col l3 s12">
                <h5 class="white-text">Connect</h5>
                <ul>
                    <li><a class="white-text" href="#!">Link 1</a></li>
                    <li><a class="white-text" href="#!">Link 2</a></li>
                    <li><a class="white-text" href="#!">Link 3</a></li>
                    <li><a class="white-text" href="#!">Link 4</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="footer-copyright">
        <div class="container" style="font-weight: bold; color: #000;">
            <a class="orange-text text-lighten-3" style="color: #000 !important;" href="index.php">translateto.me</a> Gazi Yazılım ürünüdür.
        </div>
    </div>
</footer>
<div class="ceviri-kapsayici row">
    <div class="orijinal-metin-divi col s6">
        <h5 class="original-metin-baslik">Deneme başlık</h5>
        <div class="card-panel">
            <h6>Metin sahibinin çevirmene notu:</h6>
            <span class="blue-text text-darken-2 orijinal-metin-uye-notu"></span>
        </div>
        <div class="orijinal-metin">deneme orijinal metin</div>
    </div>
    <div class="cevrilen-metin-divi col s6">
        <h5 class="cevrilen-metin-baslik">Deneme başlık</h5>
        <div class="input-field cevrilen-metin-input">
            <label for="cevrilen-metin" class="blue-text text-lighten-1">Çevirini buraya yaz</label>
            <textarea name="cevrilen-metin" class="materialize-textarea cevrilen-metin" id="cevrilen-metin"></textarea>
        </div>
        <a class="btn-floating btn-large waves-effect waves-light green kaydet tooltipped" data-position="top" data-delay="50" data-tooltip="Ctrl+S ile de kaydedebilirsin"><i class="material-icons">save</i></a>
    </div>
    <div class="ceviri-kapsayici-kapat tooltipped" data-position="left" data-delay="50" data-tooltip="Esc'ye basarak da kapatabilirsin">✖</div>
    <i class="material-icons yorumlari-gor tooltipped" data-position="left" data-delay="50" data-tooltip="Yorumları gör">comment</i>
    <input type="hidden" id="orijinal-metin-id">
    <input type="hidden" id="cevrilecek-dil-id">
    <input type="hidden" id="orijinal-kelime-sayisi">
</div>
<div class="cevrilecek-metin-ekleme-divi row">
	<form id="cevrilecek-metin-formu">
		<div class="cevrilecek-metin-divi col s7">
			<h5>Çevrilecek metin</h5>
			<div class="input-field col s12">
				<input id="baslik" type="text" name="metin-basligi" class="validate">
				<label for="baslik" class="blue-text">Metin başlığı</label>
			</div>
			<div class="input-field col s12">
				<textarea id="cevrilen-metin-notu" name="cevrilen-metin-notu" class="materialize-textarea"></textarea>
				<label for="cevrilen-metin-notu" class="blue-text">Çevirmenlere iletmek istediğin notun</label>
			</div>
			<div class="input-field col s12">
				<textarea id="cevrilen-metin" name="cevrilen-metin" class="materialize-textarea"></textarea>
				<label for="cevrilen-metin" class="blue-text">Çevrilmesini istediğin metin</label>
			</div>
            <div class="input-field col s3">
                <select name="orijinal-dil">
                    <option value="" disabled selected>Metnin orijinal dili</option>
                    <?= $diller_select ?>
                </select>
                <label class="blue-text">Metnin orijinal dilini seçin</label>
            </div>
            <div class="input-field col s3">
                <select name="cevrilecek-dil">
                    <option value="" disabled selected>Metnin çevrileceği dil</option>
                    <?= $diller_select ?>
                </select>
                <label class="blue-text">Metin çevrileceği dili seçin</label>
            </div>
		</div>
		<input type="hidden" name="cevrilecek-metni-kaydet" value="true">
	<button class="btn-floating btn-large waves-effect waves-light green cevrilecek-metni-kaydet tooltipped" data-position="top" data-delay="50" data-tooltip="Ctrl+S ile de kaydedebilirsin"><i class="material-icons">save</i></button>
	</form>
	<div class="ceviri-kapsayici-kapat tooltipped" data-position="left" data-delay="50" data-tooltip="Esc'ye basarak da kapatabilirsin">✖</div>
	<i class="material-icons yorumlari-gor tooltipped" data-position="left" data-delay="50" data-tooltip="Yorumları gör">comment</i>
	<input type="hidden" id="orijinal-metin-id">
	<input type="hidden" id="cevrilecek-dil-id">
</div>
<div class="giris-kayit-divi row">
    <div class="giris-kayit-divi-form col s3">
        <div class="giris-kayit-divi-kapat">✖</div>
        <div class="row">
            <div class="col s12">
                <ul class="tabs">
                    <li class="tab col s3"><a href="#kayit-ol">Kayıt Ol</a></li>
                    <li class="tab col s3"><a href="#giris-yap">Giriş Yap</a></li>
                </ul>
            </div>
            <div id="kayit-ol" class="col s12">
                <form id="kaydolma-formu">
                    <div class="input-field col s12">
                        <input id="adi-soyadi-form" type="text" name="adi-soyadi" class="validate">
                        <label for="adi-soyadi-form" class="blue-text">Adın ve soyadın</label>
                    </div>
                    <div class="input-field col s12">
                        <input id="eposta" type="email" name="eposta" class="validate">
                        <label for="eposta" class="blue-text">E-postan</label>
                    </div>
                    <div class="input-field col s12">
                        <input id="sifre" type="password" name="sifre" class="validate">
                        <label for="sifre" class="blue-text">Şifren</label>
                    </div>
                    <button class="waves-effect waves-light btn">Kaydol</button>
                    <div class="kayit-durumu"></div>
                    <input type="hidden" name="kayit-yap" value="true">
                </form>
            </div>
            <div id="giris-yap" class="col s12">
                <form id="giris-formu">
                    <div class="input-field col s12">
                        <input id="eposta" type="email" name="eposta" class="validate">
                        <label for="eposta" class="blue-text">E-postan</label>
                    </div>
                    <div class="input-field col s12">
                        <input id="sifre" type="password" name="sifre" class="validate">
                        <label for="sifre" class="blue-text">Şifren</label>
                    </div>
                    <button class="waves-effect waves-light btn" role="button">Giriş Yap</button>
                    <span class="giris-durumu"></span>
                    <input type="hidden" name="giris-yap" value="true">
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal Structure -->
<div id="yorumlar-modal" class="modal modal-fixed-footer">
    <div class="modal-content">
        <h5>Yorumlar</h5>
        <p class="yorumlar-modal-ic">A bunch of text</p>
    </div>
    <div class="modal-footer">
        <div class="input-field yorum-input-field">
            <label for="yorum" class="yorum-label blue-text text-lighten-1">Yorumunu yaz</label>
            <textarea name="cevirmen-yorumu" class="materialize-textarea" id="yorum"></textarea>
        </div>
    </div>
</div>
<!-- Modal Structure -->
<div id="metin-modal" class="modal modal-fixed-footer">
    <div class="modal-content">
        <h5 class="metin-modal-baslik">Metin Başlığı</h5>
        <p class="metin-modal-ic">Orijinal metin içeriği</p>
    </div>
    <div class="modal-footer">
        <div class="input-field yorum-input-field">
			<a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Kapat</a>
        </div>
    </div>
</div>
<a class="btn-floating btn-large waves-effect waves-light red tooltipped yeni-cevrilecek-metin-ekle" data-position="top" data-delay="50" data-tooltip="Çevirisini yaptırmak için bir metin ekle"><i class="material-icons">add</i></a>
<input type="hidden" value="<? echo $uye_id ?>" id="uye-id">
<input type="hidden" value="<? echo $adi_soyadi ?>" id="adi-soyadi">
<input type="hidden" value="<? echo $profil_resmi ?>" id="profil-resmi">
<!--  Scripts-->
<script src="jquery3/jquery-3.1.0.min.js"></script>
<script src="js/islemler.js"></script>
<script src="materialize/js/materialize.js"></script>
<script src="materialize/js/init.js"></script>
</body>
</html>