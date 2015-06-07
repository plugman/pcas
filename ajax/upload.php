<?php
require_once ("../ini.inc.php");
require_once ("../includes".CC_DS."global.inc.php");
require_once ("../includes".CC_DS."functions.inc.php");
require_once ("../classes".CC_DS."db".CC_DS."db.php");
require_once ("../classes".CC_DS."cart".CC_DS."shoppingCart.php");
require_once ("../classes".CC_DS."cart".CC_DS."order.php");
require_once ("../classes".CC_DS."session".CC_DS."cc_session.php");
require_once ("../classes".CC_DS."cache".CC_DS."cache.php");

$db = new db();
$cart = new cart();
$order	= new order();
$cc_session = new session();
$config = fetchdbconfig("config");

//If directory doesnot exists create it.
 $output_dir = CC_ROOT_DIR."/uploads/userdata/";

if(isset($_FILES["myfile"]))
{
	$ret = array();

	$error =$_FILES["myfile"]["error"];
   {
    $html = '';
    	if(!is_array($_FILES["myfile"]['name'])) //single file
    	{
			
            $RandomNum   = time();
            
            $ImageName      = str_replace(' ','-',strtolower($_FILES['myfile']['name']));
            $ImageType      = $_FILES['myfile']['type']; //"image/png", image/jpeg etc.
         
            $ImageExt = substr($ImageName, strrpos($ImageName, '.'));
            $ImageExt       = str_replace('.','',$ImageExt);
            $ImageName      = preg_replace("/\.[^.\s]{3,4}$/", "", $ImageName);
            $NewImageName = $ImageName.'-'.$RandomNum.'.'.$ImageExt;

       	 	if(move_uploaded_file($_FILES["myfile"]["tmp_name"],$output_dir. $NewImageName)){
       	 	 //echo "<br> Error: ".$_FILES["myfile"]["error"];
       	 	 	$data['session_id']  = $db->mySQLSafe($cc_session->ccUserData['sessId']);
				$data['customerId']  = $db->mySQLSafe($cc_session->ccUserData['customer_id']);
				$data['image']		= $db->mySQLSafe($NewImageName);
				$status = $db->insert("ImeiUnlock_user_images", $data);			
	       	 	$ret[]= $output_dir.$NewImageName;
				$userimgid = $db->insertid();	
				$icnSrc = imgPath($NewImageName,'',$path="userimage", '');
				$html .= '<li  class="column4"><div><i id="'.$userimgid.'">X</i><img id="userphoto-'.$userimgid.'" src="'.$icnSrc.'" ondragstart="drag(event)" class="dragable-image" source="'.$icnSrc.'"><div class="spinner-wrap absolute"><div class="spiner hide"><div class="circle absolute f-height f-width hide"></div><div class="circle absolute f-height f-width"></div></div></div>
</div></li>';
			}
    	}
    	else
    	{
            $fileCount = count($_FILES["myfile"]['name']);
    		for($i=0; $i < $fileCount; $i++)
    		{
                $RandomNum   = time();
            
                $ImageName      = str_replace(' ','-',strtolower($_FILES['myfile']['name'][$i]));
                $ImageType      = $_FILES['myfile']['type'][$i]; //"image/png", image/jpeg etc.
             
                $ImageExt = substr($ImageName, strrpos($ImageName, '.'));
                $ImageExt       = str_replace('.','',$ImageExt);
                $ImageName      = preg_replace("/\.[^.\s]{3,4}$/", "", $ImageName);
                $NewImageName = $ImageName.'-'.$RandomNum.'.'.$ImageExt;
                
                $ret[]= $output_dir.$NewImageName;
    		    if( move_uploaded_file($_FILES["myfile"]["tmp_name"][$i],$output_dir.$NewImageName )){
				$data['session_id']  = $db->mySQLSafe($cc_session->ccUserData['sessId']);
				$data['customerId']  = $db->mySQLSafe($cc_session->ccUserData['customer_id']);
				$data['image']		= $db->mySQLSafe($NewImageName);
				$status = $db->insert("ImeiUnlock_user_images", $data);	
				$userimgid = $db->insertid();	
				$icnSrc = imgPath($NewImageName,'',$path="userimage", '');
				$html .= '<li  class="column4"><div><i id="'.$userimgid.'">X</i><img id="userphoto-'.$userimgid.'" src="'.$icnSrc.'" ondragstart="drag(event)" class="dragable-image" source="'.$icnSrc.'"><div class="spinner-wrap absolute"><div class="spiner hide"><div class="circle absolute f-height f-width hide"></div><div class="circle absolute f-height f-width"></div></div></div>
</div></li>';
			   }
    		}
    	}
    }
  //  echo json_encode($ret);
	//$html2['userdata'] = $html;
	 echo $html;
 
}
$db->close();
?>