// JavaScript Document
var storeUrll = $("#storeaddres").val();
$(document).ready(function () {
	$("#saved-gallery .remove").click(function (e) {
		 e.preventDefault();
		 $.colorbox({inline:true, href:".save-design-popup", width: "40%"});
		caseid = this.id;
		$(".delete-design").click(function (e) {
        $.ajax({
            type: "POST",
            url: storeUrll + "controllers/removedesign.php",
            data: "photoid=" + caseid,
            error: connectionerror,
            success: function (data) {
                if (data == 1) {
                    $("#" + caseid).closest('li').remove();
              $("#cboxClose").click();
                }
            }

        });
		});
	});
});
$('#deviceprice').change(function (e) {
	var sybmol = $('#cursymbol').val();
	$("#device-price2").val($(this).val());
	$("#device-price").text(sybmol+$(this).val());
	$('#amountprince').prop('selectedIndex',null);
});
$('#amountprince').change(function (e) {
	var sybmol = $('#cursymbol').val();
	$("#device-price2").val($(this).val());
	$("#device-price").text(sybmol+$(this).val());
	$('#deviceprice').prop('selectedIndex',null);
});