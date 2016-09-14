/**
 * Created by Orhan Gazi on 11.09.2016.
 */

$(document).ready(function(){
    //kaydet düğmesinin ipucunu etkinleştirir
    $('.tooltipped').tooltip({delay: 50});

    //esc tuşuna basınca çeviri kapsayıcı div i kaybolur
    $(document).keydown(function (e) {
        if($(".ceviri-kapsayici").data("isOpen")){
            var keyCode = e.keyCode;
            if(keyCode == 27){
                $(".ceviri-kapsayici").data("isOpen",false);
                $(".ceviri-kapsayici").fadeOut(100);

                $('html, body').css({
                    'overflow': 'auto',
                    'height': 'auto'
                });
            }

            //CTRL+S ile çeviriyi kaydeder
            if((window.navigator.platform.match("Mac") ? e.metaKey : e.ctrlKey) && e.keyCode==83) {
                e.preventDefault();
                console.log("ctrl+s ile kaydedilecek");
                ceviriyiKaydet();
            }
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
        console.log(url);

        $.ajax({
            url: url,
            type: 'get',
            dataType: 'json'
        }).done(function(data) {
            console.log(data);
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

    //kaydet düğmesine tıklandığında
    $(".kaydet").click(function() {
        console.log("kaydedilecek");
        ceviriyiKaydet();
    });

    function ceviriyiKaydet() {
        var uye_id = $("#uye_id").val();
        var orijinal_metin_id = $("#orijinal-metin-id").val();
        var cevrilmis_metin = $("#cevrilen-metin").val();
        var cevrilecek_dil_id = $("#cevrilecek-dil-id").val();

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
            // Materialize.toast(message, displayLength, className, completeCallback);
            Materialize.toast(data.mesaj, 4000); // 4000 is the duration of the toast
        }).fail(function(data) {
            console.log("error",data);
        });
    }

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
            console.log(data);
            if(!data.yorum_yok){
                console.log("yorum var",data);
                $(".yorumlar-modal-ic").html(data.mesaj);
                $("#yorumlar-modal").openModal();
            }else{
                $(".yorumlar-modal-ic").html("<h5 style='text-align: center'>Hiç yorum yok</h5>");
                $("#yorumlar-modal").openModal();
            }
        }).fail(function(data) {
            console.log("error",data);
        });
    });
});
