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

    //scroll en alta indiğinde yeni kartları yükler
    anasayfa_kart_limiti = 15;

        $(window).scroll(function () {
            if($(window).scrollTop() + $(window).height() == $(document).height()) {
                if(anasayfa_kart_limiti!=undefined){
                    var veriler = {"anasayfa_kart_limiti":anasayfa_kart_limiti,
                        "kartlari_yukle":true};
                    $.ajax({
                        url: 'php/islemler.php',
                        type: 'post',
                        dataType: 'json',
                        data: veriler
                    }).done(function(data) {
                        if(!data.hata){
                            $("div.container.orta>.row").append(data.mesaj);
                            console.log("1",anasayfa_kart_limiti);
                            anasayfa_kart_limiti = data.sonraki_limit;
                            console.log("2",data.sonraki_limit);
                        }
                        else{
                            Materialize.toast(data.mesaj,5000);
                        }
                    }).fail(function(data) {
                        console.log("error",data);
                    });
                }
            }
        });



    //esc tuşuna basınca çeviri kapsayıcı div i kaybolur
    $(document).keydown(function (e) {
        //hangi katman açık
        ceviri_ortami_katmani = $(".ceviri-kapsayici").data("isOpen");
        giris_formu_katmani = $(".giris-kayit-divi").data("isOpen");
        yeni_metin_ekleme_katmani = $(".cevrilecek-metin-ekleme-divi").data("isOpen");
        resmi_kirp_kapsayici = $(".resmi-kirp-kapsayici").data("isOpen");
        if(ceviri_ortami_katmani || giris_formu_katmani || yeni_metin_ekleme_katmani || resmi_kirp_kapsayici){
            var keyCode = e.keyCode;
            if(keyCode == 27){
                $(".ceviri-kapsayici").data("isOpen",false);
                $(".giris-kayit-divi").data("isOpen",false);
                $(".cevrilecek-metin-ekleme-divi").data("isOpen",false);
                $(".resmi-kirp-kapsayici").data("isOpen",false);
                $(".ceviri-kapsayici").fadeOut(100);
                $(".giris-kayit-divi").fadeOut(100);
                $(".cevrilecek-metin-ekleme-divi").fadeOut(100);
                $(".resmi-kirp-kapsayici").fadeOut(100);

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
        var orijinal_metin_id = $("#orijinal-metin-id").val();
        var cevrilmis_metin = $("#cevrilen-metin").val();
        var cevrilecek_dil_id = $("#cevrilecek-dil-id").val();
        var orijinal_kelime_sayisi = $("#orijinal-kelime-sayisi").val();

        var veriler = {
            "orijinal_metin_id":orijinal_metin_id,
            "cevrilmis_metin":cevrilmis_metin,
            "cevrilecek_dil_id":cevrilecek_dil_id,
            "orijinal_kelime_sayisi":orijinal_kelime_sayisi,
            "ceviri_kaydedilsin_mi":true
        };

        $.ajax({
            url: 'php/islemler.php',
            type: 'post',
            dataType: 'json',
            data:veriler
        }).done(function(data) {
            if(!data.hata){
                // Materialize.toast(message, displayLength, className, completeCallback);
                Materialize.toast(data.mesaj, 4000); // 4000 is the duration of the toast
            }
            else{
                if(!data.giris_yapilmis_mi){
                    $(".giris-kayit-divini-ac").click();
                    // Materialize.toast(message, displayLength, className, completeCallback);
                    Materialize.toast(data.mesaj, 10000); // 4000 is the duration of the toast
                }else {
                    // Materialize.toast(message, displayLength, className, completeCallback);
                    Materialize.toast(data.mesaj, 4000); // 4000 is the duration of the toast
                }
            }
        }).fail(function(data) {
            console.log("error",data);
        });
    }

    //yorumları kaydeder
    function yorumuKaydet() {
        var yorum = $("#yorum").val();
        var orijinal_metin_id = $("#orijinal-metin-id").val();

        var veriler = {
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
                console.log(data);
                if(data.giris_yapilmis_mi){
                    var gonderilen_yorum = "<ul class='collection'><li class='collection-item avatar'><img src='"+data.profil_resmi+"' alt='"+data.adi_soyadi+"' class='circle'> <span class='title'><strong>"+data.adi_soyadi+"</strong></span> <p>"+yorum+"</p> </li> </ul>";
                    console.log(gonderilen_yorum);

                    $(".yorumlar-modal-ic>h5").empty();
                    $(".yorumlar-modal-ic").append(gonderilen_yorum);
                    $("#yorumlar-modal > div.modal-content").animate({ scrollTop: $("#yorumlar-modal > div.modal-content")[0].scrollHeight }, 1000);
                    $("#yorum").val("");
                }else {
                    $(".giris-kayit-divini-ac").click();
                    Materialize.toast(data.mesaj,15000);
                }
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
    gosterilecek_yorum_limiti = 0;
    $(".yorumlari-gor,.daha-fazla-yorum").click(function () {
        var orijinal_metin_id = $("#orijinal-metin-id").val();
        var veriler = {
            "orijinal_metin_id":orijinal_metin_id,
            "gosterilecek_yorum_limiti":gosterilecek_yorum_limiti,
            "yorumlari_goster":true
        };

        $.ajax({
            url: 'php/islemler.php',
            type: 'post',
            dataType: 'json',
            data:veriler
        }).done(function(data) {
            if (!data.yorum_yok) {
                console.log(gosterilecek_yorum_limiti);
                if(gosterilecek_yorum_limiti==0){
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
                }else {
                    $(".yorumlar-modal-ic").prepend(data.mesaj);
                }
                gosterilecek_yorum_limiti = data.sonraki_limit;
            } else {
                if(gosterilecek_yorum_limiti==0){
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
                }else {
                    gosterilecek_yorum_limiti = 0;
                    yorum_yok_div = "<div class='card-panel'>"+data.mesaj+"</div>";
                    $(".yorumlar-modal-ic").prepend(yorum_yok_div);
                }
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
        if(!giris_yapilmis_mi()){
            Materialize.toast("Önce giriş yapmanız ya da kayıt olmanız gerekli.",5000);
            return;
        }

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
        var veriler = $(this).serialize();
        $.ajax({
            url: 'php/islemler.php',
            type: 'post',
            dataType: 'json',
            data: veriler
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

    //kullanıcının çevirttiği metinleri gösterir
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
        console.log(kart_genisligi);
        if(kart_genisligi>322){
            //$(".kart").animate({width:"322px"},300);
            $(".kart").addClass("s4");
            $(".kart").removeClass("s6");
            //düğmede 2li sütun simgesini gösterir
            $(".gorunumu-degistir > i").html("pause");
            $(".gorunumu-degistir").tooltip('remove');
            var gorunum = $(".gorunumu-degistir").attr("data-tooltip","2li sütun görünümü");
            $(".gorunumu-degistir").tooltip({delay:50});
            console.log(gorunum);
        }
        else{
            //$(".kart").animate({width:"483px"},300);
            $(".kart").addClass("s6");
            $(".kart").removeClass("s4");
            //düğmede 3lü sütun simgesini gösterir
            $(".gorunumu-degistir > i").html("view_week");
            $(".gorunumu-degistir").tooltip('remove');
            var gorunum = $(".gorunumu-degistir").attr("data-tooltip","3lü sütun görünümü");
            $(".gorunumu-degistir").tooltip({delay:50});
            console.log(gorunum);
        }
    });

    //metnin tümünü göstermek için
    $(".tumunu-goster").click(function(e) {
        var url = $(this).attr("href");
        e.preventDefault();
        $.ajax({
            url: url,
            type: 'get',
            dataType: 'json'
        }).done(function(data) {
            $(".metin-modal-baslik").html(data.baslik);
            $(".metin-modal-ic").html(data.orijinal_metin);
            $('#metin-modal').openModal();
        }).fail(function(data) {
            console.log("error",data);
        });
    });

    //ayarları kaydeder
    $(".ayarlar").click(function (e) {
        e.preventDefault();
        var url = $(this).attr("href");
        $.ajax({
            url: url,
            type: 'get'
        }).done(function(data) {
            $(".orta").html(data);
        }).fail(function(data) {
            console.log("error",data);
        });
    });

    //kullanıcı resmini değiştirir
    $(document).on('click','.kullanici-resmi',function () {
        $("#kullanici-resmi-dosya").click();
        $('.surec-ic').css({width:'0%'});
    });

    $('#kullanici-resmi-img').cropper({
        aspectRatio: 1/1,
        preview:$('.on-izleme'),
        rotatable:true
    });
    
    //yükleme yap
    $("#resmi-yukle").click(function () {
        $('.surec-ic').css({width:'0%'});
        var kirpma_verileri = $('#kullanici-resmi-img').cropper('getData',{rounded:true});
        kirpma_verileri = JSON.stringify(kirpma_verileri);
        //resim_input = $("#kullanici-resmi-dosya");
        resim_input = document.getElementById("kullanici-resmi-dosya");
        resim = resim_input.files[0];
        resim_formdata = new FormData();
        resim_formdata.append('kullanici-resmi-dosya',resim);
        resim_formdata.append('kirpma-verileri',kirpma_verileri);
        resim_formdata.append('resmi-yukle',true);
        //console.log(kirpma_verileri);

        $.ajax({
            url: 'php/islemler.php',
            type: 'post',
            dataType: 'json',
            enctype: 'multipart/form-data',
            cache: false,
            processData: false,  // do not process the data as url encoded params
            contentType: false,   // by default jQuery sets this to urlencoded string
            data: resim_formdata,
            xhr: function() {
                var myXhr = $.ajaxSettings.xhr();
                if (myXhr.upload) {
                    myXhr.upload.addEventListener('progress',function(ev) {
                        if (ev.lengthComputable) {
                            var percentUploaded = Math.floor(ev.loaded * 100 / ev.total);
                            console.log('Uploaded '+percentUploaded+'%');
                            $('.surec-ic').css({width:percentUploaded+'%'});
                            // update UI to reflect percentUploaded
                        } else {
                            console.info('Uploaded '+ev.loaded+' bytes');
                            // update UI to reflect bytes uploaded
                        }
                    }, false);
                }
                return myXhr;
            },
            beforeSend:function () {
                //hata durumunda tekrar yüklemek için.
                eski_profil_resmi = $(".collection-item.avatar>img").attr('src');
                $(".collection-item.avatar>img").attr('src','resimler/yukleniyor.gif');
            }
        }).done(function(data) {
            if(!data.hata){
                $(".collection-item.avatar>img").attr('src',data.profil_resmi);
                $(".resmi-kirp-kapsayici").fadeOut(100);
                $(".resmi-kirp-kapsayici").data("isOpen",false);
                Materialize.toast(data.mesaj,5000);
            }else {
                $(".resmi-kirp-kapsayici").fadeOut(100);
                $(".resmi-kirp-kapsayici").data("isOpen",false);
                Materialize.toast(data.mesaj,5000);
                $(".collection-item.avatar>img").attr('src',eski_profil_resmi);
            }
        }).fail(function(data) {
            console.log("error",data);
        });
    });

    // Import image
    var $inputImage = $('#kullanici-resmi-dosya');
    var $image = $('#kullanici-resmi-img');
    var URL = window.URL || window.webkitURL;
    var blobURL;

    if (URL) {
        $(document).on('change','#kullanici-resmi-dosya',function () {

            $(".resmi-kirp-kapsayici").data("isOpen",true);
            $(".resmi-kirp-kapsayici").fadeIn(100);

            var files = this.files;
            var file;

            if (!$image.data('cropper')) {
                return;
            }

            if (files && files.length) {
                file = files[0];

                if (/^image\/\w+$/.test(file.type)) {
                    blobURL = URL.createObjectURL(file);
                    $image.one('built.cropper', function () {

                        // Revoke when load complete
                        URL.revokeObjectURL(blobURL);
                    }).cropper('reset').cropper('replace', blobURL);
                    $inputImage.val('');
                } else {
                    $(".resmi-kirp-kapsayici").fadeOut(100);
                    $(".resmi-kirp-kapsayici").data("isOpen",false);
                    Materialize.toast('Lütfen bir resim dosyası seçin',5000);
                }
            }
        });
    } else {
        //$inputImage.prop('disabled', true).parent().addClass('disabled');
    }
});

//bilgileri günceller
function bilgileri_guncelle(form) {
    var form_adi = $(form).attr("id");
    var veriler = $(form).serialize()+"&form-adi="+form_adi+"&bilgileri_guncelle=true";
    console.log(veriler);

    $.ajax({
        url: 'php/islemler.php',
        type: 'post',
        dataType: 'json',
        data: veriler
    }).done(function(data) {
        Materialize.toast(data.mesaj,4000);
        console.log(data);
    }).fail(function(data) {
        console.log("error",data);
    });
}

//giriş yapılıp yapılmadığını kontrol eder
function giris_yapilmis_mi(){
    var httpRequest = new XMLHttpRequest();
    httpRequest.open('GET', "php/islemler.php?uye_girisi_yapilmis_mi=true",false);
    httpRequest.send();
    veri = JSON.parse(httpRequest.response);
    console.log(veri.giris_yapilmis_mi);
    $('.giris-kayit-divini-ac').click();
    return veri.giris_yapilmis_mi;
    //ajax içerisinde return kullanılamadığı için normal ajax yazmak zorunda kaldım.
}