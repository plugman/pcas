<!-- BEGIN: view_cat -->
<!-- BEGIN: added -->
<script type="text/javascript">
window.setTimeout(ShowBasket,500);
</script>
<!-- END: added -->
<script type="text/javascript">
function stringval(id){
	var str = document.getElementById(id).value;
	var n = str.replace(/'/g,"");
	var m = n.replace(/\"/g, "");
	document.getElementById(id).value =m;
}
</script>
<div class="maindiv breadbg">
  <div class=" maincenter"> <a href="index.php"><img alt="" src="skins/{VAL_SKIN}/styleImages/home3.jpg" /></a> / 
    Repairs </div>
</div>
<div class="maindiv mainbody">
  <div class="maincenter">
    <div class="maincontent"> 
      <!-- BEGIN: cate_true -->
      <div class="mainpricebox" id="make">
        <h2 class="tit">Who makes your device. </h2>
        <!-- BEGIN: cat_true -->
        <div class="detailbox55" onclick="loaddevices('{TXT_CAT_ID}','1','{TXT_CAT_ID}');">
          <div class="makeimg"> <img src="{IMG_CURENT_CATEGORY}" alt="{TXT_CAT_TITLE}"  />
            <div class="make" id="{TXT_CAT_ID}"> <span class="maketitle" >{TXT_CAT_TITLE}</span> </div>
          </div>
        </div>
        <!-- END: cat_true --> 
      </div>
      <div class="mainpricebox" id="devices"> </div>
      <div class="mainpricebox" id="model"> </div>
      <div class="mainpricebox" id="problems"> </div>
      <div class="mainpricebox" id="problemdetail">
        <h2 class="tit" id="probtree"></h2>
        <div class="leftboxd"> <span id="problemtit">Water Damage
          Repair</span> <span id="problemprice">$66.00</span>
          <ul>
            <li>Genuine Parts</li>
            <li>90 Day Warranty*</li>
            <li>Free Private Courier</li>
            <li>Money Back Guarantee</li>
          </ul>
          <img alt="" src="skins/{VAL_SKIN}/styleImages/logo.png" /> </div>
        <div class="rightrbox">
          <h2>How it works</h2>
          <div class="repdetail">
            <ul>
              <li>Click continue and place your order online. If you don't want to do this online,
                just get in touch to place an orde</li>
              <li>Select your preferred service option (whether we'll come and pick your device
                up from you, or you will mail it in to us)</li>
              <li>For pick up repairs:</li>
              <ul>
                <li>We'll call you to confirm pick up and drop off times</li>
                <li>We come to you to collect your broken device</li>
                <li>We fix it and drop it back to you</li>
                <li>Your phone's back in your hands the same day!</li>
              </ul>
              <li>For mail in repairs:</li>
              <ul>
                <li>Express Post your device to us, and we'll receive it that day.</li>
                <li>We'll do the repair, and you should have it back in your hands in approximately 3 days.</li>
              </ul>
            </ul>
            <p class="contactup">If you need a quote on more than one repair, please <a href="" style="color:#23afdf; text-decoration:underline" id="contactus">contact us directly.</a></p>
            <a href="" id="repairid" class="orderrepair">Continue</a> 
            <!--<form action="{CURRENT_URL}" method="post" id="prod{PRODUCT_ID}" name="addtobasket" target="_self">
          <input type="hidden" value="" name="add"  />
          <input type="submit" class="orderrepair" value="Continue" />
          </form>--> 
          </div>
        </div>
      </div>
      
      <!-- END: cate_true --> 
      <!-- BEGIN: contactus -->
      <div class="maindiv mainbox ">
        <center>
          <label class="title txt-purple txt24"> Feel free to drop us an email below and we'll make sure to get back to you quickly.</label>
        </center>
        
        <!-- BEGIN: error -->
        <p id="tdstatus" class="txtError" style="margin-left:10px; width:96%">{VAL_ERROR}</p>
        <!-- END: error -->
        <form action="" method="post" id="frmContactus" name="frmContactus" class="formValidation">
          <div class="loginleft" style="margin-top:30px; border:none;">
            <div class="maindiv">
              <label class="txt18 txt-grey maindiv">{LANG_NAME}</label>
              <div class="txtboxmain"> <span class="txtboxmain-left"></span>
                <input type="text"  name="name" id="name" value="{VAL_NAME}" />
                <span class="txtboxmain-right"> <span class="mandatory"></span> </span> </div>
            </div>
            <div class="maindiv">
              <label class="txt18 txt-grey maindiv">{LANG_EMAIL}</label>
              <div class="txtboxmain"> <span class="txtboxmain-left"></span>
                <input type="text"  name="email" id="email" value="{VAL_EMAIL}"  />
                <span class="txtboxmain-right"> <span class="mandatory"></span> </span> </div>
            </div>
            <div class="maindiv">
              <label class="txt18 txt-grey maindiv">{LANG_PHONE}</label>
              <div class="txtboxmain"> <span class="txtboxmain-left"></span>
                <input type="text"  name="phone" id="phone" value="{VAL_PHONE}"  />
                <span class="txtboxmain-right"> <span class="mandatory"></span> </span> </div>
            </div>
            <div class="maindiv">
              <label class="txt18 txt-grey maindiv">{LANG_DEVICE}</label>
              <div class="txtboxmain"> <span class="txtboxmain-left"></span>
                <input type="text"  name="device" id="phone" value="{VAL_DEVICE}"  />
                <span class="txtboxmain-right"> <span class="mandatory"></span> </span> </div>
            </div>
            <div class="maindiv">
              <label class="txt18 txt-grey maindiv">{LANG_COMMENTS}</label>
              <div class="txtboxmain txtboxmain2"> <span class="txtboxmain-left txtboxmain-left2"></span>
                <textarea name="msg"  id="msg"   class="textarea"  cols="1" rows="1" style="width:404px">{VAL_COMMENTS}</textarea>
                <span class="txtboxmain-right txtboxmain-right2"> <span class="mandatory"></span> </span> </div>
            </div>
          </div>
          <div  class="loginright loginrightc "  > <span class="submitlogin button radius3px">Our Location</span> {DOC_CONTENT} </div>
          <div class="maindiv footerlogin">
            <input type="submit"  class="submitlogin button radius3px"   value="Submit"  style="margin:12px 0 0 20px" />
          </div>
        </form>
      </div>
      <!-- END: contactus --> 
      <!-- BEGIN: procedure -->
      <div class="mainpricebox testmain">
        <div style="margin-left:0px;" class="price-column repair-description first span8">
         <!-- BEGIN: pickup_true -->
          <div>
            <h3 class="tit">Pickup</h3>
            <p> We'll send one of your private couriers to your door, at the time you choose. </p>
            <p> Once we're done, we'll have it couriered straight back to you. </p>
            <p> Simply select a time period and provide us with some basic contact details, place your order, and we'll be straight in touch with you to confirm your repair. </p>
            <p> It's easy, and there's no extra fees! </p>
            <p> <a href="Contact-Us.html">Just contact us with any questions.</a> </p>
            <a style="float:right" class="mybtn-small" href="{PICK_UP}">We'll come to you</a> </div>
             <!-- END: pickup_true -->
              <!-- BEGIN: mailin_true -->
          <div>
            <h3>Mail in</h3>
            <p> Mailing your phone to us is safe and secure, and it allows us to service you no matter where you are in Australia. </p>
            <p> Place an order, either on line or by telephone. Express Post your device to us, and we'll receive it that day. </p>
            <p> We'll do the repair, and you should have it back in your hands in approximately 3 days. </p>
            <p> <a href="Contact-Us.html">Just contact us with any questions.</a> </p>
            <a style="float:right" class="mybtn-small" href="{MAIL_IN}">Securely mail your device to us</a> </div>
             <!-- END: mailin_true -->
        </div>
      </div>
      <!-- END: procedure --> 
      <!-- BEGIN: pick_up -->
      <div class="mainpricebox testmain">
        <div style="margin-left:0px" class="price-column repair-description first span8">
          <h4>Your details</h4>
          <p style="font-size:14px"> We need to collect just a few details from you in order to book in your repair. </p>
          <p style="font-size:14px; margin-top:16px;"> You will need to nominate a preferred time for us to come and pick up your device. Please note that this is just a suggestion - we'll give you a call soon after your order is finalized to organise an exact time that works well for both of us. </p>
          <p style="font-size:14px; margin-top:16px;"> If you can't find the time you're looking for, please don't hesistate to <a href="/contact">contact us directly</a>. </p>
          <p style="font-size:14px; margin-top:16px; font-weight:bold;"> Please be mindful when selecting a time that we are constrained by the laws of physics and cannot pick your device up without sufficient notice. </p>
          <!-- BEGIN: errors -->
          <div class="alert alert-error"> 
            <!-- BEGIN: error -->
            <p>{VAL_ERROR}</p>
            <!-- END: error --> 
            <!-- BEGIN: info -->
            <p>{VAL_INFO}</p>
            <!-- END: info --> 
          </div>
          <!-- END: errors --> 
          <!-- BEGIN: addtobasket -->
          <form method="post" id="new_repair" class="new_repair" action="{CURRENT_URL}"  style="margin-top:25px;">
            <p>We can Repair this device</p>
            <input type="hidden" value="{PRO_ID}" name="addrepair"  />
            <input type="hidden" value="{CATE}" name="productOptions[Device]"  />
            <input type="hidden" value="{PICK_UP}" name="productOptions[Pickup]"  />
            <input type="hidden" value="{DROP}" name="productOptions[Dropoff]"  />
            <input type="hidden" value="{POST_CODE}" name="productOptions[postcode]"  />
            <p>
              <input type="submit" value="Order Now " name="commit" class="orderrepair">
            </p>
          </form>
          <!-- END: addtobasket -->
          <form method="post" id="new_repair" class="new_repair" action="{CURRENT_URL}"  style="margin-top:25px;">
            <p>
              <label for="repair_postcode">Postcode</label>
              <input type="text" size="30" name="repair[postcode]" id="repair_postcode" class="textbox" style="width:245px;" required="required">
            </p>
            <p>
              <label for="repair_pickup_time">Pickup time</label>
              <select  name="repair[pickup_time]" id="repair_pickup_time" class="textbox">
                
                <!-- BEGIN: frmrepeat_date -->
                <optgroup label="{DAY_GROUP}"></optgroup>
                <!-- BEGIN: frmrepeat_time -->
                <option value="{DAY_TIME_VALUE}">{DAY_TIME}</option>
                <!-- END: frmrepeat_time --> 
                <!-- END: frmrepeat_date -->
              </select>
            </p>
            <p>
              <label for="repair_dropoff_time">Dropoff time</label>
              <select  name="repair[dropof_time]" id="repair_pickup_time" class="textbox">
                
                <!-- BEGIN: repeat_date -->
                <optgroup label="{DAY_GROUP}"></optgroup>
                <!-- BEGIN: repeat_time -->
                <option value="{DAY_TIME_VALUE}">{DAY_TIME}</option>
                <!-- END: repeat_time --> 
                <!-- END: repeat_date -->
              </select>
            </p>
            <script>
                $(function() {
                  var old_repair_dropoff_html = $("#repair_dropoff_time").html();
                  $("#repair_pickup_time").change(function() {
                    var time = $(this).val();
                    $("#repair_dropoff_time").html(old_repair_dropoff_html);
                    $("#repair_dropoff_time option").each(function() {
                      var me = $(this);
                      if(me.val() &lt;= time) {
                        me.remove();
                      }
                    });
                    $("#repair_dropoff_time optgroup").each(function() {
                      if($(this).children().size() == 0) {
                        $(this).remove();
                      }
                    });
                  })
                });          
                </script>
            <p>
              <input type="submit" value="Continue " name="commit" class="orderrepair">
             
         
            </p>
          </form>
        </div>
      </div>
      <!-- END: pick_up --> 
      <!-- BEGIN: mailin -->
      <div class="mainpricebox testmain">
        <h4>Mail in repair</h4>
        <p style="font-size:14px;" class="alert"> Please read the following information carefully before continuing. </p>
        <p style="font-size:14px; margin-top:16px;"> Mailing your phone to use is safe and secure, if you do it properly. We require that you do a few things to ensure the safety of your device. </p>
        <ul style="font-size:14px; margin-top:16px;">
          <li>Do not send any chargers, or any other non-essential accessories. This includes cases and covers.</li>
          <li>Please make sure your phone has a full charge and you have backed up all your data before sending it in. We are not responsible for any data loss.</li>
          <li>Make sure your phone is well padded using bubble wrap. Ensure every surface is covered.</li>
          <li>Use Express Post Platinum to send your phone to us and keep the tracking number of the parcel for insurance reasons.</li>
          <li>We'll get your phone the next day and we'll keep you informed on the status of your repair.</li>
          <li>When we're done, we'll mail your phone back free of charge.</li>
        </ul>
 
          <form method="post" id="new_repair" class="new_repair" action="{CURRENT_URL}"  style="margin-top:25px;">
        <p>
        <input type="hidden" value="{CATE}" name="productOptions[Device]"  />
        <input type="hidden" value="{PRO_ID}" name="addrepair"  />
              <label for="repair_imei">IMEI</label>
              <input type="text" size="30" name="productOptions[imei]" onchange="stringval('txtimei');" id="txtimeii"  onkeypress="return isNumberKey(event)" class="textbox" style="width:245px;" required="required" maxlength="15">
            </p>
            <p>
              <label for="repair_scode">Security Code:</label>
              <input type="text" size="30" name="productOptions[scode]" onchange="stringval('txtimei');" id="txtscode"  onkeypress="return isNumberKey(event)" class="textbox" style="width:245px;" required="required" maxlength="15">
            </p>
             <p>
              <label for="repair_Coments">Coments</label>
              <textarea name="productOptions[Coments]" class="textbox" style="width:245px; height:100px;" onchange="stringval('coments');"  id="coments"></textarea>
            </p>
        <p> <input type="submit" class="orderrepair" name="commit" value="Order Now "></p>
        </form>
      </div>
      <!-- END: mailin --> 
      
    </div>
  </div>
</div>
<!-- END: view_cat --> 

