function alertToastr(msg) {
  if(msg=='success') {
    toastr.success("ดึงข้อมูลส่วนบุคคลเสร็จสิ้น","หน้าต่างแจ้งเตือน"); 
  }else if(msg=='warning') {
    toastr.warning("ไม่พบข้อมูลส่วนบุคคล","หน้าต่างแจ้งเตือน");
  }else if(msg=='error') {
    toastr.error("ดึงข้อมูลส่วนบุคคลล้มเหลว","หน้าต่างแจ้งเตือน");
  }

}

//url: base_url+'personals/getPersonalInfo', //demo for test
function getPersInfo(inputpid,bt_spid,setData) { //Function get Personal Info
    $.ajax({
    //url: 'https://gateway.dop.go.th/personals/getPersonalInfo', //demo for test
    url: 'https://gateway.dop.go.th/transfer/import/getPersonalInfo', 
    type: 'POST',
    dataType: 'json',
    data: {
        'pid': pers_authen.pid,
        'cid': pers_authen.cid,
        'random_string': pers_authen.random_string,
        'target_pid': $(inputpid).val(), 
        'user_id': user_id,
        'org_id': org_id,
        'csrf_dop': csrf_hash
    },
      success: function (value) { //Result True
        //console.log("success");console.dir(value);
        if(Object.keys(value).length) {
          console.log(value);
          alertToastr('success');
          $(inputpid).select();$(inputpid).parent().removeClass("has-success has-warning has-error");$(inputpid).parent().addClass("has-success");
          $(bt_spid).attr('disabled',false);         
          window[setData](value);
        }else { //Result no Data
          alertToastr('warning');
          $(inputpid).select();$(inputpid).parent().removeClass("has-success has-warning has-error");$(inputpid).parent().addClass("has-warning");
          setTimeout(function(){$(bt_spid).attr('disabled',false);},500);
        }
      },
      error:function() { //Result Error
        alertToastr('error');
        $(inputpid).select();$(inputpid).parent().removeClass("has-success has-warning has-error");$(inputpid).parent().addClass("has-error");
        setTimeout(function(){$(bt_spid).attr('disabled',false);},500);
        //console.log("error");
      },
    });
}

function run_readerPers(){
  reader_status = true;
  //location.href='webrun:C:\\readerid\\ThaiNationalIDCard\\bin\\Debug\\ThaiNationalIDCard_Demo.exe'; //demo for test
  location.href='webrun:C:\\readerid\\ThaiNationalIDCard\\bin\\Debug\\ThaiNationalIDCard.exe';
  var str = '';
  $(document).keyup(function (e) {
    if(reader_status){
      //console.log(String.fromCharCode(e.which));
      str += String.fromCharCode(e.which);
      //console.log(str);  
      if( str.length>=8 ){
        console.log(str);
        reader_status = false;
        setLogWSRV(str);
        str = '';
      }
    }
  }); 
}

$(document).keyup(function (e) { //Check return uniq key
  //console.log(e.which);
  if(e.which==120 && authen_log_id!=0) {
    console.log(authen_log_id);
    
    var URL = base_url+'personals/setSessionWSRV';
     $.ajax({
         url: URL,
         type: "POST",
         dataType: 'json',
         data: {'authen_log_id':authen_log_id,'csrf_dop': csrf_hash},   
         success: function (res) {
             if(!res.authen_log_id){
                toastr.error("เชื่อมโยงกับเครื่องอ่านบัตรล้มเหลว!","หน้าต่างแจ้งเตือน"); 
             }else {
                toastr.success("เข้าใช้งานระบบ Webservice สำเร็จ","Authentications");
                pers_authen = {'authen_log_id':res.authen_log_id,'pid':res.pid,'cid':res.cid,'random_string':res.random_string};
                console.log(pers_authen);
             }
             console.log(res.authen_log_id);
         },error:function() { //Result Error
            toastr.error("เชื่อมโยงกับเครื่องอ่านบัตรล้มเหลว!","หน้าต่างแจ้งเตือน");
         }
    });

  }
});

function setLogWSRV(uniq_id){

    console.log(uniq_id.toLowerCase());

    var URL = base_url+'personals/setLogWSRV';
     $.ajax({
         url: URL,
         type: "POST",
         dataType: 'json',
         data: {'user_id':user_id, 'org_id':org_id, 'uniq_id':uniq_id.toLowerCase(),'csrf_dop': csrf_hash},   
         success: function (res) {
             if(!res.authen_log_id){
                toastr.error("เชื่อมโยงกับเครื่องอ่านบัตรล้มเหลว!","หน้าต่างแจ้งเตือน"); 
                setTimeout(function(){$(bt_spid).attr('disabled',false);},500);
             }else {
                authen_log_id = res.authen_log_id;
             }
             console.log(res.authen_log_id);
         },error:function() { //Result Error
            toastr.error("เชื่อมโยงกับเครื่องอ่านบัตรล้มเหลว!","หน้าต่างแจ้งเตือน");
            setTimeout(function(){$(bt_spid).attr('disabled',false);},500);
         }
    });
}