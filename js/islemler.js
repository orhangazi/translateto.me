/**
 * Created by Orhan Gazi on 11.09.2016.
 */

$(document).ready(function(){
    //kaydet düğmesinin ipucunu etkinleştirir
    $('.tooltipped').tooltip({delay: 50});
    //görünümü değiştirme tooltipi
    //$(".gorunumu-degistir").tooltip({tooltip:"3lü sütun görünümü",delay:50,position:"bottom"});

    //select elementlerini başlatır
    $('select').material_select();

    //esc tuşuna basınca çeviri kapsayıcı div i kaybolur
    $(document).keydown(function (e) {
        //hangi katman açık
        ceviri_ortami_katmani = $(".ceviri-kapsayici").data("isOpen");
        giris_formu_katmani = $(".giris-kayit-divi").data("isOpen");
        yeni_metin_ekleme_katmani = $(".cevrilecek-metin-ekleme-divi").data("isOpen");

        if(ceviri_ortami_katmani || giris_formu_katmani || yeni_metin_ekleme_katmani){
            var keyCode = e.keyCode;
            if(keyCode == 27){
                $(".ceviri-kapsayici").data("isOpen",false);
                $(".giris-kayit-divi").data("isOpen",false);
                $(".cevrilecek-metin-ekleme-divi").data("isOpen",false);
                $(".ceviri-kapsayici").fadeOut(100);
                $(".giris-kayit-divi").fadeOut(100);
                $(".cevrilecek-metin-ekleme-divi").fadeOut(100);


                $('html, body').css({
                    'overflow': 'auto',
                    'height': 'auto'
                });
            }

            //CTRL+S ile çeviriyi kaydeder
            if((window.navigator.platform.match("Mac") ? e.metaKey : e.ctrlKey) && e.keyCode==83) {
                e.preventDefault();
                if(ceviri_ortami_katmani){
                    ceviriyiKaydet();
                }
                else if(yeni_metin_ekleme_katmani){
                    $("#cevrilecek-metin-formu").submit();
                }
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
    $(document).on("click",".cevir",function(e){
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

            $("#orijinal-kelime-sayisi").val(data.orijinal_kelime_sayisi);
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
        var orijinal_kelime_sayisi = $("#orijinal-kelime-sayisi").val();

        var veriler = {
            "uye_id":uye_id,
            "orijinal_metin_id":orijinal_metin_id,
            "cevrilmis_metin":cevrilmis_metin,
            "cevrilecek_dil_id":cevrilecek_dil_id,
            "orijinal_kelime_sayisi":orijinal_kelime_sayisi,
            "ceviri_kaydedilsin_mi":true
        };

        console.log(veriler);

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
                        //çeviri yapıldığı esnada bu yorumlara bakıldığından
                        //ceviri-kapsayici nın açıkmı özelliğini kapalı yapıyorum ki islemler.js nin
                        //üst taraflarında esc ye basıldığında kapanmasını engelliyorum.
                        //kapatılırken de complate özelliğiyle tekrar açık olduğunu true yapıyorum ve
                        //böylece esc ye basınca çalışacak.
                        $(".ceviri-kapsayici").data("isOpen",false);
                        $("#yorumlar-modal > div.modal-content").animate({ scrollTop: $("#yorumlar-modal > div.modal-content")[0].scrollHeight }, 1000);
                        $("#yorum").focus();
                    },
                    complete: function() {
                        $(".ceviri-kapsayici").data("isOpen",true);
                    }
                });
            } else {
                $(".yorumlar-modal-ic").html("<h5 style='text-align: center'>Hiç yorum yok</h5>");
                $("#yorumlar-modal").openModal({
                    ready: function () {
                        $(".ceviri-kapsayici").data("isOpen",false);
                        $("#yorum").focus();
                        console.log("açık");
                    },
                    complete: function() {
                        $(".ceviri-kapsayici").data("isOpen",true);
                        console.log("kapalı");
                    }
                });
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

    //çeviri yapılacak metin eklemek için ortamı açar
    $(".yeni-cevrilecek-metin-ekle").click(function () {
        $(".cevrilecek-metin-ekleme-divi").fadeIn(100);
        $(".cevrilecek-metin-ekleme-divi").data("isOpen",true);

        $('html, body').css({
            'overflow': 'hidden',
            'height': '100%'
        });
    });

    //çevrilecek metni kaydettirir
    $("#cevrilecek-metin-formu").submit(function (e){
        e.preventDefault();
        console.log($(this).serialize());
        var metin_sahibi_id = $("#uye-id").val();
        var veriler = $(this).serialize();
        $.ajax({
            url: 'php/islemler.php',
            type: 'post',
            dataType: 'json',
            data: veriler+"&metin-sahibi-id="+metin_sahibi_id
        }).done(function(data) {
            console.log(data);
            Materialize.toast(data.mesaj,4000);
        }).fail(function(data) {
            console.log("error",data);
        });
    });

    //kullanıcının çevirdiği metinleri gösterir
    $(".cevirdigim-metinler").click(function (e) {
        e.preventDefault();
        var url = $(this).attr("href");

        $.ajax({
            url: url,
            type: 'get',
            dataType: 'json'
        }).done(function(data) {
            $("#index-banner > div.container > div.row").empty();
            $("#index-banner > div.container > div.row").html(data.mesaj);
        }).fail(function(data) {
            console.log("error",data);
        });
    });
    //kullanıcının çevirdiği metinleri gösterir
    $(".cevirttigim-metinler").click(function (e) {
        e.preventDefault();
        var url = $(this).attr("href");

        $.ajax({
            url: url,
            type: 'get',
            dataType: 'json'
        }).done(function(data) {
            $("#index-banner > div.container > div.row").empty();
            $("#index-banner > div.container > div.row").html(data.mesaj);
        }).fail(function(data) {
            console.log("error",data);
        });
    });
    
    //uclu gorunume ve ikili gorunume geçirir
    $(".gorunumu-degistir").click(function () {
        var kart_genisligi = $(".kart").width();
        if(kart_genisligi>322){
            $(".kart").animate({width:"322px"},300);
            //düğmede 2li sütun simgesini gösterir
            $(".gorunumu-degistir > i").html("pause");
            $(".gorunumu-degistir").tooltip('remove');
            var gorunum = $(".gorunumu-degistir").attr("data-tooltip","2li sütun görünümü");
            $(".gorunumu-degistir").tooltip({delay:50});
            console.log(gorunum);
        }
        else{
            $(".kart").animate({width:"483px"},300);
            //düğmede 3lü sütun simgesini gösterir
            $(".gorunumu-degistir > i").html("view_week");
            $(".gorunumu-degistir").tooltip('remove');
            var gorunum = $(".gorunumu-degistir").attr("data-tooltip","3lü sütun görünümü");
            $(".gorunumu-degistir").tooltip({delay:50});
            console.log(gorunum);
        }
    });
});
