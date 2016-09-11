/**
 * Created by Orhan Gazi on 11.09.2016.
 */

$(document).ready(function(){
    //kaydet düğmesinin ipucunu etkinleştirir
    $('.kaydet').tooltip({delay: 50});

    //anasayfadaki kartların çevir düğmesine tıklandığında
    $(".cevir").click(function(e){
        e.preventDefault();
        $(".ceviri-kapsayici").fadeIn(100);
        $('html, body').css({
            'overflow': 'hidden',
            'height': '100%'
        });

        var url = $(this).attr("href");

        $.ajax({
            url: url,
            type: 'get',
            dataType: 'json'
        }).done(function(data) {
            console.log(data);
            $(".original-metin-baslik,.cevrilen-metin-baslik").html(data.baslik);
            $(".orijinal-metin").html(data.orijinal_metin);
            $(".orijinal-metin-uye-notu").html(data.uye_notu);
            $("#orijinal-metin-id").val(data.orijinal_metin_id);
            $("#cevrilecek-dil-id").val(data.cevrilecek_dil_id);
        }).fail(function(data) {
            console.log("error",data);
        });
    });

    $(".kaydet").click(function() {
        console.log("kaydedilecek");

        var uye_id = $("#uye_id").val();
        var orijinal_metin_id = $("#orijinal-metin-id").val();
        var cevrilmis_metin = $("#cevrilen-metin").val();
        var cevrilecek_dil_id = $("#cevrilecek-dil-id").val();
        var cevirmen_notu = $("#cevirmen-notu").val();
        var veriler = {
            "uye_id":uye_id,
            "orijinal_metin_id":orijinal_metin_id,
            "cevrilmis_metin":cevrilmis_metin,
            "cevrilecek_dil_id":cevrilecek_dil_id,
            "kaydedilsin_mi":true
        };

        $.ajax({
            url: 'php/islemler.php',
            type: 'post',
            dataType: 'json',
            data:veriler
        }).done(function(data) {
            console.log(data);
        }).fail(function(data) {
            console.log("error",data);
        });
    });
});