<?php 
include '../config.php';
date_default_timezone_set('asia/calcutta');

 if($_REQUEST['userid'] == '' || $_REQUEST['value'] == '')
 {
  echo json_encode(array( "status" => "100","msg" => "Parameter missing!") );
 }
 elseif($_REQUEST['userid']!= '' && $_REQUEST['value'] == 'agreereport')
 {
 
  //$from = date('Y-m-d',strtotime($_REQUEST['dfrom'])); $agyearf = (int)date('Y',strtotime($_REQUEST['dfrom']));
  //$to = date('Y-m-d',strtotime($_REQUEST['dto']));     $agyeart = (int)date('Y',strtotime($_REQUEST['dto']));
  
  
  //----------------------------------------------------------------
  //----------------------------------------------------------------	
  //User List with Reporting
  
  $uu1=0; $uu2=0; $uu3=0; $uu4=0; $uu5=0; $uu6=0; 
  $Rep=$_REQUEST['userid'];
  
  $array_u1=array(); $array_u2=array(); $array_u3=array(); $array_u4=array(); $array_u5=array(); $array_u6=array();
  $su1=mysql_query("select * from users where uStatus='A' and uReporting=".$Rep); $row1=mysql_num_rows($su1);
  if($row1>0)
  {
   while($ru1=mysql_fetch_assoc($su1)){ $array_u1[]=$ru1['uId']; } 
   $uu1 = implode(',', $array_u1);
  
   $su2=mysql_query("select * from users where uStatus='A' and uReporting in (".$uu1.") "); $row2=mysql_num_rows($su2);
   if($row2>0)
   {
    while($ru2=mysql_fetch_assoc($su2)){ $array_u2[]=$ru2['uId']; } 
    $uu2 = implode(',', $array_u2); 
  
    $su3=mysql_query("select * from users where uStatus='A' and uReporting in (".$uu2.") "); $row3=mysql_num_rows($su3);
	if($row3>0)
	{
     while($ru3=mysql_fetch_assoc($su3)){ $array_u3[]=$ru3['uId']; } 
     $uu3 = implode(',', $array_u3); 
  
     $su4=mysql_query("select * from users where uStatus='A' and uReporting in (".$uu3.") "); $row4=mysql_num_rows($su4);
	 if($row4>0)
	 {
      while($ru4=mysql_fetch_assoc($su4)){ $array_u4[]=$ru4['uId']; } 
      $uu4 = implode(',', $array_u4);
	  
	  $su5=mysql_query("select * from users where uStatus='A' and uReporting in (".$uu4.") "); $row5=mysql_num_rows($su5);
	  if($row5>0)
	  {
       while($ru5=mysql_fetch_assoc($su5)){ $array_u5[]=$ru5['uId']; } 
       $uu5 = implode(',', $array_u5);
	   
	   $su6=mysql_query("select * from users where uStatus='A' and uReporting in (".$uu5.") "); $row6=mysql_num_rows($su6);
	   if($row6>0)
	   {
        while($ru6=mysql_fetch_assoc($su6)){ $array_u6[]=$ru6['uId']; } 
        $uu6 = implode(',', $array_u6);
	   } 
	   
	  } 
	  
	 } 
	} 
   }	 
  }	
  $uesrList=$uu1.','.$uu2.','.$uu3.','.$uu4.','.$uu5.','.$uu6.','.$_REQUEST['userid'];
  	
  
  
  //----------------------------------------------------------------	
  //----------------------------------------------------------------
  
  
  $sSe=mysql_query("select * from view_season where SesId=1"); $rSe=mysql_fetch_assoc($sSe);
  $sVd=mysql_query("select * from app_version where VersionId=1"); $rVd=mysql_fetch_assoc($sVd); 
  $Kdfy=date("Y",strtotime($rVd['Kharif_From'])); 
  $Kdty=date("Y",strtotime($rVd['Kharif_To']));
  $Rdfy=date("Y",strtotime($rVd['Rabi_From']));  
  $Rdty=date("Y",strtotime($rVd['Rabi_To'])); 
  
  $Sdfy=date("Y",strtotime($rVd['Summer_From']));
  $Sdty=date("Y",strtotime($rVd['Summer_To']));
  
  if($rSe['Kharif']=='Y' AND $rSe['Rabi']=='Y')
  { 
     $from=date('Y-m-d',strtotime($rVd['Kharif_From']));  
     $to=date('Y-m-d',strtotime($rVd['Rabi_To']));
     $Dfy=$Kdfy; $Dty=$Rdty; 
  }
  elseif($rSe['Rabi']=='Y' AND $rSe['Summer']=='Y')
  { 
     $from=date('Y-m-d',strtotime($rVd['Rabi_From']));   
     $to=date('Y-m-d',strtotime($rVd['Summer_To'])); 
     $Dfy=$Rdfy; $Dty=$Sdty;
  }
  elseif($rSe['Summer']=='Y' AND $rSe['Kharif']=='Y')
  { 
     $from=date('Y-m-d',strtotime($rVd['Summer_From']));    
     $to=date('Y-m-d',strtotime($rVd['Kharif_To'])); 
     $Dfy=$Sdfy; $Dty=$Kdty;
  }
  
  /*
  if($rSe['Kharif']=='Y')
  { 
    $from=date('Y-m-d',strtotime($rVd['Kharif_From'])); 
    $to=date('Y-m-d',strtotime($rVd['Kharif_To']));
    $Dfy=$Kdfy; $Dty=$Kdty; 
  }
  elseif($rSe['Rabi']=='Y')
  { 
    $from=date('Y-m-d',strtotime($rVd['Rabi_From'])); 
    $to=date('Y-m-d',strtotime($rVd['Rabi_To']));  
    $Dfy=$Rdfy; $Dty=$Rdty; 
  }
  */
  
  $agyearf = (int)date($Dfy,strtotime($from));
  $agyeart = (int)date($Dty,strtotime($to));
  
  //$from = date($Dfy.'-m-d',strtotime($_REQUEST['dfrom'])); $agyearf = (int)date($Dtf,strtotime($_REQUEST['dfrom']));
  //$to = date($Dty.'-m-d',strtotime($_REQUEST['dto']));     $agyeart = (int)date($Dty,strtotime($_REQUEST['dto']));
  
  //$from = date('2019-m-d',strtotime($_REQUEST['dfrom'])); $agyearf = (int)date('2019',strtotime($_REQUEST['dfrom']));
  //$to = date('2020-m-d',strtotime($_REQUEST['dto']));     $agyeart = (int)date('2020',strtotime($_REQUEST['dto']));
 
  /************* <!--------------------------------------------> ****************/
  /************* <!--------------------------------------------> ****************/
   $stid= mysql_query("SELECT state_id from user_location where state_id>0 and sts='A' and uid=".$_REQUEST['userid']);
   $dtid= mysql_query("SELECT district_id from user_location where district_id>0 and sts='A' and uid=".$_REQUEST['userid']);
   $ttid= mysql_query("SELECT tahsil_id from user_location where tahsil_id>0 and sts='A' and uid=".$_REQUEST['userid']);
   $vtid= mysql_query("SELECT village_id from user_location where village_id>0 and sts='A' and uid=".$_REQUEST['userid']);
   $rows=mysql_num_rows($stid); $rowd=mysql_num_rows($dtid); 
   $rowt=mysql_num_rows($ttid); $rowv=mysql_num_rows($vtid);
     
   if($rowv>0)
   {  
      while($rv=mysql_fetch_array($vtid)){ $array_v[]=$rv['village_id']; } 
      $vv = implode(',', $array_v); $qryy='agr.vi'; //$qryy='f.village_id';   
   } 
   else if($rowt>0)
   {  
      while($rt=mysql_fetch_array($ttid)){ $array_t[]=$rt['tahsil_id']; } 
      $vv = implode(',', $array_t); $qryy='agr.ti'; //$qryy='f.tahsil_id';
   }
   else if($rowd>0)
   {
      while($rd=mysql_fetch_array($dtid)){ $array_d[]=$rd['district_id']; } 
      $vv = implode(',', $array_d); $qryy='agr.di'; //$qryy='f.distric_id';
   }
   else
   {
      while($rs=mysql_fetch_array($stid)){ $array_s[]=$rs['state_id']; } 
      $vv = implode(',', $array_s); $qryy='agr.si'; //$qryy='f.state_id';
   }
  /************* <!--------------------------------------------> ****************/
  /************* <!--------------------------------------------> ****************/
   
   
  /************** Check Conditio ********************* Open *********************/
  /************** Check Conditio ********************* Open *********************/
  /*
    if($_REQUEST['crop']=='PADDY SEED'){ $crop=23; }
	else if($_REQUEST['crop']=='MAIZE SEED'){ $crop=24; }
	else if($_REQUEST['crop']=='BAJRA SEED'){ $crop=25; }
	else if($_REQUEST['crop']=='MUSTARD SEED'){ $crop=30; }
	else if($_REQUEST['crop']=='SWEET CORN'){ $crop=31; }  
  */
    $crop=$_REQUEST['crop']; 
  
	if(isset($_REQUEST['crop']) && $_REQUEST['crop']!='')
	{ $cropCond="agr.ann_crop=".$crop; }else{ $cropCond='1=1'; }
	
	if(isset($_REQUEST['orgr']) && $_REQUEST['orgr']!='')
	{ $orgrCond="agr.org_id=".$_REQUEST['orgr'];   }else{ $orgrCond='1=1'; }
	
	if(isset($_REQUEST['hybrid']) && $_REQUEST['hybrid']!='')
	{ $hybrid="agr.ann_prodcode='".$_REQUEST['hybrid']."'"; }else{ $hybrid='1=1'; }
	
	$village='1=1'; $tahsil='1=1'; $distric='1=1'; $state='1=1'; 
	if(isset($_REQUEST['village_name']) && $_REQUEST['village_name']!='')
	{ 
	    $sqlvt=mysql_query("select VillageId from village where VillageName='".$_REQUEST['village_name']."'"); $rvt=mysql_fetch_assoc($sqlvt); $vtds="agr.vi=".$rvt['VillageId'];     
	}
	elseif(isset($_REQUEST['block_name']) && $_REQUEST['block_name']!='')
	{ 
	    $sqltt=mysql_query("select TahsilId from tahsil where TahsilName='".$_REQUEST['block_name']."'"); $rtt=mysql_fetch_assoc($sqltt);  $vtds="agr.ti=".$rtt['TahsilId']; 	    
	} 
	elseif(isset($_REQUEST['district_name']) && $_REQUEST['district_name']!='')
	{ 
	    $sqldt=mysql_query("select DictrictId from distric where DictrictName='".$_REQUEST['district_name']."'"); $rdt=mysql_fetch_assoc($sqldt);  $vtds="agr.di=".$rdt['DictrictId']; 
	    
	}
	elseif(isset($_REQUEST['state']) && $_REQUEST['state']!='')
	{ 
	    $sqlst=mysql_query("select StateId from state where StateName='".$_REQUEST['state']."'"); $rst=mysql_fetch_assoc($sqlst);  $vtds="agr.si=".$rst['StateId']; 
	    
	}else{ $vtds='1=1'; }
	
	
	if(isset($_REQUEST['production_executive_name']) && $_REQUEST['production_executive_name']!='')
	{ 
	    $sqlext=mysql_query("select uId from users where uName='".$_REQUEST['production_executive_name']."'"); $rext=mysql_fetch_assoc($sqlext);
	    $executive="agr.prod_executive=".$rext['uId']; 
	    
	}else{ $executive='1=1'; } 
	
	if(isset($_REQUEST['prod_person_name']) && $_REQUEST['prod_person_name']!='')
	{ 
	    $sqlppt=mysql_query("select uId from users where uName='".$_REQUEST['prod_person_name']."'"); $rppt=mysql_fetch_assoc($sqlppt);
	    $pperson="agr.prod_person=".$rppt['uId']; 
	    
	}else{ $pperson='1=1'; }
	
	if(isset($_REQUEST['organiser_name']) && $_REQUEST['organiser_name']!='')
	{ 
	    $sqlorgt=mysql_query("select oid from organiser where oname='".$_REQUEST['organiser_name']."'"); $rorgt=mysql_fetch_assoc($sqlorgt);
	    $organiser="agr.org_id=".$rorgt['oid']; 
	    
	}else{ $organiser='1=1'; }
	
	
	if(isset($_REQUEST['keywordSearch']) && $_REQUEST['keywordSearch']!='')
	{
	    
	 $keyCond="(f.fname like '%".$_REQUEST['keywordSearch']."%' or f.father_name like '%".$_REQUEST['keywordSearch']."%' or o.oname like '%".$_REQUEST['keywordSearch']."%')";	
	}
	else{ $keyCond='1=1';}
	
	//== query condition for getting results as per users hierarchy 
	//$allids = array_unique(gethierarchy($_REQUEST['userid']));
	//$hierarchyCond = "agr.cr_by IN (".implode(', ', $allids).")";
	
  /************** Check Conditio ********************* Close *********************/
  /************** Check Conditio ********************* Close *********************/ 
      
  
  /********************** 0000000000000000000000000000 11111 Open Open ***/
  /********************** 0000000000000000000000000000 11111 Open Open ***/
   
   $qry=""; 
   for($i=$agyearf; $i <= $agyeart; $i++)
   {   
       
     
	 $qry.="SELECT agr.*, u.uName as Prod_Person, uu.uName as Prod_Executive, o.oname as Organiser_Name, f.tem_fid, f.fname as Farmer_Name, f.father_name, f.village_id, f.tahsil_id, f.distric_id, f.state_id, c.cropname, c.cropcode, v.VillageName as village_name, t.TahsilName as block_name, d.DictrictName as district_name, s.StateName as state_name FROM `agreement_".$i."` agr, users u, users uu, farmers f, organiser o, crop c, village v, tahsil t, distric d, state s where agree_date between '".$from."' and '".$to."' and ".$cropCond." and ".$orgrCond." and ".$keyCond." and ".$hybrid." and ".$executive." and ".$pperson." and ".$organiser." and ".$state." and ".$distric." and ".$tahsil." and ".$village." and ".$vtds." and agr.prod_person=u.uId and agr.prod_executive=uu.uId and agr.second_party=f.fid and agr.org_id=o.oid and agr.ann_crop=c.cropid and agr.vi=v.VillageId and agr.ti=t.TahsilId and agr.di=d.DictrictId and agr.si=s.StateId and agr.prod_executive in (".$uesrList.")";
	 
	 //$qry.="SELECT agr.*, u.uName, f.tem_fid, f.fname, f.father_name, o.oname, f.village_id, f.tahsil_id, f.distric_id, f.state_id, c.cropname, c.cropcode, v.VillageName as village_name, t.TahsilName as block_name, d.DictrictName as district_name, s.StateName as state_name FROM `agreement_".$i."` agr, users u, farmers f, organiser o, crop c, village v, tahsil t, distric d, state s where agree_date between '".$from."' and '".$to."' and ".$cropCond." and ".$orgrCond." and ".$keyCond." and ".$hybrid." and ".$executive." and ".$pperson." and ".$organiser." and ".$state." and ".$distric." and ".$tahsil." and ".$village." and ".$vtds." and agr.prod_person=u.uId and agr.second_party=f.fid and agr.org_id=o.oid and agr.ann_crop=c.cropid and agr.vi=v.VillageId and agr.ti=t.TahsilId and agr.di=d.DictrictId and agr.si=s.StateId and ".$qryy." in (".$vv.")";
	 
	 
	 if($i!=$agyeart){ $qry.=' UNION '; }
   }
     //echo $qry;
   $run_qry=mysql_query($qry); $num=mysql_num_rows($run_qry); $farray = array();
   
   if($num>0)
   {
     while($res=mysql_fetch_assoc($run_qry))
     {   
         
	   if($res['ann7_flandid']>0)
	   {
	    
		$fld=mysql_query('SELECT StateName, DictrictName, TahsilName, VillageName FROM farmers_land fl, state s, distric d, tahsil t, village v where fl.StateId=s.StateId AND fl.DictrictId=d.DictrictId AND fl.TahsilId=t.TahsilId AND fl.VillageId=v.VillageId AND flandid='.$res['ann7_flandid']); $rld=mysql_fetch_assoc($fld);
	   }
	   
	   $agr_y = substr($res['agree_no'], 0, 4); $lotno = array();
	   if($res['agree_no']>0)
	   {
		$slot=mysql_query("SELECT SUM(noofbag) as tbag, SUM(qty) as tqty FROM agreementlot_".$agr_y." where agree_no='".$res['agree_no']."'"); 
		$rlot=mysql_fetch_assoc($slot);
	
	   }
	   
	   $res['Lot_Qty']=$rlot;
	   $farray[]=$res;
     } 
     
     
    
     echo json_encode(array( "agreement_reports" => $farray, "countvalue" =>$num) ); 
        
   }
   else 
   {
    //echo 'hello error'; die();   
    echo json_encode(array( "data" => "100", "msg" => "Data Not Found!") );
   }
  
   /********************** 0000000000000000000000000000 11111 Close Close ***/
   /********************** 0000000000000000000000000000 11111 Close Close ***/
   
 }
 else 
 {
   echo json_encode(array( "data" => "100", "msg" => "Missing Value!") );
 }
 
 
 
 
function gethierarchy($uid)
{
 $id=array($uid);	
 /*if($_SESSION['uType']=='S')
 {
   $sel=mysql_query("select uId from users");
   while($seld = mysql_fetch_assoc($sel))
   {
	$idc = array($seld['uId']);
	$id = array_merge($id,$idc);
   }
 }
 else
 {*/
   $sel=mysql_query("select uId from users where uReporting='".$uid."'");
   if(mysql_num_rows($sel) > 0)
   {
	  while($seld = mysql_fetch_assoc($sel))
	  {
		$idc = array($seld['uId']);
		$id = array_merge($id,$idc,gethierarchy($seld['uId']));
	  }
   }
 /*}*/
 return $id;	
} 
 
 
 
?>










