<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
<title>Launch Payer Authentication Page</title>
<script language="javascript">
function onLoadHandler(){
	document.processform.submit();
}
</script>
</head>
<body onload="onLoadHandler();">
  <form name="processform" method="post" action="<?php echo $_SESSION['centinel']['ACSUrl']; ?>" />
	<input type="hidden" name="PaReq" value="<?php echo $_SESSION['centinel']['Payload']; ?>" />
	<input type="hidden" name="TermUrl" value="<?php echo $_SESSION['centinel']['TermUrl']; ?>" />
	<input type="hidden" name="MD" value="" />
	<noscript>
	  <h2>Processing your Payer Authentication Transaction</h2> 
	  <h3>JavaScript is currently disabled or is not supported by your browser.<br></h3> 
	  <h4>Please click Submit to continue the processing of your transaction.</h4> 
	  <input type="submit" value="Submit"> 
	</noscript> 
  </form>
</body>
</html>