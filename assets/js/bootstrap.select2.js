$(document).ready(function() {
	$(".select2-length").select2({
		formatNoMatches: function(term) {
        	return " ";
    	}
	});
	
	$(".select2-single").select2({
		allowClear: true,
		formatNoMatches: function(term) {
        	return " ";
    	}
	});
	
	$(".select2-multiple").select2({
		multiple: true,
		tags: [""],
		tokenSeparators: [",", " "]
	});
	
	function initSelect2(sAttr, sUrl, sClear) {
		$(sAttr).select2({
			allowClear: sClear,
	        ajax: {
		        url: sUrl,
		        dataType: "json",
		        type: "GET",
		        quietMillis: 50,
		        data: function(term) {
		            return {
		                q: term
		            };
		        },
		        results: function(data) {
		            return {
		                results: $.map(data, function(item) {
		                    return {
		                        id: item.id,
		                        text: item.text
		                    }
		                })
		            };
		        }
		    },
		    initSelection: function(element, callback) {
		        callback({ id: element.attr("init-id"), text: element.attr("init-text") });
		    },
		    formatSearching: function() {
		    	return "SEARCHING\u2026";
		    },
		    formatNoMatches: function(term) {
	        	return " ";
	    	}
		}).select2("val", []);
	}
	
	initSelect2(".aviation", "modules/aviation/aviation_action.php?page=aviation&act=select", true);
	initSelect2(".aircraft", "modules/aircraft/aircraft_action.php?page=aircraft&act=select", true);
	initSelect2(".equipment", "modules/equipment/equipment_action.php?page=equipment&act=select", true);
	initSelect2(".airline", "modules/airline/airline_action.php?page=airline&act=select", true);
	initSelect2(".flight", "modules/flight/flight_action.php?page=flight&act=select", true);
	initSelect2(".status", "modules/status/status_action.php?page=status&act=select", true);
	initSelect2(".config", "modules/config/config_action.php?page=config&act=select", true);
	initSelect2("input[name='flight[]']", "modules/flight/flight_action.php?page=flight&act=select", false);
	initSelect2("input[name='status[]']", "modules/status/status_action.php?page=status&act=select", false);
	initSelect2("input[name='config[]']", "modules/config/config_action.php?page=config&act=select", false);
	
	$(".config-select").on("change", function(e) {
		var data = $(this).select2("data");
		if(data) {
			$.ajax({
				data : $(this).serialize(),
				url  : "modules/config/config_action.php?page=config&act=choose",
				success: function(result){
					if (result) {
						$("#aircraft").val(result[0].aircraft);
						$("#equipment").val(result[0].equipment);
	                    $("#seatfc").val(result[0].seat_fc);
	                    $("#seatbc").val(result[0].seat_bc);
	                    $("#seatyc").val(result[0].seat_yc);
	                    $("#seatcr").val(result[0].seat_cr);
	                    $("#seatcp").val(result[0].seat_cp);
	                }
				}
			});
		}
	});
	
	$(".config-select").on("select2-clearing", function(e) {
		$("#aircraft").val("");
		$("#equipment").val("");
		$("#seatfc").val("");
        $("#seatbc").val("");
        $("#seatyc").val("");
        $("#seatcr").val("");
        $("#seatcp").val("");
	});
});