(function(c,q){var m="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";c.fn.imagesLoaded=function(f){function n(){var b=c(j),a=c(h);d&&(h.length?d.reject(e,b,a):d.resolve(e));c.isFunction(f)&&f.call(g,e,b,a)}function p(b){k(b.target,"error"===b.type)}function k(b,a){b.src===m||-1!==c.inArray(b,l)||(l.push(b),a?h.push(b):j.push(b),c.data(b,"imagesLoaded",{isBroken:a,src:b.src}),r&&d.notifyWith(c(b),[a,e,c(j),c(h)]),e.length===l.length&&(setTimeout(n),e.unbind(".imagesLoaded",
p)))}var g=this,d=c.isFunction(c.Deferred)?c.Deferred():0,r=c.isFunction(d.notify),e=g.find("img").add(g.filter("img")),l=[],j=[],h=[];c.isPlainObject(f)&&c.each(f,function(b,a){if("callback"===b)f=a;else if(d)d[b](a)});e.length?e.bind("load.imagesLoaded error.imagesLoaded",p).each(function(b,a){var d=a.src,e=c.data(a,"imagesLoaded");if(e&&e.src===d)k(a,e.isBroken);else if(a.complete&&a.naturalWidth!==q)k(a,0===a.naturalWidth||0===a.naturalHeight);else if(a.readyState||a.complete)a.src=m,a.src=d}):
n();return d?d.promise(g):g}})(jQuery);

var x1, y1, h1, w1;
var jcrop_api, jcrop_api2;
d = new Date();
var image1, image2;


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
  
      
    $("#pressme").click(function(){
        $("#img1-selector").show("500");
     });

    $("#pressme2").click(function(){
       $("#img2-selector").show("500");
    });

    $("#cropme").click(function(){
//        jcrop_api.destroy();
        $('#crop-image1').attr('src', "srvscripts/"+image1+"?"+d.getTime());
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
        $('#crop-image2').attr('src', "srvscripts/"+image2+"?"+d.getTime());
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
      $('#mix-image1').attr('src', "srvscripts/"+image1);
    });

    $(".btnCropReset2").click(function() {
      $('#mix-image2').attr('src', "srvscripts/"+image2);
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
            data: {xi1:x1, yi1: y1, wi1:w1, hi1:h1, index: 1},
            dataType: "text"
          }).done (function(data) {
                   
                    $('#mix-image1').attr('src', "srvscripts/"+data+"?"+d.getTime());
                    $("#mix-image1").imagesLoaded(function() {
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
            data: {xi1:x1, yi1: y1, wi1:w1, hi1:h1, index: 2},
            dataType: "text"
          }).done (function(data) {
                      $('#mix-image2').attr('src', "srvscripts/"+data+"?"+d.getTime());
                      $("#mix-image2").imagesLoaded(function() {
                           $(".btnCrop2").text("Crop"); 
                       }); 
                    }
             ) ;
     
  }
);
 
 $("#grab").click(function() {
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
                            alert(j.msg);
                       }
                           
                   if (j.error === 0) {
                       image1 = j.filename;
                       $("#ajax-loader").hide(); 
                       $('#mix-image1').attr('src', "srvscripts/"+image1);
                       $("#msgLoad1").hide(100);
                  $("#mix-image1").imagesLoaded(function() {
                       $("#img1-selector").fadeOut(200);
                       $("#grab").text("Load");
                       $("#cropme").removeAttr('disabled');
                       $( "#slider1" ).slider("option", "disabled", false);
                       showInstructResize(1);
                        });
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
                       alert(j2.msg);
                }
                       
                if (j2.error === 0) {
                       image2 = j2.filename;
                       $("#ajax-loader2").hide();
                       $('#mix-image2').attr('src', "srvscripts/"+image2);
                       $("#msgLoad2").hide(100);
                   $("#mix-image2").imagesLoaded(function() {
                       $("#img2-selector").fadeOut(200);
                       $("#grab2").text("Load");
                       $("#cropme2").removeAttr('disabled');
                       $( "#slider2" ).slider("option", "disabled", false);
                       showInstructResize(2); 
                   });
                 }
            
            }) ;
   
    });

$.ajax({
             url: "srvscripts/scheduled_cleanup.php"
          });

/*
$("#btnExit").click(function() {
     $.ajax({
             url: "srvscripts/cleanup.php"
                        
          }).done(function() {
                    alert("OK!");
                    $(".app-area").hide(1500);
          });
    
});


window.onbeforeunload = function(e) {
    alert("on-beforeunload"); 
    $.ajax({
             url: "srvscripts/cleanup.php"
                        
          }).done(function(data) {
                   alert("OK!");
          });
};
*/

});




