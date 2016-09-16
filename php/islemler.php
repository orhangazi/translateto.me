<?php
/**
 * Created by PhpStorm.
 * User: Orhan Gazi
 * Date: 11.09.2016
 * Time: 16:39
 */
include "baglan.php";

//çeviri yapılması için açılan div e verileri gönderir
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

//çeviriyi kaydeder
$ceviri_kaydedilsin_mi = $_POST['ceviri_kaydedilsin_mi'];
if ($ceviri_kaydedilsin_mi){
    $orijinal_metin_id = $_POST['orijinal_metin_id'];
    $cevrilecek_dil_id = $_POST['cevrilecek_dil_id'];
    $cevirmen_notu = mysqli_real_escape_string($baglan,$_POST['cevirmen_notu']);
    $cevrilmis_metin = mysqli_real_escape_string($baglan,$_POST['cevrilmis_metin']);
    $uye_id = $_POST['uye_id'];

    $kayitli_mi_sql = mysqli_query($baglan,"select id from cevrilmis_metinler where orijinal_metin_id=$orijinal_metin_id");
    if (mysqli_num_rows($kayitli_mi_sql)>0){
        $guncelle_sql = mysqli_query($baglan,"update cevrilmis_metinler set cevrilmis_metin='$cevrilmis_metin' where orijinal_metin_id=$orijinal_metin_id");
        $hata = ($guncelle_sql)?false:true;
        $mesaj = ($guncelle_sql)?"Çeviri başarıyla güncellendi":"Çeviri güncellenemedi. Tekrar deneyin";
    }
    else{
        $ceviriyi_kaydet_sql = mysqli_query($baglan,"insert into cevrilmis_metinler(orijinal_metin_id,cevrilmis_metin,dil_id) values('$orijinal_metin_id','$cevrilmis_metin','$cevrilecek_dil_id')");
        $hata = ($ceviriyi_kaydet_sql)?false:true;
        $mesaj = ($ceviriyi_kaydet_sql)?"Çeviri başarıyla kaydedildi":"Çeviri kaydedilemedi. Tekrar deneyin";
    }

    echo json_encode(["hata"=>$hata,"mesaj"=>$mesaj]);
}

//yorumları gösterir
$yorumlari_goster = $_POST['yorumlari_goster'];
if ($yorumlari_goster){
    $orijinal_metin_id = $_POST['orijinal_metin_id'];
    $orijinal_metin_id = mysqli_real_escape_string($baglan,$orijinal_metin_id);

    //$kayitli_mi_sql = mysqli_query($baglan,"select * from yorumlar where orijinal_metin_id=$orijinal_metin_id");
    $yorumlari_getir_sql = mysqli_query($baglan,"select * from yorumlar,uyeler where orijinal_metin_id=$orijinal_metin_id and yorumlar.yorum_sahibi_id = uyeler.id order by yorumlar.id asc");
    if (mysqli_num_rows($yorumlari_getir_sql)>0){
        $hata = ($yorumlari_getir_sql)?false:true;
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


//yorum kaydı yapar
$yorum_kaydedilsin_mi = $_POST['yorum_kaydedilsin_mi'];
if ($yorum_kaydedilsin_mi){
    $orijinal_metin_id = $_POST['orijinal_metin_id'];
    $yorum = mysqli_real_escape_string($baglan,trim($_POST['yorum']));
    $yorum_sahibi_id = $_POST['uye_id'];
    if(!empty($yorum)){
        $yorumu_kaydet_sql = mysqli_query($baglan,"insert into yorumlar(yorum_sahibi_id,yorum,orijinal_metin_id) values('$yorum_sahibi_id','$yorum','$orijinal_metin_id')");
        $hata = ($yorumu_kaydet_sql)?false:true;
        $mesaj = ($yorumu_kaydet_sql)?"Yorum başarıyla başarıyla kaydedildi":"Çeviri yorum kaydedilemedi. Tekrar deneyin";
    }
    else{
        $hata = true;
        $mesaj = "Boş yorum kaydedilemez";
    }

    echo json_encode(["hata"=>$hata,"mesaj"=>$mesaj]);
}

//üye kaydı yapar
$uye_kaydi_yapilsin_mi = $_GET['kayit-yap'];
if ($uye_kaydi_yapilsin_mi){
    $adi_soyadi = trim($_GET['adi-soyadi']);
    $eposta = trim($_GET['eposta']);
    $sifre = $_GET['sifre'];

    $options = [
        'cost' => 12
    ];
    $sifre = password_hash($sifre, PASSWORD_BCRYPT, $options);

    if(!empty($adi_soyadi) && !empty($eposta) && !empty($sifre)){
        $uyeyi_kaydet_sql = mysqli_query($baglan,"insert into uyeler(eposta,adi_soyadi,sifre) values('$eposta','$adi_soyadi','$sifre')");
        $hata = ($uyeyi_kaydet_sql)?false:true;
        if ($uyeyi_kaydet_sql) {
            $_SESSION["eposta"] = $eposta;
            $mesaj = "<script>location.reload();</script><strong>Hoşgeldiniz.</strong>";
        } else {
            $mesaj = "Kaydınız bir nedenden dolayı olmadı. Tekrar deneyiniz.";
        }
    }
    else{
        $hata = true;
        $mesaj = "3 alanı da doldurmalısınız";
    }

    //$mesaj = "$adi_soyadi - $eposta - $sifre <br> ".!empty($adi_soyadi).!empty($eposta)."";
    echo json_encode(["hata"=>$hata,"mesaj"=>$mesaj]);
}

//giriş yaptırır
$giris_yapilsin_mi = $_GET['giris-yap'];
if ($giris_yapilsin_mi){
    $eposta = trim($_GET['eposta']);
    $sifre = $_GET['sifre'];

    if(!empty($eposta) && !empty($sifre)){
        $vt_sifre_sql = mysqli_query($baglan,"select eposta,sifre from uyeler where eposta='$eposta' limit 1");
        $hata = ($vt_sifre_sql)?false:true;

        if (mysqli_num_rows($vt_sifre_sql)>0) {
            $vt_sifre_nesne = mysqli_fetch_object($vt_sifre_sql);
            $vt_sifre = $vt_sifre_nesne->sifre;
            $sifre_uyusuyor_mu = password_verify($sifre,$vt_sifre);
            if($sifre_uyusuyor_mu){
                $_SESSION["eposta"] = $eposta;
                $mesaj = "<script>location.reload();</script><strong>Hoşgeldiniz.</strong>";
            }
            else{
                $mesaj = "<strong>Epostanız ya da şifreniz uyuşmuyor. Gözden geçirip tekrar giriş yapın</strong>";
            }
        } else {
            $mesaj = "Bu epostayı kullanan bir kullanıcı yok. Tekrar kontrol edin veya bu epostayla kaydolun.";
        }
    }
    else{
        $hata = true;
        $mesaj = "2 alanı da doldurmalısınız";
    }

    echo json_encode(["hata"=>$hata,"mesaj"=>$mesaj]);
}
?>