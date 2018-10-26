jQuery(document).ready( function($){ 


// alert(33);

	 /* *
	 @function destroyPopUp()
	  *@description destroys pop up content and closes it
	  *
	  */  
   var uri = $("#base_url").val();
 
 fetch_user_list();
 fetch_category_list();
 fetch_event_list();
 fetch_payment_list();
 fetch_feature_list();

 function showMod(){
   $('.beedy-kaydee-popup').css('left', $(window).width() / 2 - ($('.beedy-kaydee-popup').width() / 2));
	 $('.beedy-kaydee-popup').show();
 
 }
	 function destroyPopUp(){
			$('.popup-content').empty();  
				$('.beedy-kaydee-popup').hide();
	 }
	  
 
       /**
      *@function hide alert box
      *@returns void
       */
     function hideAlertBox(id){
      setInterval( function(){
       $("#"+id).html('');
       }, 10000);
       
     }

/**
 * [hideorShowMore description]
 * @param  {String}  id   [description]
 * @param  {Boolean} bool [description]
 * @return {[type]}       [description]
 */
function hideorShowMore(id='', bool = false)
{

  if(bool == true)
  {
     $("#"+id).css("display", "inline-block");
 $(".split-dropdown").css("display", "inline-block");
  }
  else
  {
    $("#"+id).css("display", "none");
   $(".split-dropdown").css("display", "none");
  }

}
 /*???????????????????????????????ADD OR SHOW FORM ?????????????????????????????????????????*/      
  
  $(document).on('click', '.addNewCategory', function(){
 
   $.ajax({
    url: uri+'category/create', 
       success:function(data)
      {
    
   $('.headerTitle').html("Register");
   $('.popup-content').html(data);
           showMod();
           
   }
    });
   
   });
 
/**
 * Roles
 * @param            
 */
  $(document).on('click', '.addNewEvent', function(){
 
   $.ajax({
    url: uri+'event/create', 
       success:function(data)
      {
    
   $('.headerTitle').html("Create New Event");
   $('.popup-content').html(data);
           showMod();
           
   }
    });
   
   });
 

  $(document).on('click', '.createFund', function(){ 
 var id = $(this).attr("id");
   $.ajax({
    url: uri+'payment/createFund/'+id, 
       success:function(data)
      {
    
   $('.headerTitle').html("Create Fund");
   $('.popup-content').html(data);
           showMod();
           
   }
    });
   
   });
 

  $(document).on('click', '.addNewUser', function(){  
   $.ajax({
    url: uri+'user/create', 
       success:function(data)
      {
    
   $('.headerTitle').html("Create User");
   $('.popup-content').html(data);
           showMod();
           
   }
    });
   
   });
 
  $(document).on('click', '.addAccRecovery', function(){ 
   $.ajax({
    url: uri+'user/recovery', 
       success:function(data)
      {    
       $('.headerTitle').html("Account Recovery");
       $('.popup-content').html(data);
           showMod();
           
   }
    });
   
   });
 
  $(document).on('click', '.updatePassword', function(){ 
   $.ajax({
    url: uri+'user/accPassword', 
       success:function(data)
      {    
       $('.headerTitle').html("Change Password");
       $('.popup-content').html(data);
           showMod();
           
   }
    });
   
   });
 

  


/*?????????????????????????????????????????????????????????????????????????????????????*/


/*???????????????????????????READ OR GET LIST OF ITEMS ????????????????????????????????*/

   
 function fetch_feature_list(){
 $.ajax({
    url:uri + 'feature/list/',
       success:function(data)
      {
    //alert(data);
  $('#featureTable tbody').html(data);
           
   }
    }); 
   
 } 
 function fetch_category_list(){
 $.ajax({
    url:uri + 'category/list/',
       success:function(data)
      {
    //alert(data);
  $('#catTable tbody').html(data);
           
   }
    }); 
   
 } 
 /**
  * [fetch_role_list description]
  * @return {[type]} [users role list]
  */
 function fetch_event_list(){
 $.ajax({
    url:uri + 'event/list/',
       success:function(data)
      { 
  $('#evtTable tbody').html(data);
           
   }
    }); 
   
 }

 function fetch_user_list()
 {
   $.ajax({
    url:uri + 'user/list/',
       success:function(data)
      { 
        $('#userTable tbody').html(data);
           
     }
    }); 
   
 }
 function fetch_payment_list(){
  var evt_id = $("#evt_id").val();
 $.ajax({
    url:uri + 'payment/list/'+evt_id,
       success:function(data)
      {  
        fetch_payment_summary();
    $('#payTable tbody').html(data);
           
   }
    }); 
   
 }
 function fetch_payment_summary(){
  var evt_id = $("#evt_id").val();
 $.ajax({
    url:uri + 'payment/summary/'+evt_id,
       success:function(data)
      {  
  $('#paySummary').html(data);
           
   }
    }); 
   
 }
/*??????????????????????????????PRINT ???????????????????????????????????*/




  $(document).on('click', '.csvAllUser', function(){  
   
   var selected  = new Array();
   // var formdata = $('input[name="userCheck"]:checked').serialize();
   var formdata = $('.userCheckCase:checked').serialize();
   // $('input[name="userCheck"]:checked').each(function()
   // {
   //      selected.push(this.value);
   // })

   // var formdata = selected.serialize();

  $.ajax({
      url:uri + 'user/csv2', 
      type: "POST",
      data: formdata,
      // dataType: 'json',
      success: function(data){

         alert(data);
      }

    });
   // alert("Number of selected user: "+ selected.length+ "\n"+ "And, they are: "+ selected);
    
   
  });

 

  $(document).on('click', '.csvAlSelectedlUser', function(){  
     var formdata = $('.userCheckCase:checked').serialize(); 
  $.ajax({
      url:uri + 'user/csvSelected', 
      type: "POST",
      data: formdata, 
      dataType: 'json'
    }).done(function(data)
    {
     
       var $a = $("<a>");
          $a.attr("href", data.file);
          $("body").append($a);
          $a.attr("download", data.name);
          $a[0].click();
          $a.remove();
       
    })
   
  });


function processSuccess(data)
{ 
  if(data.status =='success')
                     {
                      $('#alert_message_mod').html('<div class="alert alert-success" role="alert">' + data.msg + '</div>');    
                      fetch_user_list(); 
                     }
                     
                     else if(data.status == "error")
                    {
                      $('#alert_message_mod').html('<div class="alert alert-danger"role="alert">' + data.msg + '</div>');
                    
                    }
                    
                    else
                    {
                      $('#alert_message_mod').html('<div class="alert alert-danger"role="alert">' + data.msg + '</div>');
                   
                    }
                     hideAlertBox("alert_message_mod");
}


function Clickheretoprint(data)
{ 
   var printContents = $('body').clone().find('script').remove().end().html();

   //get  all link
   var allLinks = $('head').clone().find('script').remove().end().html();

   var popupWin = window.open('', '_blank');

   popupWin.document.open();


   var keepColors = '<style>body {-webkit-print-color-adjust: exact !important; }</style>';

   popupWin.document.write('<html><head>' + keepColors + allLinks + '</head><body onload="window.print()">' + data  + '</body></html>');

   popupWin.document.close();
   
}

  $(document).on('click', '.printAllUser', function(){   
  $.ajax({
      url:uri + 'user/printAll', 
      type: "POST", 
    }).done(function(data)
    {
     
       Clickheretoprint(data)
       
    })
   
  });

  $(document).on('click', '.printAlSelectedlUser', function(){  
     var formdata = $('.userCheckCase:checked').serialize(); 
   $.ajax({
      url:uri + 'user/printSelected', 
      type: "POST",
      data: formdata, 
    }).done(function(data)
    {
     
       Clickheretoprint(data)
       
    })
   
  });

  $(document).on('click', '.banSelectedUsers', function(){  
     var formdata = $('.userCheckCase:checked').serialize(); 
   $.ajax({
      url:uri + 'user/banSelected', 
      type: "POST",
      dataType: "json", 
      data: formdata, 
    }).done(function(data)
    {
        if(data.status =='success')
                     {
                      $('#alert_message_mod').html('<div class="alert alert-success" role="alert">' + data.msg + '</div>');    
                      fetch_user_list(); 
                     }
                     
                     else if(data.status == "error")
                    {
                      $('#alert_message_mod').html('<div class="alert alert-danger"role="alert">' + data.msg + '</div>');
                    
                    }
                    
                    else
                    {
                      $('#alert_message_mod').html('<div class="alert alert-danger"role="alert">' + data.msg + '</div>');
                   
                    }
                     hideAlertBox("alert_message_mod");
       
    })
   
  });

   $(document).on('click', '.banUser', function(){  
     var id = $(this).attr('id');
   $.ajax({
      url:uri + 'user/ban/'+id, 
      type: "POST",
      dataType: "json", 
    }).done(function(data)
    {
       if(data.status =='success')
        {
            $('#alert_message_mod').html('<div class="alert alert-success" role="alert">' + data.msg + '</div>');    
            fetch_user_list(); 
        }
                     
       else if(data.status == "error")
      {
        $('#alert_message_mod').html('<div class="alert alert-danger"role="alert">' + data.msg + '</div>');
      }
                    
     else
     {
        $('#alert_message_mod').html('<div class="alert alert-danger"role="alert">' + data.msg + '</div>');
                   
    }
     hideAlertBox("alert_message_mod"); 
       
    })
   
  });

   $(document).on('click', '.unBanUser', function(){  
     var id = $(this).attr('id'); 
   $.ajax({
      url:uri + 'user/unBan/'+id, 
      type: "POST",
      dataType: "json", 
    }).done(function(data)
    {
       if(data.status =='success')
        {
            $('#alert_message_mod').html('<div class="alert alert-success" role="alert">' + data.msg + '</div>');    
            fetch_user_list(); 
        }
                     
       else if(data.status == "error")
      {
        $('#alert_message_mod').html('<div class="alert alert-danger"role="alert">' + data.msg + '</div>');
      }
                    
     else
     {
        $('#alert_message_mod').html('<div class="alert alert-danger"role="alert">' + data.msg + '</div>');
                   
    }
     hideAlertBox("alert_message_mod"); 
       
    })
   
  });

/**
 * reporting module function 
 * @param    id 
 * @return new printer's window
 */
  $(document).on('click', '.printAllPayment', function(){  
  var id = $(this).attr("id"); 
  $.ajax({
      url:uri + 'report/printAll/'+id, 
      type: "POST", 
    }).done(function(data)
    {
     
       Clickheretoprint(data)
       
    })
   
  });

  $(document).on('click', '.printAlSelectedlReport', function(){  
     var formdata = $('.reportCheckCase:checked').serialize(); 
   $.ajax({
      url:uri + 'report/printSelected', 
      type: "POST",
      data: formdata, 
    }).done(function(data)
    {
     
       Clickheretoprint(data)
       
    })
   
  });


  $(document).on('click', '.csvAlSelectedlReport', function(){  
     var formdata = $('.reportCheckCase:checked').serialize(); 
  $.ajax({
      url:uri + 'report/csvSelected', 
      type: "POST",
      data: formdata, 
      dataType: 'json'
    }).done(function(data)
    {
     
       var $a = $("<a>");
          $a.attr("href", data.file);
          $("body").append($a);
          $a.attr("download", data.name);
          $a[0].click();
          $a.remove();
       
    })
   
  });

 
 


/*?????????????????????????????? ???????????????????????????????????*/




/*??????????????????????????????SAVE OR ADD NEW RECORD ???????????????????????????????????*/
/**
 * [storeCategory]
 * @param  {[type]} ){    
 * @param  {[type]} success:function(data)       
 * @return {[type]}                           
 */
  $(document).on('submit','.storeCategory', function(evt){
     
evt.preventDefault();
var formdata = $(this).serialize();  
        $.ajax({
            url:uri + 'category/store',
            type: "POST", 
             dataType: "json", 
             data:formdata,  
             success:function(data){

                          // alert(data);
                     if(data.status =='success')
                     {
                      $('#alert_message_mod').html('<div class="alert alert-success" role="alert">' + data.msg + '</div>');    
                      fetch_category_list();
                      destroyPopUp();
                     }
                     
                     else if(data.status == "error")
                    {
                      $('#alert_message_mod').html('<div class="alert alert-danger"role="alert">' + data.msg + '</div>');
                    
                    }
                    
                    else
                    {
                      $('#alert_message_mod').html('<div class="alert alert-danger"role="alert">' + data.msg + '</div>');
                   
                    }
                     hideAlertBox("alert_message_mod");
       
             }


          }); 
       
      });



$(document).on('submit','.storeEvent', function(evt)
{
 
  evt.preventDefault();
  var formdata = $(this).serialize();  
           $.ajax({
            url:uri + 'event/store',
            type: "POST", 
             dataType: "json", 
             data:formdata,  
             success:function(data)
             {
 
                     if(data.status =='success')
                     {
                      $('#alert_message_mod').html('<div class="alert alert-success" role="alert">' + data.msg + '</div>');    
                      fetch_event_list();
                      destroyPopUp();
                     }
                     
                     else if(data.status == "error")
                    {
                      $('#alert_message_mod').html('<div class="alert alert-danger"role="alert">' + data.msg + '</div>');
                    
                    }
                    
                    else
                    {
                      $('#alert_message_mod').html('<div class="alert alert-danger"role="alert">' + data.msg + '</div>');
                   
                    }
              hideAlertBox("alert_message_mod");
             }


          }); 
});



$(document).on('submit','.storeUser', function(evt)
{
 
  evt.preventDefault();
  var formdata = $(this).serialize();  
           $.ajax({
            url:uri + 'user/store',
            type: "POST", 
             dataType: "json", 
             data:formdata,  
             success:function(data)
             {
 
                     if(data.status =='success')
                     {
                      $('#alert_message_mod').html('<div class="alert alert-success" role="alert">' + data.msg + '</div>');    
                      fetch_user_list();
                      destroyPopUp();
                     }
                     
                     else if(data.status == "error")
                    {
                      $('#alert_message_mod').html('<div class="alert alert-danger"role="alert">' + data.msg + '</div>');
                    
                    }
                    
                    else
                    {
                      $('#alert_message_mod').html('<div class="alert alert-danger"role="alert">' + data.msg + '</div>');
                   
                    }
              hideAlertBox("alert_message_mod");
             }


          }); 
});


$(document).on('submit','.payFund', function(evt)
{ 
  evt.preventDefault();
  var formdata = $(this).serialize();  
           $.ajax({
            url:uri + 'payment/payFund',
            type: "POST", 
             dataType: "json", 
             data:formdata,  
             success:function(data)
             {
 
                     if(data.status =='success')
                     {
                      $('#alert_message_mod').html('<div class="alert alert-success" role="alert">' + data.msg + '</div>');    
                      fetch_payment_list();
                      destroyPopUp();
                     }
                     
                     else if(data.status == "error")
                    {
                      $('#alert_message_mod').html('<div class="alert alert-danger"role="alert">' + data.msg + '</div>');
                    
                    }
                    
                    else
                    {
                      $('#alert_message_mod').html('<div class="alert alert-danger"role="alert">' + data.msg + '</div>');
                   
                    }
              hideAlertBox("alert_message_mod");
             }


          }); 
});


$(document).on('submit','.storeRecovery', function(evt)
{ 
  evt.preventDefault();
  var formdata = $(this).serialize();  
           $.ajax({
            url:uri + 'user/saveRecovery',
            type: "POST", 
             dataType: "json", 
             data:formdata,  
             success:function(data)
             {
 
                     if(data.status =='success')
                     {
                      $('#alert_message_mod').html('<div class="alert alert-success" role="alert">' + data.msg + '</div>');    
                      $('.notice').hide();    
                        destroyPopUp();
                     }
                     
                     else if(data.status == "error")
                    {
                      $('#alert_message_mod').html('<div class="alert alert-danger"role="alert">' + data.msg + '</div>');
                    
                    }
                    
                    else
                    {
                      destroyPopUp();
                   
                    }
              hideAlertBox("alert_message_mod");
             }


          }); 
});


$(document).on('submit','.storeUpdatePassword', function(evt)
{ 
  evt.preventDefault();
  var formdata = $(this).serialize();  
           $.ajax({
            url:uri + 'user/updatePassword',
            type: "POST", 
             dataType: "json", 
             data:formdata,  
             success:function(data)
             {
 
                     if(data.status =='success')
                     {
                      $('#alert_message_mod').html('<div class="alert alert-success" role="alert">' + data.msg + '</div>');    
                      $('.notice').hide();    
                        destroyPopUp();
                     }
                     
                     else if(data.status == "error")
                    {
                      $('#alert_message_mod').html('<div class="alert alert-danger"role="alert">' + data.msg + '</div>');
                    
                    }
                    
                    else
                    {
                      destroyPopUp();
                   
                    }
              hideAlertBox("alert_message_mod");
             }


          }); 
});



/*?????????????????????????????????????PAGINATION????????????????????????????????????????????????*/


  $(document).on('click','.CategoryListPagin', function(){
       var page = $(this).attr('id'); 
        $.ajax({
      url:uri + 'category/list/page/'+page,
    type: 'POST',
   
       success:function(data)
      {
    // alert(data);
  $('#catTable tbody').html(data);
           
   }
    }); 
       
      });


  $(document).on('click','.EventListPagin', function(){
       var page = $(this).attr('id'); 
        $.ajax({
      url:uri + 'event/list/page/'+page,
    type: 'POST',
   
       success:function(data)
      {
    // alert(data);
  $('#evtTable tbody').html(data);
           
   }
    }); 
       
      });


  $(document).on('click','.PaymentListPagin', function(){

       var evt_id = $("#evt_id").val();
       var page = $(this).attr('id');  
        $.ajax({
      url: uri + 'payment/list/'+evt_id+'/page/'+page,
    type: 'POST',
   
       success:function(data)
      {
    // alert(data);
  $('#payTable tbody').html(data);
           
   }
    }); 
       
      });


  $(document).on('click','.UserListPagin', function(){
 
       var page = $(this).attr('id');  
        $.ajax({
      url: uri + 'user/list/page/'+page,
    type: 'POST',
   
       success:function(data)
      {
    // alert(data);
  $('#userTable tbody').html(data);
           
   }
    }); 
       
      });



     /*?????????????????????????????????????????????????????????????????????????????????????*/



     /*?????????????????????????????MODIFICATION LOG????????????????????????????????????????*/  
  
  $(document).on('click', '.modCategory', function(){
 var id = $(this).attr("id");
   $.ajax({
    url: uri+'category/edit/'+id, 
       success:function(data)
      { 
   $('.headerTitle').html("Modify");
   $('.popup-content').html(data);
           showMod();
           
   }
    });
   
   });
  
$(document).on('click', '.modEvent', function(){
 var id = $(this).attr("id");
 var part = $(this).attr("part");
   $.ajax({
    url: uri+'event/edit/'+id+'/'+part, 
       success:function(data)
      { 
   $('.headerTitle').html("Modify Event");
   $('.popup-content').html(data);
           showMod();
           
   }
    });
   
   });


$(document).on('click', '.modPayment', function(){
 var id = $(this).attr("id"); 
   $.ajax({
    url: uri+'payment/edit/'+id, 
       success:function(data)
      { 
   $('.headerTitle').html("Modify Payment");
   $('.popup-content').html(data);
           showMod();
           
   }
    });
   
   });

$(document).on('click', '.modUser', function(){
 var id = $(this).attr("id"); 
   $.ajax({
    url: uri+'user/edit/'+id, 
       success:function(data)
      { 
   $('.headerTitle').html("Modify User");
   $('.popup-content').html(data);
           showMod();
           
   }
    });
   
   });

$(document).on('change', '#reportCat', function(){
 var id = $(this).val(); 
 if(id == '') 
 {
  alert("Please select a category");
  return false;
 }
   $.ajax({
    url: uri+'report/fetchEvent/'+id, 
       success:function(data)
      {  
          $('#allEventList').html(data); 
           
      }
    });
   
   });

$(document).on('change', '#getReportFromEvt', function(){
 var id = $(this).val(); 
 if(id == '') 
 {
  alert("Please select an Event");
  return false;
 }
   $.ajax({
    url: uri+'report/getReportFromEvt/'+id, 
       success:function(data)
      {  
          $('.reporting').html(data); 
           
      }
    });
   
   });

     /*?????????????????????????????????????????????????????????????????????????????????????*/
     /*????????????????????????????UPDATE MODIFIED FIELDS???????????????????????????????????*/
 
/**
 * [update category]
 * @param  {[type]} evt){    
 * @return {[json result]}        [error or success message]
 */
  $(document).on('submit','.updateCategory', function(evt){ 
evt.preventDefault();
var formdata = $(this).serialize();  
        $.ajax({
            url:uri + 'category/update',
            type: "POST", 
             dataType: "json", 
             data:formdata,  
             success:function(data){
 
                     if(data.status =='success')
                     {
                      $('#alert_message_mod').html('<div class="alert alert-success" role="alert">' + data.msg + '</div>');    
                      fetch_category_list();
                      destroyPopUp();
                     }
                     
                     else if(data.status == "error")
                    {
                      $('#alert_message_mod').html('<div class="alert alert-danger"role="alert">' + data.msg + '</div>');
                    
                    }
                    
                    else
                    {
                      $('#alert_message_mod').html('<div class="alert alert-danger"role="alert">' + data.msg + '</div>');
                   
                    }
                     hideAlertBox("alert_message_mod");
       
             }


          }); 
       
      }); 

  $(document).on('submit','.updateEvent', function(evt){ 
evt.preventDefault(); 
var formdata = $(this).serialize();   
        $.ajax({
            url:uri + 'event/update',
            type: "POST", 
             dataType: "json", 
             data:formdata,  
             success:function(data){
 
                     if(data.status =='success')
                     {
                      $('#alert_message_mod').html('<div class="alert alert-success" role="alert">' + data.msg + '</div>');    
                      fetch_event_list();
                      destroyPopUp();
                     }
                     
                     else if(data.status == "redirect")
                    {
                 
                      location.href=""+data.msg;
                    
                    }
                    
                    else if(data.status == "error")
                    {
                      $('#alert_message_mod').html('<div class="alert alert-danger"role="alert">' + data.msg + '</div>');
                    
                    }
                    
                    else
                    {
                     
                      destroyPopUp();
                   
                    }
                     hideAlertBox("alert_message_mod");
       
             }


          }); 
       
      });


  $(document).on('submit','.updatePayment', function(evt){ 
evt.preventDefault(); 
var formdata = $(this).serialize();   
        $.ajax({
            url:uri + 'payment/update',
            type: "POST", 
             dataType: "json", 
             data:formdata,  
             success:function(data){
 
                     if(data.status =='success')
                     {
                      $('#alert_message_mod').html('<div class="alert alert-success" role="alert">' + data.msg + '</div>');    
                      fetch_payment_list();
                      destroyPopUp();
                     }                     
                    else if(data.status == "error")
                    {
                      $('#alert_message_mod').html('<div class="alert alert-danger"role="alert">' + data.msg + '</div>');
                    
                    }
                    
                    else
                    {
                     
                      destroyPopUp();
                   
                    }
                     hideAlertBox("alert_message_mod");
       
             }


          }); 
       
      });

  $(document).on('submit','.updateUser', function(evt){ 
evt.preventDefault(); 
var formdata = $(this).serialize();   
        $.ajax({
            url:uri + 'user/update',
            type: "POST", 
             dataType: "json", 
             data:formdata,  
             success:function(data){
 
                     if(data.status =='success')
                     {
                      $('#alert_message_mod').html('<div class="alert alert-success" role="alert">' + data.msg + '</div>');    
                      fetch_user_list();
                      destroyPopUp();
                     }                     
                    else if(data.status == "error")
                    {
                      $('#alert_message_mod').html('<div class="alert alert-danger"role="alert">' + data.msg + '</div>');
                    
                    }
                    
                    else
                    {
                     
                      destroyPopUp();
                   
                    }
                     hideAlertBox("alert_message_mod");
       
             }


          }); 
       
      });
  $(document).on('submit','.updateCompany', function(evt){ 
evt.preventDefault(); 
var formdata = $(this).serialize();   
        $.ajax({
            url:uri + 'company/update',
            type: "POST", 
             dataType: "json", 
             data:formdata,  
             success:function(data){
 
                     if(data.status =='success')
                     {
                      $('#alert_message_mod').html('<div class="alert alert-success" role="alert">' + data.msg + '</div>');    
                       
                     }                     
                    else if(data.status == "error")
                    {
                      $('#alert_message_mod').html('<div class="alert alert-danger"role="alert">' + data.msg + '</div>');
                    
                    }
                    
                    else
                    {
                      
                   
                    }
                     hideAlertBox("alert_message_mod");
       
             }


          }); 
       
      });

/*?????????????????????????????????????????????????????????????????????????????????????*/

/*?????????????????????????????????DELETE ITEMS ????????????????????????????????????????????*/
//delete category
 $(document).on( "click", ".delCategory", function()
 {
     var id = $(this).attr("id"); 
       var url = uri + 'category/destroy/'+id;  
       if(confirm("Are you sure you want to delete this category?"))
       {
        
        $.ajax({
       url: url,
       type: "POST", 
       success: function(data){  
       $('#alert_message_mod').html('<div class="alert alert-success" role="alert">' + data + '</div>');
         fetch_category_list();
         hideAlertBox("alert_message_mod");
       
       }  
       
       });
       } 
       else
       {
        return false;
       } 

 });
 /*
       
  $(document).on( "click", ".delEvent", function()
 {
     var id = $(this).attr("id"); 
       var url = uri + 'event/destroy/'+id;  
       if(confirm("Are you sure you want to delete this event?"))
       {
        
        $.ajax({
       url: url,
       type: "POST", 
       success: function(data){  
       $('#alert_message_mod').html('<div class="alert alert-success" role="alert">' + data + '</div>');
         fetch_event_list();
         hideAlertBox("alert_message_mod");
       
       }  
       
       });
       } 
       else
       {
        return false;
       } 

 });
 */
       
       
    


/*


  $(document).on('click','.userPagin', function(){
       var page = $(this).attr('id');
        $.ajax({
    url:uri + 'user/getIt/',
    type: 'POST',
    data:{page:page},
       success:function(data)
      {
    //alert(data);
  $('#userTable tbody').html(data);
           
   }
    }); 
       
      });


 */













//category side

$( document ).on( "click", "#categoryAll", function( e ) {  
  $('.catCase').prop('checked', this.checked);
  } );
   

$( document ).on( "click", ".catCase", function( e ) {  
        if($(".catCase").length == $(".catCase:checked").length) {
     // swal('equal');
            $("#categoryAll").prop("checked", "checked");
        } else {
            $("#categoryAll").removeAttr("checked");
        }

    });


//event

$( document ).on( "click", "#eventAll", function( e ) {  
  $('.eventCase').prop('checked', this.checked);
  } );
   

$( document ).on( "click", ".eventCase", function( e ) { 
        if($(".eventCase").length == $(".eventCase:checked").length) {
     // swal('equal');
            $("#eventAll").prop("checked", "checked");
        } else {
            $("#eventAll").removeAttr("checked");
        }

    });


//Payment
$( document ).on( "click", "#paymentAll", function( e ) {  
  $('.paymentCase').prop('checked', this.checked);
  } );
   

$( document ).on( "click", ".paymentCase", function( e ) { 
        if($(".paymentCase").length == $(".paymentCase:checked").length) {
     // swal('equal');
            $("#paymentAll").prop("checked", "checked");
        } else {
            $("#paymentAll").removeAttr("checked");
        }

    });


 


//user
$( document ).on( "click", "#userAll", function( e ) {  
  $('.userCase').prop('checked', this.checked);
  } );
   

$( document ).on( "click", ".userCase", function( e ) { 
        if($(".userCase").length == $(".userCase:checked").length) {
     // swal('equal');
            $("#userAll").prop("checked", "checked");
        } else {
            $("#userAll").removeAttr("checked");
        }

    });




//role
$( document ).on( "click", "#roleAll", function( e ) {  
  $('.roleCase').prop('checked', this.checked);
  } );
   

$( document ).on( "click", ".roleCase", function( e ) { 
        if($(".roleCase").length == $(".roleCase:checked").length) {
     // swal('equal');
            $("#roleAll").prop("checked", "checked");
        } else {
            $("#roleAll").removeAttr("checked");
        }

    });



//role
$( document ).on( "click", "#otherAll", function( e ) {  
  $('.otherCase').prop('checked', this.checked);
  } );
   

$( document ).on( "click", ".otherCase", function( e ) { 
        if($(".otherCase").length == $(".otherCase:checked").length) {
     // swal('equal');
            $("#otherAll").prop("checked", "checked");
        } else {
            $("#otherAll").removeAttr("checked");
        }

    });









//select users
$( document ).on( "click", "#userCheckAll", function( e ) {  
  $('.userCheckCase').prop('checked', this.checked); 

if($("#userCheckAll").prop("checked"))
{
  hideorShowMore('userMore', true);
}
else
{
   hideorShowMore('userMore');
}
 

  } );
   

$( document ).on( "click", ".userCheckCase", function( e ) { 
        if($(".userCheckCase").length == $(".userCheckCase:checked").length) 
        {  
            $("#userCheckAll").prop("checked", "checked");

              hideorShowMore('userMore', true);
        }
         else
          { 
             if($(".userCheckCase:checked").length < 1) 
            {  
              hideorShowMore('userMore');
          }
          else
          {

            hideorShowMore('userMore', true);
          }
             
            $("#userCheckAll").prop("checked", false); 
          }

    });
 

 



//select users
$( document ).on( "click", "#reportCheckAll", function( e ) {  
  $('.reportCheckCase').prop('checked', this.checked); 

if($("#reportCheckAll").prop("checked"))
{
  hideorShowMore('reportMore', true);
}
else
{
   hideorShowMore('reportMore');
}
 

  } );
   

$( document ).on( "click", ".reportCheckCase", function( e ) { 
        if($(".reportCheckCase").length == $(".reportCheckCase:checked").length) 
        {  
            $("#reportCheckAll").prop("checked", "checked");

              hideorShowMore('reportMore', true);
        }
         else
          { 
             if($(".reportCheckCase:checked").length < 1) 
            {  
              hideorShowMore('reportMore');
          }
          else
          {

            hideorShowMore('reportMore', true);
          }
             
            $("#reportCheckAll").prop("checked", false); 
          }

    });






 






  //ends here

})