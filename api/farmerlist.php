<?php 
include '../config.php';
date_default_timezone_set('asia/calcutta');


 if($_REQUEST['userid'] == '' || $_REQUEST['value'] == '')
 {
   echo json_encode(array( "status" => "100","msg" => "Parameter missing!") );
 }
 elseif($_REQUEST['userid']!= '' || $_REQUEST['value'] == 'farmerlist')
 {
 
 
 
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
 
 //From-To Date
 
  $sVd=mysql_query("select * from app_version where VersionId=1"); 
  $rVd=mysql_fetch_assoc($sVd); 
  $Kdf=date("Y-m-d",strtotime($rVd['Kharif_From'])); 
  $Kdt=date("Y-m-d",strtotime($rVd['Kharif_To']));
  $Rdf=date("Y-m-d",strtotime($rVd['Rabi_From']));
  $Rdt=date("Y-m-d",strtotime($rVd['Rabi_To']));
  $Sdf=date("Y-m-d",strtotime($rVd['Summer_From']));
  $Sdt=date("Y-m-d",strtotime($rVd['Summer_To']));
  
  if($ress['Kharif']=='Y' AND $ress['Rabi']=='Y'){ $from=$Kdf; $to=$Rdt; }
  elseif($ress['Rabi']=='Y' AND $ress['Summer']=='Y'){ $from=$Rdf; $to=$Sdt; }
  elseif($ress['Summer']=='Y' AND $ress['Kharif']=='Y'){ $from=$Sdf; $to=$Kdt;  }
  else
  {
   $from = date('Y-m-d',strtotime($_REQUEST['dfrom'])); 
   $to = date('Y-m-d',strtotime($_REQUEST['dto']));     
  } 

    $agyearf = (int)date('Y',strtotime($from));
    $agyeart = (int)date('Y',strtotime($to));
 
 
 
 
   /************* <!--------------------------------------------> ****************/
  /************* <!--------------------------------------------> ****************/
   
   $uchk=mysql_query("SELECT QADept,Qs1,Qs2,Qs3,Qs4,Qs5 from users where uId=".$_REQUEST['userid']); $ruchk=mysql_fetch_assoc($uchk);
   
   if($ruchk['QADept']=='N')
   {
  
     $stid= mysql_query("SELECT state_id from user_location where state_id>0 and sts='A' and uid=".$_REQUEST['userid']);
     $dtid= mysql_query("SELECT district_id from user_location where district_id>0 and sts='A' and uid=".$_REQUEST['userid']);
     $ttid= mysql_query("SELECT tahsil_id from user_location where tahsil_id>0 and sts='A' and uid=".$_REQUEST['userid']);
     $vtid= mysql_query("SELECT village_id from user_location where village_id>0 and sts='A' and uid=".$_REQUEST['userid']);
   
     $rows=mysql_num_rows($stid); $rowd=mysql_num_rows($dtid); 
     $rowt=mysql_num_rows($ttid); $rowv=mysql_num_rows($vtid);
   
     
     if($rowv>0)
     {  
        while($rv=mysql_fetch_array($vtid)){ $array_v[]=$rv['village_id']; } 
        $vv = implode(',', $array_v); $qry='village_id';   
     } 
     else if($rowt>0)
     {  
        while($rt=mysql_fetch_array($ttid)){ $array_t[]=$rt['tahsil_id']; } 
        $vv = implode(',', $array_t); $qry='tahsil_id';
     }
     else if($rowd>0)
     {
        while($rd=mysql_fetch_array($dtid)){ $array_d[]=$rd['district_id']; } 
        $vv = implode(',', $array_d); $qry='distric_id';
     }
     else
     {
        while($rs=mysql_fetch_array($stid)){ $array_s[]=$rs['state_id']; } 
        $vv = implode(',', $array_s); $qry='state_id';
     }
     
   } //if($ruchk=='N')
   
   elseif($ruchk['QADept']=='Y')
   {  
      if($ruchk['Qs1']>0){$s1=$ruchk['Qs1'];}else{$s1=0;}
      if($ruchk['Qs2']>0){$s2=$ruchk['Qs2'];}else{$s2=0;}
      if($ruchk['Qs3']>0){$s3=$ruchk['Qs3'];}else{$s3=0;}
      if($ruchk['Qs4']>0){$s4=$ruchk['Qs4'];}else{$s4=0;}
      if($ruchk['Qs5']>0){$s5=$ruchk['Qs5'];}else{$s5=0;}

      $vv = $s1.','.$s2.','.$s3.','.$s4.','.$s5; 
      $qry='state_id';
   }
     
  /************* <!--------------------------------------------> ****************/
  /************* <!--------------------------------------------> ****************/     
 
   //$uesrList
   /*******************************************************************/
   
   $run_qry=""; 
   for($i=$agyearf; $i <= $agyeart; $i++)
   {   
	 $run_qry.="SELECT f.* FROM `agreement_".$i."` agr, farmers f where agree_date between '".$from."' and '".$to."' and agr.second_party=f.fid and agr.prod_executive in (".$uesrList.")";
	 if($i!=$agyeart){ $run_qry.=' UNION '; } 
   }
   
   
   $farray = array();
   //$run_qry=mysql_query("select * from farmers where (".$qry." in (".$vv.") or cr_by='".$_REQUEST['userid']."')"); 
   
   $num=mysql_num_rows($run_qry);
   
   ini_set('memory_limit', '-1');
   
   if($num>0)
   {
     while($res=mysql_fetch_assoc($run_qry)){ $farray[]=$res; }
     echo json_encode(array( "farmer_list" => $farray) ); 
   }
   else 
   {
     echo json_encode(array( "data" => "100", "msg" => "Invalid id!") );
   }
   /*******************************************************************/ 
 
 }
 else 
 {
   echo json_encode(array( "data" => "100", "msg" => "Missing Value!") );
 }

?>









