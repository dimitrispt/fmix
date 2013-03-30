var x1, y1, h1, w1;
var jcrop_api, jcrop_api2;
d = new Date();
var image1, image2;
var bg_clicked = 1;

var width1,height1,pos1,opacity1,width2,height2,pos2,opacity2,zindex;

var images_folder = "srvscripts/"; //location of images folder

/*! waitForImages jQuery Plugin - v1.4.2 - 2013-01-19
* https://github.com/alexanderdickson/waitForImages
* Copyright (c) 2013 Alex Dickson; Licensed MIT */
(function(e){var t="waitForImages";e.waitForImages={hasImageProperties:["backgroundImage","listStyleImage","borderImage","borderCornerImage"]},e.expr[":"].uncached=function(t){if(!e(t).is('img[src!=""]'))return!1;var n=new Image;return n.src=t.src,!n.complete},e.fn.waitForImages=function(n,r,i){var s=0,o=0;e.isPlainObject(arguments[0])&&(i=arguments[0].waitForAll,r=arguments[0].each,n=arguments[0].finished),n=n||e.noop,r=r||e.noop,i=!!i;if(!e.isFunction(n)||!e.isFunction(r))throw new TypeError("An invalid callback was supplied.");return this.each(function(){var u=e(this),a=[],f=e.waitForImages.hasImageProperties||[],l=/url\(\s*(['"]?)(.*?)\1\s*\)/g;i?u.find("*").andSelf().each(function(){var t=e(this);t.is("img:uncached")&&a.push({src:t.attr("src"),element:t[0]}),e.each(f,function(e,n){var r=t.css(n),i;if(!r)return!0;while(i=l.exec(r))a.push({src:i[2],element:t[0]})})}):u.find("img:uncached").each(function(){a.push({src:this.src,element:this})}),s=a.length,o=0,s===0&&n.call(u[0]),e.each(a,function(i,a){var f=new Image;e(f).bind("load."+t+" error."+t,function(e){o++,r.call(a.element,o,s,e.type=="load");if(o==s)return n.call(u[0]),!1}),f.src=a.src})})}})(jQuery);


$.ajaxSetup({ cache: false });

 function load_images_url(data, index) {
     
     var images =  jQuery.parseJSON(data);
     
     var images_url_html = ' <div class="ajax-loader" id="ajax-loader-url">'+
                           '<img src="styles/ajax-loader.gif">'+
                            '</div>'+
                            '<div style="text-align: right;margin-top:-5px;margin-right:-20px;">'+
                            '<img src="styles/close2.png" class="url-close"></div>';
     
     images_url_html += "Choose the image you'd like to load:<br/><p>"+
             "<table>";
     
     $.each(images, function(i, img){
     images_url_html += '<tr><a href="#"><img class="image_url'+index
             +'" src="'+images_folder+img+'"/></a></tr>';
     });
     
     images_url_html += "</table></p>";
     
     $("#images_url").html(images_url_html);
     
   
     $(".pops").hide();
     $("#mix-area").animate({width: '0px'},200).hide();
     $("#images_url").show(10).animate({width: '500px'},200);
     
    $(".image_url"+index).click(function() {
        $("#ajax-loader-url").show();
        $("#images_url").css("opacity","0.6");
        imgindex = $(".image_url"+index).index(this);
       // index = 1;
        $.ajax({
            url: "srvscripts/get_images_url.php",
            data: {imgi: imgindex, mixindex: index},
            type: "GET"
        }).done(function(j) {
                        
                        if (j.error !== 0) {
                               
                                alert(j.msg);
                           }

                     if (j.error === 0) { 

                           image = j.filename;
                           if (index===1) {image1 = image;}
                           else if (index===2) {image2 = image;}
                           
                           $('#mix-image'+index).attr('src', images_folder+image+"?t="+d.getTime());
                           
                      $("#mix-image"+index).parent().waitForImages(function() {
                            $("#ajax-loader-url").hide();
                            $("#images_url").css("opacity","1");
                            $(".pops").hide();
                            $("#images_url").animate({width: '0px'},200).hide();
                            $("#mix-area").show(10).animate({width: '550px'},200);
     
                        
                           $("#cropme"+index).removeAttr('disabled');
                           $( "#slider"+index ).slider("option", "disabled", false);
                           showInstructResize(index);

                      });


                  }
               });
    });
    $(".url-close").click(function() {
        $("#images_url").animate({width: '0px'},200).hide();
        $("#mix-area").show(10).animate({width: '550px'},200);
    });
}



function bg_choice_preview(bg_clickd) {
    $("#save").text("please wait..");//replace with ajax loader image
    $("#img-prev").css("opacity",".5");
    $("#ajax-loader3").show();

    $.ajax({
             url: "srvscripts/save_mix.php",
            data: {wi1:width1, hi1:height1, t1:pos1.top, l1: pos1.left, opac1:opacity1,
                wi2:width2, hi2:height2, t2:pos2.top, l2: pos2.left, opac2: opacity2, zx:zindex, bg_index: bg_clickd},
            type: "POST",
            dataType: "text"
            }).done (function(data) {
                     // alert(data);
                        $('#img-prev').attr('src', images_folder+data+"?t="+d.getTime());
                        $("#img-prev").parent().waitForImages(function() {
                        $("#save").text("Save Mix"); //replace with stopping ajax loader image
                        $("#ajax-loader3").hide();
                        $("#img-prev").css("opacity","1");
                        
                      }); 
                }) ;
}



function showInstructResize(img) {
    var position, width, height, instructTop, instructLeft;
    

    
    if (img==1) {
        offset = $("#mix-image1").offset();
        width = $("#mix-image1").css("width");
        height = $("#mix-image1").css("height");
        width = parseInt(width);
        height = parseInt(height);
       instructTop = offset.top + height;
       instructLeft = offset.left + width;

       $("#instructResize").css("top", instructTop).css("left", instructLeft);
       $("#instructResize").fadeIn(800).delay(2800).fadeOut(700);
    }
    else if (img==2) {
        offset = $("#mix-image2").offset();
        width = $("#mix-image2").css("width");
        height = $("#mix-image2").css("height");
        width = parseInt(width);
        height = parseInt(height);
       instructTop = offset.top + height - 47;
       instructLeft = offset.left + width + 2;

       $("#instructResize2").css("top", instructTop).css("left", instructLeft);
       $("#instructResize2").fadeIn(800).delay(2800).fadeOut(700);
       
    }
}

function updateSlider(slideAmount) {
    if (slideAmount>0 && slideAmount<=1){
        $("#mix-image1").css("opacity", slideAmount);
        $("#opacity_value").text(Math.floor(slideAmount*100)+"%");
    }
 }
 
 function updateSlider2(slideAmount) {
    if (slideAmount>0 && slideAmount<=1){
        $("#mix-image2").css("opacity", slideAmount);
        $("#opacity_value2").text(Math.floor(slideAmount*100)+"%");
    }
 }

function showCoords(c) {
  
    x1 = c.x;
    y1 = c.y;
    h1 = c.h;
    w1 = c.w;
    
}

$(document).ready(function(){
  
   $(".mix-image").resizable();
   $(".img-div").draggable({stack: ".img-div"});
   
   $(".img-div").css("top","100px").css("left","100px");
  
      
    $("#pressme").click(function(){
        $(".pops").hide();
        $("#mix-area").show(10).animate({width: '550px'},200);
        $("#img1-selector").show("500");
         
     });

    $("#pressme2").click(function(){
       $(".pops").hide();
       $("#mix-area").show(10).animate({width: '550px'},200);
       $("#img2-selector").show("500");
        
    });

    $("#cropme1").click(function(){
//        jcrop_api.destroy();
        $(".btnCrop").text("please wait..."); 
        $(".pops").hide();
        $("#mix-area").show(10).animate({width: '550px'},200);
        $('#crop-image1').attr('src', images_folder+image1+"?t="+d.getTime());
        $("#crop-image1").parent().waitForImages(function() {
            $(".btnCrop").text("Crop");    
        });
        $("#crop-image1").css("width","250px;");
        $("#img1-cropper").css("height","250px;");
        $("#img1-cropper").show("500");
    
     $('#crop-image1').Jcrop({
            onSelect: showCoords
            
        },function() {
            jcrop_api = this;
        });
     });
 
     $("#cropme2").click(function(){
        $(".btnCrop2").text("please wait..."); 
        $(".pops").hide();
        $("#mix-area").show(10).animate({width: '550px'},100);
        $('#crop-image2').attr('src', images_folder+image2+"?t="+d.getTime());
        $("#crop-image2").parent().waitForImages(function() {
            $(".btnCrop2").text("Crop");    
        });
        $("#img2-cropper").css("width","250px;");
        $("#img2-cropper").css("height","250px;");
        $("#img2-cropper").show("500");
        $('#crop-image2').Jcrop({
            onSelect: showCoords
            
        },function() {
            jcrop_api2 = this;
        });
        
     });
    
    $(".crop-close1").click(function() {
        $(this).parent().parent().fadeOut("200");
        jcrop_api.destroy();
        
    });

    $(".crop-close2").click(function() {
        $(this).parent().parent().fadeOut("200");
        jcrop_api2.destroy();
        
    });

    $(".btnCropReset").click(function() {
      $('#mix-image1').attr('src', images_folder+image1);
      $.ajax({
          url: 'srvscripts/reset-crop.php',
          data: {index:1}
      });
    });

    $(".btnCropReset2").click(function() {
      $('#mix-image2').attr('src', images_folder+image2);
       $.ajax({
          url: 'srvscripts/reset-crop.php',
         data: {index:2}
      });
    });

    $(".close1").click(function() {
        $(this).parent().parent().fadeOut("200");
        
    });



    $(".img-div").click(function() {
         $(".img-div").css("z-index","1");
         $(this).css("z-index","2");
     });
 
 $(function() {
    $( "#slider2" ).slider({
     // orientation: "vertical",
      range: "min",
      min: 0.1,
      max: 1,
      step:0.1,
      disabled: true,
      value: 1,
      slide: function( event, ui ) {
        $( "#opacity_value2" ).text( Math.floor(ui.value*100)+"%" );
        $( "#mix-image2" ).css("opacity", ui.value );
      }
    });
    
  });

$(function() {
    $( "#slider1" ).slider({
      //orientation: "vertical",
      range: "min",
      min: 0.1,
      max: 1,
      step:0.1,
      disabled: true,
      value: 1,
      
      slide: function( event, ui ) {
        $( "#opacity_value1" ).text( Math.floor(ui.value*100)+"%" );
        $( "#mix-image1" ).css("opacity", ui.value );
      }
    });
    
  });


$(".btnCrop").click(function(){
    $(".btnCrop").text("please wait..");
   
    $.ajax({
            url: "srvscripts/crop.php",
            
            data: {xi1:x1, yi1: y1, wi1:w1, hi1:h1, image: image1, index:1},
            dataType: "text"
          }).done (function(data) {
                  
                    $('#mix-image1').attr('src', images_folder+data+"?t="+d.getTime());
                    $("#mix-image1").parent().waitForImages(function() {
                    $(".btnCrop").text("Crop"); 
                    });
                 }
                    
             ) ;
   
  }
);

$(".btnCrop2").click(function(){
    $(".btnCrop2").text("please wait..");
    $.ajax({
             url: "srvscripts/crop.php",
            data: {xi1:x1, yi1: y1, wi1:w1, hi1:h1, image: image2, index:2},
            dataType: "text"
          }).done (function(data) {
                      $('#mix-image2').attr('src', images_folder+data+"?t="+d.getTime());
                      $("#mix-image2").parent().waitForImages(function() {
                           $(".btnCrop2").text("Crop"); 
                       }); 
                    }
             ) ;
     
  }
);
 
 $("#grab").click(function(event) {
     event.preventDefault();
     var img_url = $("#enter_url").attr('value');
     $("#grab").text(" please wait...");
     $("#ajax-loader").show();
     $.ajax({
             url: "srvscripts/get_images.php",
            data: {url:img_url, index: 1},
           
            dataType: "text"
            
          }).done (function(data) {
                   var j = jQuery.parseJSON(data);
                
          
                    if (j.error !== 0) {
                            $("#ajax-loader").hide();
                            $("#grab").text("Load");
                            alert(j.msg);
                       }
              
                   if (j.error === 0) { 
                     if (j.array === 0) {
                       image1 = j.filename;
                       
                       $('#mix-image1').attr('src', images_folder+image1+"?t="+d.getTime());
                       $("#msgLoad1").hide(100);
                  $("#mix-image1").parent().waitForImages(function() {
                       $("#ajax-loader").hide(); 
                       $("#img1-selector").fadeOut(200);
                       $("#grab").text("Load");
                       $("#cropme1").removeAttr('disabled');
                       $( "#slider1" ).slider("option", "disabled", false);
                       showInstructResize(1);
                       
                  });
                       }else if (j.array ===1 ) {
                            $("#images_url").css("overflow","auto");
                            $("#ajax-loader").hide(); 
                            $("#grab").text("Load");
                            load_images_url(j.filename,1);
                         }
                  }
               });
           
    });



$("#grab2").click(function(event) {
    event.preventDefault();
     var img_url = $("#enter_url2").attr('value');
     $("#grab2").text(" please wait...");
     $("#ajax-loader2").show();
     $.ajax({
             url: "srvscripts/get_images.php",
            data: {url:img_url, index: 2},
           
            dataType: "text"
            
          }).done (function(data) {
                var j2 = jQuery.parseJSON(data);
                       
                if (j2.error !== 0) {
                       $("#ajax-loader2").hide();
                        $("#grab2").text("Load");
                       alert(j2.msg);
                }
                       
                if (j2.error === 0) {
                    if (j2.array === 0) {
                       image2 = j2.filename;
                     
                       $('#mix-image2').attr('src', images_folder+image2+"?t="+d.getTime());
                       $("#msgLoad2").hide(100);
                   $("#mix-image2").parent().waitForImages(function() {
                       $("#ajax-loader2").hide();
                       $("#img2-selector").fadeOut(200);
                       $("#grab2").text("Load");
                       $("#cropme2").removeAttr('disabled');
                       $( "#slider2" ).slider("option", "disabled", false);
                       showInstructResize(2); 
                   });
                 }else if (j2.array ===1 ) {
                        $("#images_url").css("overflow","auto");
                        $("#ajax-loader2").hide(); 
                        $("#grab2").text("Load");
                        load_images_url(j2.filename,2);
                                             
                    }
             
             }
            
       }) ;
   
  });

$.ajax({
             url: "srvscripts/scheduled_cleanup.php"
          }).done(function(data){
                  $('#mix-image1').attr('src', images_folder+data+"/image1.jpg");  
                  $('#mix-image2').attr('src', images_folder+data+"/image2.jpg");
            });

 
       // *************************  SAVE ********************* //

$("#save").click(function(){
    $("#save").text("please wait..");
    $(".pops").hide();
    $("#mix-area").show();
    $(".pops").hide();
    //$("#save").attr("disabled","disabled");
   
    width1 = $("#mix-image1").css("width");
    height1 = $("#mix-image1").css("height");
    pos1 = $("#mix-image1").offset();
    pos1.left = pos1.left - 92;
    opacity1 = $("#mix-image1").css("opacity");
    opacity1 =  Math.floor(opacity1*100);
    zindex1 = $("#mix-image1").parent().parent().css("z-index"); 
    
    
    width2 = $("#mix-image2").css("width");
    height2 = $("#mix-image2").css("height");
    pos2 = $("#mix-image2").offset();
    pos2.left = pos2.left - 92;
    opacity2 = $("#mix-image2").css("opacity");
    opacity2 =  Math.floor(opacity2*100);
    zindex2 = $("#mix-image2").parent().parent().css("z-index"); 
    
    
    zindex = 0;
    if (zindex1>zindex2) {zindex = 1;}
    
    //alert(zindex1);

    $.ajax({
             url: "srvscripts/save_mix.php",
            data: {wi1:width1, hi1:height1, t1:pos1.top, l1: pos1.left, opac1:opacity1,
                wi2:width2, hi2:height2, t2:pos2.top, l2: pos2.left, opac2: opacity2, zx:zindex, bg_index: bg_clicked},
            type: "POST",
            dataType: "text"
            }).done (function(data) {
                     // alert(data);
                        $('#img-prev').attr('src', images_folder+data+"?t="+d.getTime());
                        $("#img-prev").parent().waitForImages(function() {
                          $("#save").text("Save Mix"); 
                          $("#mix-area").animate({width: '0px'},200).hide(200);
                          $(".pops").hide();
                          $("#preview").show(10).animate({width: '500px'},200);
                      }); 
                }) ;
     
  });
  
   $(".prev-close").click(function() {
            $(this).parent().parent().animate({width: '0px'},200).hide(10);
            $("#mix-area").show(10).animate({width: '550px'},300);
    
       
    });

$("#savemixes").click(function() {
    alert("Coming soon...");
});

$(".bkg-prev").click(function() {
    bg_clicked = $(".bkg-prev").index(this) + 1;
    
    bg_choice_preview(bg_clicked);
});

$("#enter_url, #enter_url2").focusin(function() {
    $(this).attr("value", "");
    $(this).css("color", "#000");
});

$("#enter_url, #enter_url2").focusout(function() {
    $(this).css("color", "#888");
   
});

});
