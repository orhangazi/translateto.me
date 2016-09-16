/**
 * Created by Orhan Gazi on 11.09.2016.
 */

$(document).ready(function(){
    //kaydet düğmesinin ipucunu etkinleştirir
    $('.tooltipped').tooltip({delay: 50});

    //esc tuşuna basınca çeviri kapsayıcı div i kaybolur
    $(document).keydown(function (e) {
        if($(".ceviri-kapsayici").data("isOpen") || $(".giris-kayit-divi").data("isOpen")){
            var keyCode = e.keyCode;
            if(keyCode == 27){
                $(".ceviri-kapsayici").data("isOpen",false);
                $(".giris-kayit-divi").data("isOpen",false);
                $(".ceviri-kapsayici").fadeOut(100);
                $(".giris-kayit-divi").fadeOut(100);

                $('html, body').css({
                    'overflow': 'auto',
                    'height': 'auto'
                });
            }

            //CTRL+S ile çeviriyi kaydeder
            if((window.navigator.platform.match("Mac") ? e.metaKey : e.ctrlKey) && e.keyCode==83) {
                e.preventDefault();
                ceviriyiKaydet();
            }
        }
    });


    //giriş divini açılmasını sağlar
    $(".giris-kayit-divini-ac").click(function(e){
        e.preventDefault();
        $(".giris-kayit-divi").data("isOpen", true);
        $(".giris-kayit-divi").fadeIn(100);
        $('html, body').css({
            'overflow': 'hidden',
            'height': '100%'
        });
    });

    //giriş divinin kapanmasını sağlar
    $(".giris-kayit-divi-kapat").click(function () {
        $(".giris-kayit-divi").data("isOpen",false);
        $(".giris-kayit-divi").fadeOut(100);
        $('html, body').css({
            'overflow': 'auto',
            'height': 'auto'
        });
    });

    //kullanıcı enter a bastığında yorum kaydedilir, ctrl+enter a bastığında satırbaşı yapılır
    $("#yorum").keydown(function (e) {
        if((window.navigator.platform.match("Mac") ? e.metaKey : e.ctrlKey) && e.keyCode==13){
            console.log("yorum ctrl+enter ile satırbaşı yapılacak");

        }else if(e.keyCode==13){
            yorumuKaydet();
        }
    });
    
    //kapata basınca çeviri kapsayıcı div i kaybolur
    $(".ceviri-kapsayici-kapat").click(function () {
        $(".ceviri-kapsayici").data("isOpen",false);
        $(".ceviri-kapsayici").fadeOut(100);
        $('html, body').css({
            'overflow': 'auto',
            'height': 'auto'
        });
    });

    //yeni kullanıcı kaydeder
    $("#kaydolma-formu").submit(function (e) {

    });

    //kaydet düğmesine tıklandığında
    $(".kaydet").click(function() {
        ceviriyiKaydet();
    });

    //anasayfadaki kartların çevir düğmesine tıklandığında
    $(".cevir").click(function(e){
        e.preventDefault();
        $(".ceviri-kapsayici").data("isOpen", true);
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
            if(data.cevirisi_var_mi==true){
                $(".original-metin-baslik,.cevrilen-metin-baslik").html(data.baslik);
                $(".orijinal-metin").html(data.orijinal_metin);
                $(".orijinal-metin-uye-notu").html(data.metin_sahibi_notu);
                $("#orijinal-metin-id").val(data.orijinal_metin_id);
                $("#cevrilecek-dil-id").val(data.cevrilecek_dil_id);
                $("#cevrilen-metin").val(data.cevrilmis_metin).focus();
            }
            else {
                $(".original-metin-baslik,.cevrilen-metin-baslik").html(data.baslik);
                $(".orijinal-metin").html(data.orijinal_metin);
                $(".orijinal-metin-uye-notu").html(data.metin_sahibi_notu);
                $("#orijinal-metin-id").val(data.orijinal_metin_id);
                $("#cevrilecek-dil-id").val(data.cevrilecek_dil_id);
            }
        }).fail(function(data) {
            console.log("error",data);
        });
    });

    //çeviriyi kaydeder
    function ceviriyiKaydet() {
        var uye_id = $("#uye-id").val();
        var orijinal_metin_id = $("#orijinal-metin-id").val();
        var cevrilmis_metin = $("#cevrilen-metin").val();
        var cevrilecek_dil_id = $("#cevrilecek-dil-id").val();

        var veriler = {
            "uye_id":uye_id,
            "orijinal_metin_id":orijinal_metin_id,
            "cevrilmis_metin":cevrilmis_metin,
            "cevrilecek_dil_id":cevrilecek_dil_id,
            "ceviri_kaydedilsin_mi":true
        };

        $.ajax({
            url: 'php/islemler.php',
            type: 'post',
            dataType: 'json',
            data:veriler
        }).done(function(data) {
            // Materialize.toast(message, displayLength, className, completeCallback);
            Materialize.toast(data.mesaj, 4000); // 4000 is the duration of the toast
        }).fail(function(data) {
            console.log("error",data);
        });
    }

    //yorumları kaydeder
    function yorumuKaydet() {
        var uye_id = $("#uye-id").val();
        var adi_soyadi = $("#adi-soyadi").val();
        var profil_resmi = $("#profil-resmi").val();
        var yorum = $("#yorum").val();
        var orijinal_metin_id = $("#orijinal-metin-id").val();

        var veriler = {
            "uye_id":uye_id,
            "orijinal_metin_id":orijinal_metin_id,
            "yorum":yorum,
            "yorum_kaydedilsin_mi":true
        };

        $.ajax({
            url: 'php/islemler.php',
            type: 'post',
            dataType: 'json',
            data:veriler
        }).done(function(data) {
            if(!data.hata){
                var gonderilen_yorum = "<ul class='collection'><li class='collection-item avatar'><img src='"+profil_resmi+"' alt='' class='circle'> <span class='title'><strong>"+adi_soyadi+"</strong></span> <p>"+yorum+"</p> </li> </ul>";
                $(".yorumlar-modal-ic>h5").empty();
                $(".yorumlar-modal-ic").append(gonderilen_yorum);
                $("#yorumlar-modal > div.modal-content").animate({ scrollTop: $("#yorumlar-modal > div.modal-content")[0].scrollHeight }, 1000);
                $("#yorum").val("");
            }
            else{
                // Materialize.toast(message, displayLength, className, completeCallback);
                Materialize.toast(data.mesaj, 4000); // 4000 is the duration of the toast
            }
        }).fail(function(data) {
            console.log("error",data);
        });
    }

    //yorumları gösterir
    $(".yorumlari-gor").click(function () {
        var orijinal_metin_id = $("#orijinal-metin-id").val();

        var veriler = {
            "orijinal_metin_id":orijinal_metin_id,
            "yorumlari_goster":true
        };

        $.ajax({
            url: 'php/islemler.php',
            type: 'post',
            dataType: 'json',
            data:veriler
        }).done(function(data) {
            if (!data.yorum_yok) {
                $(".yorumlar-modal-ic").html(data.mesaj);
                $("#yorumlar-modal").openModal({
                    ready: function () {
                        $(".ceviri-kapsayici").data("isOpen",false);
                        $("#yorumlar-modal > div.modal-content").animate({ scrollTop: $("#yorumlar-modal > div.modal-content")[0].scrollHeight }, 1000);
                    },
                    complete: function() {
                        $(".ceviri-kapsayici").data("isOpen",true);
                    }
                });
            } else {
                $(".yorumlar-modal-ic").html("<h5 style='text-align: center'>Hiç yorum yok</h5>");
                $("#yorumlar-modal").openModal();
            }
        }).fail(function(data) {
            console.log("error",data);
        });
    });

    //üye kaydı yapılır
    $("#kaydolma-formu").submit(function (e) {
        e.preventDefault();
        var veriler = $(this).serialize();
        $.ajax({
            url: "php/islemler.php",
            type: 'get',
            data: veriler,
            dataType: 'json'
        }).done(function(data) {
            $(".kayit-durumu").addClass("card-panel teal lighten-2");
            $(".kayit-durumu").html(data.mesaj);
        }).fail(function(data) {
            console.log("error",data);
        });
    });

    //giriş yaptırır
    $("#giris-formu").submit(function (e) {
        e.preventDefault();
        var veriler = $(this).serialize();
        $.ajax({
            url: "php/islemler.php",
            type: 'get',
            data: veriler,
            dataType: 'json'
        }).done(function(data) {
            $(".giris-durumu").addClass("card-panel teal lighten-2");
            $(".giris-durumu").html(data.mesaj);
        }).fail(function(data) {
            console.log("error",data);
        });
    });
});
