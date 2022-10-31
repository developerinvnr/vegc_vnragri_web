<?php session_start();
date_default_timezone_set('asia/calcutta');
include 'config.php'; 
mysql_query("SET NAMES utf8"); mysql_set_charset('utf8'); 

$agr_y = substr($_REQUEST['no'], 0, 4);

$agr=mysql_query("SELECT agr.agree_no, agr.first_party, agr.second_party, agr.org_id, agr.agree_date, agr.ann_crop, agr.start_date, agr.end_date, agr.ann7_areain_acre, agr.ann7_surveyno, water_availability, topography_land, typeof_land, soil_type, ann_fscode_f, ann_fscode_m, extent_cultivability, ann1_germination_per, ann1_genetic_purity, ann1_moisure, ann2_procmnt_price, ann2_qualbased_inc_price, ann2_payment_within_day, ann5_parental_seed, ann5_noofacr_plant, ann5_plant_matsupply, ann5_rate, ann5_total_amount, ann7_areain_acre, ann7_surveyno, f.fname, f.father_name, v.VillageName, t.TahsilName, d.DictrictName, s.StateName FROM `agreement_".$agr_y."` agr inner join farmers f on agr.second_party=f.fid inner join village v on agr.vi=v.VillageId inner join tahsil t on agr.ti=t.TahsilId inner join distric d on agr.di=d.DictrictId inner join state s on agr.si=s.StateId where agree_no='".$_REQUEST['no']."' AND agree_id=".$_REQUEST['id']); $agrd=mysql_fetch_assoc($agr);


$frm=mysql_query("SELECT f.tem_fid, f.address, f.age, f.pincode, v.VillageName, t.TahsilName, d.DictrictName, s.StateName FROM farmers f inner join village v on f.village_id=v.VillageId inner join tahsil t on f.tahsil_id=t.TahsilId inner join distric d on f.distric_id=d.DictrictId inner join state s on f.state_id=s.StateId where f.fid='".$agrd['second_party']."'"); 
$rfrm=mysql_fetch_assoc($frm);

$scrp=mysql_query("SELECT cropname FROM crop where cropid='".$agrd['ann_crop']."'");  $rcrp=mysql_fetch_assoc($scrp);



?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
 <meta charset="utf-8">
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
 <title><?php if($_REQUEST['n']==1){?>बीज उत्पादक अनुबन्ध<?php }elseif($_REQUEST['n']==2){?>Annexure<?php } ?></title>
</head>
<style>
.td{ color:#000000;font-size:14px;width:100%; }
.th{ color:#000000;font-size:18px;width:100%; }
</style>
<body>
<?php 
//$SecondParty=ucwords(strtolower('श्री  '.$agrd['fname'].' पिता  '.$agrd['father_name'].', Age : '.$rfrm['age'].' निवासी Village :'.$rfrm['VillageName'].','.$rfrm['TahsilName'].', '.$rfrm['DictrictName'].' ('.$rfrm['StateName'].')  के स्वामित्व व अधिकार कि कृषि भूमि सर्वे नं '.$agrd['ann7_surveyno'].', रकबा एकड़ '.$agrd['ann7_areain_acre'].' ग्राम/पोस्ट:  '.$agrd['VillageName'].', '.$agrd['TahsilName'].' जिला: '.$agrd['DictrictName'].'('.$agrd['StateName'].')'));

$AgriDate=''; $AgriFrom=''; $AgriTo='';

if($agrd['agree_date']!='1970-01-01'){ $AgriDate=date("d-m-Y",strtotime($agrd['agree_date'])); }
if($agrd['start_date']!='1970-01-01'){ $AgriFrom=date("d-m-Y",strtotime($agrd['start_date'])); }
if($agrd['end_date']!='1970-01-01'){ $AgriTo=date("d-m-Y",strtotime($agrd['end_date'])); }
$Village=ucwords(strtolower($rfrm['VillageName']));
$FarmerN=ucwords(strtolower($agrd['fname']));
//$Farmer=ucwords(strtolower('श्री '.$agrd['fname'].' पिता  '.$agrd['father_name'].', Age : '.$rfrm['age']));
$FarmerA=$rfrm['VillageName'].', '.$rfrm['TahsilName'].'<br>'.$rfrm['DictrictName'].' - '.$rfrm['StateName'].'<br>'.$rfrm['pincode'].', INDIA'; 

//$s=mysql_query("select TermId, Type, TermDetails from agri_term where Type='TopDetails'"); $rs=mysql_fetch_assoc($s);
$s1=mysql_query("select TermId, Type, TermDetails from agri_term where Type='FirstParty'"); $rs1=mysql_fetch_assoc($s1);
$s2=mysql_query("select TermId, Type, TermDetails from agri_term where Type='SecondParty'"); $rs2=mysql_fetch_assoc($s2);
$s3=mysql_query("select TermId, Type, TermDetails from agri_term where Type='One'"); $rs3=mysql_fetch_assoc($s3);
$s4=mysql_query("select TermId, Type, TermDetails from agri_term where Type='Two'"); $rs4=mysql_fetch_assoc($s4); 

$Term1Details=$rs1['TermDetails']; 
$Term2Details=$rs2['TermDetails'];

?>
<table style="width:100%;" border="0" cellpadding="5">

<?php if($_REQUEST['n']==1){ ?>

<tr><td colspan="2" style=" height:500px;">&nbsp;</td></tr>

<tr>
 <td class="th" colspan="2" style="text-align:center;vertical-align:top;height:50px">
  <table style="width:100%;">
  <tr>
    <td style="width:62%;text-align:left;font-size:14px;padding-left:40px;">
	 <b>AGREEMENT ID:</b>&nbsp;<?=$agrd['agree_no']?>
	</td>
	<td style="width:38%;text-align:left;font-size:14px;">
	 <b>FARMER ID:</b>&nbsp;<?=chunk_split($rfrm['tem_fid'], 4, ' ')?>
	</td>
   </tr>
  </table>
 </td>
</tr>

<tr>
 <td class="th" colspan="2" style="text-align:center;"><u><b>बीज उत्पादक - उत्पादक अनुबन्ध</b></u></td>
</tr>

<tr>
 <td class="td" colspan="2"> यह उत्पादक अनुबन्ध आज दिनांक <?=$AgriDate?> को <?=$Village?> में <b>मेसर्स VNR Seeds Pvt Ltd</b > एवं <b> बीज उत्पादक ( श्री <?=ucwords(strtolower($agrd['fname']))?> पिता <?=ucwords(strtolower($agrd['father_name']))?> Age : <?=$rfrm['age']?> वर्ष)</b> के मध्य निष्पादित किया गया है| </td>
</tr>

<tr>
 <td class="td" style="width:15%;"><b>प्रथम-पक्ष</b></td>
 <td class="td" style="width:85%;"><?=$Term1Details?></td>
</tr>
<tr><td colspan="2" style="text-align:center;"><b>और</b></td></tr>
<tr>
 <td class="td" style="width:15%;"><b>द्वितीय-पक्ष</b></td>
 <td class="td" style="width:85%;">श्री <?=ucwords(strtolower($agrd['fname']))?> पिता <?=ucwords(strtolower($agrd['father_name']))?>, Age : <?=$rfrm['age']?> वर्ष 
 निवासी Village :<?=ucwords(strtolower($rfrm['VillageName']))?>, <?=ucwords(strtolower($rfrm['TahsilName']))?>, <?=ucwords(strtolower($rfrm['DictrictName']))?> (<?=ucwords(strtolower($rfrm['StateName']))?>) 
 के स्वामित्व व अधिकीर की कृषि भूमि सर्वे नं <?=$agrd['ann7_surveyno']?>, रकबा एकड़ <?=$agrd['ann7_areain_acre']?> ,ग्राम/पोस्ट:  <?=ucwords(strtolower($agrd['VillageName']))?>, <?=ucwords(strtolower($agrd['TahsilName']))?> जिला: <?=ucwords(strtolower($agrd['DictrictName']))?> (<?=ucwords(strtolower($agrd['StateName']))?>) <?=$Term2Details?></td>
</tr>

<?php //$rs['TermDetails']?>

<tr><td colspan="2" style="height:150px;">&nbsp;</td></tr>
<tr><td class="td" colspan="2"><?=$rs3['TermDetails']?></td></tr>
<tr><td class="td" colspan="2"><?=$rs4['TermDetails']?></td></tr>

<tr>
 <td class="td" colspan="2">
 <?php $s5=mysql_query("select TermId, Type, TermDetails from agri_term where TermId>=5 AND TermId<=10 order by TermId asc");
       while($rs5=mysql_fetch_assoc($s5))
	   {
	     echo $rs5['TermDetails'];
 	   } 
 ?> 
 </td>
</tr>

<?php //$s6=mysql_query("select TermId, Type, TermDetails from agri_term where Type='Three'"); $rs6=mysql_fetch_assoc($s6); ?>

<tr><td class="td" colspan="2"> अतएव आज दिनांक <?=$AgriDate?> को दोनों पक्ष इस अनुबंध की शर्तो को अच्छी तरह पढ़कर, समझकर आपसी सहमति से स्वस्थचित अवस्था में दो गवाहों के समक्ष मुकाम रायपुर (छ.ग.) में अपने - अपने हस्ताक्षर कर दिए, ताकि सनद रहे जरूरत पर काम आये|<br></td></tr>

<tr><td colspan="2" style="height:25px;">&nbsp;</td></tr>
<tr>
 <td class="td" colspan="2">
  समय  : - <?=$AgriDate?><br>
  स्थान  : - <?=$Village?>
 </td>
</tr>

<?php 
$s7=mysql_query("select TermId, Type, TermDetails from agri_term where Type='SignFN'"); $rs7=mysql_fetch_assoc($s7); 
$s8=mysql_query("select TermId, Type, TermDetails from agri_term where Type='SignFA'"); $rs8=mysql_fetch_assoc($s8); 
$s9=mysql_query("select TermId, Type, TermDetails from agri_term where Type='SignFN2'"); $rs9=mysql_fetch_assoc($s9); 
$s10=mysql_query("select TermId, Type, TermDetails from agri_term where Type='SignFA2'"); $rs10=mysql_fetch_assoc($s10); 
$s11=mysql_query("select TermId, Type, TermDetails from agri_term where Type='SignFM2'"); $rs11=mysql_fetch_assoc($s11);
?>

<tr>
 <td class="td" colspan="2" style="width:100%; vertical-align:top;">
  <table style="width:100%;" border="0">
   <tr>
    <td style="width:60%; vertical-align:top;">
	 <b>प्रथम पक्ष</b><P>
	  हस्ताक्षर  :  . . . . . . . . . . . . . . . . . <br>
	  पक्ष नाम :  <?php /*=$rs7['TermDetails'] */?><br>
	  पता	 :   <?php /*=$rs8['TermDetails'] */ ?>
	</td>
	<td style="width:40%; vertical-align:top;">
	 <b>द्वितीय पक्ष</b><p>
	  हस्ताक्षर  :  . . . . . . . . . . . . . . . . . <br>
	  पक्ष नाम : <?=$FarmerN?><br>
	  पता	 :    <?=$FarmerA?>
	</td>
   </tr>
   <tr>
    <td class="td" style="width:60%; vertical-align:top;"> 
	<u><b>गवाह</b></u><p>  
	  <b>(1)</b> हस्ताक्षर  :  . . . . . . . . . . . . . . . . . <br>
	  नाम	 :    <?php /*$rs9['TermDetails']*/?><br>
	  संपर्क/मोबाइल : <?php /*?=$rs11['TermDetails']*/?><br>
	  पता :        <?php /*<?=$rs10['TermDetails']*/?> 
	</td>
		
	<td class="td" style="width:40%; vertical-align:top;">
	<u><b>गवाह</b></u><p>  
	  <b>(1)</b> हस्ताक्षर  :  . . . . . . . . . . . . . . . . . <br>
	  नाम :   <br>
	  संपर्क/मोबाइल	 : <br>   
	  पता :        
	</td>
   </tr>
   <tr>
    <td class="td" style="width:60%; vertical-align:top;">   
	  <b>(2)</b> हस्ताक्षर  :  . . . . . . . . . . . . . . . . . <br>
	  नाम	 :  <br>
	  संपर्क/मोबाइल : <br>
	  पता :       
	</td>
		
	<td class="td" style="width:40%; vertical-align:top;">
	  <b>(2)</b> हस्ताक्षर  :  . . . . . . . . . . . . . . . . . <br>
	  नाम :   <br>
	  संपर्क/मोबाइल	 : <br>   
	  पता :        
	</td>
   </tr>
  </table>
 </td>
</tr>

<?php } else if($_REQUEST['n']==2){ ?> 
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
<tr><td colspan="2" style=" height:50px;">&nbsp;</td></tr>
<tr>
 <td class="th" colspan="2" style="text-align:center;"><u>Annexure-I</u> <br><b>Soil-Details</b></u></td>
</tr>
<tr>
 <td colspan="2" style="text-align:center;">
<table class="antable" style="width:100%;text-align:center;" border="1">
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
	<td><?=$agrd['water_availability']?></td>
	<td><?=$agrd['topography_land']?></td>
	<td><?=$agrd['typeof_land']?></td>
	<td><?=$agrd['soil_type']?></td>
	<td><?=$agrd['extent_cultivability']?></td>
  </tr>
</tbody>
</table>
 </td>
</tr>
<tr><td colspan="2" style="height:40px;">&nbsp;</td></tr>
<tr>
 <td class="td" colspan="2" style="width:100%; vertical-align:top;">
  <table style="width:100%;" border="0">
   <tr>
    <td style="width:60%; vertical-align:top;">
	 समय  : - <?=$AgriDate?><br>
     स्थान  : - <?=$Village?>
	</td>
	<td style="width:40%; vertical-align:top;">
	 <b>(प्रथम पक्ष)</b><br>
	  हस्ताक्षर  :  . . . . . . . . . . . . . . . . . . . . . . . . . . . . . <br>
	  पक्ष नाम : VNR SEEDS PVT LTD<br>
	  पता	 :   CORPORATE CENTRE, RING ROAD NO -01, RAIPUR (C.G.)	</td>
   </tr>
   <tr><td style="height:40px;">&nbsp;</td></tr>
   <tr>
    <td style="width:60%; vertical-align:top;">
	</td>
	<td style="width:40%; vertical-align:top;">
	 <b>(द्वितीय पक्ष)</b><br>
	  हस्ताक्षर  :  . . . . . . . . . . . . . . . . . . . . . . . . . . . . . <br>
	  पक्ष नाम : <?=$FarmerN?><br>
	  पता	 :    <?=$FarmerA?>
	</td>
   </tr>
  </table>
 </td>
</tr>
<tr><td colspan="2" style="height:550px;">&nbsp;</td></tr>


<tr>
 <td class="th" colspan="2" style="text-align:center;"><u>Annexure-II</u> <br><b>QC % </b></u></td>
</tr>
<tr>
 <td colspan="2" style="text-align:center;">
<table class="antable" style="width:100%;text-align:center;" border="1">
<thead style="background-color: #cccccc !important;">
	<tr>
		<th rowspan="2">Germination<br>% ( Min. )</th>
		<th rowspan="2">Genetic Purity<br>% ( Min. )</th>
		<th rowspan="2">Moisture<br>% ( Max.)</th>
	</tr>
</thead>
<tbody>
	<tr style="height:28px;">
	 <td><?=$agrd['ann1_germination_per']?></td>
	 <td><?=$agrd['ann1_genetic_purity']?></td>
	 <td><?=$agrd['ann1_moisure']?></td>
	</tr>
</tbody>
</table> 
 </td>
</tr>
<tr><td colspan="2" style="height:40px;">&nbsp;</td></tr>
<tr>
 <td class="td" colspan="2" style="width:100%; vertical-align:top;">
  <table style="width:100%;" border="0">
   <tr>
    <td style="width:60%; vertical-align:top;">
	 समय  : - <?=$AgriDate?><br>
     स्थान  : - <?=$Village?>
	</td>
	<td style="width:40%; vertical-align:top;">
	 <b>(प्रथम पक्ष)</b><br>
	  हस्ताक्षर  :  . . . . . . . . . . . . . . . . . . . . . . . . . . . . . <br>
	  पक्ष नाम : VNR SEEDS PVT LTD<br>
	  पता	 :   CORPORATE CENTRE, RING ROAD NO -01, RAIPUR (C.G.)	</td>
   </tr>
   <tr><td style="height:40px;">&nbsp;</td></tr>
   <tr>
    <td style="width:60%; vertical-align:top;">
	</td>
	<td style="width:40%; vertical-align:top;">
	 <b>(द्वितीय पक्ष)</b><br>
	  हस्ताक्षर  :  . . . . . . . . . . . . . . . . . . . . . . . . . . . . . <br>
	  पक्ष नाम : <?=$FarmerN?><br>
	  पता	 :    <?=$FarmerA?>
	</td>
   </tr>
  </table>
 </td>
</tr>
<tr><td colspan="2" style="height:550px;">&nbsp;</td></tr>






<tr>
 <td class="th" colspan="2" style="text-align:center;"><u>Annexure-III</u> <br><b>Payment Condition </b></u></td>
</tr>
<tr>
 <td colspan="2" style="text-align:center;">
<table class="antable" style="width:100%;text-align:center;" border="1">
<thead style="background-color: #cccccc !important;">
	<tr>
		<th rowspan="2">Crop</th>
		<th rowspan="2">SP Code</th>
		<th rowspan="2">Basic procurement<br /> price/kg</th>
		<th rowspan="2">Quality based Incentive</th>
		<th rowspan="2">Payment within days <br />after delivery</th>
	</tr>
</thead>
<tbody>
    <tr style="height:28px;">
     <td><?=$rcrp['cropname']?></td> 
     <td><?=$agrd['ann_fscode_f'].'*'.$agrd['ann_fscode_m']?></td> 
	 <td><?=$agrd['ann2_procmnt_price']?></td>
	 <td><?=$agrd['ann2_qualbased_inc_price']?></td>
	 <td><?=$agrd['ann2_payment_within_day']?></td>
	</tr>
</tbody>
</table> 
 </td>
</tr>
<tr><td colspan="2" style="height:40px;">&nbsp;</td></tr>
<tr>
 <td class="td" colspan="2" style="width:100%; vertical-align:top;">
  <table style="width:100%;" border="0">
   <tr>
    <td style="width:60%; vertical-align:top;">
	 समय  : - <?=$AgriDate?><br>
     स्थान  : - <?=$Village?>
	</td>
	<td style="width:40%; vertical-align:top;">
	 <b>(प्रथम पक्ष)</b><br>
	  हस्ताक्षर  :  . . . . . . . . . . . . . . . . . . . . . . . . . . . . . <br>
	  पक्ष नाम : VNR SEEDS PVT LTD<br>
	  पता	 :   CORPORATE CENTRE, RING ROAD NO -01, RAIPUR (C.G.)	</td>
   </tr>
   <tr><td style="height:40px;">&nbsp;</td></tr>
   <tr>
    <td style="width:60%; vertical-align:top;">
	</td>
	<td style="width:40%; vertical-align:top;">
	 <b>(द्वितीय पक्ष)</b><br>
	  हस्ताक्षर  :  . . . . . . . . . . . . . . . . . . . . . . . . . . . . . <br>
	  पक्ष नाम : <?=$FarmerN?><br>
	  पता	 :    <?=$FarmerA?>
	</td>
   </tr>
  </table>
 </td>
</tr>
<tr><td colspan="2" style="height:550px;">&nbsp;</td></tr>






<tr>
 <td class="th" colspan="2" style="text-align:center;"><u>Annexure-IV</u> <br><b>Cost of FS Seed </b></u></td>
</tr>
<tr>
 <td colspan="2" style="text-align:center;">
<table class="antable" style="width:100%;text-align:center;" border="1">
<thead style="background-color: #cccccc !important;">
	<tr>						
		<th rowspan="2">FS  Seed per Acre supplied<br>( M+F ) in kg</th>
		<th rowspan="2">No. of Acres</th>								
		<th rowspan="2">Total FS Supplied in kg</th>								
		<th rowspan="2">Price/Acre</th>								
		<th rowspan="2">Total Amount</th>								
	</tr>
</thead>
<tbody>
	<tr style="height:28px;">
	  <td><?=$agrd['ann5_parental_seed']?></td>
	  <td><?=$agrd['ann5_noofacr_plant']?></td>
	  <td><?=$agrd['ann5_plant_matsupply']?></td>
	  <td><?=$agrd['ann5_rate']?></td>
	  <td><?=$agrd['ann5_total_amount']?></td>							
	</tr>
</tbody>
</table> 
 </td>
</tr>
<tr><td colspan="2" style="height:40px;">&nbsp;</td></tr>
<tr>
 <td class="td" colspan="2" style="width:100%; vertical-align:top;">
  <table style="width:100%;" border="0">
   <tr>
    <td style="width:60%; vertical-align:top;">
	 समय  : - <?=$AgriDate?><br>
     स्थान  : - <?=$Village?>
	</td>
	<td style="width:40%; vertical-align:top;">
	 <b>(प्रथम पक्ष)</b><br>
	  हस्ताक्षर  :  . . . . . . . . . . . . . . . . . . . . . . . . . . . . . <br>
	  पक्ष नाम : VNR SEEDS PVT LTD<br>
	  पता	 :   CORPORATE CENTRE, RING ROAD NO -01, RAIPUR (C.G.)	</td>
   </tr>
   <tr><td style="height:40px;">&nbsp;</td></tr>
   <tr>
    <td style="width:60%; vertical-align:top;">
	</td>
	<td style="width:40%; vertical-align:top;">
	 <b>(द्वितीय पक्ष)</b><br>
	  हस्ताक्षर  :  . . . . . . . . . . . . . . . . . . . . . . . . . . . . . <br>
	  पक्ष नाम : <?=$FarmerN?><br>
	  पता	 :    <?=$FarmerA?>
	</td>
   </tr>
  </table>
 </td>
</tr>
<tr><td colspan="2" style="height:550px;">&nbsp;</td></tr>



<tr>
 <td class="th" colspan="2" style="text-align:center;"><u>Annexure-V</u> <br><b>Land Details</b></u></td>
</tr>
<tr>
 <td colspan="2" style="text-align:center;">
<table class="antable" style="width:100%;text-align:center;" border="1">
<thead style="background-color: #cccccc !important;">
	<tr>
		<th style="width:300px;">Sowing Acres</th>
		<th>Survey Number</th>										
	</tr>
</thead>
<tbody>
	<tr>
	 <td><?=$agrd['ann7_areain_acre']?></td>
	 <td><?=$agrd['ann7_surveyno']?></td>									
	</tr>
</tbody>
</table> 
 </td>
</tr>
<tr><td colspan="2" style="height:40px;">&nbsp;</td></tr>
<tr>
 <td class="td" colspan="2" style="width:100%; vertical-align:top;">
  <table style="width:100%;" border="0">
   <tr>
    <td style="width:60%; vertical-align:top;">
	 समय  : - <?=$AgriDate?><br>
     स्थान  : - <?=$Village?>
	</td>
	<td style="width:40%; vertical-align:top;">
	 <b>(प्रथम पक्ष)</b><br>
	  हस्ताक्षर  :  . . . . . . . . . . . . . . . . . . . . . . . . . . . . . <br>
	  पक्ष नाम : VNR SEEDS PVT LTD<br>
	  पता	 :   CORPORATE CENTRE, RING ROAD NO -01, RAIPUR (C.G.)	</td>
   </tr>
   <tr><td style="height:40px;">&nbsp;</td></tr>
   <tr>
    <td style="width:60%; vertical-align:top;">
	</td>
	<td style="width:40%; vertical-align:top;">
	 <b>(द्वितीय पक्ष)</b><br>
	  हस्ताक्षर  :  . . . . . . . . . . . . . . . . . . . . . . . . . . . . . <br>
	  पक्ष नाम : <?=$FarmerN?><br>
	  पता	 :    <?=$FarmerA?>
	</td>
   </tr>
  </table>
 </td>
</tr>
<tr><td colspan="2" style="height:500px;">&nbsp;</td></tr>









<tr>
 <td class="th" colspan="2" style="text-align:center;"><u>Annexure-VI</u> <br><b>Health Declaration</b></u></td>
</tr>
<tr>
 <td colspan="2" style="text-align:center;">
<table class="antable" style="width:100%;text-align:center;">
<thead style="background-color: #cccccc !important;">
<tr>
	<th >स्वास्थ सुरक्षा एवं पर्यावरण नीति (पालिसी)</th>
</tr>

</thead>
<tbody>
<tr style="height:28px;">
	<td style="font-size:14px; text-align:left;">
		<br><br>
<b>*</b>	अधिकृत प्रतिनिधि , अधिकारियों एवं हमारे आतंरिक अभ्यास कोड के द्धारा निर्धारित समस्त आवश्यकताओं के साथ अनुपालन व पूर्ति किया जावेगा |
<br><br>											
<b>*</b>	हम लेखा तकनीकी विकास , ग्राहकों की आवश्यकताओं को ध्यम में रखतें हुये स्वास्थ्य, सुरक्षा एवं पर्यावरण को लगातार सुधारेंगे |
<br><br>
<b>*</b>	हम ग्राहकों एवं जनता को हमारे उत्पाद को सुरक्षित उपयोग हेतु शिक्षित करेंगे |
<br><br>
<b>*</b>	हम अपने कर्मचारियों , ठेकेदारों, प्रदायकों को पर्यावरण की सुरक्षा व पर्यावरण हितार्थ व मित्रतापूर्ण व्यवहार करने व रखने हेतु प्रशिक्षित करेंगे |
<br><br>
<b>*</b>	हम स्वास्थ्य, सुरक्षा एवं पर्यावरण की नीतियों तथा कार्यक्रमों को अपने व्यवसायी कार्यों के साथ जोड़कर करने हेतु वचनबद्ध है |
<br><br>
<b>*</b>	हम स्वास्थ्य, सुरक्षा एवं पर्यावरण के व उनके मुद्दों के प्रति समर्पित है तथा समस्त अभ्यास, प्रक्रियाओं तथा उत्पादों का प्रभाव उनके अनुरूप है | हम व्यापार के साथ जनता व ग्राहकों की अपेक्षाओं के साथ हैं |

<br><br><br>
&nbsp;&nbsp;&nbsp;&nbsp;मैं श्री <?=ucwords(strtolower($agrd['fname']))?> पिता <?=ucwords(strtolower($agrd['father_name']))?>, VNR SEEDS PVT LTD स्वास्थ सुरक्षा एवं पर्यावरण नीति के समस्त बिन्दुओ का पालन करने के लिये सहमत एवं वचनबद्ध हूँ |
<br><br><br>
</td>

</tr>
</tbody>
</table> 
 </td>
</tr>
<tr><td colspan="2" style="height:40px;">&nbsp;</td></tr>
<tr>
 <td class="td" colspan="2" style="width:100%; vertical-align:top;">
  <table style="width:100%;" border="0">
   <tr>
    <td style="width:60%; vertical-align:top;">
	 समय  : - <?=$AgriDate?><br>
     स्थान  : - <?=$Village?>
	</td>
	<td style="width:40%; vertical-align:top;">
	 <b>(प्रथम पक्ष)</b><br>
	  हस्ताक्षर  :  . . . . . . . . . . . . . . . . . . . . . . . . . . . . . <br>
	  पक्ष नाम : VNR SEEDS PVT LTD<br>
	  पता	 :   CORPORATE CENTRE, RING ROAD NO -01, RAIPUR (C.G.)	</td>
   </tr>
   <tr><td style="height:40px;">&nbsp;</td></tr>
   <tr>
    <td style="width:60%; vertical-align:top;">
	</td>
	<td style="width:40%; vertical-align:top;">
	 <b>(द्वितीय पक्ष)</b><br>
	  हस्ताक्षर  :  . . . . . . . . . . . . . . . . . . . . . . . . . . . . . <br>
	  पक्ष नाम : <?=$FarmerN?><br>
	  पता	 :    <?=$FarmerA?>
	</td>
   </tr>
  </table>
 </td>
</tr>
<tr><td colspan="2" style="height:250px;">&nbsp;</td></tr>





<?php $sorg=mysql_query("SELECT o.*, v.VillageName, t.TahsilName, d.DictrictName, s.StateName FROM organiser o left join village v on o.village_id=v.VillageId left join tahsil t on o.tahsil_id=t.TahsilId left join distric d on o.district_id=d.DictrictId left join state s on o.state_id=s.StateId where o.oid='".$agrd['org_id']."'"); 
$org=mysql_fetch_assoc($sorg); 

$OrgN=ucwords(strtolower($org['oname']));

?>


<tr>
 <td class="th" colspan="2" style="text-align:center;"><u>Annexure-VII</u> <br><b>मुख्त्यारनामा</b></u></td>
</tr>
<tr>
 <td colspan="2" style="text-align:center;">
<table class="antable" style="width:100%;text-align:center;">
<tbody>
<tr style="height:28px;">
	<td style="font-size:14px; text-align:left;">
	
 यह मुख्त्यारनामा आम आज <?=$AgriDate?> दिनांक को <?=$Village?> में यह मुख्त्यारनामा निष्पादित किया गया है! <p>
	  
 <b>मुख्त्यारनामा नियुक्त कर्ता :</b><br>
 श्री <b><?=ucwords(strtolower($agrd['fname']))?></b> आत्मज श्री <b><?=ucwords(strtolower($agrd['father_name']))?></b> उम्र लगभग <?=$rfrm['age']?> वर्ष, के स्वामित्व व अधिकार की 
 कृषि भूमि सर्वे नं <?=$agrd['ann7_surveyno']?>, रकबा <?=$agrd['ann7_areain_acre']?> एकड़, ग्राम/पोस्ट:  <?=ucwords($agrd['VillageName'])?>, <?=ucwords($agrd['TahsilName'])?> जिला: <?=ucwords($agrd['DictrictName'])?> (<?=ucwords($agrd['StateName'])?>) में स्थित है! जो आगे चलकर सुविधा दृष्टि से उत्पादक कहलायेगा (जो और जिसके वारिसान, उत्तराधिकारी व प्रतिनिधि इसमें सम्मिलित है)|<br><br>

 <b>मुख्त्यारनामा प्राप्त कर्ता :</b><br>
 श्री <b><?=ucwords(strtolower($org['oname']))?></b> आत्मज श्री <b><?=ucwords(strtolower($org['fname']))?></b> उम्र लगभग <?=$org['age']?> वर्ष, निवासी ग्राम-<?=ucwords($org['VillageName'])?>, तहसील-<?=ucwords($org['TahsilName'])?>, जिला-<?=ucwords($org['DictrictName'])?>, राज्य-<?=ucwords($org['StateName'])?> है| जो आगे चलकर सुविधा दृष्टि से आर्गेनाईजर कहलायेगा(जो और जिसके वारिसन, उत्तराधिकारी व प्रतिनिधि इसमें सम्मिलित है)|<br><br>

 यह कि, व्ही एन आर सीड्स प्राइवेट लिमिटेड (व्ही एस पी एल), जो कि कंम्पनी अधिनियम 1956 अंन्तर्गत भारत में स्थापित कंम्पनी है! जो दिनांक <?=$AgriDate?> को बीज उत्पादन : उत्पादक अनुबन्ध के अंन्तर्गत उत्पादक के साथ अनुबन्ध किया है|<br><br>

 यह कि, व्ही एन आर सीड्स प्राइवेट लिमिटेड द्वारा उत्पादक अनुबन्ध में उल्लेखित शर्तों के अधीन बीज उत्पादन के सापेछ में उत्पन्न किसी भी प्रकार की धनराशि का भुगतान (जिसे आगे भुगतान कहा जायेगा) उत्पादक को किया जावेगा|<br><br>

 यह कि, उत्पादक इस बीज उत्पादन के सम्बन्ध में व्ही एन आर सीड्स प्राइवेट लिमिटेड से समस्त व सभी प्रकार के उत्पन्न भुगतान प्राप्त करने हेतु आर्गेनाईजर को अधिकृत करता है|<br><p>
	
	
 <u><b>उत्पादक निम्नलिखित घोषणाओं के तहत आर्गेनाईजर को अधिकृत करता है:-</b></u><br><br>

<b>*</b>	यह कि, व्ही एन आर सीड्स प्राइवेट लिमिटेड द्वारा उत्पादक अनुबन्ध में लिखित शर्तो के अधीन समस्त व किसी भी प्रकार के उतपन्न व निर्मित भुगतान को प्राप्त करने हेतु आर्गेनाईजर को इस मुख्त्यारनामा के द्वारा अधिकृत करता हूँ ! में स्पष्टत रूप से सहमत हूँ कि, इस सन्दर्भ में मेरे द्वारा किसी भी प्रकार के भुगतान का दावा व्ही एन आर सीड्स प्राइवेट लिमिटेड से स्वंय व सीधे रूप में नहीं किया जावेगा|<br><br>											
<b>*</b>	यह कि, व्ही एन आर सीड्स प्राइवेट लिमिटेड द्वारा प्रदान भुगतान के सन्दर्भ में रसीद व अन्य लिखित दस्तावेज़ प्राप्त करने हेतु आर्गेनाईजर अधिकृत है|<br><br>
<b>*</b>	यह कि, मुझे किये गये भुगतान की पृष्टि से सम्बन्धित दस्तावेज़ प्राप्त करने हेतु अधिकृत है|<br><br> 
<b>*</b>	यह कि, उत्पादक अनुबन्ध के अन्तर्गत भुगतान सम्बन्धित समस्त विवरण व्ही एन आर सीड्स प्राइवेट लिमिटेड को प्रदान करने हेतु अधिकृत है|<br><br>
<b>*</b>	यह कि, उत्पादक अनुबन्ध के अन्तर्गत उतपन्न समस्त दावों के निवारण हेतु अधिकृत है|<br><br>
<b>*</b>	यह कि, आर्गेनाइजर उत्पादक अनुबन्ध के अन्तर्गत बीजों व अन्य इससे सम्बन्धित समस्त सामग्रियों को प्राप्त करने व उसे व्ही एन आर सीड्स प्राइवेट लिमिटेड को प्रदान करने हेतु अधिकृत है|<br><br>
<b>*</b>    यह कि, सामान्यत: यह मुख्त्यारनामा में वर्णित अधिकार व यह बीज उत्पादन के उदेश्यों में मान्य होगा|<br><br> 
<b>*</b>    यह कि, उत्पादक यह मुख्त्यारनामा 30 दिनों पूर्व लिखित सूचना देकर किसी भी समय निरस्त कर सकता है, जिसकी लिखित सूचना उत्पादक द्वारा व्ही एन आर सीड्स प्राइवेट लिमिटेड को भी दी जावेगी|<br><br>
<b>*</b>    यह कि, इस सन्दर्भ में उत्पन्न किसी भी प्रकार वाद - विवाद का निपटारा भारतीय कानून के अन्तर्गत रायपुर (छत्तीसगढ़) के न्यायालय में ही किया जावेगा, जिस पर दोनों पक्ष अपनी - अपनी सहमति प्रदान करता है |<br><p>

यह कि, में यह मुख्त्यारनामा द्वारा व इससे सम्बन्धित समस्त उक्त अधिकार मात्र आर्गेनाइजर को प्रदान किया हूँ| आर्गेनाइजर इस मुख्त्यारनामा के आधार पर अन्य को अधिकृत नहीं कर सकता है|

<br><br><br>
&nbsp;&nbsp;&nbsp;&nbsp;अतएव आज दिनांक <?=$AgriDate?> को दोनों पक्ष इस मुख्त्यारनामा की शर्तों को अच्छी तरह पड़कर, समझकर आपसी सहमति से स्वस्थचित अवस्था में दो गवाहों के 
समक्ष मुकाम <?=$Village?> में अपने - अपने हस्ताक्षर कर दिये, ताकि सनद रहे जरूरत पर काम आवें|
<br><br><br>
</td>

</tr>
</tbody>
</table> 
 </td>
</tr>
<tr><td colspan="2" style="height:25px;">&nbsp;</td></tr>
<tr>
 <td class="td" colspan="2">
  समय  : - <?=$AgriDate?><br>
  स्थान  : - <?=$Village?>
 </td>
</tr>

<?php 
$s7=mysql_query("select TermId, Type, TermDetails from agri_term where Type='SignFN'"); $rs7=mysql_fetch_assoc($s7); 
$s8=mysql_query("select TermId, Type, TermDetails from agri_term where Type='SignFA'"); $rs8=mysql_fetch_assoc($s8); 
$s9=mysql_query("select TermId, Type, TermDetails from agri_term where Type='SignFN2'"); $rs9=mysql_fetch_assoc($s9); 
$s10=mysql_query("select TermId, Type, TermDetails from agri_term where Type='SignFA2'"); $rs10=mysql_fetch_assoc($s10); 
$s11=mysql_query("select TermId, Type, TermDetails from agri_term where Type='SignFM2'"); $rs11=mysql_fetch_assoc($s11);
?>

<tr>
 <td class="td" colspan="2" style="width:100%; vertical-align:top;">
  <table style="width:100%;" border="0">
   <tr>
    <td style="width:60%; vertical-align:top;">
	  हस्ताक्षर  :  . . . . . . . . . . . . . . . . . <br>
	  <b>उत्पादक</b> :  <br>
	  पता	 :   
	</td>
	<td style="width:40%; vertical-align:top;">
	  हस्ताक्षर  :  . . . . . . . . . . . . . . . . . <br>
	  <b>आर्गेनाइजर</b> : <?=$OrgN?><br>
	  पता	 :    <?=$OrgA?>
	</td>
   </tr>
   <tr><td colspan="2">&nbsp;</td></tr>
   <tr>
    <td class="td" style="width:60%; vertical-align:top;"> 
	<u><b>गवाह</b></u><p>  
	  <b>(1)</b> हस्ताक्षर  :  . . . . . . . . . . . . . . . . . <br>
	  नाम	 :  <br>
	  संपर्क/मोबाइल :   <br>
	  पता :         
	</td>
		
	<td class="td" style="width:40%; vertical-align:top;">
	<u><b>गवाह</b></u><p>  
	  <b>(1)</b> हस्ताक्षर  :  . . . . . . . . . . . . . . . . . <br>
	  नाम :   <br>
	  संपर्क/मोबाइल	 : <br>   
	  पता :        
	</td>
   </tr>
   <tr><td colspan="2">&nbsp;</td></tr>
   <tr>
    <td class="td" style="width:60%; vertical-align:top;">   
	  <b>द्वारा प्रामाणित</b> :   <br>
	  <b>प्रथम पक्ष</b>	 :  VNR SEEDS PVT LTD<br>     
	</td>
   </tr>
  </table>
 </td>
</tr>






<?php } ?>
<tr><td colspan="2" style="height:50px;">&nbsp;</td></tr>
<tr>
 <td colspan="2" style="color:#000066;text-align:center; cursor:pointer;">
  <span onClick="printP()" id="PPSpn" style="background-color:#80FF00;">&nbsp;&nbsp;<u><b>PRINT</b></u>&nbsp;&nbsp;</span>
 </td>
</tr>
<tr><td colspan="2" style="height:100px;">&nbsp;</td></tr>
</table>
<script type="text/javascript">
function printP()
{
 document.getElementById("PPSpn").style.display='none';
 window.print();
}
</script>

