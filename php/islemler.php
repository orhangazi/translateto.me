<?php
/**
 * Created by PhpStorm.
 * User: Orhan Gazi
 * Date: 11.09.2016
 * Time: 16:39
 */
include "baglan.php";
$orijinal_metin_id = $_GET["orijinal_metin_id"];
$orijinal_metin_id = mysqli_real_escape_string($baglan,$orijinal_metin_id);
if (!empty(is_numeric($orijinal_metin_id))){
    $metinler_sql = mysqli_query($baglan,"select * from orijinal_metinler,cevrilmis_metinler where orijinal_metinler.id=$orijinal_metin_id");

    if (mysqli_num_rows($metinler_sql)>0){
        $metinler = mysqli_fetch_object($metinler_sql);
        $orijinal_metin = $metinler->orijinal_metin;
        $uye_id = $metinler->uye_id; //çevirten id
        $uye_notu = $metinler->uye_notu; //çevirten notu
        $orijinal_dil_id = $metinler->orijinal_dil_id; //metnin dili
        $cevrilecek_dil_id = $metinler->cevrilecek_dil_id; //metnin dili
        $kayit_tarihi = $metinler->kayit_tarihi;//orijinal metnin kayıt tarihi
        $orijinal_metin_guncelleme_tarihi = $metinler->orijinal_metinler->guncelleme_tarihi;//orijinal metni güncelleme tarihi
        $cevrilmis_metin = $metinler->cevrilmis_metin;
        $cevirmen_notu = $metinler->cevirmen_notu;
        $cevrilmis_metin_guncelleme_tarihi = $metinler->cevrilmis_metinler->guncelleme_tarihi;
        $ceviriye_baslama_tarihi = $metinler->baslama_tarihi;

        $icerik = var_dump($metinler_sql);
        $liste = ["a"=>$icerik];
        $json = json_encode($liste);
        echo $json;
    }
    else{
        $orijinal_metin_sql = mysqli_query($baglan,"select * from orijinal_metinler where id=$orijinal_metin_id");
        $orijinal_metin_nesne = mysqli_fetch_object($orijinal_metin_sql);
        $orijinal_metin_id = $orijinal_metin_nesne->id;
        $orijinal_metin = $orijinal_metin_nesne->orijinal_metin;
        $baslik = $orijinal_metin_nesne->baslik;
        $uye_notu = $orijinal_metin_nesne->uye_notu;
        $orijinal_dil_id = $orijinal_metin_nesne->orijinal_dil_id;
        $cevrilecek_dil_id = $orijinal_metin_nesne->cevrilecek_dil_id;
        $kayit_tarihi = $orijinal_metin_nesne->kayit_tarihi;
        $guncelleme_tarihi = $orijinal_metin_nesne->guncelleme_tarihi;

        $json_dizi = ["baslik"=>$baslik,
                        "orijinal_metin"=>$orijinal_metin,
                        "uye_notu"=>$uye_notu,
                        "orijinal_dil_id"=>$orijinal_dil_id,
                        "cevrilecek_dil_id"=>$cevrilecek_dil_id,
                        "kayit_tarihi"=>$kayit_tarihi,
                        "orijinal_metin_id"=>$orijinal_metin_id,
                        "guncelleme_tarihi"=>$guncelleme_tarihi];
        $json = json_encode($json_dizi);
        echo $json;
    }
}

$kaydedilsin_mi = $_POST['kaydedilsin_mi'];
if ($kaydedilsin_mi){
    $orijinal_metin_id = $_POST['orijinal_metin_id'];
    $cevrilecek_dil_id = $_POST['cevrilecek_dil_id'];
    $cevirmen_notu = mysqli_real_escape_string($baglan,nl2br($_POST['cevirmen_notu']));
    $cevrilmis_metin = mysqli_real_escape_string($baglan,nl2br($_POST['cevrilmis_metin']));
    $uye_id = $_POST['uye_id'];

    $kayitli_mi_sql = mysqli_query($baglan,"select id from cevrilmis_metinler where orijinal_metin_id=$orijinal_metin_id");
    if (mysqli_num_rows($kayitli_mi_sql)>0){
        $guncelle_sql = mysqli_query($baglan,"update cevrilmis_metinler set cevrilmis_metin='$cevrilmis_metin'");
        $hata = ($guncelle_sql)?"false":"true";
        $mesaj = ($guncelle_sql)?"Çeviri başarıyla güncellendi":":Çeviri güncellenemedi. Tekrar deneyin";
    }
    else{
        $ceviriyi_kaydet_sql = mysqli_query($baglan,"insert into cevrilmis_metinler(orijinal_metin_id,cevrilmis_metin,cevirmen_notu,dil_id) values('$orijinal_metin_id','$cevrilmis_metin','$cevirmen_notu','$cevrilecek_dil_id')");
        $hata = ($ceviriyi_kaydet_sql)?"false":"true";
        $mesaj = ($ceviriyi_kaydet_sql)?"Çeviri başarıyla kaydedildi":":Çeviri kaydedilemedi. Tekrar deneyin";
    }

    echo json_encode(["hata"=>$hata,"mesaj"=>$mesaj]);
}
?>