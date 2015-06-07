<?php
/*
+--------------------------------------------------------------------------
|   Cub3Cart 4
|   ========================================
|	
|   5 Bridge Street,
|   Bishops Stortford,
|   HERTFORDSHIRE.
|   CM23 2JU
|   UNITED KINGDOM
|   http://www.d.e.v.e.l.l.i.o.n.com
|	
|   ========================================
|   Web: http://www.c.u.b.e.c.a.r.t.com
|   Email: info (at) c.u.b.e.c.a.r.t (dot) com
|	  License Type: C.u.b.e.C.a.r.t is NOT Open Source Software and Limitations Apply 
|   Licence Info: http://www.c.u.b.e.c.a.r.t.com/site/faq/license.php
+--------------------------------------------------------------------------
|	index.inc.php
|   ========================================
|	Add/Edit/Delete Products	
+--------------------------------------------------------------------------
*/

if(!defined('CC_INI_SET')){ die("Access Denied"); }

$lang = getLang("admin".CC_DS."admin_misc.inc.php");
require($glob['adminFolder'].CC_DS."includes".CC_DS."header.inc.php");

?>
<p class='pageTitle'><?php //echo $lang['admin']['stats_search_terms'];?>Product Reviews Rating Report</p>

<?php
$query = sprintf("SELECT R.id, R.name,R.email,R.rating,R.title,R.review,R.ip, R.time, R.productId FROM %1\$sImeiUnlock_reviews AS R LEFT JOIN %1\$sImeiUnlock_inventory as I ON R.productId = I.productId WHERE R.approved = 0 ORDER BY time ASC", $glob['dbprefix']);
$reviewsPerPage = 5;
$reviews = $db->select($query, $reviewsPerPage, $_GET['rev']);
$numrows = $db->numrows($query);
$pagination = paginate($numrows, $reviewsPerPage, $_GET['rev'], "rev");

if ($reviews == true) {
?>
  <table width="100%" border="0" cellpadding="3" cellspacing="1" class="mainTable">
<!--  <tr>
    <td width="50%" class="pageTitle"><?php echo $lang['admin_common']['other_product_reviews']; ?></td>
  </tr>-->
  <tr>
    <td width="10%" align="left" valign="top" class="tdTitle">
	Product Name
	</td>
    <td width="10%" align="left" valign="top" class="tdTitle">
	Name
	</td>
    <td width="10%" align="left" valign="top" class="tdTitle">
	Email
	</td>
    <td width="20%" align="left" valign="top" class="tdTitle">
	Rating
	</td>        
    <td width="20%" align="left" valign="top" class="tdTitle">
	Time
	</td>
  </tr>
<?php
	for ($i=0; $i<count($reviews); $i++) 
	{
?>		
  <tr>
    <td width="30%" align="left" valign="top" class="tdText">
	<? 
		$qry 	 	= "SELECT name FROM cc4_ImeiUnlock_inventory  WHERE productId = ".$reviews[$i]['productId'];
		$prodRS  	= $db->select($qry, $reviewsPerPage, $_GET['rev']);
		
		if ($prodRS == true) 
			echo $prodRS[0]['name'];
		else
			echo "-";
	?>	
	</td>
    <td width="20%" align="left" valign="top" class="tdText">
	<? echo "<a href='".$glob['adminFile']."?_g=reviews/index&amp;edit=".$reviews[$i]['id']."' class='txtDash'>".$reviews[$i]['name']."</a>"; ?>
	</td>
    <td width="10%" align="left" valign="top" class="tdText">
	<?=$reviews[$i]['email']?>
	</td>
    <td width="20%" align="left" valign="top" class="tdText">
	<?php
		
	for($j=0;$j<5;$j++) 
	{
		echo "<img src='".$glob['adminFolder']."/images/rating/".starImg($j,$reviews[$i]['rating']).".gif' name='star".($j+1)."' width='15' height='15' id='star".($j+1)."' />\n";
	}
	?>
	</td>        
    <td width="20%" align="left" valign="top" class="tdText">
	<? echo formatTime($reviews[$i]['time']); ?>
	</td>
  </tr>
<?
	}
?>  
	<tr>
    	<td colspan="5">
        <? echo $pagination; ?>
        </td>
    </tr>
  </table><br />
<?php
}
else
{
	echo "<p class='copyText'>Sorry, no data found.</p>";
}
?>