<?php
/**
 * Created by PhpStorm.
 * User: Orhan Gazi
 * Date: 29.09.2016
 * Time: 16:27
 */
include "baglan.php";

$uye_id = $_SESSION['id'];
if(empty($uye_id)){
	exit;
}

$uye_bilgileri_sql = mysqli_query($baglan,"select * from uyeler,ayarlar where uyeler.id=$uye_id and ayarlar.uye_id=$uye_id limit 1");
$uye_bilgileri = mysqli_fetch_object($uye_bilgileri_sql);
$adi_soyadi = $uye_bilgileri->adi_soyadi;
$biyografi = $uye_bilgileri->biyografi;
$profil_resmi = $uye_bilgileri->profil_resmi;
$eposta = $uye_bilgileri->eposta;
$iban_no = $uye_bilgileri->iban_no;
$yeni_metin_sayisi = $uye_bilgileri->yeni_metin_sayisi;

$profil_resmi = $profil_resmi==""?"resimler/kullanici_resmi_50x50.png":$profil_resmi;

echo "<div class='row ayar-bolumu'>
    <div class='col s8'>
		<span class='ayar-basligi'>Bilgilerini Değiştir</span>
		<form class='col s12' id='kisisel-bilgileri-guncelleme-formu' onsubmit='bilgileri_guncelle(this); return false'>
		  	<ul class='collection'>
				<li class='collection-item avatar ayar-bilgiler'>
				  <img src='$profil_resmi' alt='' class='circle kullanici-resmi'>
				  <input type='file' name='kullanici-resmi' style='display: none;' id='kullanici-resmi-dosya'>
				  <div class='input-field col s6'>
					  <input id='adi-soyadi' name='adi-soyadi' type='text' value='$adi_soyadi'>
					  <label for='adi-soyadi' class='active'>Adın ve soyadın</label>
				  </div>
				  <div class='input-field col s12'>
					  <textarea name='biyografi' class='materialize-textarea' id='biyografi'>$biyografi</textarea>
					  <label for='biyografi' class='active'>Biyografin</label>
				  </div>
				</li>
			</ul>
			<button class='btn waves-effect waves-light tam-genislik tooltipped' data-position='top' data-delay='50' data-tooltip='Sadece Entera basarak da kaydedebilirsin'>Kaydet</button>
		</form>
		<form class='col s6' id='eposta-degistirme-formu' onsubmit='bilgileri_guncelle(this); return false'>
		  <div class='row'>
			<span class='ayar-basligi'>Epostanı değiştir</span>
			<div class='input-field col s12'>
			  <input id='eposta' name='eposta' value='$eposta' type='email'>
			  <label for='eposta' class='active'>Epostanı yaz</label>
			</div>
		  </div>
		  <blockquote><strong>Önemli not:</strong> Giriş yapmak için bu epostanı kullanacağın için doğru yazdığından emin ol</blockquote>
		  <button class='btn waves-effect waves-light tam-genislik tooltipped' data-position='top' data-delay='50' data-tooltip='Sadece Entera basarak da kaydedebilirsin'>Kaydet</button>
		</form>
		<form class='col s6' id='sifre-degistirme-formu' onsubmit='bilgileri_guncelle(this); return false;'>
		  <div class='row'>
			<span class='ayar-basligi'>Şifreni değiştir</span>
			<div class='input-field col s12'>
			  <input id='suanki-sifre' name='suanki-sifre' type='password'>
			  <label for='suanki-sifre'>Şuanki şifren</label>
			</div>
			<div class='input-field col s12'>
			  <input id='yeni-sifre' name='yeni-sifre' type='password'>
			  <label for='yeni-sifre'>Yeni şifren</label>
			</div>
			<div class='input-field col s12'>
			  <input id='yeni-sifre-2' name='yeni-sifre-2' type='password'>
			  <label for='yeni-sifre-2'>Yeni şifren</label>
			</div>
		  </div>
		  <button class='btn waves-effect waves-light tam-genislik tooltipped' data-position='top' data-delay='50' data-tooltip='Sadece Entera basarak da kaydedebilirsin'>Kaydet</button>
		</form>
		<form class='col s6' id='iban-degistirme-formu' onsubmit='bilgileri_guncelle(this); return false;'>
		  <div class='row'>
			<span class='ayar-basligi'>IBAN No</span>
			<div class='input-field col s12'>
			  <input id='iban' name='iban-no' type='text' value='$iban_no'>
			  <label for='iban' class='active'>IBAN numarası</label>
			</div>
		  </div>
		  <button class='btn waves-effect waves-light tam-genislik tooltipped' data-position='top' data-delay='50' data-tooltip='Sadece Entera basarak da kaydedebilirsin'>Kaydet</button>
		</form>
		<form class='col s6' id='eposta-gonderim-ayari-formu' onsubmit='bilgileri_guncelle(this); return false;'>
		  <div class='row'>
			<span class='ayar-basligi'>Eposta gönderme ayarları</span>
			<div class='input-field col s12'>
			  Bu kadar ya da daha fazla yeni çevrilecek metin kaydedildiğinde bana eposta gönder:<input id='yeni-metin-sayisi' name='yeni-metin-sayisi' type='text' value='$yeni_metin_sayisi'>
			</div>
		  </div>
		  <button class='btn waves-effect waves-light tam-genislik tooltipped' data-position='top' data-delay='50' data-tooltip='Sadece Entera basarak da kaydedebilirsin'>Kaydet</button>
		</form>
	</div>
   	<div class='col s4'>
      <div class='row'>
       	 <ul class='collection'>
			<li class='collection-item avatar'>
			  <img src='$profil_resmi' alt='' class='circle'>
			  <span class='title'>Bilgilerini Değiştir</span>
			  <p>Kullanıcı bilgilerini değiştirebilirsin. Böylece daha dikkat çekici olursun.</p>
			</li>
			<li class='collection-item avatar'>
			  <i class='material-icons circle green'>email</i>
			  <span class='title'>Epostanı değiştir</span>
			  <p>Epostanı değiştirmen gerektiğinde</p>
			</li>
			<li class='collection-item avatar'>
			  <i class='material-icons circle green'>lock_outline</i>
			  <span class='title'>Şifreni Değiştir</span>
			  <p>Şifreni değiştirmen gerektiğinde</p>
			</li>
			<li class='collection-item avatar'>
			  <i class='material-icons circle green'>group_work</i>
			  <span class='title'>IBAN no</span>
			  <p>Ödemeleri yapabilmemiz için iban numaranızı kaydetmelisiniz.</p>
			</li>
			<li class='collection-item avatar'>
			  <i class='material-icons circle green'>comment</i>
			  <span class='title'>Bildirim Ayarları</span>
			  <p>Eposta ile bildirim ayarlarını yapılandır</p>
			</li>
			<li class='collection-item avatar'>
			  <i class='material-icons circle red'>settings_power</i>
			  <span class='title'>Hesabını Sil</span>
			  <a class='btn waves-effect waves-light waves-block red'>Hesabımı Sil</a>
			  <p><strong>Dikkatli ol.</strong> Hesabını kalıcı olarak silersen her şeyini kaybedersin</p>
			</li>
		  </ul>
      </div>
    </div>
</div>
<script>$(document).ready(function(){
    $('.tooltipped').tooltip({delay: 50});
    
  });</script></script>";