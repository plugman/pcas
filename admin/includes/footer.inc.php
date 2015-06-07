<?php 
/*

|	footer.inc.php
|   ========================================
|	Admin Footer
+--------------------------------------------------------------------------
*/
if (!defined('CC_INI_SET')) die("Access Denied");
if (isset($GLOBALS[CC_ADMIN_SESSION_NAME]) && !isset($skipFooter)) {
?>
</div>
<!-- start wrapping table -->
	</td>
  </tr>
 <tr>
 	<td>
    	
    </td>
 </tr>
</table>
<div class="footer">
          <span class="fleft"><?php echo date('l, M d, Y'); ?></span> 
          <span class="right">This System is Powered By <a href="http://www.imei-unlock.net/"><strong style="color:#fff">IMEI Unlock</strong></a> Team  </span>
        </div>
<!-- end wrapping table -->
<?php } ?>
</body>
</html>