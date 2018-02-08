  
    <div id="tmp_menu" hidden='hidden'>
     <a class="navbar-minimalize minimalize-styl-2 btn btn-primary" style="margin-left: 0px; background-color: #2f4250; border: 0;font-size: 17px; padding: 2px 20px 2px 20px;" href="<?php echo site_url('admin_access');?>"><i class="fa fa-caret-left" aria-hidden="true"></i> </a>

    </div>
    <script>
    setTimeout(function(){
      $("#menu_topright").html($("#tmp_menu").html());
    },300);

    function showChart(){
      $("#chart_display").slideToggle();
    }
  </script>

<div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>ตารางรายการ </h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                <i class="fa fa-wrench"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-user">
                                <li><a href="#">Config option 1</a>
                                </li>
                                <li><a href="#">Config option 2</a>
                                </li>
                            </ul>
                            <a class="close-link">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">

                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>ชื่อ</th>
                                <th>หน่วยงาน</th>
                                <th>ประเภท</th>
                                <th>ลิ้งค์</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>1</td>
                                <td>ข้อมูลดัชนีจำแนกตัวชี้วัดคุณภาพชีวิตของประชาชนในชนบท ตามเกณฑ์ความจำเป็นพื้นฐาน (จปฐ.)</td>
                                <td>กรมการพัฒนาชุมชน กระทรวงมหาดไทย</td>
                                <td>Crontab</td>
                                <td class="text-navy"><a target='_blank' href="https://gateway.dop.go.th/transfer/import/RequestElderyJPTH">https://gateway.dop.go.th/transfer/import/RequestElderyJPTH</a></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>ข้อมูลตำแหน่งว่าง</td>
                                <td>กรมการจัดหางาน กระทรวงแรงงาน</td>
                                <td>Webservive, Crontab</td>
                                <td class="text-navy"><a target='_blank' href="https://gateway.dop.go.th/transfer/import/RequestJobVacancy">https://gateway.dop.go.th/transfer/import/RequestJobVacancy({is_non_profit_job},{data_form},{date_to},{province_id}</a></td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>ข้อมูลสถิติประชากรไทยจากการทะเบียน จำแนกรายอายุ</td>
                                <td>กรมการปกครอง กระทรวงมหาดไทย</td>
                                <td>Webservive, Crontab</td>
                                <td class="text-navy"><a target='_blank' href="https://gateway.dop.go.th/transfer/import/RequestThaiPopulationRegistrationRecord">https://gateway.dop.go.th/transfer/import/RequestThaiPopulationRegistrationRecord({year_of_stat})</a></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
</div>