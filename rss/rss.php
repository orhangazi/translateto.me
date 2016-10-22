<?php
/**
 * Created by PhpStorm.
 * User: Orhan Gazi
 * Date: 3.10.2016
 * Time: 22:13
 */
include "../php/baglan.php";
$yenileri_getir_sql = mysqli_query($baglan,"select baslik,orijinal_metin,DATE_FORMAT(kayit_tarihi,'%a, %d %b %Y %T') as kayit_tarihi from orijinal_metinler where kayit_tarihi>=DATE_SUB(CURDATE(), INTERVAL 20 DAY) order by id desc");

$veriler = "";
if(mysqli_num_rows($yenileri_getir_sql)>0){
	$guid = 1;
	while($yeniler = mysqli_fetch_object($yenileri_getir_sql)){
		$baslik = $yeniler->baslik;
		$orijinal_metin = substr($yeniler->orijinal_metin,0,150);
		$kayit_tarihi = $yeniler->kayit_tarihi;

		$veriler .= "<item>
						  <title>$baslik</title>
						  <link>http://www.translateto.me/$guid</link>
						  <description>$orijinal_metin</description>
						  <pubDate>$kayit_tarihi</pubDate>
						  <guid isPermaLink='false'>$guid</guid>
					  </item>";
		$guid++;
	}
}
$son_yapilandirma_tarihi_sql = mysqli_query($baglan,"select DATE_FORMAT(kayit_tarihi,'%a, %d %b %Y %T') as kayit_tarihi from orijinal_metinler order by id desc limit 1 ");
$son_yapilandirma_tarihi_nesne = mysqli_fetch_object($son_yapilandirma_tarihi_sql);
$son_yapilandirma_tarihi = $son_yapilandirma_tarihi_nesne->kayit_tarihi;

header("Content-type: text/xmlnn");

echo "<?xml version='1.0' encoding='UTF-8'?>
<rss version='2.0' xmlns:atom='http://www.w3.org/2005/Atom'>
	<channel>
		<atom:link href='http://localhost/translateto.me/rss/rss.php' rel='self' type='application/rss+xml' />
		<title>translateto.me</title>
		<link>http://www.translateto.me</link>
		<description>Geliştirici: Orhan Gazi Kılıç</description>
		<lastBuildDate>$son_yapilandirma_tarihi</lastBuildDate>
		$veriler
</channel>
</rss>";