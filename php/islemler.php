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
    $metinler_sql = mysqli_query($baglan,"select * from orijinal_metinler,cevrilmis_metinler where orijinal_metinler.id=$orijinal_metin_id and orijinal_metinler.id=cevrilmis_metinler.orijinal_metin_id;");//çevirisinin olup olmadığına bakıyor

    //çevirisi varsa
    if (mysqli_num_rows($metinler_sql)>0){
        $metinler = mysqli_fetch_object($metinler_sql);
        $orijinal_metin = $metinler->orijinal_metin;
        $baslik = $metinler->baslik;
        $metin_sahibi_id = $metinler->metin_sahibi_id; //çevirten id
        $metin_sahibi_notu = $metinler->metin_sahibi_notu; //çevirten notu
        $orijinal_dil_id = $metinler->orijinal_dil_id; //metnin dili
        $cevrilecek_dil_id = $metinler->cevrilecek_dil_id; //metnin dili
        $orijinal_metin_kayit_tarihi = $metinler->kayit_tarihi;//orijinal metnin kayıt tarihi
        $orijinal_metin_guncelleme_tarihi = $metinler->orijinal_metinler->guncelleme_tarihi;//orijinal metni güncelleme tarihi
        $cevrilmis_metin = $metinler->cevrilmis_metin;
        $cevirmen_notu = $metinler->cevirmen_notu;
        $cevrilmis_metin_guncelleme_tarihi = $metinler->cevrilmis_metinler->guncelleme_tarihi;
        $ceviriye_baslama_tarihi = $metinler->baslama_tarihi;

        $icerik = ["orijinal_metin"=>$orijinal_metin,
        "baslik"=>$baslik,
        "metin_sahibi_id"=>$metin_sahibi_id,
        "metin_sahibi_notu"=>$metin_sahibi_notu,
        "orijinal_dil_id"=>$orijinal_dil_id,
        "cevrilecek_dil_id"=>$cevrilecek_dil_id,
        "orijinal_metin_kayit_tarihi"=>$orijinal_metin_kayit_tarihi,
        "orijinal_metin_guncelleme_tarihi"=>$orijinal_metin_guncelleme_tarihi,
        "cevrilmis_metin"=>$cevrilmis_metin,
        "orijinal_metin_id"=>$orijinal_metin_id,
        "cevrilmis_metin_guncelleme_tarihi"=>$cevrilmis_metin_guncelleme_tarihi,
        "ceviriye_baslama_tarihi"=>$ceviriye_baslama_tarihi,
        "cevirisi_var_mi" => true];

        $json = json_encode($icerik);
        echo $json;
    }
    else{
        $orijinal_metin_sql = mysqli_query($baglan,"select * from orijinal_metinler where id=$orijinal_metin_id");
        $orijinal_metin_nesne = mysqli_fetch_object($orijinal_metin_sql);
        $orijinal_metin_id = $orijinal_metin_nesne->id;
        $orijinal_metin = $orijinal_metin_nesne->orijinal_metin;
        $baslik = $orijinal_metin_nesne->baslik;
        $metin_sahibi_id = $orijinal_metin_nesne->metin_sahibi_id;
        $metin_sahibi_notu = $orijinal_metin_nesne->metin_sahibi_notu;
        $orijinal_dil_id = $orijinal_metin_nesne->orijinal_dil_id;
        $cevrilecek_dil_id = $orijinal_metin_nesne->cevrilecek_dil_id;
        $kayit_tarihi = $orijinal_metin_nesne->kayit_tarihi;
        $guncelleme_tarihi = $orijinal_metin_nesne->guncelleme_tarihi;

        $icerik = ["baslik"=>$baslik,
                        "orijinal_metin"=>$orijinal_metin,
                        "orijinal_dil_id"=>$orijinal_dil_id,
                        "cevrilecek_dil_id"=>$cevrilecek_dil_id,
                        "kayit_tarihi"=>$kayit_tarihi,
                        "orijinal_metin_id"=>$orijinal_metin_id,
                        "guncelleme_tarihi"=>$guncelleme_tarihi,
                        "metin_sahibi_id"=>$metin_sahibi_id,
                        "metin_sahibi_notu"=>$metin_sahibi_notu,
                        "cevirisi_var_mi" => true];

        $json = json_encode($icerik);
        echo $json;
    }
}

$kaydedilsin_mi = $_POST['kaydedilsin_mi'];
if ($kaydedilsin_mi){
    $orijinal_metin_id = $_POST['orijinal_metin_id'];
    $cevrilecek_dil_id = $_POST['cevrilecek_dil_id'];
    $cevirmen_notu = mysqli_real_escape_string($baglan,$_POST['cevirmen_notu']);
    $cevrilmis_metin = mysqli_real_escape_string($baglan,$_POST['cevrilmis_metin']);
    $uye_id = $_POST['uye_id'];

    $kayitli_mi_sql = mysqli_query($baglan,"select id from cevrilmis_metinler where orijinal_metin_id=$orijinal_metin_id");
    if (mysqli_num_rows($kayitli_mi_sql)>0){
        $guncelle_sql = mysqli_query($baglan,"update cevrilmis_metinler set cevrilmis_metin='$cevrilmis_metin' where orijinal_metin_id=$orijinal_metin_id");
        $hata = ($guncelle_sql)?"false":"true";
        $mesaj = ($guncelle_sql)?"Çeviri başarıyla güncellendi":"Çeviri güncellenemedi. Tekrar deneyin";
    }
    else{
        $ceviriyi_kaydet_sql = mysqli_query($baglan,"insert into cevrilmis_metinler(orijinal_metin_id,cevrilmis_metin,dil_id) values('$orijinal_metin_id','$cevrilmis_metin','$cevrilecek_dil_id')");
        $hata = ($ceviriyi_kaydet_sql)?"false":"true";
        $mesaj = ($ceviriyi_kaydet_sql)?"Çeviri başarıyla kaydedildi":"Çeviri kaydedilemedi. Tekrar deneyin";
    }

    echo json_encode(["hata"=>$hata,"mesaj"=>$mesaj]);
}


$yorumlari_goster = $_POST['yorumlari_goster'];
if ($yorumlari_goster){
    $orijinal_metin_id = $_POST['orijinal_metin_id'];
    $orijinal_metin_id = mysqli_real_escape_string($baglan,$orijinal_metin_id);

    $kayitli_mi_sql = mysqli_query($baglan,"select * from yorumlar where orijinal_metin_id=$orijinal_metin_id");
    if (mysqli_num_rows($kayitli_mi_sql)>0){
        $yorumlari_getir_sql = mysqli_query($baglan,"select * from yorumlar,uyeler where orijinal_metin_id=$orijinal_metin_id and yorumlar.cevirmen_id = uyeler.id");
        $hata = ($yorumlari_getir_sql)?"false":"true";

        $yorumlar_html = "";
        while ($yorumlar=mysqli_fetch_object($yorumlari_getir_sql)){
            $yorum = $yorumlar->yorum;
            $adi_soyadi = $yorumlar->adi_soyadi;
            $profil_resmi = $yorumlar->profil_resmi!=""?"$yorumlar->profil_resmi":"resimler/kullanici_resmi_50x50.png";

            $yorumlar_html .= "<ul class='collection'>
                            <li class='collection-item avatar'>
                              <img src='$profil_resmi' alt='' class='circle'>
                              <span class='title'><strong>$adi_soyadi</strong></span>
                              <p>$yorum</p>
                            </li>
                        </ul>";
        }

        $mesaj = ["mesaj"=>$yorumlar_html];
    }
    else{
        $mesaj = ["yorum_yok"=>true,"mesaj"=>"Hiç yorum yok"];
    }

    echo json_encode($mesaj);
}
?>