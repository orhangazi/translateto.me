<?php
/**
 * Created by PhpStorm.
 * User: Orhan Gazi
 * Date: 10.09.2016
 * Time: 20:24
 */
include "php/baglan.php";
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
    <div class="nav-wrapper container"><a id="logo-container" href="#" class="brand-logo">Logo</a>
        <ul class="right hide-on-med-and-down">
            <li><a href="#">Orhan Gazi</a></li>
        </ul>
    </div>
</nav>
<div class="section no-pad-bot" id="index-banner">
    <div class="container">
        <div class="row">
            <?
                $html="";
                $orijinal_metinler_sql = mysqli_query($baglan,"select *,uyeler.adi_soyadi,uyeler.profil_resmi,orijinal_metinler.id as orijinal_metin_id from orijinal_metinler,uyeler where uyeler.id=orijinal_metinler.metin_sahibi_id;");

                while($orijinal_metinler = mysqli_fetch_object($orijinal_metinler_sql)){
                    $orijinal_metin_id = $orijinal_metinler->orijinal_metin_id;
                    $orijinal_metin = $orijinal_metinler->orijinal_metin;
                    $baslik = $orijinal_metinler->baslik;
                    $metin_sahibi_id = $orijinal_metinler->metin_sahibi_id;
                    $metin_sahibi_notu = $orijinal_metinler->metin_sahibi_notu;
                    $guncellenme_tarihi = $orijinal_metinler->guncellenme_tarihi;
                    $adi_soyadi = $orijinal_metinler->adi_soyadi;
                    $orijinal_dil_id = $orijinal_metinler->orijinal_dil_id;
                    $cevrilecek_dil_id = $orijinal_metinler->cevrilecek_dil_id;
                    $profil_resmi = $orijinal_metinler->profil_resmi != ""?"$orijinal_metinler->profil_resmi":"resimler/kullanici_resmi_50x50.png";
                    $orijinal_metin = substr($orijinal_metin,0,150);

                    $html.="<div class='col s4 kart'>
                                <div class='card-panel hoverable teal lighten-5'>
                                    <h6><a href='php/islemler.php?orijinal_metin_id=$orijinal_metin_id' class='cevir' style='color: rgba(0, 0, 0, 0.87);'>$baslik</a></h6>
                                    <span class='kart-aciklama blue-text text-darken-2'>$orijinal_metin</span>
                                    <div class='kart-alt'>
                                        <div class='chip'>
                                            <img src='$profil_resmi' alt='Contact Person'>
                                            <a class='' href='javascript:void(0)'>$adi_soyadi</a>
                                        </div>
                                        <a href='php/islemler.php?orijinal_metin_id=$orijinal_metin_id' class='cevir waves-effect waves-light btn right'>Çevir</a>
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
            <a class="orange-text text-lighten-3" style="color: #000 !important;" href="http://materializecss.com">translateto.me</a> Gazi Yazılım ürünüdür.
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
</div>
<!--  Scripts-->
<script src="jquery3/jquery-3.1.0.min.js"></script>
<script src="js/islemler.js"></script>
<script src="materialize/js/materialize.js"></script>
<script src="materialize/js/init.js"></script>
<inpu type="hidden" value="1" id="uye_id"></inpu>
<!-- Modal Structure -->
<div id="yorumlar-modal" class="modal modal-fixed-footer">
    <div class="modal-content">
        <h5>Yorumlar</h5>
        <p class="yorumlar-modal-ic">A bunch of text</p>
    </div>
    <div class="modal-footer">
        <div class="input-field cevirmen-notu-input-field">
            <label for="cevirmen-yorumu" class="cevirmen-yorumu-label blue-text text-lighten-1">Yorumunu yaz</label>
            <textarea name="cevirmen-yorumu" class="materialize-textarea" id="cevirmen-yorumu"></textarea>
        </div>
    </div>
</div>
</body>
</html>