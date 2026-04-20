<?php
session_start();

//	if($_POST['capacha'] == '123'){
		//echo "in";

if(((isset($_POST['userName'])) && $_POST['userName'] !='') && ((isset($_POST['userEmail'])) && $_POST['userEmail'] != '') && ((isset($_POST['userMessage'])) && $_POST['userMessage'] !='')){
require_once  './mailinclude.php';
        
        
        $mailbody1="<div style='font-family:arial,sans-serif;font-size:12.8px;max-width:600px;margin:0px auto;width:594px;border-radius:10px'>
    <div style='font-size:13px'>
        <p>
            <a href='https://supergifts.in/' target='_blank'><img src='https://supergifts.in/images/logo.png' width='150px' style='width:178.188px;padding:5.9375px' class='CToWUd'></a>
        </p>
    </div>
    <div style='font-size:14px;width:594px'>
        <h4 style='margin:5px auto;padding:5px 0px;color:white;background-color:rgb(0,160,227)'>&nbsp;&nbsp;Website Form Details</h4></div>
    <div style='text-align:justify'>
       <table style='font-family:arial;font-size:14px;width:596px'>
            <tbody>
           
                <tr bgcolor='#f5f5f5'>
				 <td bgcolor='#e5e5e5' width='232' style='padding: 10px;'>  Name </td>
				<td width='450' style='padding: 10px;'>".$_POST['userName']."</td>
               </tr>  
				
			
            
			
			<tr bgcolor='#f5f5f5'>
				 <td bgcolor='#e5e5e5' width='232' style='padding: 10px;'>  Email </td>
				 <td width='450' style='padding: 10px;'> ".$_POST['userEmail']."</td>
            </tr> 
 			
            <tr bgcolor='#f5f5f5'>
            <td bgcolor='#e5e5e5' width='232' style='padding: 10px;'>  Message </td>
            <td width='450' style='padding: 10px;'> ".$_POST['userMessage']."</td>
       </tr> 
     
             
            </tbody>
        </table>
     </div>
   
    <div style='font-size:14px;text-align:justify'>
        <p style='background-color:#AAACAD;padding:5px;color:black;margin-top:5px;text-align:center'>Powerd By <a href='http://aiir.in/' style='color:black;'>AiiR</a></p>
      
          </p>
    </div>
</div>";
        
        
        
        
        
				  
			//	 $mail1->AddAddress("jatinmistrii@gmail.com");
            //     $mail1->AddAddress("jatin@oakthree.in");
			$mail1->AddAddress("hitesh@hitesh.co.in");
                    $mail1->IsHTML(true);
					$mail1->Body=$mailbody1;
					$mail1->Subject="SGIPL Website Form Submited";
					if($mail1->Send())
              
{
	$redata = array(
        'type' => "Success",
        'text' => 'Message Sent Successfully'
    );


		
}else{

	$redata = array(
        'type' => "error",
        'text' => 'Message Sending Failed'
   		 );



}				 

}else{
		$redata = array(
            'type' => "error",
            'text' => 'Message Sending Failed'
   		 );
		}
    
		 header("Content-Type: application/json; charset=UTF-8");
     echo json_encode((object) $redata);
	




                        ?>