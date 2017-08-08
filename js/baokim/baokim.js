/**
 * Created by Hieu on 12/08/2014.
 */
function choose_bank(clicked_id) {
	//alert(clicked_id);
	var el = clicked_id.split("_");
	var elements = document.getElementsByClassName("logo_bank");
	for (var i = 0; i < elements.length; i++) {
		document.getElementById(elements[i].id).className = "logo_bank";
		//console.log(elements[i].id);
	}
	document.getElementById(clicked_id).className = "logo_bank selected";
	document.getElementById("paymentpro_bank_method_id").value = el[1];
}

function check_pm_baokim_pro(){
	var val = document.getElementById("params[pm_baokim_pro][baokim_bank_payment_method_id]").value;
	if(val.length == 0){
		document.getElementById("bank-err").style.display = 'block';
	}else{
		jQuery('#payment_form').submit();
	}
}