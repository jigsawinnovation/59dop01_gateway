var table;
$(document).ready(function() {

    table = $('#dtable').DataTable({
      "searching": false,
      "bLengthChange": false,
      "pageLength": 25,
      "responsive": true,
      "language": {
        "sProcessing":   "กำลังดำเนินการ...",
        "sLengthMenu":   "แสดง _MENU_ แถว",
        "sZeroRecords":  "ไม่พบข้อมูล",
        "sInfo":         "แสดง _START_ ถึง _END_ จาก _TOTAL_ แถว",
        "sInfoEmpty":    "แสดง 0 ถึง 0 จาก 0 แถว",
        "sInfoFiltered": "(กรองข้อมูล _MAX_ ทุกแถว)",
        "sInfoPostFix":  "",
        "sSearch":       "ค้นหา: ",
        "sUrl":          "",
        "oPaginate": {
          "sFirst":    "หน้าแรก",
          "sPrevious": "ก่อนหน้า",
          "sNext":     "ถัดไป",
          "sLast":     "หน้าสุดท้าย"
        }
      }
       /*
       // Show Export Tools
       dom: '<"html5buttons"B>lTfgitp',
       buttons: [
           { extend: 'copy'},
           {extend: 'csv'},
           {extend: 'excel', title: 'ExampleFile'},
           {extend: 'pdf', title: 'ExampleFile'},
           {extend: 'print',

            customize: function (win){
                   $(win.document.body).addClass('white-bg');
                   $(win.document.body).css('font-size', '10px');

                   $(win.document.body).find('table')
                           .addClass('compact')
                           .css('font-size', 'inherit');
           }
           }
       ]
       */
    });

});

$('#dtable').on('click', 'td.lnk', function () {
      
  // Get Data from record table
  var tmp = table.row($(this).parent()).data();
  console.log(tmp[0]);

  //console.log(name);
  //console.log( companyTable.row(this).data()[1]);
  //$("#company-full-name").val(companyTable.row(this).data()[1]);
  //$("#company-short-name").val(companyTable.row(this).data()[2]);
  //console.log(pid);
  $('#n'+tmp[0]).modal("show");
});