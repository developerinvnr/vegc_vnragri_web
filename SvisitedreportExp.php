<?php

session_start();

include "config.php";

date_default_timezone_set('Asia/Calcutta');



function getUName($uid){

	$u=mysql_query("select uName from users where uId ='".$uid."'");

	$ud=mysql_fetch_assoc($u);

	return $ud['uName'];



}



function gethierarchy($uid){



	$id=array($uid);

	

	if($_SESSION['uType']=='S'){

		$sel=mysql_query("select uId from users");

		while($seld = mysql_fetch_assoc($sel)){



			$idc = array($seld['uId']);



			$id = array_merge($id,$idc);



		}



	}else{

		$sel=mysql_query("select uId from users where uReporting='".$uid."'");

		if(mysql_num_rows($sel) > 0){

			while($seld = mysql_fetch_assoc($sel)){

				$idc = array($seld['uId']);

				$id = array_merge($id,$idc,gethierarchy($seld['uId']));

			}

		}

	}

	return $id;	



}





$from = date('Y-m-d',strtotime($_REQUEST['from']));

$agyearf = (int)date('Y',strtotime($_REQUEST['from']));



$to = date('Y-m-d',strtotime($_REQUEST['to']));

$agyeart = (int)date('Y',strtotime($_REQUEST['to']));
$from11 = date('d-m-Y',strtotime($_REQUEST['from']));
$to11 = date('d-m-Y',strtotime($_REQUEST['to']));
$xls_filename = 'Scheduled Visit Report_'.$from11.'-'.$to11.'.xls'; 
header("Content-Type: application/csv");
header("Content-Disposition: attachment; filename=$xls_filename");
header("Pragma: no-cache");
header("Expires: 0");
$sep = "\t"; 

echo "Sn\tAgreement-ID\tAgreement Date\tCrop\tFemale FSCode\tMale FSCode\tHybrid Code\tFarmer Name\tFarmer Id\tOrganiser Name\tFarmer Father Name\tFARMER Contact\tProduction Person\tProduction Executive\tVisit Date\tEstimated/ Acaeage Yield\tStandard Acreage\tEstimated Yield in kg";

print("\n");

$alls=mysql_query('SELECT * FROM `state`');
while ($allsd=mysql_fetch_assoc($alls)) {
	$sarr[$allsd['StateId']]=$allsd['StateName'];
}


$alld=mysql_query('SELECT * FROM `distric`');
while ($alldd=mysql_fetch_assoc($alld)) {
	$darr[$alldd['DictrictId']]=$alldd['DictrictName'];
}

$allt=mysql_query('SELECT * FROM `tahsil`');
while ($alltd=mysql_fetch_assoc($allt)) {
	$tarr[$alltd['TahsilId']]=$alltd['TahsilName'];
}


$allv=mysql_query('SELECT * FROM `village`');
while ($allvd=mysql_fetch_assoc($allv)) {
	$varr[$allvd['VillageId']]=$allvd['VillageName'];
}

//==== query condition for crop filter =======================================================
if(isset($_REQUEST['crop']) && $_REQUEST['crop']!=''){
	$cropCond="agr.ann_crop=".$_REQUEST['crop'];
}else{
	$cropCond='1=1';
}

//==== query condition for production code filter =======================================================
if(isset($_REQUEST['prodcode']) && $_REQUEST['prodcode']!=''){
	$prodcode="agr.ann_prodcode='".$_REQUEST['prodcode']."'";
}else{
	$prodcode='1=1';
}
$prodcode='1=1';


//==== query condition for organiser filter ==================================================
if(isset($_REQUEST['orgr']) && $_REQUEST['orgr']!=''){
	$orgrCond="agr.org_id=".$_REQUEST['orgr'];
}else{
	$orgrCond='1=1';
}


//==== query condition for production person filter ==================================================
if(isset($_REQUEST['pperson']) && $_REQUEST['pperson']!=''){
	$pperson="agr.prod_person=".$_REQUEST['pperson'];
}else{
	$pperson='1=1';
}


//==== query condition for production executive filter ==================================================
if(isset($_REQUEST['pexe']) && $_REQUEST['pexe']!=''){
	$pexecutive="agr.prod_executive=".$_REQUEST['pexe'];
}else{
	$pexecutive='1=1';
}

//==== query condition for organiser filter ==================================================
if(isset($_REQUEST['secondparty']) && $_REQUEST['secondparty']!=''){
	$farmerCond="agr.second_party=".$_REQUEST['secondparty'];
}else{
	$farmerCond='1=1';
}


$userCond='1=1';


//==== query condition for keyword search START==================================================
    if(isset($_REQUEST['vii']) && $_REQUEST['vii']!=''){ $keyArea="agr.vi like '%".$_REQUEST['vii']."%'";}
	elseif(isset($_REQUEST['tii']) && $_REQUEST['tii']!=''){ $keyArea="agr.ti like '%".$_REQUEST['tii']."%'";}
	elseif(isset($_REQUEST['dii']) && $_REQUEST['dii']!=''){ $keyArea="agr.di like '%".$_REQUEST['dii']."%'";}
	elseif(isset($_REQUEST['sii']) && $_REQUEST['sii']!=''){ $keyArea="agr.si like '%".$_REQUEST['sii']."%'";}
	else{$keyArea='1=1';}


if(isset($_REQUEST['keywordSearch']) && $_REQUEST['keywordSearch']!='')
{

  $keyCond="(f.fname like '%".$_REQUEST['keywordSearch']."%' or o.oname like '%".$_REQUEST['keywordSearch']."%' or agr.ann_prodcode like '%".$_REQUEST['keywordSearch']."%' or agr.agree_no like '%".$_REQUEST['keywordSearch']."%' or s.StateName like '%".$_REQUEST['keywordSearch']."%' or d.DictrictName like '%".$_REQUEST['keywordSearch']."%' or t.TahsilName like '%".$_REQUEST['keywordSearch']."%' or v.VillageName like '%".$_REQUEST['keywordSearch']."%')";
}
else
{
  $keyCond='1=1';
}


if(isset($_REQUEST['users']) && $_REQUEST['users']!='')
{	
 function get2hierarchy($uid)
 {
  $id=array($uid);
  $sel=mysql_query("select uId from users where uReporting='".$uid."'");
  if(mysql_num_rows($sel) > 0)
  {
	while($seld = mysql_fetch_assoc($sel))
	{
	 $idc = array($seld['uId']);
	 $id = array_merge($id,$idc,get2hierarchy($seld['uId']));
	}
  }
  return $id;	
 }

 $allids = array_unique(get2hierarchy($_REQUEST['users'])); //$_SESSION['uId']
 $hierarchyCond = "(agr.prod_person IN (".implode(', ', $allids).") or agr.prod_executive IN (".implode(', ', $allids)."))";	
}
else { $hierarchyCond='1=1'; }		


$arr_Ve=array(); $arr_Vg=array(); $arr_Va=array(); $arr_Vp=array(); $arr_Vu=array();	
$arr_Fe=array(); $arr_Fg=array(); $arr_Fa=array(); $arr_Fp=array(); $arr_Fu=array();	
$arr_pFe=array(); $arr_pFg=array(); $arr_pFa=array(); $arr_pFp=array(); $arr_pFu=array();
	
//==== query condition for getting results as per users hierarchy ================= end =======================
	$qry="";
	for ($i=$agyearf; $i <= $agyeart; $i++) 
	{

      $qry.=" SELECT agr.*, u.uName, f.tem_fid, f.fname, f.father_name, o.oname, f.contact_1, VisitDate, EstAcrYiels, StndAcr, EstYielsKg FROM agreement_remark rmk, `agreement_".$i."` agr, users u, farmers f, organiser o where rmk.VisitDate between '".$from."' and '".$to."' and rmk.agree_no=agr.agree_no and agr.prod_person=u.uId and agr.second_party=f.fid and agr.org_id=o.oid and ".$cropCond." and ".$orgrCond." and ".$farmerCond." and ".$hierarchyCond." and ".$userCond." and ".$prodcode." and ".$pperson." and ".$keyArea." and ".$pexecutive." and ".$keyCond;

	  if($i!=$agyeart)
	  {
		$qry.=' UNION';
	  }

	}
	//echo $qry;
	$agr=mysql_query($qry);
    $sn=1;
	while ($agrd=mysql_fetch_assoc($agr)) 
	{
	if($agrd['ann_crop']>0)
	{ $cr=mysql_fetch_assoc(mysql_query("select cropname,cropcode from crop where cropid=".$agrd['ann_crop'])); 
	  $cropn=$cr['cropname']; }else{ $cropn=''; }
	if($agrd['ann7_flandid']>0)
	{
	 $staten=$sarr[$agrd['si']]; $distn=$darr[$agrd['di']]; 
	 $tahsiln=$tarr[$agrd['ti']]; $villagen=$varr[$agrd['vi']];
	}
	else
	{
	 $staten=$sarr[$agrd['state_id']]; $distn=$darr[$agrd['distric_id']]; 
	 $tahsiln=$tarr[$agrd['tahsil_id']]; $villagen=$varr[$agrd['village_id']];
	}


    echo $sn.$sep;
	echo $agrd['agree_no'].$sep;
	echo $agrd['agree_date'].$sep;
	echo $cropn.$sep;
	echo $agrd['ann_fscode_f'].$sep;
	echo $agrd['ann_fscode_m'].$sep;
	echo $agrd['ann_prodcode'].$sep;
	echo ucwords(strtolower($agrd['fname'])).$sep;
	$str = chunk_split($agrd['tem_fid'], 4, ' ');
	echo $str.$sep;  //second_party
	echo ucwords(strtolower($agrd['oname'])).$sep;
	echo ucwords(strtolower($agrd['father_name'])).$sep;
	echo '`'.$agrd['contact_1'].'`'.$sep;
	echo ucwords(strtolower($agrd['uName'])).$sep;
	echo getUName($agrd['prod_executive']).$sep;
	
	echo $agrd['VisitDate'].$sep;
	echo $agrd['EstAcrYiels'].$sep;
	echo $agrd['StndAcr'].$sep;
	echo $agrd['EstYielsKg'].$sep;
	

	print("\n");
	$sn++;
}







?>





