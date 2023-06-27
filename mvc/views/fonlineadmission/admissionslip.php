<div class="mainadmissionslip">
	<div class="admissioninfo" id="admissioninfo">
		<style type="text/css">
			.mainadmissionslip {
				/*padding: 10%;*/
			}

			.admissionheading {
				text-align: center;
			    color: #495057;
			    font-size: 25px;
			    font-weight: 700;
			    margin-bottom: 15px;
			}

			.areaTop {
		    	font-family: Helvetica;
		    	overflow: hidden;
			}

			.studentImage {
			    width: 25%;
			    float: left;
			    text-align: center;
			}

			.studentProfile {
			    width: 75%;
			    float: right;
			}

			.studentImg {
			    width: 100px;
			    height: 120px;
			    border: 5px solid #ddd;
			    padding: 5px;
			    border-radius: 10%;
			    overflow: hidden;
			}

			.singleItem {
			    width: 100%;
			    line-height: 25px;
			    font-size: 16px;
			    font-family: Helvetica;
			    overflow: hidden;
			}

			.single_label {
			    width: 25%;
			    float: left;
			    font-weight: bold;
			}

			.single_value {
			    width: 75%;
			    float: left;
			}

			.label {
			    width: 40%;
			    float: left;
			    font-weight: bold;
			}

			.value {
			    width: 58%;
			    float: left;
			}

			.areaBottom {
			    padding: 0px 30px;
			    padding-top: 20px;
			    font-size: 16px;
			    overflow: hidden;
			    width: 100%
			}

			.areaBottomLeft {
			    width: 50%;
			    float: left;
			}

			.areaBottomRight {
			    width: 50%;
			    float: right;
			}

			.table {
			    border-collapse: collapse !important;
			    width: 100%;
			    max-width: 100%;
			    margin-bottom: 20px;
			    text-align: left;
			    font-family: helvetica;
			}

			.table td,
			.table th {
			    background-color: #fff !important;
			    padding: 6px !important;
			    border: 1px solid #ddd !important;
			    line-height: 1.42857143;
			    vertical-align: top;
			}

			span.idclass {
			    border: 1px solid #ddd;
			    padding: 1px 5px;
			}

			.admissionprint {
			    display: block;
			    width: 100%;
			    text-align: center;
			}
			.title {
				text-align: center;
				line-height: 20px;
				margin: 0px;
			}

			.title-desc{
				text-align: center;
				line-height: 20px;
				margin: 0px;
			}
		</style>
		<div class="mainArea">
			<div class="areaTop">
				<div class="studentImage">
				  	<img class="studentImg" src="<?=imagelink($admission['photo'])?>" alt="">
				</div>
				<div class="studentProfile">
					<div class="singleItem">
						<div class="single_label">Admission ID</div>
						<div class="single_value">: 
							<?php 
	                            $admissionIDlen = strlen($admission['admissionID']);
	                            $boxLimit = 8;

	                            if($admissionIDlen >= $boxLimit) {
	                            	$boxLimit += 2;
	                            }

	                            $zerolength = ($boxLimit - $admissionIDlen);
	                            if($zerolength > 0) {
	                                for($i=1; $i <= $zerolength; $i++) {
	                                    echo "<span class='idclass'>0</span>";
	                                }
	                            }
	                            $admissionIDArray = str_split($admission['admissionID']);
	                            if(count($admissionIDArray)) {
	                                foreach ($admissionIDArray as $value) {
	                                    echo "<span class='idclass'>".$value."</span>";
	                                }
	                            }
	                        ?>
						</div>
					</div>
					<div class="singleItem">
						<div class="single_label">Name</div>
						<div class="single_value">: <?=$admission['name']?></div>
					</div>
					<div class="singleItem">
						<div class="single_label">Apply Class</div>
						<div class="single_value">: <?=isset($classes[$admission['classesID']]) ? $classes[$admission['classesID']] : ''?></div>
					</div>
					<div class="singleItem">
						<div class="single_label">Email</div>
						<div class="single_value">: <?=$admission['email']?></div>
					</div>
				</div>
	      	</div>
			<div class="areaBottom">
				<table class="table table-bordered">
					<tr>
						<td width="30%">Phone</td>
						<td width="70%"><?=$admission['phone']?></td>
					</tr>
					<tr>
						<td width="30%">Date of Birth</td>
						<td width="70%"><?=date('d M Y',strtotime($admission['dob']))?></td>
					</tr>
					<tr>
						<td width="30%">Gender</td>
						<td width="70%"><?=$admission['sex']?></td>
					</tr>
					<tr>
						<td width="30%">Religion</td>
						<td width="70%"><?=$admission['religion']?></td>
					</tr>
					<tr>
						<td width="30%">Address</td>
						<td width="70%"><?=$admission['address']?></td>
					</tr>
					<tr>
						<td width="30%">Country</td>
						<td width="70%"><?=isset($countrys[$admission['country']]) ? $countrys[$admission['country']] : ''?></td>
					</tr>
					<tr>
						<td width="30%">Apply Date</td>
						<td width="70%"><?=date('d M Y',strtotime($admission['create_date']))?></td>
					</tr>
				</table>
			</div>
	    </div>
	</div>
	<div class="admissionprint">
		<button class="btn btn-success" onclick="javascript:printDiv('admissioninfo')">Print</button>
	</div>
</div>

<script type="text/javascript">
	function printDiv(divID) {
        var oldPage = document.body.innerHTML;
        var divElements = document.getElementById(divID).innerHTML;
        var footer = "<center><img src='<?=base_url('uploads/images/'.$siteinfos->photo)?>' style='width:30px;' /></center>";
        var copyright = "<center><?=$siteinfos->footer?> | hotline : <?=$siteinfos->phone?></center>";
        document.body.innerHTML =
          "<html><head><title></title></head><body>" +
          "<center><img src='<?=base_url('uploads/images/'.$siteinfos->photo)?>' style='width:50px;' /></center><p class=\"title\"><?=$siteinfos->sname?></p><p style='margin-bottom:50px' class=\"title-desc\"><?=$siteinfos->address?></p>"
          + divElements + footer + copyright + "</body>";

        window.print();
        document.body.innerHTML = oldPage;
        window.location.reload();
    }
</script>