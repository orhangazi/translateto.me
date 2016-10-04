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
		$orijinal_kelime_sayisi = count(explode(' ',$orijinal_metin));
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
        "orijinal_kelime_sayisi"=>$orijinal_kelime_sayisi,
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
		$orijinal_kelime_sayisi = count(explode(' ',$orijinal_metin));
        $baslik = $orijinal_metin_nesne->baslik;
        $metin_sahibi_id = $orijinal_metin_nesne->metin_sahibi_id;
        $metin_sahibi_notu = $orijinal_metin_nesne->metin_sahibi_notu;
        $orijinal_dil_id = $orijinal_metin_nesne->orijinal_dil_id;
        $cevrilecek_dil_id = $orijinal_metin_nesne->cevrilecek_dil_id;
        $kayit_tarihi = $orijinal_metin_nesne->kayit_tarihi;
        $guncelleme_tarihi = $orijinal_metin_nesne->guncelleme_tarihi;

        $icerik = ["baslik"=>$baslik,
					"orijinal_kelime_sayisi"=>$orijinal_kelime_sayisi,
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
	$uye_id = $_SESSION['id'];
    $orijinal_metin_id = $_POST['orijinal_metin_id'];
    $cevrilecek_dil_id = $_POST['cevrilecek_dil_id'];
    $cevirmen_notu = mysqli_real_escape_string($baglan,$_POST['cevirmen_notu']);
    $cevrilmis_metin = mysqli_real_escape_string($baglan,$_POST['cevrilmis_metin']);

	$orijinal_kelime_sayisi = $_POST['orijinal_kelime_sayisi'];
	$cevrilmis_kelime_sayisi = count(explode(' ',$cevrilmis_metin));
	//yüzdeye göre

	//var_dump($orijinal_kelime_sayisi);
	$ceviri_miktari = ($cevrilmis_kelime_sayisi*100)/$orijinal_kelime_sayisi;

    $kayitli_mi_sql = mysqli_query($baglan,"select id from cevrilmis_metinler where orijinal_metin_id=$orijinal_metin_id");
    if (mysqli_num_rows($kayitli_mi_sql)>0){
    	$cevrilmis_metin_id_nesne = mysqli_fetch_object($kayitli_mi_sql);
		$cevrilmis_metin_id = $cevrilmis_metin_id_nesne->id;
        $guncelle_sql = mysqli_query($baglan,"update cevrilmis_metinler set cevrilmis_metin='$cevrilmis_metin' where orijinal_metin_id=$orijinal_metin_id");
		$hata = ($guncelle_sql)?false:true;
		$ayni_kisi_mi_sql = mysqli_query($baglan,"select id from ceviriler where ceviren_id=$uye_id and cevrilmis_metin_id=$cevrilmis_metin_id");
		if(mysqli_num_rows($ayni_kisi_mi_sql)>0){
			$ceviriler_vt_guncelle = mysqli_query($baglan,"update ceviriler set ceviri_miktari=$ceviri_miktari where ceviren_id=$uye_id and cevrilmis_metin_id=$cevrilmis_metin_id");
			$mesaj = ($guncelle_sql)?"Çeviri başarıyla güncellendi":"Çeviri güncellenemedi. Tekrar deneyin";
		}
		else{
			$ceviriler_vt_ekle = mysqli_query($baglan,"insert into ceviriler(ceviren_id, cevrilmis_metin_id, ceviri_miktari) values('$uye_id','$cevrilmis_metin_id','$ceviri_miktari')");
			$mesaj = ($guncelle_sql)?"Çeviri başarıyla güncellendi":"Çeviri güncellenemedi. Tekrar deneyin";
		}
    }
    else{
        $ceviriyi_kaydet_sql = mysqli_query($baglan,"insert into cevrilmis_metinler(orijinal_metin_id,cevrilmis_metin,dil_id) values('$orijinal_metin_id','$cevrilmis_metin','$cevrilecek_dil_id')");
		$son_cevrilmis_metin_id = mysqli_insert_id($baglan);
		$ceviriler_vt_kaydet = mysqli_query($baglan,"insert into ceviriler(cevrilmis_metin_id,ceviren_id,ceviri_miktari) values('$son_cevrilmis_metin_id','$uye_id','$ceviri_miktari')");
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
    $yorum_sahibi_id = $_SESSION['id'];
	$adi_soyadi = $_SESSION["adi_soyadi"];
	$profil_resmi = $_SESSION["profil_resmi"];
    if(!empty($yorum)){
        $yorumu_kaydet_sql = mysqli_query($baglan,"insert into yorumlar(yorum_sahibi_id,yorum,orijinal_metin_id) values('$yorum_sahibi_id','$yorum','$orijinal_metin_id')");
        $hata = ($yorumu_kaydet_sql)?false:true;
        $mesaj = ($yorumu_kaydet_sql)?"Yorum başarıyla başarıyla kaydedildi":"Çeviri yorum kaydedilemedi. Tekrar deneyin";
    }
    else{
        $hata = true;
        $mesaj = "Boş yorum kaydedilemez";
    }

    echo json_encode(["hata"=>$hata,"mesaj"=>$mesaj,"adi_soyadi"=>$adi_soyadi,"profil_resmi"=>$profil_resmi]);
}

//üye kaydı yapar
$uye_kaydi_yapilsin_mi = $_GET['kayit-yap'];
if ($uye_kaydi_yapilsin_mi){
    $adi_soyadi = trim($_GET['adi-soyadi']);
    $eposta = trim($_GET['eposta']);
    $sifre = trim($_GET['sifre']);

    $options = [
        'cost' => 12
    ];
    $sifre = password_hash($sifre, PASSWORD_BCRYPT, $options);

    if(!empty($adi_soyadi) && !empty($eposta) && !empty($sifre)){
        $uyeyi_kaydet_sql = mysqli_query($baglan,"insert into uyeler(eposta,adi_soyadi,sifre) values('$eposta','$adi_soyadi','$sifre')");
        $hata = ($uyeyi_kaydet_sql)?false:true;
        if ($uyeyi_kaydet_sql) {
            $_SESSION["eposta"] = $eposta;
			$uye_id = mysqli_insert_id($baglan);
			$ayar_ekle_sql = mysqli_query($baglan,"insert into ayarlar(uye_id) values($uye_id)");
			$_SESSION["id"] = $uye_id;
			$_SESSION["adi_soyadi"] = $adi_soyadi;
			$_SESSION["profil_resmi"] = "resimler/kullanici_resmi_50x50.png";
            $mesaj = "<script>location.reload();</script><strong>Hoşgeldiniz.</strong>";
        } else {
            $mesaj = "Kaydınız bir nedenden dolayı olmadı. Tekrar deneyiniz.";
        }
    }
    else{
        $hata = true;
        $mesaj = "3 alanı da doldurmalısınız";
    }

    echo json_encode(["hata"=>$hata,"mesaj"=>$mesaj]);
}

//giriş yaptırır
$giris_yapilsin_mi = $_GET['giris-yap'];
if ($giris_yapilsin_mi){
    $eposta = trim($_GET['eposta']);
    $sifre = trim($_GET['sifre']);

    if(!empty($eposta) && !empty($sifre)){
        $uye_bilgileri_sql = mysqli_query($baglan,"select * from uyeler where eposta='$eposta' limit 1");
        $hata = ($uye_bilgileri_sql)?false:true;

        if (mysqli_num_rows($uye_bilgileri_sql)>0) {
            $uye_bilgileri = mysqli_fetch_object($uye_bilgileri_sql);
            $vt_sifre = $uye_bilgileri->sifre;
            $sifre_uyusuyor_mu = password_verify($sifre,$vt_sifre);
            if($sifre_uyusuyor_mu){
                $_SESSION["eposta"] = $eposta;
                $_SESSION["id"] = $uye_bilgileri->id;
                $_SESSION["adi_soyadi"] = $uye_bilgileri->adi_soyadi;
                $_SESSION["profil_resmi"] = $uye_bilgileri->profil_resmi==""?"resimler/kullanici_resmi_50x50.png":"$uye_bilgileri->profil_resmi";
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

//çevrilecek metni kaydeder
$cevrilecek_metin_kaydedilsin_mi=$_POST["cevrilecek-metni-kaydet"];
if($cevrilecek_metin_kaydedilsin_mi){
	$metin_basligi = mysqli_real_escape_string($baglan,$_POST["metin-basligi"]);
	$cevrilen_metin_notu = mysqli_real_escape_string($baglan,$_POST["cevrilen-metin-notu"]);
	$cevrilen_metin = mysqli_real_escape_string($baglan,$_POST["cevrilen-metin"]);
	$metin_sahibi_id = $_SESSION["id"];
	$orijinal_dil_id = mysqli_real_escape_string($baglan,$_POST["orijinal-dil"]);
	$cevrilecek_dil_id = mysqli_real_escape_string($baglan,$_POST["cevrilecek-dil"]);

	if(!empty($metin_basligi) && !empty($cevrilen_metin) && !empty($orijinal_dil_id) && !empty($cevrilecek_dil_id)){
		$cevrilecek_metni_kaydet_sql = mysqli_query($baglan,"insert into orijinal_metinler(baslik,metin_sahibi_notu,orijinal_metin,metin_sahibi_id,orijinal_dil_id,cevrilecek_dil_id) values('$metin_basligi','$cevrilen_metin_notu','$cevrilen_metin','$metin_sahibi_id','$orijinal_dil_id','$cevrilecek_dil_id')");

		$hata = $cevrilecek_metni_kaydet_sql?false:true;
		$mesaj = $cevrilecek_metni_kaydet_sql?"Metin kaydedildi. Çevirinin herhangi bir aşamasındayken de görebilirsiniz.":"Metin kaydedilemedi. Tekrar deneyin.";
	}
	else{
		$hata = true;
		$mesaj = "Çevirmenlere notun dışında diğer tüm bilgileri girmelisin";
	}

	echo json_encode(["hata"=>$hata,"mesaj"=>$mesaj]);
}

//kullanıcının çevirdiği metinleri gösterir
$cevirdigim_metinler=$_GET['cevirdigim_metinler'];
if($cevirdigim_metinler){
	//$uye_id = $_SESSION[''];
	$html="<blockquote><h4>Çevirdiğim Metinler</h4></blockquote>";
	$orijinal_metinler_sql = mysqli_query($baglan,"select *,uyeler.adi_soyadi,uyeler.profil_resmi,orijinal_metinler.id as orijinal_metin_id from orijinal_metinler,uyeler where uyeler.id=orijinal_metinler.metin_sahibi_id order by orijinal_metinler.id desc;");

	while($orijinal_metinler = mysqli_fetch_object($orijinal_metinler_sql)){
		$orijinal_metin_id = $orijinal_metinler->orijinal_metin_id;
		$orijinal_metin = $orijinal_metinler->orijinal_metin;
		$baslik = $orijinal_metinler->baslik;
		$metin_sahibi_id = $orijinal_metinler->metin_sahibi_id;
		$metin_sahibi_notu = $orijinal_metinler->metin_sahibi_notu;
		$guncellenme_tarihi = $orijinal_metinler->guncellenme_tarihi;
		$adi_soyadi_kart = $orijinal_metinler->adi_soyadi;
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
								<a class='' href='javascript:void(0)'>$adi_soyadi_kart</a>
							</div>
							<a href='php/islemler.php?orijinal_metin_id=$orijinal_metin_id' class='cevir waves-effect waves-light btn right'>Çevir</a>
						</div>
					</div>
				</div>";
	}

	echo json_encode(["mesaj"=>$html]);
}

//kullanıcının çevirdiği metinler
$cevirttigim_metinler = $_GET['cevirttigim_metinler'];
if($cevirttigim_metinler){
	$uye_id = $_SESSION["id"];
	$html="<blockquote><h4>Çevirttiğim Metinler</h4></blockquote>";
	$orijinal_metinler_sql = mysqli_query($baglan,"select *,uyeler.adi_soyadi,uyeler.profil_resmi,orijinal_metinler.id as orijinal_metin_id from orijinal_metinler,uyeler where uyeler.id=orijinal_metinler.metin_sahibi_id and orijinal_metinler.metin_sahibi_id=$uye_id order by orijinal_metinler.id desc;");

	while($orijinal_metinler = mysqli_fetch_object($orijinal_metinler_sql)){
		$orijinal_metin_id = $orijinal_metinler->orijinal_metin_id;
		$orijinal_metin = $orijinal_metinler->orijinal_metin;
		$baslik = $orijinal_metinler->baslik;
		$metin_sahibi_id = $orijinal_metinler->metin_sahibi_id;
		$metin_sahibi_notu = $orijinal_metinler->metin_sahibi_notu;
		$guncellenme_tarihi = $orijinal_metinler->guncellenme_tarihi;
		$adi_soyadi_kart = $orijinal_metinler->adi_soyadi;
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
                                            <a class='' href='javascript:void(0)'>$adi_soyadi_kart</a>
                                        </div>
                                        <a href='php/islemler.php?orijinal_metin_id=$orijinal_metin_id' class='cevir waves-effect waves-light btn right'>Çevir</a>
                                    </div>
                                </div>
                            </div>";
	}

	echo json_encode(["mesaj"=>$html]);
}

//üyenin bilgilerini ve ayarlarını günceller
$bilgileri_guncelle = $_POST['bilgileri_guncelle'];
if($bilgileri_guncelle){
	$uye_id = $_SESSION['id'];
	$form_adi = $_POST['form-adi'];//forma göre kayıt yapmak için hangi formda olduğumu bu şekilde buluyorum

	if($form_adi=="kisisel-bilgileri-guncelleme-formu"){
		$adi_soyadi = mysqli_real_escape_string($baglan,$_POST['adi-soyadi']);
		$biyografi = mysqli_real_escape_string($baglan,$_POST['biyografi']);

		if(!empty($adi_soyadi)){
			$kisisel_bilgileri_guncelle_sql = mysqli_query($baglan,"update uyeler set adi_soyadi='$adi_soyadi', biyografi='$biyografi' where id=$uye_id");
			if($kisisel_bilgileri_guncelle_sql){
				$mesaj = "Kişisel bilgilerin başarıyla güncellendi";
				$hata = false;
			}else{
				$mesaj= "Güncellemede sorun çıktı. Tekrar dene.";
				$hata = true;
			}
		}
		else{
			$mesaj = "Adın boş olamaz";
			$hata = true;
		}
		$veriler = ["hata"=>$hata, "mesaj"=>$mesaj];
		echo json_encode($veriler);
	}
	else if($form_adi=="eposta-degistirme-formu"){
		$eposta = mysqli_real_escape_string($baglan,$_POST['eposta']);

		if(!filter_var($eposta,FILTER_VALIDATE_EMAIL)){
			$veriler = ["hata"=>true, "mesaj"=>"Geçerli bir eposta giriniz"];
			echo json_encode($veriler);
			exit();
		}

		if(!empty($eposta)){
			$epostayi_guncelle_sql = mysqli_query($baglan,"update uyeler set eposta='$eposta' where id=$uye_id");
			if($epostayi_guncelle_sql){
				$_SESSION['eposta'] = $eposta;
				$mesaj = "Epostan başarıyla güncellendi";
				$hata = false;
			}else{
				$mesaj= "Güncellemede sorun çıktı. Tekrar dene.";
				$hata = true;
			}
		}
		else{
			$mesaj = "Epostan boş olamaz";
			$hata = true;
		}
		$veriler = ["hata"=>$hata, "mesaj"=>$mesaj];
		echo json_encode($veriler);
	}
	else if($form_adi=="sifre-degistirme-formu") {
		$eski_sifre = mysqli_real_escape_string($baglan, $_POST['suanki-sifre']);
		$yeni_sifre = mysqli_real_escape_string($baglan, $_POST['yeni-sifre']);
		$yeni_sifre_2 = mysqli_real_escape_string($baglan, $_POST['yeni-sifre-2']);

		if ($yeni_sifre == $yeni_sifre_2) {
			if (!empty($eski_sifre) && !empty($yeni_sifre) && !empty($yeni_sifre_2)) {
				$options = [
					'cost' => 12
				];
				$yeni_sifre = password_hash($yeni_sifre, PASSWORD_BCRYPT, $options);

				$vt_sifre_sql = mysqli_query($baglan, "select sifre from uyeler where id=$uye_id limit 1");
				$vt_sifre_nesne = mysqli_fetch_object($vt_sifre_sql);
				$vt_sifre = $vt_sifre_nesne->sifre;

				$sifre_uyusuyor_mu = password_verify($eski_sifre, $vt_sifre);
				if ($sifre_uyusuyor_mu) {
					$sifreyi_guncelle_sql = mysqli_query($baglan, "update uyeler set sifre='$yeni_sifre' where id=$uye_id");
					if ($sifreyi_guncelle_sql) {
						$mesaj = "Şifren başarıyla güncellendi";
						$hata = false;
					}
					else {
						$mesaj = "Güncellemede sorun çıktı. Tekrar dene.";
						$hata = true;
					}
				}
				else {
					$mesaj = "Eski şifreni yanlış girdin. Tekrar dene.";
					$hata = true;
				}
			}
			else {
				$mesaj = "Şifreni değiştirebilmek için tüm alanları doldurmalısın";
				$hata = true;
			}
		}
		else {
			$mesaj = "Yeni şifren birbiriyle aynı değil.";
			$hata = true;
		}

		$veriler = ["hata" => $hata, "mesaj" => $mesaj];
		echo json_encode($veriler);
	}
	else if($form_adi=="iban-degistirme-formu"){
		$iban_no = $_POST['iban-no'];
		$uye_id = $_SESSION['id'];
		$iban_no_guncelle_sql = mysqli_query($baglan,"update uyeler set iban_no='$iban_no' where id=$uye_id");
		if($iban_no_guncelle_sql){
			$mesaj = "IBAN numaran kaydedildi.";
			$hata = false;
		}
		else {
			$mesaj = "IBAN numaran kaydedilemedi. Tekrar deneyin.";
			$hata = true;
		}

		$veriler = ["hata" => $hata, "mesaj" => $mesaj.mysqli_error($baglan)];
		echo json_encode($veriler);
	}
	else if($form_adi=="eposta-gonderim-ayari-formu"){
		$yeni_metin_sayisi = mysqli_real_escape_string($baglan,$_POST['yeni-metin-sayisi']);
		if(is_numeric($yeni_metin_sayisi)){
			$uye_id = $_SESSION['id'];
			$eposta_gonderme_ayarini_guncelle_sql = mysqli_query($baglan,"update ayarlar set yeni_metin_sayisi='$yeni_metin_sayisi' where uye_id=$uye_id");
			if($eposta_gonderme_ayarini_guncelle_sql){
				$mesaj = "Ayarın kaydedildi.";
				$hata = false;
			}
			else {
				$mesaj = "Ayarın kaydedilemedi. Tekrar dene.";
				$hata = true;
			}

			$veriler = ["hata" => $hata, "mesaj" => $mesaj];
			echo json_encode($veriler);
		}
		else{
			$veriler = ["hata" => true, "mesaj" => "Sadece sayı girebilirsin"];
			echo json_encode($veriler);
		}
	}
}


//kullanıcı resmini yükler
$resmi_yukle = $_POST['resmi-yukle'];
if(isset($resmi_yukle)){
	ini_set('memory_limit','512M');
	ini_set ('gd.jpeg_ignore_warning', 1); //bazı resim dosyalarında bu hatanın oluşmasını engellemek için. imagecreatefromjpeg libjpeg: recoverable error: Invalid SOS parameters for sequential JPEG
	define('MB', 1048576);
	$kirpma_verileri = json_decode($_POST['kirpma-verileri'],true);//true kullanılırsa array olarak, kullanılmazsa object olarak çıktı verir
	$gelen_resim_adi = $_FILES['kullanici-resmi-dosya']['name'];
	$gelen_resim_yolu = $_FILES['kullanici-resmi-dosya']['tmp_name'];
	$gelen_resim_tipi = $_FILES['kullanici-resmi-dosya']['type'];
	$gelen_resim_boyutu = $_FILES['kullanici-resmi-dosya']['size'];

	if(!getimagesize($gelen_resim_yolu)){
		$mesaj = "Resim dosyanız bozuk. Lütfen geçerli bir resim dosyası yükleyin.";
		$json_dizi = ["mesaj"=>$mesaj,"hata"=>true];
		echo json_encode($json_dizi);
		return;
	}

	$gecerli_dosya_turleri = ["image/jpeg", "image/jpg", "image/gif","image/png"];
	if(in_array($gelen_resim_tipi,$gecerli_dosya_turleri)){

		if($gelen_resim_boyutu > 15*MB){
			$mesaj = "Resim boyutu çok yüksek. En fazla 15 mb boyutunda resim yükleyebilirsiniz";
			$json_dizi = ["mesaj"=>$mesaj,"hata"=>true];
			echo json_encode($json_dizi);
			return;
		}

		$kirpilmis_resim_genislik = $kirpma_verileri['width'];
		$kirpilmis_resim_yukseklik = $kirpma_verileri['height'];
		$kirpilmis_resim_x = $kirpma_verileri['x'];
		$kirpilmis_resim_y = $kirpma_verileri['y'];
		$kirpilmis_resim_donme_acisi = $kirpma_verileri['rotate'];

		list($gelen_resim_genislik,$gelen_resim_yukseklik) = getimagesize($gelen_resim_yolu);
		$yeni_genislik = 100;
		$yeni_yukseklik = 100;
		$kalite = 75; //yüksek kaliteye yakın IJP standardı 75

		switch ($gelen_resim_tipi){
			case 'image/jpg':
			case 'image/jpeg': $eski_resim = imagecreatefromjpeg($gelen_resim_yolu);break;
			case 'image/png': $eski_resim = imagecreatefrompng($gelen_resim_yolu);break;
			case 'image/gif': $eski_resim = imagecreatefromgif($gelen_resim_yolu);break;
		}

		if($kirpilmis_resim_donme_acisi==90){
			//list($gelen_resim_yukseklik,$gelen_resim_genislik) = getimagesize($gelen_resim_yolu);
			//resmi sağa döndürür
			//cropper.js nin bilinen bir hatası yüzünden çok büyük boyutlu dosyalarda otomatik döndürme yapıyor. Özellikle iphone fotoğraflarında.
			//onun sola yatırdığı fotoğrafları tekrar sağa yatırmak için bunu kullanıyorum.
			//imagerotate saatin tersi yönünde döndürür.
			$eski_resim = imagerotate($eski_resim,-90,0);

			if($eski_resim){
				$mesaj_ek = "döndürme başarılı";
			}else{
				$mesaj_ek = "döndürme başarısız";
			}
		}elseif ($kirpilmis_resim_donme_acisi==-90){
			//list($gelen_resim_yukseklik,$gelen_resim_genislik) = getimagesize($gelen_resim_yolu);
			//resmi sağa döndürür
			//cropper.js nin bilinen bir hatası yüzünden çok büyük boyutlu dosyalarda otomatik döndürme yapıyor. Özellikle iphone fotoğraflarında.
			//onun sağa yatırdığı fotoğrafları tekrar sola yatırmak için bunu kullanıyorum.
			//imagerotate saatin tersi yönünde döndürür.
			$eski_resim = imagerotate($eski_resim,90,0);

			if($eski_resim){
				$mesaj_ek = "döndürme başarılı";
			}else{
				$mesaj_ek = "döndürme başarısız";
			}
		}

		$kirpilmis_resim_tuvali = imagecreatetruecolor($kirpilmis_resim_genislik,$kirpilmis_resim_yukseklik);

		imagecopyresampled($kirpilmis_resim_tuvali,$eski_resim,0,0,$kirpilmis_resim_x,$kirpilmis_resim_y,$kirpilmis_resim_genislik,$kirpilmis_resim_yukseklik,$kirpilmis_resim_genislik,$kirpilmis_resim_yukseklik);
		$yeni_resim_tuvali = imagecreatetruecolor($yeni_genislik,$yeni_yukseklik);

		imagecopyresampled($yeni_resim_tuvali,$kirpilmis_resim_tuvali,0,0,0,0,$yeni_genislik,$yeni_genislik,$kirpilmis_resim_genislik,$kirpilmis_resim_yukseklik);//resmi 100x100 küçülttüm.

		$uye_id = $_SESSION['id'];
		//tarayıcı resim adresi gönderildiğinde aynı adres gönderildiği için resim farklı olsa bile
		//aynı önbellekteki resmi gösteriyordu. Bunu atlatmak için resmi her yüklediğimde
		//adını değiştiriyorum
		$resim_adi = substr(md5($uye_id),0,5)."_$uye_id.jpg";
		$kayit_yeri = "../resimler/kullanici/$resim_adi";
		$profil_resmi = "resimler/kullanici/$resim_adi";
		$kaydet = imagejpeg($yeni_resim_tuvali,$kayit_yeri, $kalite);

		imagedestroy($yeni_resim_tuvali);
		imagedestroy($kirpilmis_resim_tuvali);
		imagedestroy($eski_resim);

		if($kaydet){
			$resmin_yolunu_kaydet_sql = mysqli_query($baglan,"update uyeler set profil_resmi='$profil_resmi' where id=$uye_id");

			if ($resmin_yolunu_kaydet_sql) {
				$mesaj = "Başarılı. Resim kaydedildi.";
				$hata = false;
			}
			else {
				$mesaj = "Resmi kaydederken sorun yaşadık. Tekrar dene";
				$hata = true;
			}
		}
		else{
			$mesaj = "Başarısız. Resim kaydedilemedi tekrar deneyin";
			$hata = true;
		}

		$json_dizi = ["mesaj"=>$mesaj,"profil_resmi"=>$profil_resmi,"hata"=>$hata];
		echo json_encode($json_dizi);
	}else{
		$mesaj = "Dosya türünüz geçerli bir resim türü değil. Dosyanız jpg, png, gif";
		$json_dizi = ["mesaj"=>$mesaj,"hata"=>true];
		echo json_encode($json_dizi);
		return;
	}
}
?>