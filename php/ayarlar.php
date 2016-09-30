<?php
/**
 * Created by PhpStorm.
 * User: Orhan Gazi
 * Date: 29.09.2016
 * Time: 16:27
 */
include "baglan.php";

$uye_id = $_SESSION['id'];
$uye_bilgileri_sql = mysqli_query($baglan,"select * from uyeler where id=$uye_id limit 1");
$uye_bilgileri = mysqli_fetch_object($uye_bilgileri_sql);
$adi_soyadi = $uye_bilgileri->adi_soyadi;
$biyografi = $uye_bilgileri->biyografi;
$profil_resmi = $uye_bilgileri->profil_resmi;
$eposta = $uye_bilgileri->eposta;

echo "<div class='row ayar-bolumu'>
    <div class='col s8'>
		<span class='ayar-basligi'>Bilgilerini Değiştir</span>
		<form class='col s12' id='kisisel-bilgileri-guncelleme-formu' onsubmit='bilgileri_guncelle(this); return false'>
		  	<ul class='collection'>
				<li class='collection-item avatar ayar-bilgiler'>
				  <img src='resimler/kullanici_resmi_50x50.png' alt='' class='circle kullanici-resmi'>
				  <input type='file' name='kullanici-resmi' style='display: none;' id='kullanic-resmi'>
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
	</div>
   	<div class='col s4'>
      <div class='row'>
       	 <ul class='collection'>
			<li class='collection-item avatar'>
			  <img src='resimler/kullanici_resmi_50x50.png' alt='' class='circle'>
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
  });</script>";