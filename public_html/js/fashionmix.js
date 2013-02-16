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
    
    $.ajax({
             url: "srvscripts/crop.php",
            data: {xi1:x1, yi1: y1, wi1:w1, hi1:h1, index: 1},
            dataType: "text"
          }).done (function(data) {
                   
                      $('#mix-image1').attr('src', "srvscripts/"+data+"?"+d.getTime());
                          }
             ) ;
     
  }
);

$(".btnCrop2").click(function(){
    
    $.ajax({
             url: "srvscripts/crop.php",
            data: {xi1:x1, yi1: y1, wi1:w1, hi1:h1, index: 2},
            dataType: "text"
          }).done (function(data) {
                      $('#mix-image2').attr('src', "srvscripts/"+data+"?"+d.getTime());
                          }
             ) ;
     
  }
);
 
 $("#grab").click(function() {
     var img_url = $("#enter_url").attr('value');
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
                       $("#img1-selector").fadeOut(200);
                       $("#cropme").removeAttr('disabled');
                       $( "#slider1" ).slider("option", "disabled", false);
                       showInstructResize(1);
                   }
            }) ;
   
    });

$("#grab2").click(function(event) {
    event.preventDefault();
     var img_url = $("#enter_url2").attr('value');
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
                       $("#img2-selector").fadeOut(200);
                       $("#cropme2").removeAttr('disabled');
                       $( "#slider2" ).slider("option", "disabled", false);
                       showInstructResize(2);
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

