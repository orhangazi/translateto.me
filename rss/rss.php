<?php
/**
 * Created by PhpStorm.
 * User: Orhan Gazi
 * Date: 3.10.2016
 * Time: 22:13
 */
include "../php/baglan.php";
$yenileri_getir_sql = mysqli_query($baglan,"select baslik,orijinal_metin,kayit_tarihi from orijinal_metinler where kayit_tarihi>=DATE_SUB(CURDATE(), INTERVAL 20 DAY) order by kayit_tarihi desc");

$veriler = "";
if(mysqli_num_rows($yenileri_getir_sql)>0){
	$guid = 1;
	while($yeniler = mysqli_fetch_object($yenileri_getir_sql)){
		$baslik = $yeniler->baslik;
		$orijinal_metin = substr($yeniler->orijinal_metin,0,150);
		$kayit_tarihi = $yeniler->kayit_tarihi;

		$veriler .= "<item>
						  <title>$baslik</title>
						  <link>http://www.translateto.me</link>
						  <description>$orijinal_metin</description>
						  <pubDate>$kayit_tarihi</pubDate>
						  <guid>$guid</guid>
					  </item>";
		$guid++;
	}
}

header("Content-type: text/xmlnn");

echo "<?xml version='1.0' encoding='UTF-8'?>
<rss version='2.0'>
	<channel>
		<title>translateto.me</title>
		<link>http://www.translateto.me</link>
		<description>Geliştirici: Orhan Gazi Kılıç</description>
		<url>http://www.xul.fr/xul.gif</url>
		<image>
			<url>http://www.xul.fr/xul-icon.gif</url>
			<link>http://www.xul.fr/en/index.php</link>
		</image>
		$veriler
</channel>
</rss>";