jQuery(document).ready( function($){ 

  
 $("#login-form").submit( function(e) { loginProcess(e);  });
 $("#saveEvent").submit( function(e) { saveEvent(e);  }); 
 $("#payNow").submit( function(e) { payNow(e);  }); 
 $("#edit").change( function(e) { getHallRecord(e);  });  
 $("#seatTable").change( function(e) { getseatTable(e);  HallList(e);  }); 
 //alert('hi'); alert('hi'); 
 
  /**
       *
       *@param {String} uniform resource identifier
       *@returns nothing
       *
       */ 
       var uri = $("#url").val(); 
       //  swal(uri);
       
       
  fetch_user_data(); 
	 fetch_event_list();
   viewEvent();
	 /**
	  *@function destroyPopUp()
	  *@description destroys pop up content and closes it
	  *
	  */  
 
 function showMod(){
   $('.beedy-kaydee-popup').css('left', $(window).width() / 2 - ($('.beedy-kaydee-popup').width() / 2));
	 $('.beedy-kaydee-popup').show();
 
 }
	 function destroyPopUp(){
			$('.beedy-kaydee-popup-content').empty();  
				$('.beedy-kaydee-popup').hide();
	 }
	  
  
  $(document).on('click', '.addNewEvent', function(){
   
   $.ajax({
    url:uri + 'event/create/',
    	 success:function(data)
			{
 
  								 $('.beedy-kaydee-popup-content').html(data);
           showMod();
           
   }
    });
   
   });
  
  function saveEvent(evt){
var _this = $(evt.target);
evt.preventDefault();
var formdata = $(_this).serialize(); 
   
   $.ajax({
       url:uri + 'event/add/',
       type: "POST",
    	data: formdata,
      success:function(data)
			{
    //alert(data);
  
    if(data == 1){
     alert("Event added successfully");
           destroyPopUp();
           fetch_event_list();
    }
    if(data == 2){
     alert("This event name already exist under the given category!!... Please try another name");
        
    }
    else{
    alert("Error adding new event"); 
    }
    
   }
       
   });
  }
	 
   
	 
 function fetch_event_list(){
 $.ajax({
    url:uri + 'event/eventList/',
    	 success:function(data)
			{
    //alert(data);
  $('#eventTable tbody').html(data);
           
   }
    }); 
	 
 }
  $(document).on('click', '.eventPagin', function(){
	 
		var page = $(this).attr('id');
        $.ajax({
    url:uri + 'event/eventList/',
    type: 'POST',
    data:{page:page}, 
			 success:function(data)
			{  
			 
			  //alert(data);
  $('#eventTable tbody').html(data);
				 
			 
				}
		});
	});
   
    
       $( document ).on( "click", ".delEvent", function(  ) {
         var evt_id = $(this).attr("id");
       
      var url = uri + 'event/delete/';  
       if(confirm("Are you sure you want to delete this event?"))
       {
       $.ajax({
       url: url,
       method: "POST",
       data:{evt_id:evt_id},
       success: function(data){  
       $('#alert_message_mod').html('<div class="alert alert-success" role="alert">' + data + '</div>');
    
        $("."+evt_id).hide();
       
       } 
       
       });
       } else 
       return false;
       
       } ); 
   
 
  $(document).on('click', '.createFund', function(){
   
   $.ajax({
    url:uri + 'event/createFund/',
    	 success:function(data)
			{
 
  								 $('.beedy-kaydee-popup-content').html(data);
           showMod();
           
   }
    });
   
   });
	 //report based on staff performance
 

$(document).on('change', '#eftype', function(){
 
	var etype  = $(this).val();
 if(etype == " "){
  alert("Event type can not be empty");
 }
 else{
  
 $.ajax({
	url:uri + 'event/allEvent/',
	type: 'POST',
	data: {type:etype},
	success: function( result ){
  //alert(result);
	  $("#allEventList").html(result);
  
 }
 
 });
 }
	
	  
	
	});
	 
  function viewEvent(){ 
 
 $.ajax({
	url:uri + 'event/fundProgress/', 
	success: function( result){
  //alert(result);
	   
 	 $('#progressTable tbody').html(result);
   
  
 }
 
  
	
	});
 
  }
	 
   
  function payNow(evt){
var _this = $(evt.target);
evt.preventDefault();
var formdata = $(_this).serialize(); 
   
   $.ajax({
       url:uri + 'event/pay/',
       type: "POST",
    	data: formdata,
      success:function(data)
			{
     //alert(data);
  
    if(data == 1){
     alert("Money paid successfully");
           destroyPopUp();
           viewEvent();
    }
     
    else{
    alert("Error in performing this transaction!!! Please check your form fields and try again"); 
    }
    
   }
       
   });
  }
	 
  
	//cancelCartMeal
		   /**
	    *@function hide alert box
	    *@returns void
	    */
	   function hideAlertBox(id){
		  setInterval( function(){
       $("#"+id).html('');
       }, 7000);
       
	   }
    
 /**
       *@function isFieldEmp
       *@param {String} caller (id or class)
       *@description checks if a field is empty ? true : false
       */
       function isFieldEmp(caller) {
       if (caller.text() == '') {
       caller.css('border', '1px solid red');
       return false;
       } else
       caller.css('border', '');
       
       return true;
       }
       
    
	    
function loginProcess(evt){ 
var _this = $(evt.target);
evt.preventDefault();  
var formdata = $(_this).serialize();
$(_this).find(':input').attr('disabled',true);
$(_this).find(':button').attr('disabled',false);
$(_this).find(':button').html('Loading..');
  
$.ajax({
	url:uri + 'user/login/',
	type: 'POST',
	data: formdata,
	success: function( result ){
	 //alert(result);
		if(result == 1) {
			$("#login-bottom").removeClass('hide').addClass('alert-success').html('<p>Logging in......</p>');
	 location.href=uri + 'user/list/';
			
	 
		 }
		 	if(result == 4) {
					$("#login-bottom").removeClass('hide').addClass('alert-danger').html('<p>This account is inactive..</p>');
	
	 
		 }
		 else{
			$("#login-bottom").removeClass('hide').addClass('alert-danger').html('<p>No user is registered using this credentials.</p>');
			$(_this).find(':input').attr('disabled',false);
			$(_this).find(':button').attr('disabled',false);
			$(_this).find(':button').html('<i class="icon-signin icon-large"></i>Login');
		}
	}
});
 
return this;
}
 
  function fetch_user_data(){  
       
   $.ajax({
    url:uri + 'user/getIt/',
    	 success:function(data)
			{
    //alert(data);
  $('#userTable tbody').html(data);
           
   }
    }); 
       
       }
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
       /**
       * @event addNewClassGrp
       *@description add new contenteditable to classGrpTable 
       *
       */
       
       $('#addNewUser').click(function (){  
       var html = '<tr>';
       html += '<td contenteditable id="data1"></td>';  
        html += '<td contenteditable id="data2"></td>';  
        html += '<td contenteditable id="data3"></td>';  
        html += '<td contenteditable id="data4"></td>';  
        html += '<td contenteditable id="data5"></td>';  
        html += '<td ></td>';  
        html += '<td ></td>';  
     html += '<td><button type="button" name="insert" id="insertUser" class="btn btn-success btn-xs">Insert</button></td>';
       html += '</tr>'; 
       $('#userTable tbody').prepend(html);
       });
        
       /**
       *@event insertClassGrp on click
       *@description send to database a new class group
       *@returns gets all data in json format
       */
       
       $( document ).on( "click", "#insertUser", function() {   
       var url = uri + 'user/save/';  
       var fname = $("#data1");  
       var lname = $("#data2");   
       var email = $("#data3");  
       var phone = $("#data4");  
       var password = $("#data5"); 
       if (isFieldEmp(fname) && isFieldEmp(email) && isFieldEmp(email) && isFieldEmp(password) ) {
       $.ajax({
       url:url,
       method: "POST", 
       dataType: "json",
       cache: false,
       data:{fname:fname.text(),lname:lname.text() ,phone:phone.text() ,password:password.text() ,email:email.text()  },
       success: function(data){  
       if(data.status =='success'){
       $('#alert_message_mod').html('<div class="alert alert-success" role="alert">' + data.msg + '</div>');
      // $("#userTable").DataTable().destroy(); 
          fetch_user_data();
       }
       else if(data.status == "error"){
       $('#alert_message_mod').html('<div class="alert alert-danger"role="alert">' + data.msg + '</div>');
       fname.focus();
       }
       
       else{
       $('#alert_message_mod').html('<div class="alert alert-danger"role="alert">' + data.msg + '</div>');
       
       }
       
       
       } 
       });
       hideAlertBox("alert_message_mod");
       
       
       }
       else {
       
       $("#alert_message_mod").html('<div class="alert alert-warning" >All fields are required</div>');
       //swal("All fields are required");
       }
       
       });
       
       
       
       
       $( document ).on( "blur", ".modifyUser", function() { 
       var acc_id = $(this).data("id");
       var column_name = $(this).data("column");
       var value = $(this).text();
       
       var url = uri + 'user/edit/';  
       $.ajax({
       url: url,
       method: "POST",
       data:{acc_id:acc_id, column_name:column_name, value:value},
       success: function(data){  
       $('#alert_message_mod').html('<div class="alert alert-success" role="alert">' + data + '</div>');
       $("#userTable").DataTable().destroy(); 
       fetch_user_data();
       
       } 
       
       });
       
       } ); 
       
          
       
       $( document ).on( "click", ".delUser", function(  ) { 
       var acc_id = $(this).attr("id"); 
       var url = uri + 'user/delete/';  
       if(confirm("Are you sure you want to delete this user?"))
       {
       $.ajax({
       url: url,
       method: "POST",
       data:{acc_id:acc_id},
       success: function(data){  
       $('#alert_message_mod').html('<div class="alert alert-success" role="alert">' + data + '</div>');
       $("#userTable").DataTable().destroy(); 
       fetch_user_data();
       
       } 
       
       });
       } else 
       return false;
       
       } ); 
   
   

$(document).on('change', '#reftype', function(){
 
	var etype  = $(this).val();
 if(etype == " "){
  alert("Event type can not be empty");
 }
 else{
  
 $.ajax({
	url:uri + 'report/allEvent/',
	type: 'POST',
	data: {type:etype},
	success: function( result ){
  //alert(result);
	  $("#allEventList").html(result);
  
 }
 
 });
 }
	
	  
	
	});
	 
  
$(document).on('change', '#revt_id', function(){
 
	var evt_id  = $(this).val();
 if(evt_id == " "){
  alert("Event  can not be empty");
 }
 else{
  
 $.ajax({
	url:uri + 'report/fetchReport/',
	type: 'POST',
	data: {evt_id:evt_id},
	success: function( result ){
  $("#reportable tbody").html(result);
  
 }
 
 });
 }
	
	  
	
	});
	 
  
  
  //ends

 });
