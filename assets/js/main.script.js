$(document).ready(function() {
  	$(".form-validate").ketchup();
  	$(".modal").appendTo("body");
});

$(document).ready(function(){
	$(".login-form").submit(function(event) {
		var email = $("#email");
		var password = $("#password");
		var login = $("#login");
		var reset = $("#reset");
		var loading = $("#loading");
		var result = $("#result");
		
		if (email.val() != "" && password.val() != "") {
			if (/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i.test(email.val())) {
				$.ajax({
					data : $(this).serialize(),
					url  : "login.php",
					beforeSend: function() {
						loading.html("<img class='loader' style='padding:4px;' src='assets/img/loading.gif'>");
						result.hide();
						email.attr("disabled", true);
						password.attr("disabled", true);
						login.attr("disabled", true);
						reset.attr("disabled", true);
				    },
					success: function(response){
						result.show();
						if(response == "empty") {
							email.focus();
							result.html("<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>&times;</button><center>EMAIL OR PASSWORD CANNOT EMPTY !</center></div>");
						} else if(response == "deactive") {
							email.focus();
							result.html("<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>&times;</button><center>USER IS NOT ACTIVE !</center></div>");
						} else if(response == "wrong") {
							email.focus();
							result.html("<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>&times;</button><center>EMAIL OR PASSWORD IS INCORRECT !</center></div>");
						} else if(response == "login") {
							result.html("<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>&times;</button><center>LOGIN SUCCESS !</center></div>");
							window.location.href = "index.php?page=dashboard";
						} else {
							email.focus();
							result.html("<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>&times;</button><center>FAILED TO CONNECT TO THE SERVER !</center></div>");
						}
					},
					complete: function() {
						loading.html("");
						email.attr("disabled", false);
						password.attr("disabled", false);
						login.attr("disabled", false);
						reset.attr("disabled", false);
						email.focus();
					}
				});
				event.preventDefault();
			}
		}
		return false;
	});
});

$(document).on("click.collapse.data-api", ".accordion-toggle", function(event) {
    var $this = $(this),
		parent = $this.data("parent"),
		$parent = parent && $(parent);
		
	var actives = parent && $(parent).find(".collapse.in");
		
	if (actives && actives.length) {
        hasData = actives.data("collapse");
        actives.collapse("hide");
    }

    if ($parent) {
        $parent.find("[data-toggle=collapse][data-parent=" + parent + "]").not($this).addClass("collapsed");
    }
	
	var target = $this.attr("data-target") || (href = $this.attr("href")) && href.replace(/.*(?=#[^\s]+$)/, "");
	$(target).collapse("toggle");
});

$(document).ready(function() {
	var nowTemp = new Date();
	var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate() + 1, 0, 0, 0, 0);
	
	var dp = $(".datepicker").datetimepicker ({
		format: "dd/MM/yyyy",
		maskInput: true,
		pickTime: false,
		endDate: now
	}).on("changeDate", function(e) {
		dp.hide();
	}).data("datetimepicker");
	
	var tp = $(".timepicker").datetimepicker ({
		format: "hh:mm",
		maskInput: true,
		pickDate: false,
		pickSeconds: false
	}).on("changeDate", function(e) {
		tp.hide();
	}).data("datetimepicker");
});

$(document).ready(function() {
	$(".date-mask").mask("00/00/0000", { placeholder: "__/__/____" });
	$(".time-mask").mask("00:00", { placeholder: "__:__" });
	$(".flight-mask").mask("0000000", { reverse: true });
	$(".decimal-mask").mask("#.##0", { reverse: true, maxlength: false });
});

$(document).ready(function() {
	$(".tips").tooltip({ "container": "body", "placement": "bottom" });
	$(".tips-right").tooltip({ "container": "body", "placement": "right" });
});

function NewMealOrderPOB() {
	var dialog = $("#modal-mealorderpob-new, #modal-mealorderpob-edit");
	
	dialog.modal("show").on("shown", function() {
		$("#id").val("");
		$("#header").val("");
		$("#ori_flight").val("");
		$("#flight").select2("val", null);
		$("#ori_status").val("");
		$("#status").select2("val", null);
		$("#config").select2("val", null);
	    $("#aircraft").val("");
		$("#equipment").val("");
		$("#seatfc").val("");
        $("#seatbc").val("");
        $("#seatyc").val("");
        $("#seatcr").val("");
        $("#seatcp").val("");
        $("#mofc").val("");
        $("#mobc").val("");
        $("#moyc").val("");
        $("#mocr").val("");
        $("#mocp").val("");
        $("#pobfc").val("");
        $("#pobbc").val("");
        $("#pobyc").val("");
        $("#pobcr").val("");
        $("#pobcp").val("");
        $("#bbml").val("");
        $("#ksml").val("");
        $("#hcake").val("");
        $("#result").html("");
    });
}

function GetMealOrderPOB(dtId, dtHeader, dtIdFlight, dtFlight, dtIdStatus, dtStatus, dtIdConfig, dtConfig, dtAircraft, dtEquipment, dtSeatfc, dtSeatbc, dtSeatyc, dtSeatcr, dtSeatcp, dtMofc, dtMobc, dtMoyc, dtMocr, dtMocp, dtPobfc, dtPobbc, dtPobyc, dtPobcr, dtPobcp, dtSpmlbbml, dtSpmlksml, dtSpmlhcake) {
	var id = $("#id");
	var header = $("#header");
	var ori_flight = $("#ori_flight");
	var flight = $("#flight");
	var ori_status = $("#ori_status");
	var status = $("#status");
	var config = $("#config");
	var aircraft = $("#aircraft");
	var equipment = $("#equipment");
	var seatfc = $("#seatfc");
	var seatbc = $("#seatbc");
	var seatyc = $("#seatyc");
	var seatcr = $("#seatcr");
	var seatcp = $("#seatcp");
	var mofc = $("#mofc");
	var mobc = $("#mobc");
	var moyc = $("#moyc");
	var mocr = $("#mocr");
	var mocp = $("#mocp");
	var pobfc = $("#pobfc");
	var pobbc = $("#pobbc");
	var pobyc = $("#pobyc");
	var pobcr = $("#pobcr");
	var pobcp = $("#pobcp");
	var spmlbbml = $("#bbml");
	var spmlksml = $("#ksml");
	var spmlhcake = $("#hcake");
	var result = $("#result");
	var dialog = $("#modal-mealorderpob-new, #modal-mealorderpob-edit");
	
    dialog.modal("show").on("shown", function() {
	    id.val(dtId);
	    header.val(dtHeader);
	    ori_flight.val(dtIdFlight);
	    ori_status.val(dtIdStatus);
		flight.select2("data", { id:dtIdFlight, text:dtFlight });
		status.select2("data", { id:dtIdStatus, text:dtStatus });
		config.select2("data", { id:dtIdConfig, text:dtConfig });
		aircraft.val((dtAircraft == "X") ? "" : dtAircraft);
		equipment.val((dtEquipment == "X") ? "" : dtEquipment);
		seatfc.val((dtSeatfc == "X") ? "" : dtSeatfc);
	    seatbc.val((dtSeatbc == "X") ? "" : dtSeatbc);
	    seatyc.val((dtSeatyc == "X") ? "" : dtSeatyc);
	    seatcr.val((dtSeatcr == "X") ? "" : dtSeatcr);
	    seatcp.val((dtSeatcp == "X") ? "" : dtSeatcp);
		mofc.val((dtMofc == "X") ? "" : dtMofc);
	    mobc.val((dtMobc == "X") ? "" : dtMobc);
	    moyc.val((dtMoyc == "X") ? "" : dtMoyc);
	    mocr.val((dtMocr == "X") ? "" : dtMocr);
	    mocp.val((dtMocp == "X") ? "" : dtMocp);
	    pobfc.val((dtPobfc == "X") ? "" : dtPobfc);
	    pobbc.val((dtPobbc == "X") ? "" : dtPobbc);
	    pobyc.val((dtPobyc == "X") ? "" : dtPobyc);
	    pobcr.val((dtPobcr == "X") ? "" : dtPobcr);
	    pobcp.val((dtPobcp == "X") ? "" : dtPobcp);
	    spmlbbml.val((dtSpmlbbml == "X") ? "" : dtSpmlbbml);
	    spmlksml.val((dtSpmlksml == "X") ? "" : dtSpmlksml);
	    spmlhcake.val((dtSpmlhcake == "X") ? "" : dtSpmlhcake);
	    result.html("");
    	mofc.focus();
    });
}

function DeleteMealOrderPOB(dtHeader) {
	var header = $("#header_delete");
	var result = $("#result-delete");
	var dialog = $("#modal-mealorderpob-delete");
	
    dialog.modal("show").on("shown", function() {
	    header.val(dtHeader);
	    result.html("");
    });
}

function GetProduction(dtId, dtHeader, dtFlight, dtStatus, dtFc, dtBc, dtYc, dtCr, dtCp, dtSfc, dtSbc, dtSyc, dtScr, dtScp, dtFrz) {
	var id = $("#id");
	var header = $("#header");
	var flight = $("#flight");
	var status = $("#status");
	var fc = $("#fc");
	var bc = $("#bc");
	var yc = $("#yc");
	var cr = $("#cr");
	var cp = $("#cp");
	var sfc = $("#sfc");
	var sbc = $("#sbc");
	var syc = $("#syc");
	var scr = $("#scr");
	var scp = $("#scp");
	var frz = $("#frz");
	var result = $("#result");
	var dialog = $("#modal-production");
	
    dialog.modal("show").on("shown", function() {
	    id.val(dtId);
	    header.val(dtHeader);
		flight.html(dtFlight);
		status.html(dtStatus);
	    fc.val((dtFc == "X") ? "" : dtFc);
	    bc.val((dtBc == "X") ? "" : dtBc);
	    yc.val((dtYc == "X") ? "" : dtYc);
	    cr.val((dtCr == "X") ? "" : dtCr);
	    cp.val((dtCp == "X") ? "" : dtCp);
	    sfc.val((dtSfc == "X") ? "" : dtSfc);
	    sbc.val((dtSbc == "X") ? "" : dtSbc);
	    syc.val((dtSyc == "X") ? "" : dtSyc);
	    scr.val((dtScr == "X") ? "" : dtScr);
	    scp.val((dtScp == "X") ? "" : dtScp);
	    frz.val((dtFrz == "X") ? "" : dtFrz);
	    result.html("");
    	fc.focus();
    });
}

function GetMealUplift(dtId, dtHeader, dtFlight, dtStatus, dtFc, dtBc, dtYc, dtCr, dtCp) {
	var id = $("#id");
	var header = $("#header");
	var flight = $("#flight");
	var status = $("#status");
	var fc = $("#fc");
	var bc = $("#bc");
	var yc = $("#yc");
	var cr = $("#cr");
	var cp = $("#cp");
	var result = $("#result");
	var dialog = $("#modal-mealuplift");
	
    dialog.modal("show").on("shown", function() {
	    id.val(dtId);
	    header.val(dtHeader);
		flight.html(dtFlight);
		status.html(dtStatus);
	    fc.val((dtFc == "X") ? "" : dtFc);
	    bc.val((dtBc == "X") ? "" : dtBc);
	    yc.val((dtYc == "X") ? "" : dtYc);
	    cr.val((dtCr == "X") ? "" : dtCr);
	    cp.val((dtCp == "X") ? "" : dtCp);
	    result.html("");
    	fc.focus();
    });
}

$(document).ready(function() {
	$("#modal-mealorderpob-new, #modal-mealorderpob-edit").on("hidden", function() {
		$("#id").val("");
		$("#header").val("");
		$("#ori_flight").val("");
		$("#flight").select2("val", null);
		$("#ori_status").val("");
		$("#status").select2("val", null);
		$("#config").select2("val", null);
	    $("#aircraft").val("").end();
		$("#equipment").val("");
		$("#seatfc").val("");
        $("#seatbc").val("");
        $("#seatyc").val("");
        $("#seatcr").val("");
        $("#seatcp").val("");
        $("#mofc").val("");
        $("#mobc").val("");
        $("#moyc").val("");
        $("#mocr").val("");
        $("#mocp").val("");
        $("#pobfc").val("");
        $("#pobbc").val("");
        $("#pobyc").val("");
        $("#pobcr").val("");
        $("#pobcp").val("");
        $("#bbml").val("");
        $("#ksml").val("");
        $("#hcake").val("");
        $("#result").html("");
    });
    
    $("#modal-mealorderpob-delete").on("hidden", function() {
		$("#id-delete").val("");
		$("#header-delete").val("");
        $("#result-delete").html("");
    });
    
    $("#modal-production").on("hidden", function() {
    	$("#header").val("");
		$("#flight").html("");
		$("#status").html("");
		$("#fc").val("");
        $("#bc").val("");
        $("#yc").val("");
        $("#cr").val("");
        $("#cp").val("");
        $("#sfc").val("");
        $("#sbc").val("");
        $("#syc").val("");
        $("#scr").val("");
        $("#scp").val("");
        $("#frz").val("");
        $("#result").html("");
    });
    
    $("#modal-mealuplift").on("hidden", function() {
    	$("#header").val("");
		$("#flight").html("");
		$("#status").html("");
		$("#fc").val("");
        $("#bc").val("");
        $("#yc").val("");
        $("#cr").val("");
        $("#cp").val("");
        $("#result").html("");
    });
});

$(document).ready(function(){
	$("#form-mealorderpob-new, #form-mealorderpob-edit").submit(function(event) {
		var flight = $("#flight");
		var status = $("#status");
		var config = $("#config");
		var mofc = $("#mofc");
		var mobc = $("#mobc");
		var moyc = $("#moyc");
		var mocr = $("#mocr");
		var mocp = $("#mocp");
		var pobfc = $("#pobfc");
		var pobbc = $("#pobbc");
		var pobyc = $("#pobyc");
		var pobcr = $("#pobcr");
		var pobcp = $("#pobcp");
		var spmlbbml = $("#bbml");
		var spmlksml = $("#ksml");
		var spmlhcake = $("#hcake");
		var save = $("#save");
		var cancel = $("#cancel");
		var loading = $("#loading");
		var result = $("#result");
		var dialog = $("#modal-mealorderpob-new, #modal-mealorderpob-edit");
		var dt = $("#dt-mealorderpob-new, #dt-mealorderpob-edit").DataTable();
		var scrollPos = $(".dataTables_scrollBody").scrollTop();
		
		if (flight.val() != "" && status.val() != "" && config.val() != "" && mofc.val() != "" && mobc.val() != "" && moyc.val() != "" && mocr.val() != "" && mocp.val() != "" && pobfc.val() != "" && pobbc.val() != "" && pobyc.val() != "" && pobcr.val() != "" && pobcp.val() != "" && spmlbbml.val() != "" && spmlksml.val() != "" && spmlhcake.val() != "") {
			$.ajax({
				data : $(this).serialize(),
				url  : "modules/mealorderpob/mealorderpob_action.php?page=mealorderpob&act=insert",
				beforeSend: function() {
					loading.html("<img class='loader' style='padding:4px;' src='assets/img/loading.gif'>");
					result.hide();
					save.attr("disabled", true);
					cancel.attr("disabled", true);
			    },
				success: function(response){
					result.show();
					if(response == "error") {
						result.html("<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>&times;</button><center>ERROR OCCURED WHILE SUBMITTING DATA !</center></div>");
					} else if(response == "exist") {
						result.html("<div class='alert alert-warning'><button type='button' class='close' data-dismiss='alert'>&times;</button><center>MEAL ORDER & P.O.B IS ALREADY EXIST !</center></div>");
					} else if(response == "insert") {
						result.html("<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>&times;</button><center>MEAL ORDER & P.O.B HAS BEEN SUCCESSFULLY SAVED !</center></div>");
						setTimeout(function() {
							dialog.modal("hide").on("hidden", function () {
								dt.ajax.reload(function() { $(".dataTables_scrollBody").scrollTop(scrollPos); }, false);
							});
					  	}, 1000);
					} else {
						result.html("<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>&times;</button><center>FAILED TO CONNECT TO THE SERVER !</center></div>");
					}
				},
				complete: function() {
					loading.html("");
					save.attr("disabled", false);
					cancel.attr("disabled", false);
				}
			});
			event.preventDefault();
		} else {
			result.show();
			result.html("<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>&times;</button><center>PLEASE FILL ALL REQUIRED FIELD !</center></div>");
		}
		return false;
	});
	
	$("#form-mealorderpob-delete").submit(function(event) {
		var header_delete = $("#header_delete");
		var yes = $("#yes");
		var no = $("#no");
		var loading = $("#loading-delete");
		var result = $("#result-delete");
		var dialog = $("#modal-mealorderpob-delete");
		var dt = $("#dt-mealorderpob-new, #dt-mealorderpob-edit").DataTable();
		var scrollPos = $(".dataTables_scrollBody").scrollTop();
		
		if (header_delete.val() != "") {
			$.ajax({
				data : $(this).serialize(),
				url  : "modules/mealorderpob/mealorderpob_action.php?page=mealorderpob&act=delete",
				beforeSend: function() {
					loading.html("<img class='loader' style='padding:4px;' src='assets/img/loading.gif'>");
					result.hide();
					yes.attr("disabled", true);
					no.attr("disabled", true);
			    },
				success: function(response){
					result.show();
					if(response == "error") {
						result.html("<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>&times;</button><center>ERROR OCCURED WHILE DELETING DATA !</center></div>");
					} else if(response == "delete") {
						result.html("<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>&times;</button><center>MEAL ORDER & P.O.B HAS BEEN SUCCESSFULLY DELETED !</center></div>");
						setTimeout(function() {
							dialog.modal("hide").on("hidden", function () {
								dt.ajax.reload(function() { $(".dataTables_scrollBody").scrollTop(scrollPos); }, false);
							});
					  	}, 1000);
					} else {
						result.html("<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>&times;</button><center>FAILED TO CONNECT TO THE SERVER !</center></div>");
					}
				},
				complete: function() {
					loading.html("");
					yes.attr("disabled", false);
					no.attr("disabled", false);
				}
			});
			event.preventDefault();
		} else {
			result.show();
			result.html("<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>&times;</button><center>ERROR OCCURED WHILE DELETING DATA !</center></div>");
		}
		return false;
	});
	
	$("#form-production").submit(function(event) {
		var fc = $("#fc");
		var bc = $("#bc");
		var yc = $("#yc");
		var cr = $("#cr");
		var cp = $("#cp");
		var sfc = $("#sfc");
		var sbc = $("#sbc");
		var syc = $("#syc");
		var scr = $("#scr");
		var scp = $("#scp");
		var frz = $("#frz");
		var save = $("#save");
		var cancel = $("#cancel");
		var loading = $("#loading");
		var result = $("#result");
		var dialog = $("#modal-production");
		var dt = $("#dt-production-edit").DataTable();
		var scrollPos = $(".dataTables_scrollBody").scrollTop();
		
		if (fc.val() != "" && bc.val() != "" && yc.val() != "" && cr.val() != "" && cp.val() != "" && sfc.val() != "" && sbc.val() != "" && syc.val() != "" && scr.val() != "" && scp.val() != "" && frz.val() != "") {
			$.ajax({
				data : $(this).serialize(),
				url  : "modules/production/production_action.php?page=production&act=insert",
				beforeSend: function() {
					loading.html("<img class='loader' style='padding:4px;' src='assets/img/loading.gif'>");
					result.hide();
					save.attr("disabled", true);
					cancel.attr("disabled", true);
			    },
				success: function(response){
					result.show();
					if(response == "error") {
						result.html("<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>&times;</button><center>ERROR OCCURED WHILE SUBMITTING DATA !</center></div>");
					} else if(response == "insert") {
						result.html("<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>&times;</button><center>PRODUCTION HAS BEEN SUCCESSFULLY SAVED !</center></div>");
						setTimeout(function() {
							dialog.modal("hide").on("hidden", function () {
								dt.ajax.reload(function() { $(".dataTables_scrollBody").scrollTop(scrollPos); }, false);
							});
					  	}, 1000);
					} else {
						result.html("<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>&times;</button><center>FAILED TO CONNECT TO THE SERVER !</center></div>");
					}
				},
				complete: function() {
					loading.html("");
					save.attr("disabled", false);
					cancel.attr("disabled", false);
				}
			});
			event.preventDefault();
		} else {
			result.show();
			result.html("<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>&times;</button><center>PLEASE FILL ALL REQUIRED FIELD !</center></div>");
		}
		return false;
	});
	
	$("#form-mealuplift").submit(function(event) {
		var fc = $("#fc");
		var bc = $("#bc");
		var yc = $("#yc");
		var cr = $("#cr");
		var cp = $("#cp");
		var save = $("#save");
		var cancel = $("#cancel");
		var loading = $("#loading");
		var result = $("#result");
		var dialog = $("#modal-mealuplift");
		var dt = $("#dt-mealuplift-edit").DataTable();
		var scrollPos = $(".dataTables_scrollBody").scrollTop();
		
		if (fc.val() != "" && bc.val() != "" && yc.val() != "" && cr.val() != "" && cp.val() != "") {
			$.ajax({
				data : $(this).serialize(),
				url  : "modules/mealuplift/mealuplift_action.php?page=mealuplift&act=insert",
				beforeSend: function() {
					loading.html("<img class='loader' style='padding:4px;' src='assets/img/loading.gif'>");
					result.hide();
					save.attr("disabled", true);
					cancel.attr("disabled", true);
			    },
				success: function(response){
					result.show();
					if(response == "error") {
						result.html("<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>&times;</button><center>ERROR OCCURED WHILE SUBMITTING DATA !</center></div>");
					} else if(response == "insert") {
						result.html("<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>&times;</button><center>MEAL UPLIFT HAS BEEN SUCCESSFULLY SAVED !</center></div>");
						setTimeout(function() {
							dialog.modal("hide").on("hidden", function () {
								dt.ajax.reload(function() { $(".dataTables_scrollBody").scrollTop(scrollPos); }, false);
							});
					  	}, 1000);
					} else {
						result.html("<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>&times;</button><center>FAILED TO CONNECT TO THE SERVER !</center></div>");
					}
				},
				complete: function() {
					loading.html("");
					save.attr("disabled", false);
					cancel.attr("disabled", false);
				}
			});
			event.preventDefault();
		} else {
			result.show();
			result.html("<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>&times;</button><center>PLEASE FILL ALL REQUIRED FIELD !</center></div>");
		}
		return false;
	});
});

$(document).ready(function() {
	$(".report").contentWindow.location.href = report.src;
});

function print_report(id) {
    var rpt = document.getElementById(id).contentWindow;
    rpt.focus();
    rpt.print();
    return false;
}

function view_report(url) {
	var w = window.open(url, "", "width="+screen.width+", height="+screen.height+", toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, fullscreen=yes");
	w.focus();
}