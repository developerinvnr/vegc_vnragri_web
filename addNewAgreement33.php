<?php 
session_start();
include 'cdns.php'; 
include 'config.php'; 
?>

<style type="text/css">
	.pagethings{
		position: absolute;
		left: 200px;
		padding: 20px;
	}
	.nshw{
		display: none;
	}
	.lightlabel{
		background-color: #ccc !important;
		padding-left: 5px !important;
		font-size: 12px;
		font-weight: bold;
		padding: 0px;
	}
	.fstable tbody th{
	  color: #000;
	  font-size: 12px;
	  font-weight: bold;
	  padding: 5px !important;
	}

	.fstable tbody td{
	  padding: 1px;
	}
	.frminp{
	  padding: 4px !important;
	  height:25px;
	  border-radius: 4px;
	  font-size: 11px;
	  font-weight:550;
	}
	.frmbtn{
	  padding: 2px 4px !important;
	  font-size: 11px;
	}
	body{
		background-color: #EDEDED;
	}
	.estable thead th,.estable tbody th,.estable tbody td{
	  font-size: 12px !important;
	  padding: 1px 2px !important;
	  text-align: center;
	  font-weight: 500;
	  border:2px solid #ccc;
	  margin:0px;
	}
	.estable thead th{
	  background-color:#b7e0f4;
	  color: #000;
	  font-size: 13px;
	  font-weight: bold;
	  padding: 7px 3px !important;
	}

	.estable tbody td{
	  background-color: #fff !important;
	}

</style>
<script type="text/javascript">
function isNumberFKey(evt)
{
  var charCode = (evt.which) ? evt.which : event.keyCode
  if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
   return false;

  return true;
}
</script>
<div id="savmsg" class="alert alert-success frminp" style="display:none ;">Saved Successfully!</div>
<div id="errormsg" class="alert alert-danger frminp" style="display:none ;">Something Went Wrong, Please try after some time!</div>
<div id="alreadyexistsmsg" class="alert alert-danger frminp" style="display:none ;">
	An agreement of the same farmer with same crop already entered by you today! Can't enter Agreement with same details on the same day
</div>
<?php
if(isset($_REQUEST['added']) && $_REQUEST['added']=='yes'){
	?>
	<script type="text/javascript">
		$("#savmsg").show(300);
		setTimeout(function(){ $("#savmsg").hide(300);  }, 3000);
	</script>
	<?php
}elseif(isset($_REQUEST['added']) && $_REQUEST['added']=='no' && $_REQUEST['reason']==''){
	?>
	<script type="text/javascript">
		$("#errormsg").show(300);
		setTimeout(function(){ $("#errormsg").hide(300);  }, 3000);
	</script>
	<?php
}elseif(isset($_REQUEST['added']) && $_REQUEST['added']=='no' && $_REQUEST['reason']=='alreadyexists'){
	?>
	<script type="text/javascript">
		$("#alreadyexistsmsg").show(300);
		setTimeout(function(){ $("#alreadyexistsmsg").hide(300);  }, 15000);
	</script>
	<?php
}
?>

<?php
/*============================================
here taking all the data from tables and putting on a array, 
so in below loop we don't have to fetch all data again and again...
==============================================*/
$allf=mysql_query("SELECT f.* FROM farmers f, user_location ul WHERE f.state_id!=0 and f.state_id=ul.state_id and ul.uid='".$_SESSION['uId']."' and ul.sts='A' order by fname asc");
while ($allfd=mysql_fetch_assoc($allf)) {
	$farr[$allfd['fid']]=$allfd['fname'];
}

$allc=mysql_query('SELECT * FROM `company`');
while ($allcd=mysql_fetch_assoc($allc)) {
	$carr[$allcd['comid']]=$allcd['com_name'];
}

$allu=mysql_query('SELECT * FROM `users`');
while ($allud=mysql_fetch_assoc($allu)) {
	$uarr[$allud['uId']]=$allud['uName'];
}

$allcr=mysql_query("SELECT c.* FROM crop c, user_crop uc where c.cropid=uc.cropid and uc.uid='".$_SESSION['uId']."' order by c.cropname");
while ($allcrd=mysql_fetch_assoc($allcr)) {
	$crarr[$allcrd['cropid']]=$allcrd['cropname'];
}


?>

<form id="agreementtable"  enctype="multipart/form-data" method="post" action="agreementprocess.php">
	
	<table class=" fstable table table-bordered">
		
		<tbody>
		<tr>
			<td colspan="8" class="lightlabel" style="text-align: center;font-size: 20px;">Seed Production Agreement</td>
		</tr>
		<tr>
			<th>First Party</th>
			<td>
				<select class="form-control frminp" name="firstparty" id="firstparty" required>
					
					<?php foreach ($carr as $key => $value) { ?>
					<option value="<?=$key?>"  ><?=$value?></option>
					<?php } ?>
				</select>
			</td>
			<th>Second Party</th>
			<td>
				<select class="form-control frminp" name="secondparty" id="secondparty" required>
					<option  >Select</option>
					<?php foreach ($farr as $key => $value) { ?>
					<option value="<?=$key?>"  ><?=$value?></option>
					<?php } ?>
				</select>
				<script type="text/javascript">
					$('#secondparty').on('change', function(){
						var fid=$('#secondparty').val();
					  	$.post("mastersAjax_agri.php",{ act:'getOrganiser',fid:fid },function(data) {
							$('#organiser').html(data);
				          }
				        );
				        $.post("mastersAjax_agri.php",{ act:'getProdPerson',fid:fid },function(data) {
				        	console.log(data);
							$('#pperson').html(data);
				          }
				        );
				        $.post("mastersAjax_agri.php",{ act:'getProdExecutive',fid:fid },function(data) {
				        	console.log(data);
							$('#pexecutive').html(data);
				          }
				        );
						$.post("mastersAjax_agri.php",{ act:'getLand',fid:fid },function(data) {
							$('#ann7_areain_acre').html(data);
				          }
				        );
				        
					});
				</script>
			</td>
			<th>Organiser</th>
			<td style="width: 200px;">
				<select class="form-control frminp" name="organiser" id="organiser" required>
				</select>
			</td>
		</tr>
		<tr>
			<th>Agreement Date</th>
			
			<td>
				<input class="form-control frminp" name="agreedate" id="agreedate" autocomplete="off">
			</td>
			<script>
			$('#agreedate').datepicker({format:'dd-mm-yyyy',}).on('change', function(){
				var date = $('#agreedate').val();
				$('#agreefrom').val(date);	
				var agreefrom = new Date(date.split("-").reverse().join("-"));		
				agreefrom.setMonth( agreefrom.getMonth() + 7 );
				var y=agreefrom.getFullYear();
				var m=agreefrom.getMonth();
				var d=agreefrom.getDate();
				if(d.toString().length == 1){ d = '0'+d; }
				if(m.toString().length == 1){ m = '0'+m; }
				$('#agreeto').val(d+'-'+m+'-'+y);
		    });
			</script>
			
			<th>Period</th>
			<td>
				<input class="form-control frminp" name="agreefrom" id="agreefrom" placeholder="From" style="width: 110px;float: left;">
				<script>
				$('#agreefrom').datepicker({format:'dd-mm-yyyy',}).on('change', function(){
					var date = $('#agreefrom').val();
					var agreefrom = new Date(date.split("-").reverse().join("-"));		
					agreefrom.setMonth( agreefrom.getMonth() + 7 );
					var y=agreefrom.getFullYear();
					var m=agreefrom.getMonth();
					var d=agreefrom.getDate();
					if(d.toString().length == 1){ d = '0'+d; }
					if(m.toString().length == 1){ m = '0'+m; }
					$('#agreeto').val(d+'-'+m+'-'+y);				
			    });
				</script>
				<input class="form-control frminp" name="agreeto" id="agreeto" placeholder="To" style="width: 110px;float: left;">
			</td>
			<th>Production Incharge</th>
			<td style="width: 200px;">
				
				<select class="form-control frminp" name="pperson" id="pperson" required>
					<option disabled selected >Select</option>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="4"></td>
			<th>Production Executive</th>
			<td style="width: 200px;">
				
				<select class="form-control frminp" name="pexecutive" id="pexecutive">
					<option disabled selected >Select</option>
				</select>
			</td>
			
		</tr>

		<tr>
			<td colspan="8" >
				
				<table class=" estable table table-bordered">
					<thead>
						<tr>
							<th rowspan="2" style="width:150px;">Crop</th>
							<th rowspan="2">Production Code</th>
							<th rowspan="2" style="width:100px;">Type</th>
							<th colspan="4">FS Code</th>
						</tr>
						<tr>
							<th colspan="2">Female</th>
							<th colspan="2">Male</th>
						</tr>
					</thead>
					<tbody id="farLandtbodya">
						<tr>
							<td>
								<select class="form-control frminp" name="ann_crop" id="ann_crop" required onchange="getAllCropDetails()">
									<option  disabled selected >Select</option>
									<?php foreach ($crarr as $key => $value) { ?>
									<option value="<?=$key?>"  ><?=$value?></option>
									<?php } ?>
								</select>
								
							</td>
							<td >
								<select class="form-control frminp" id="ann_prodcode" name="ann_prodcode" required onchange="getAll2CropDetails()">
								</select>
							
								<script type="text/javascript">
									function getAllCropDetails(){ 
										var cid=$('#ann_crop').val(); 

										if(cid!=null){   /*&& ctype!=null*/

										  	$.post("mastersAjax_agri.php",{ act:'get_production_code',cid:cid  },function(data) { //alert(data);
												$('#ann_prodcode').html(data);
												$('#an1prodcode').html(data);
												
									          }
									        );
										}	
									        
									}
									
								</script>
								
								<script type="text/javascript">
									function getAll2CropDetails(){
										var cid=$('#ann_crop').val();
										var pid=$('#ann_prodcode').val();
										

										if(pid!=null){  
                                            


										  	$.post("mastersAjax_agri.php",{ act:'get_type',pid:pid  },function(data) { 
											
											$('#GetDetailsV').html(data);
											 
											$('#ann_ophyb').val($('#typev').val());
											// $('#fsfemale1').val($('#spcode_f1v').val());
											// $('#fsfemale2').val($('#spcode_f2v').val());
											// $('#fsmale1').val($('#spcode_m1v').val());
											// $('#fsmale2').val($('#spcode_m2v').val());
											$('#ann1_germination_per').val($('#germinationv').val());
											$('#ann1_genetic_purity').val($('#genetic_purityv').val());
											$('#ann1_physical_purity').val($('#physical_purity').val());
											$('#ann1_moisure').val($('#moisturev').val()); 
											$('#ann4_estiyield_rawqty').val($('#estimated_yieldv').val());
											
									        }); 
												
									    }
									}

									$( document ).ready(function() {
									    

									    $("#fsfemale1").keyup(function(event){
									    	// alphabet Only ====================================
									    	var key = event.keyCode;
						                    if (key >= 48 && key <= 57) {
															    	
						                        $("#fsfemale1").val(
												    function(index, value){
												        return value.substr(0, value.length - 1);
												});
						                    }
									        // alphabet Only ====================================


									        // uppercase ====================================
									    	$("#fsfemale1").val($("#fsfemale1").val().toUpperCase());
									        // uppercase ====================================



									        // jump on next input when two character ==================================
									        if($("#fsfemale1").val().length == 2){
									        	$("#fsfemale2").focus();
									        }
									        // jump on next input when two character ==================================


									        // not same alphabets check ====================================
									        var words = $("#fsfemale1").val().split("");
									        if(words[0] == words[1]){
									        	$("#fsfemale1").val(
												    function(index, value){
												        return value.substr(0, value.length - 1);
												});
												$("#fsfemale1").focus();
									        }else{

									        	// uppercase ====================================
										    	if($("#ann_ophyb").val() == 'OP'){
										    		$("#fsmale1").val($("#fsfemale1").val());
										    	}
										        // uppercase ====================================

									        }
									        // not same alphabets check ====================================

									    });

									    $("#fsfemale2").keyup(function(event){
									    	// number Only====================================
									    	var key = event.keyCode;
						                    if (key != 8 && key != 0 && (key < 48 || key > 57) && (key < 96 || key > 105) ) {
															    	
						                        $("#fsfemale2").val(
												    function(index, value){
												        return value.substr(0, value.length - 1);
												});
						                    }
									        // number Only====================================

									        // uppercase ====================================
									    	$("#fsfemale1").val($("#fsfemale1").val().toUpperCase());
									        // uppercase ====================================

									        // uppercase ====================================
									    	if($("#ann_ophyb").val() == 'OP'){
									    		$("#fsmale2").val($("#fsfemale2").val());
									    		// jump on next input when three character ============================
										        if($("#fsfemale2").val().length == 3){
										        	$("#fsfemale2").blur();
										        }
										        // jump on next input when three character ============================
									    	}else if($("#ann_ophyb").val() == 'HYB'){

										    	// jump on next input when three character ============================
										        if($("#fsfemale2").val().length == 3){

										        	if ( $("#fsfemale2").val() % 2 == 0 ){
														$("#fsfemale2").val('');
														alert("On Hybrid Crop Type\nFemale Code must be ODD number")
													}else{
														$("#fsmale1").focus();
													}
										        	
										        }
										        
										        // jump on next input when three character ============================

									    	}
									        // uppercase ====================================


									        

									    });





									    $("#fsmale1").keyup(function(event){
									    	// alphabet Only ====================================
									    	var key = event.keyCode;
						                    if (key >= 48 && key <= 57) {
															    	
						                        $("#fsmale1").val(
												    function(index, value){
												        return value.substr(0, value.length - 1);
												});
						                    }
									        // alphabet Only ====================================


									        // uppercase ====================================
									    	$("#fsmale1").val($("#fsmale1").val().toUpperCase());
									        // uppercase ====================================



									        // jump on next input two character ====================================
									        if($("#fsmale1").val().length == 2){
									        	$("#fsmale2").focus();
									        }
									        // jump on next input two character ====================================


									        // not same alphabets check ====================================
									        var words = $("#fsmale1").val().split("");
									        if(words[0] == words[1]){
									        	$("#fsmale1").val(
												    function(index, value){
												        return value.substr(0, value.length - 1);
												});
												$("#fsmale1").focus();
									        }
									        // not same alphabets check ====================================

									    });

									    $("#fsmale2").keyup(function(event){
									    	// number Only====================================
									    	var key = event.keyCode;
						                    if (key != 8 && key != 0 && (key < 48 || key > 57) && (key < 96 || key > 105) ) {
															    	
						                        $("#fsmale2").val(
												    function(index, value){
												        return value.substr(0, value.length - 1);
												});
						                    }
									        // number Only====================================

									        if($("#fsmale2").val().length == 3){
										        	if($("#ann_ophyb").val() == 'HYB'){
										        		if ( $("#fsmale2").val() % 2 != 0 ){
															$("#fsmale2").val('');
															alert("On Hybrid Crop Type\nMale Code must be EVEN number")
															$("#fsmale2").focus();
														}else{
															$("#fsmale2").blur();
														}
										        	}else{
														$("#fsmale2").blur();
													}
									        }


									    });
									});
								</script>
							
							<span id="GetDetailsV"></span>
							</td>
							<!--<td>
							    
								<select class="form-control frminp" id="ann_ophyb" name="ann_ophyb" required>
									<option disabled selected>Select</option>
									<option value="OP">OP</option>
									<option value="HYB">HYB</option>
								</select>	
							</td>-->
							
							<td >
								<!-- <input> -->
								<select  id="ann_ophyb" name="ann_ophyb" class="form-control frminp" style="text-align:center; width:100%;">
									<option value="" selected disabled>Select</option>
									<option value="OP">OP</option>
									<option value="HYB">HYB</option>
								</select>
							</td>
							<td ><input id="fsfemale1" name="fsfemale1" maxlength="2" autocomplete="off" style="text-align:center;" required></td>
							<td ><input id="fsfemale2" name="fsfemale2" maxlength="3" autocomplete="off" style="text-align:center;" required></td>
							<td ><input id="fsmale1" name="fsmale1"  maxlength="2" class="alphabetOnly"  onkeyup="this.value = this.value.toUpperCase();" style="text-align:center;" required></td>
							<td ><input id="fsmale2" name="fsmale2"  maxlength="3" style="text-align:center;" required></td>
							
						</tr>
					</tbody>
				</table>
				
				<style type="text/css">
					.tabnav{
						cursor: pointer;
					}
					.antable{
					  margin:20 auto;
					}
					.antable thead th{
					  background-color:#ccc;
					  border:2px solid #b3b3b3;
					}
					
				</style>
				
				<table class=" estable table table-bordered">
					<thead >
						<tr>
							<th rowspan="2"  class="tabnav"  onclick="showtr('trSoil',this)" style="background-color: white;">
								<a>Soil <br />Details</a>
							</th>
							<th colspan="9">Annexure</th>
						</tr>
						<tr>
							<th  class="tabnav"  onclick="showtr('trI',this)">
								<font color="#000000">I</font> <br />QC%
							</th>
							<th class="tabnav" onclick="showtr('trII',this)">
								<font color="#000000">II</font> <br />Incentive
							</th>
							<th class="tabnav" onclick="showtr('trIII',this)">
								<font color="#000000">III</font> <br />Additional
							</th>
							<th class="tabnav" onclick="showtr('trIV',this)">
								<font color="#000000">IV</font> <br />Estimated Yield
							</th>
							<th class="tabnav" onclick="showtr('trIVA',this)">
								<font color="#000000">IV(A)</font> <br />Loss Of Yield
							</th>
							<th class="tabnav" onclick="showtr('trV',this)">
								<font color="#000000">V</font> <br />Cost of FS Seed
							</th>
							<!--<th class="tabnav" onclick="showtr('trVI',this)">
								<font color="#000000">VI</font> <br />Helth & Sefty
							</th>-->
							<th class="tabnav" onclick="showtr('trVII',this)">
								<font color="#000000">VII</font> <br />Land Details
							</th>
							<!--<th class="tabnav" onclick="showtr('trVIII',this)" style="width:120px;">
								<font color="#000000">VIII</font> <br />Power of Attorney
							</th>-->
						</tr>
					</thead>
					<script type="text/javascript">
						$('.tabnav').css('color', 'blue');

						function showtr(id,th){
							$('.tabnav').css('color', 'blue');
							
							$(th).css('color', 'black');
							$('.tabnav').css("cssText", "background-color: #B7E0F4 !important;color:blue;");
							$(th).css("cssText", "background-color: white !important;");

							$('.tabthings').hide();
							$('#'+id).show();
						}
					</script>
					<tbody>
						<tr id="trSoil" class="tabthings" >
							<td colspan="20">
								<!--<h6 style="margin-top:10px;margin-bottom: -15px;">Soil Details</h6>-->
								<table class="antable" style="width:80%;">
									<thead style="background-color: #cccccc !important;">
										<tr>
											<th>WATER<br />AVAILABILITYY</th>
											<th>TOPOGRAPHY <br />OF THE LAND</th>
											<th>TYPE <br />OF LAND</th>
											<th>SOIL TYPE</th>
											<th>EXTENT OF <br />CULTIVABILITY</th>
										</tr>
									</thead>
									<tbody>
										<tr style="height:28px;">
											
											<td>
												<select name="water_availability" style="width:100%;height:100%;">
													<option value="Sufficient" selected="">Sufficient</option>
											        <option value="Suitable">Suitable</option>
											        <option value="Yes">Yes</option>
											        <option value="No">No</option>
											    </select>
											</td>
											<td>
												<select name="topography_land" style="width:100%;height:100%;">
													<option value="Sufficient" selected="">Sufficient</option>
											        <option value="Suitable">Suitable</option>
											        <option value="Yes">Yes</option>
											        <option value="No">No</option>
											    </select>
											</td>
											<td>
												<select name="typeof_land" style="width:100%;height:100%;">
													<option value="Sufficient" selected="">Sufficient</option>
											        <option value="Suitable">Suitable</option>
											        <option value="Yes">Yes</option>
											        <option value="No">No</option>
											    </select>
											</td>
											<td>
												<select name="soil_type" style="width:100%;height:100%;">
													<option value="Sufficient" selected="">Sufficient</option>
											        <option value="Suitable">Suitable</option>
											        <option value="Yes">Yes</option>
											        <option value="No">No</option>
											    </select>
											</td>
											<td>
												<select name="extent_cultivability" style="width:100%;height:100%;">
													<option value="Sufficient" selected="">Sufficient</option>
											        <option value="Suitable">Suitable</option>
											        <option value="Yes">Yes</option>
											        <option value="No">No</option>
											    </select>
											</td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
						<tr id="trI" class="tabthings" style="display: none;" >
							<td colspan="20">
								<!--<h6 style="margin-top:10px;margin-bottom: -15px;">Annexure-I</h6>-->
								<table class="antable">
									<thead style="background-color: #cccccc !important;">
										<tr>
											<!--<th rowspan="2">Crop</th>
											<th colspan="4">FS Code</th>
											<th rowspan="2">Production Code</th>-->
											<th rowspan="2">Germination<br>% ( Min. )</th>
											<th rowspan="2">Genetic Purity<br>% ( Min. )</th>
											<!-- <th rowspan="2">Physical Purity <br />%</th> -->
											<th rowspan="2">Moisture<br>% ( Max.)</th>
										</tr>
										<!--<tr>
											<th colspan="2">Female</th>
											<th colspan="2">Male</th>
										</tr>-->
									</thead>
									<tbody>
										<tr style="height:28px;">
											<!--<td id="an1crop"></td>
											<td id="an1f1"></td>
											<td id="an1f2"></td>
											<td id="an1m1"></td>
											<td id="an1m2"></td>
											<td id="an1prodcode"></td>-->
											<td>
											
											 <span id="an1crop" style="display:none;"></span>
											 <span id="an1f1" style="display:none;"></span>
											 <span id="an1f2" style="display:none;"></span>
											 <span id="an1m1" style="display:none;"></span>
											 <span id="an1m2" style="display:none;"></span>
											 <span id="an1prodcode" style="display:none;"></span>
												<input type="" name="ann1_germination_per" id="ann1_germination_per" style="text-align:center;" readonly onKeyPress="return isNumberFKey(event)">
											</td>
											<td>
												<input type="" name="ann1_genetic_purity" id="ann1_genetic_purity" style="text-align:center;" readonly onKeyPress="return isNumberFKey(event)">
											</td>
											<!-- <td>
												<input type="" name="ann1_physical_purity" id="ann1_physical_purity" style="text-align:center;" readonly onKeyPress="return isNumberFKey(event)">
											</td> -->
											<td>
												<input type="" name="ann1_moisure" id="ann1_moisure" style="text-align:center;" readonly onKeyPress="return isNumberFKey(event)">
											</td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
						<tr id="trII" class="tabthings" style="display: none;">
							<td colspan="20">
								<!--<h6 style="margin-top:10px;margin-bottom: -15px;">Annexure-II</h6>-->
								<table class="antable">
									<thead style="background-color: #cccccc !important;">
										<tr>
											<!--<th rowspan="2">Crop</th>
											<th colspan="4">FS Code</th>
											<th rowspan="2">OP/Hyb</th>-->
											<th rowspan="2">Basic procurement<br /> price/kg</th>
											<th rowspan="2">Quality based Incentive</th>
											<th rowspan="2">Payment within days <br />after delivery</th>
											
										</tr>
										<!--<tr>
											<th colspan="2">Female</th>
											<th colspan="2">Male</th>
										</tr>-->
									</thead>
									<tbody>
										<tr style="height:28px;">
											<!--<td id="an2crop"></td>
											<td id="an2f1"></td>
											<td id="an2f2"></td>
											<td id="an2m1"></td>
											<td id="an2m2"></td>
											<td id="an2type"></td>-->
											<td>
											 <span id="an2crop" style="display:none;"></span>
											 <span id="an2f1" style="display:none;"></span>
											 <span id="an2f2" style="display:none;"></span>
											 <span id="an2m1" style="display:none;"></span>
											 <span id="an2m2" style="display:none;"></span>
											 <span id="an2type" style="display:none;"></span>
											
												<input type="" name="ann2_procmnt_price" style="text-align:center;" onKeyPress="return isNumberFKey(event)">
											</td>
											<td>
												<input type="" name="ann2_qualbased_inc_price" style="text-align:center;" onKeyPress="return isNumberFKey(event)">
											</td>
											<td>
												<input type="" name="ann2_payment_within_day" style="text-align:center;" onKeyPress="return isNumberFKey(event)">
											</td>
											
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
						<tr id="trIII" class="tabthings" style="display: none;">
							<td colspan="20">
								<!--<h6 style="margin-top:10px;margin-bottom: -15px;">Annexure-III</h6>-->
								<table class="antable">
									<thead style="background-color: #cccccc !important;">
										<tr>
											<th >Type</th>
											<th >Price(Rs)</th>											
										</tr>
									
									</thead>
									<tbody>
										<tr style="height:28px;">
											<td>
												Additional Fees in case of male chopping, removal and destruction
											</td>
											<td>
												<input type="" name="ann3_additional_fee" style="text-align:center;" onKeyPress="return isNumberFKey(event)">
											</td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
						<tr id="trIV" class="tabthings" style="display: none;">
							<td colspan="20">
								<!--<h6 style="margin-top:10px;margin-bottom: -15px;">Annexure-IV</h6>-->
								<table class="antable">
									<thead style="background-color: #cccccc !important;">
										<tr>
											<!--<th rowspan="2">Crop</th>
											<th colspan="4">FS Code</th>
											<th rowspan="2">OP/Hyb</th>-->
											<th rowspan="2">Cultivation Area</th>								
											<th rowspan="2">Cultivation Cost</th>												
											<th rowspan="2">Estimated Yield Range</th>								
										</tr>
										<!--<tr>
											<th colspan="2">Female</th>
											<th colspan="2">Male</th>
										</tr>-->
									</thead>
									<tbody>
										<tr style="height:28px;">
											<!--<td id="an4crop"></td>
											<td id="an4f1"></td>
											<td id="an4f2"></td>
											<td id="an4m1"></td>
											<td id="an4m2"></td>
											<td id="an4type"></td>-->
											<td>
												<input type="" name="ann4_cult_area" id="ann4_cult_area" style="text-align:center;" onKeyPress="return isNumberFKey(event)">
											</td>
											<td>
												<input type="" name="ann4_cult_cost" id="ann4_cult_cost" style="text-align:center;" onKeyPress="return isNumberFKey(event)">
											</td>
											<td>
											 <span id="an4crop" style="display:none;"></span>
											 <span id="an4f1" style="display:none;"></span>
											 <span id="an4f2" style="display:none;"></span>
											 <span id="an4m1" style="display:none;"></span>
											 <span id="an4m2" style="display:none;"></span>
											 <span id="an4type" style="display:none;"></span>
												<input type="" name="ann4_estiyield_rawqty" id="ann4_estiyield_rawqty" style="text-align:center;" onKeyPress="return isNumberFKey(event)">
											</td>											
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
						<tr id="trIVA" class="tabthings" style="display: none;">
							<td colspan="20">
								<!--<h6 style="margin-top:10px;margin-bottom: -15px;">Annexure-IV(A)</h6>-->
								<table class="antable">
									<thead style="background-color: #cccccc !important;">
										<tr>
											<!--<th rowspan="2">Crop</th>
											<th colspan="4">FS Code</th>-->
											<th rowspan="2">Compensation (In case of Loss of Yield)</th>								
										</tr>
										<!--<tr>
											<th colspan="2">Female</th>
											<th colspan="2">Male</th>
										</tr>-->
									</thead>
									<tbody>
										<tr style="height:28px;">
											<!--<td id="an4acrop"></td>
											<td id="an4af1"></td>
											<td id="an4af2"></td>
											<td id="an4am1"></td>
											<td id="an4am2"></td>-->
											<td>
											
											 <span id="an4acrop" style="display:none;"></span>
											 <span id="an4af1" style="display:none;"></span>
											 <span id="an4af2" style="display:none;"></span>
											 <span id="an4am1" style="display:none;"></span>
											 <span id="an4am2" style="display:none;"></span>
									
											
												<input type="" name="ann4a_loss_ofyield" style="text-align:center;" onKeyPress="return isNumberFKey(event)">
											</td>											
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
						<tr id="trV" class="tabthings" style="display: none;">
							<td colspan="20">
								
								<!--<h6 style="margin-top:10px;margin-bottom: -15px;">Cost of FS Seed</h6>-->
								<table class="antable">
									<thead style="background-color: #cccccc !important;">
										<tr>
											<!--<th rowspan="2">Crop</th>
											<th colspan="4">FS Code</th>-->						
											<th rowspan="2">FS  Seed per Acre supplied<br>( M+F ) in kg</th>
											<th rowspan="2">No. of Acres</th>								
											<th rowspan="2">Total FS Supplied in kg</th>								
											<th rowspan="2">Price/Acre</th>								
											<th rowspan="2">Total Amount</th>								
										</tr>
										<!--<tr>
											<th colspan="2">Female</th>
											<th colspan="2">Male</th>
										</tr>-->
									</thead>
									<tbody>
										<tr style="height:28px;">
											<!--<td id="an5crop"></td>
											<td id="an5f1"></td>
											<td id="an5f2"></td>
											<td id="an5m1"></td>
											<td id="an5m2"></td>-->
											<td>
												<input type="" name="ann5_parental_seed" id="ann5_parental_seed" style="text-align:center;" onKeyPress="return isNumberFKey(event)">
											</td>
											<td>
												<input type="" name="ann5_noofacr_plant" id="ann5_noofacr_plant" onkeyup="calCostFsSeed()" style="text-align:center;" onKeyPress="return isNumberFKey(event)">
											</td>
											<td>
												<input type="" name="ann5_plant_matsupply" id="ann5_plant_matsupply" style="text-align:center;" onKeyPress="return isNumberFKey(event)">
											</td>
											<td>
											 <span id="an5crop" style="display:none;"></span>
											 <span id="an5f1" style="display:none;"></span>
											 <span id="an5f2" style="display:none;"></span>
											 <span id="an5m1" style="display:none;"></span>
											 <span id="an5m2" style="display:none;"></span>
											
												<input type="" name="ann5_rate" id="ann5_rate" onkeyup="calCostFsSeed()" style="text-align:center;" onKeyPress="return isNumberFKey(event)">
											</td>		
											
											
											
											<td>
												<input type="" name="totAmtOfFSSeed" id="totAmtOfFSSeed" style="text-align:center;" onKeyPress="return isNumberFKey(event)">
											</td>									
										</tr>
									</tbody>
								</table>
								<script type="text/javascript">
									function calCostFsSeed(){

										/* ===================================================================================
											commented portion is previous calculation only kept because it might be useful in future
											===================================================================================
										*/
									    
									 	// var rate2=0; var parSeedPerAc2=0; var noOfAcOfPla2=0; var plaMatSup2=0;
									    
										// var rate = $('#ann5_rate').val();
										// var parSeedPerAc = $('#ann5_parental_seed').val();
										// var noOfAcOfPla = $('#ann5_noofacr_plant').val();
										// var plaMatSup = $('#ann5_plant_matsupply').val();
										
										// if(rate!=''){ var rate2 = parseFloat($('#ann5_rate').val()); }
										// if(parSeedPerAc!=''){ var parSeedPerAc2 = parseFloat($('#ann5_parental_seed').val()); }
										// if(noOfAcOfPla!=''){ var noOfAcOfPla2 = parseFloat($('#ann5_noofacr_plant').val()); }
										// if(plaMatSup!=''){ var plaMatSup2 = parseFloat($('#ann5_plant_matsupply').val()); }
											

										// var tot = rate2 * (parSeedPerAc2 + noOfAcOfPla2 + plaMatSup2);
										// $('#totAmtOfFSSeed').val(tot);


										
									    
										var rate = $('#ann5_rate').val();
										var noOfAcOfPla = $('#ann5_noofacr_plant').val();
										
										if(rate!=''){ var rate2 = parseFloat($('#ann5_rate').val()); }else{var rate2=0;}
										if(noOfAcOfPla!=''){ var noOfAcOfPla2 = parseFloat($('#ann5_noofacr_plant').val()); }else{var noOfAcOfPla2=0;}

										var tot = rate2 * noOfAcOfPla2;
										$('#totAmtOfFSSeed').val(tot);
									
									}
								</script>
							</td>
						</tr>
						<!--<tr id="trVI" class="tabthings" style="display: none;">
							<td colspan="20">
								<h6 style="margin-top:10px;margin-bottom: -15px;">Health & Safety</h6>
	                            <br /><br /><br /><br /><br /><br />  
							</td>
						</tr>-->
						<tr id="trVII" class="tabthings" style="display: none;">
							<td colspan="20">
								<!--<h6 style="margin-top:10px;margin-bottom: -15px;">Land Details</h6>-->
								<table class="antable">
									<thead style="background-color: #cccccc !important;">
										<tr>
											<th style="width:200px;">Sowing Acres</th>
											<th>Survey Number</th>										
										</tr>
									</thead>
									<tbody>
										<tr>
											<td style="height:28px;">
											 <select name="ann7_areain_acre" id="ann7_areain_acre" style="width:100%;height:100%;" required>                                       
										      
											 </select>
											 
												<!--<input type="" name="ann7_areain_acre" id="ann7_areain_acre" style="text-align:center;" readonly onKeyPress="return isNumberFKey(event)">-->
											</td>
											<td>
												<input type="" name="ann7_surveyno" id="ann7_surveyno">
											</td>
												
																				
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
						<!--<tr id="trVIII" class="tabthings" style="display: none;">
							<td colspan="20">
								<h6 style="margin-top:10px;margin-bottom: -15px;">Power of Attorney</h6>
								<br><br><br><br><br>
							</td>
						</tr>-->
					</tbody>
				</table>


				

				<input type="hidden" id="landcount" name="landcount" value="0">
				<!-- <button type="button" class="btn btn-sm btn-primary frmbtn" style="float: right;margin-top: -20px;" onclick="addfarmerland()"><i class="fa fa-plus" aria-hidden="true"></i>Add</button> -->

				<script type="text/javascript">
					function addfarmerland(){
						var lc=parseInt($('#landcount').val());
						lc=lc+1;
						$('#landcount').val(lc);
						$.post("mastersAjax_agri.php",{ act:'addFarmerLand',lc:lc },function(data) {
							$('#farLandtbody').append(data);
				          }
				        );
					}
					$(document).ready(function() {
						var lc=1;
					    while(lc<=1){
					    	addfarmerland();
					    	lc++;
					    }
					});
				</script>
				
			</td>
		</tr>
		<tr>
			<td colspan="10">
				
				<center>
				<button type="submit" name="saveagreement" id="addbtn" class="frmbtn btn btn-success btn-sm" style="width:100px;" >Save</button>
				<!-- <button type="button" class="frmbtn btn btn-sm btn-danger" style="width:100px;" onclick="$('#addfartbl', window.parent.document).hide(500);window.parent.document.getElementById('addfarbtn').style.display='block';" >Cancel</button> -->
				</center>
			</td>
			
		</tr>
		
		</tbody>
		
	</table>
</form>
<script type="text/javascript">
	
	$('form input').keydown(function (e) {
	    if (e.keyCode == 13) {
	        var inputs = $(this).parents("form").eq(0).find(":input");
	        if (inputs[inputs.index(this) + 1] != null) {                    
	            inputs[inputs.index(this) + 1].focus();
	        }
	        e.preventDefault();
	        return false;
	    }
	});

	function removethistr(th){
		var whichtr = $(th).closest("tr");
		whichtr.remove();   
	}

	function setInputFilter(textbox, inputFilter) {
	  ["input", "keydown", "keyup", "mousedown", "mouseup", "select", "contextmenu", "drop"].forEach(function(event) {
	    textbox.addEventListener(event, function() {
	      if (inputFilter(this.value)) {
	        this.oldValue = this.value;
	        this.oldSelectionStart = this.selectionStart;
	        this.oldSelectionEnd = this.selectionEnd;
	      } else if (this.hasOwnProperty("oldValue")) {
	        this.value = this.oldValue;
	        this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
	      }
	    });
	  });
	}

	setInputFilter(document.getElementById("contact_1"), function(value) {
	  return /^\d*$/.test(value) && (value.length <=10); 
	});
	setInputFilter(document.getElementById("contact_2"), function(value) {
	  return /^\d*$/.test(value) && (value.length <=10); 
	});


</script>