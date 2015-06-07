<!-- BEGIN: order_form -->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{VAL_STORE_URL}</title>
<meta http-equiv="Content-Type" content="text/html; charset={VAL_ISO}" />
<style type="text/css" media="screen, print">
<!--
html, body {
	font-family: Verdana, Arial, Helvetica, sans-serif; 
	font-size: 12px;
}

#wrapper {
	margin: auto;
	padding: 2px;
	width: 650px;
	border: 2px solid #CCCCCC;
	background-color:#FFFFFF;
}

#header {
	padding: 3px 10px;
	margin-bottom: 10px;
}


#address {
	margin: 0px 10px;
	clear: both;
	padding: 10px 0px;
	border-top: 1px solid #666666;
	border-bottom: 1px solid #666666;
}

#invoice-to {
	width: 300px;
}

#deliver-to {
	width: 300px;
	float: right;
}

#info {
	margin: 10px 0px 30px;
	padding: 0px 10px;
	clear: both;
}

div.product {
	clear: both;
	padding: 2px 10px;
	border-bottom: 1px dashed #CCCCCC;
}
span.price {
	float: right;
	padding-right: 10px;
}

#totals {
	margin-top: 5px;
}
.total {
	text-align: right;
	padding-right: 10px;
}

div.payment_method {
	margin: 10px;
	padding: 2px;
	border-top: 1px solid #CCCCCC;
	clear: both;
}
div.customer_comments {
	margin: 10px;
	padding: 2px;
	clear: both;
}
div.payment_method > div {
	margin: 2px 0px;
}

#card_types {
	width: 300px;
}

#card_details {
	float: right;
	width: 300px;
}
#card_details > .detail {
	margin: 3px 0px;
	height: 30px;
	border-bottom: 1px solid #666666;
}

#notes {
	margin: 10px;
	padding: 3px;
	border-top: 1px solid #CCCCCC;
	border-bottom: 1px solid #CCCCCC;
	text-align: center
}

#thanks {
	margin-top: 20px;
	text-align: center;
	font-weight: bold;
}

#footer {
	margin: 10px 0px 0px;
	padding-top: 5px;
	border-top: 1px solid #666666;
	text-align: center;
	font-size: 0.8em;
}
#footer p {
	margin: 0px;
}
-->
</style>
<style type="text/css" media="print">
form, input.button {
	display: none;
	visibility: hidden;
}
</style>
</head>

<body>
<form action="index.php" method="get">
  <input type="submit" value="{LANG_RETURN_STORE}" class="button" />
  <input type="button" value="Print" class="button" onclick="window.print();" />
</form>

<div id="wrapper">
  <div id="header">
	<strong>{VAL_STORE_NAME}</strong><br />
	<span style="float: right;">{VAL_STORE_URL}</span>
	{VAL_TAX_REG}
  </div>
  
  <div id="address">
	<div id="deliver-to">
	  <strong>{LANG_DELIVER_TO}</strong><br />
	  {VAL_DELIVER_NAME}<br />
	  {VAL_DELIVER_COMPANY}<br />
	  {VAL_DELIVER_ADD1}<br />
	  {VAL_DELIVER_ADD2}<br />
	  {VAL_DELIVER_TOWN}, {VAL_DELIVER_STATE}<br />
	  {VAL_DELIVER_POSTCODE}<br />
	  {VAL_DELIVER_COUNTRY}
	</div>
	<div id="invoice-to">
	<strong>{LANG_INVOICE_TO}</strong><br />
	  {VAL_INVOICE_NAME}<br />
	  {VAL_INVOICE_COMPANY}<br />
	  {VAL_INVOICE_ADD1}<br />
	  {VAL_INVOICE_ADD2}<br />
	  {VAL_INVOICE_TOWN}, {VAL_INVOICE_STATE}<br />
	  {VAL_INVOICE_POSTCODE}<br />
	  {VAL_INVOICE_COUNTRY}
	</div>
  </div>
  
  <div id="info">
    <span style="float: right;"><strong>{LANG_CART_ORDER_ID}</strong> {VAL_CART_ORDER_ID}</span>
	<strong>{LANG_INVOICE_RECIEPT_FOR}</strong> {VAL_TIME_DATE}
  </div>

  <div class="product">
    <span class="price"><strong>{LANG_PRICE}</strong></span>
	<strong>{LANG_PRODUCT}</strong>
  </div>
  
  <!-- BEGIN: repeat_order_inv -->
  <div class="product">
    <span class="price">{VAL_PRODUCT_PRICE}</span>
	{VAL_PRODUCT_QUANTITY} x {VAL_PRODUCT_NAME} ({VAL_PRODUCT_CODE})<br />
	<em>{VAL_PRODUCT_OPTS}</em>
  </div>
  <!-- END: repeat_order_inv -->
  
  <div id="totals">
	<div class="total">{LANG_SUBTOTAL} <strong>{VAL_SUBTOTAL}</strong></div>
	<div class="total">{LANG_DISCOUNT} <strong>{VAL_DISCOUNT}</strong></div>
	<div class="total">{LANG_SHIPPING} <strong>{VAL_SHIPPING}</strong></div>
	<div class="total">{LANG_TOTAL_TAX} <strong>{VAL_TOTAL_TAX}</strong></div>
	<!-- BEGIN: repeat_additional_taxes -->
	<div class="total">{LANG_TAX} <strong>{VAL_TAX}</strong></div>
	<!-- END: repeat_additional_taxes -->
	<div class="total"><strong>{LANG_GRAND_TOTAL} {VAL_GRAND_TOTAL}</strong></div>
  </div>
  <!-- BEGIN: customer_comments -->
  <div class="customer_comments">
  &quot;{VAL_CUSTOMER_COMMENTS}&quot;
  </div>
  <!-- END: customer_comments -->
  <!-- BEGIN: check_true -->
  <div class="payment_method">
	<strong>{LANG_PAY_BY_CHEQUE}</strong><br />
	{VAL_MAKE_CHEQUES_PAYABLE_TO}
  </div>
  <!-- END: check_true -->
  
  <!-- BEGIN: card_true -->
  <div class="payment_method">
	<div id="card_details">
	  <div class="detail">{LANG_CARD_NO}</div>
	  <div class="detail">{LANG_EXPIRE_DATE}</div>
	  <div class="detail">{LANG_ISSUE_DATE}</div>
	  <div class="detail">{LANG_ISSUE_NUMBER}</div>
	  <div class="detail">{LANG_SIGNATURE}</div>
	</div>

	<div style="margin-bottom: 5px;"><strong>{LANG_PAY_BY_CARD}</strong></div>
	<div id="card_types">
	  <!-- BEGIN: repeat_card -->
	  <img src="modules/gateway/Print_Order_Form/images/box.gif" alt="" /> {VAL_CARD_NAME}<br />
	  <!-- END: repeat_card -->	
	</div>
	
  </div>
  <!-- END: card_true -->
  
  <!-- BEGIN: bank_true -->
  <div class="payment_method">
	<strong>{LANG_PAY_BY_WIRE}</strong><br />
	<br />
	<div><strong>{LANG_BANK_NAME}</strong> {VAL_BANK_NAME}</div>
	<div><strong>{LANG_ACCOUNT_NAME}</strong> {VAL_ACCOUNT_NAME}</div>
	<div><strong>{LANG_SORT_CODE}</strong> {VAL_SORT_CODE}</div>
	<div><strong>{LANG_AC_NO}</strong> {VAL_AC_NO}</div>
	<div><strong>{LANG_SWIFT_CODE}</strong> {VAL_SWIFT_CODE}</div>
	<div><strong>{LANG_ADDRESS}</strong> {VAL_ADDRESS}</div>
  </div>  
  <!-- END: bank_true -->
  
  <!-- BEGIN: cust_notes -->
  <div id="notes">{VAL_CUST_NOTES}</div>
  <!-- END: cust_notes -->

  <div id="thanks">{LANG_THANK_YOU}</div>
  <div id="footer">{LANG_SEND_TO} {VAL_STORE_ADDRESS}</div>

</div>
</body>
</html>
<!-- END: order_form -->