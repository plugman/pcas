<!-- BEGIN: viewAllfaq -->

<script type="text/javascript">
//<![CDATA[
$(document).ready(function() {
	$('a.login-window1').click(function() {
		
		// Getting the variable's value from a link 
		var loginBox = $(this).attr('href');

		//Fade in the Popup and add close button
		$(loginBox).fadeIn(300);
		
		//Set the center alignment padding + border
		var popMargTop = ($(loginBox).height() + 24) / 2; 
		var popMargLeft = ($(loginBox).width() + 24) / 2; 
		
		$(loginBox).css({ 
			'margin-top' : -popMargTop,
			'margin-left' : -popMargLeft
		});
		
		// Add the mask to body
		$('body').append('<div id="mask"><\/div>');
		$('#mask').fadeIn(300);
		
		return false;
	});
	
	// When clicking on the button close or the mask layer the popup closed
	$('a.close, #mask').live('click', function() { 
	  $('#mask , .login-popup1').fadeOut(300 , function() {
		$('#mask').remove();  
	}); 
	return false;
	});
});
//]]>
</script>
<script type="text/javascript">
function setId1(id){
	var val1=$('#desc1'+id).html();
	var head1=$('#head1'+id).val();
	document.getElementById('content1'+id).innerHTML=val1;
	document.getElementById('heading1'+id).innerHTML=head1;
}
function setId2(id){
	var val2=$('#desc2'+id).html();
	var head2=$('#head2'+id).val();
	document.getElementById('content2'+id).innerHTML=val2;
	document.getElementById('heading2'+id).innerHTML=head2;
}
function setid3(id){
	var a=$('#otherdesc3'+id).html();
	var b=$('#other3'+id).val();
	document.getElementById('othercontent3'+id).innerHTML=a;
	document.getElementById('otherheading3'+id).innerHTML=b;
}
</script>
<div class="maindiv breadbg">
      <div class=" maincenter">
       <a href="index.php"><img alt="" src="skins/{VAL_SKIN}/styleImages/home3.jpg" /></a> / 
         {LANG_FAQ_TITLE}
      </div>
    </div>
<div class="maindiv">
  <div class="maincenter">
  
    <div class="maindiv mainbox">
      
      <div >
        <div class="txt-purple txt24 mainboxheading">
            {LANG_FAQ_DESC}
        </div>
        <div class="faqcolumn">
          <div class="columnhead">{LANG_FAQ_HEAD1}</div>
          <div class="columncontent">
<!-- BEGIN: viewFaqs_owner_true -->
<ul>
<!-- BEGIN: faq_detail_owner -->
<li>
<a class="login-window1" onclick="setId1({DATA.faq_id});" href="#login-box1{DATA.faq_id}">{DATA.faq_title}</a>

</li>
<div style="display:none;" id="desc1{DATA.faq_id}">{DATA.faq_description}</div>
<input type="hidden" value="{DATA.faq_title}" id="head1{DATA.faq_id}" />
<div id="login-box1{DATA.faq_id}" class="login-popup1">
<div class="headstyling" id="heading1{DATA.faq_id}"></div>
<div class="descstyling" id="content1{DATA.faq_id}"></div>
                <a class="close" href="#">
<img class="btn_close_faq" alt="Close" title="Close Window" src="skins/Classic/styleImages/close.png" />
</a>
                
              
		</div>
<!-- END: faq_detail_owner -->
</ul>
<!-- END: viewFaqs_owner_true -->
<!-- BEGIN: viewFaqs_owner_false -->
{LANG_NO_viewFaqs_owner}
<!-- END: viewFaqs_owner_false -->
</div>
        </div>
        <div class="faqcolumn" style="margin-right:0">
          <div class="columnhead">{LANG_FAQ_HEAD2}</div>
          <div class="columncontent">
<!-- BEGIN: viewFaqs_renter_true -->
<ul>
<!-- BEGIN: faq_detail_renter -->
<li>
<a class="login-window" onclick="setId2({DATA.faq_id});" href="#login-box2{DATA.faq_id}">{DATA.faq_title}</a>
</li>
<div style="display:none;" id="desc2{DATA.faq_id}">{DATA.faq_description}</div>
<input type="hidden" value="{DATA.faq_title}" id="head2{DATA.faq_id}" />
<div id="login-box2{DATA.faq_id}" class="login-popup1">
<div class="headstyling" id="heading2{DATA.faq_id}"></div>
<div class="descstyling" id="content2{DATA.faq_id}"></div>
                <a class="close" href="#">
<img class="btn_close_faq" alt="Close" title="Close Window" src="skins/Classic/styleImages/close.png" />
</a>
                
              
		</div>
<!-- END: faq_detail_renter -->
</ul>
<!-- END: viewFaqs_renter_true -->
<!-- BEGIN: viewFaqs_renter_false -->
{LANG_NO_viewFaqs_owner}
<!-- END: viewFaqs_renter_false -->
</div>
        </div>
        <div class="faqcolumn">
          <div class="columnhead">{LANG_FAQ_HEAD3}</div>
          <div class="columncontent">
<div class="faqcontacttext">For any questions or issues you can't find an answer to, please</div>
<div class="faqcontacttext1">send us an email <a href="mailto:sales@ljtronics.com">sales@ljtronics.com.au </a></div>
<!-- BEGIN: policy_true -->
<ul>
<!-- BEGIN: policy_detail -->
<li>
<!--<a href="index.php?_a=viewDoc&docId={DATA.doc_id}">{DATA.doc_name}</a>-->
</li>
<!-- END: policy_detail -->
</ul>
<!-- END: policy_true -->
<!-- BEGIN: policy_false -->
{LANG_NO_POLICY}
<!-- END: policy_false -->
</div>
        </div>
        <div class="faqcolumn" style="margin-right:0">
          <div class="columnhead">{LANG_FAQ_HEAD4}</div>
          <div class="columncontent">
<!-- BEGIN: viewFaqs_otherss_true -->
<ul>
<!-- BEGIN: faq_detail_otherss -->
<li>
<a class="login-window" onclick="setid3({DATA.faq_id});" href="#login-box3{DATA.faq_id}">{DATA.faq_title}</a>
</li>

<div id="login-box3{DATA.faq_id}" class="login-popup1">
<div class="headstyling" id="otherheading3{DATA.faq_id}"></div>
<div class="descstyling" id="othercontent3{DATA.faq_id}"></div>
                <a class="close" href="#">
<img class="btn_close_faq" alt="Close" title="Close Window" src="skins/Classic/styleImages/close.png" />
</a>
		</div>
<div style="display:none;" id="otherdesc3{DATA.faq_id}">{DATA.faq_description}</div>
<input type="hidden" value="{DATA.faq_title}" id="other3{DATA.faq_id}" />
<!-- END: faq_detail_otherss -->
</ul>
<!-- END: viewFaqs_othessr_true -->
<!-- BEGIN: viewFaqs_otherss_false -->
{LANG_NO_viewFaqs_owner}
<!-- END: viewFaqs_otherss_false -->
</div>
        </div>
      </div>
    </div>
<div class="maindiv  hf">
  <div class="maincenter">
    
  </div>
</div>
  </div>
</div>
<!-- END: viewAllfaq --> 
