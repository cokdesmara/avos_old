/* Set the defaults for DataTables initialisation */
$.extend( true, $.fn.dataTable.defaults, {
	"sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
	"sPaginationType": "bootstrap",
	"oLanguage": {
		"sLengthMenu": "_MENU_ records per page"
	}
} );

/* Default class modification */
$.extend( $.fn.dataTableExt.oStdClasses, {
	"sWrapper": "dataTables_wrapper",
	"sLengthSelect": "select2-length input-small input-upper",
	"sFilterInput": "input-xlarge input-upper"
} );

/* API method to get paging information */
$.fn.dataTableExt.oApi.fnPagingInfo = function ( oSettings )
{
    return {
        "iStart":         oSettings._iDisplayStart,
        "iEnd":           oSettings.fnDisplayEnd(),
        "iLength":        oSettings._iDisplayLength,
        "iTotal":         oSettings.fnRecordsTotal(),
        "iFilteredTotal": oSettings.fnRecordsDisplay(),
        "iPage":          Math.ceil( oSettings._iDisplayStart / oSettings._iDisplayLength ),
        "iTotalPages":    Math.ceil( oSettings.fnRecordsDisplay() / oSettings._iDisplayLength )
    };
}

$.extend( $.fn.dataTableExt.oPagination, {
    "bootstrap": {
        "fnInit": function( oSettings, nPaging, fnDraw ) {
            var oLang = oSettings.oLanguage.oPaginate;
            var fnClickHandler = function ( e ) {
                e.preventDefault();
                if ( oSettings.oApi._fnPageChange(oSettings, e.data.action) ) {
                    fnDraw( oSettings );
                }
            };
 			
            $(nPaging).addClass("pagination pagination-right").append(
                '<ul>' +
                    '<li class="prev disabled"><a href="javascript:void(0)">' + oLang.sFirst + '</a></li>' +
                    '<li class="prev disabled"><a href="javascript:void(0)">' + oLang.sPrevious + '</a></li>'+
                    '<li class="next disabled"><a href="javascript:void(0)">' + oLang.sNext + '</a></li>' +
                    '<li class="next disabled"><a href="javascript:void(0)">' + oLang.sLast + '</a></li>' +
                '</ul>'
            );
            var els = $('a', nPaging);
            $(els[0]).bind('click.DT', { action: "first" }, fnClickHandler);
            $(els[1]).bind('click.DT', { action: "previous" }, fnClickHandler );
            $(els[2]).bind('click.DT', { action: "next" }, fnClickHandler);
            $(els[3]).bind('click.DT', { action: "last" }, fnClickHandler);
        },
 		
        "fnUpdate": function ( oSettings, fnDraw ) {
            var iListLength = 5;
            var oPaging = oSettings.oInstance.fnPagingInfo();
            var an = oSettings.aanFeatures.p;
            var i, j, sClass, iStart, iEnd, iHalf=Math.floor(iListLength/2);
 			
            if ( oPaging.iTotalPages < iListLength) {
                iStart = 1;
                iEnd = oPaging.iTotalPages;
            }
            else if ( oPaging.iPage <= iHalf ) {
                iStart = 1;
                iEnd = iListLength;
            } else if ( oPaging.iPage >= (oPaging.iTotalPages-iHalf) ) {
                iStart = oPaging.iTotalPages - iListLength + 1;
                iEnd = oPaging.iTotalPages;
            } else {
                iStart = oPaging.iPage - iHalf + 1;
                iEnd = iStart + iListLength - 1;
            }
 			
            for ( i=0, iLen=an.length ; i<iLen ; i++ ) {
                // Remove the middle elements
                $("li:gt(1)", an[i]).filter(":not(.next)").remove();
 				
                // Add the new list items and their event handlers
                for ( j=iStart ; j<=iEnd ; j++ ) {
					sClass = (j==oPaging.iPage+1) ? "class='active'" : "";
                    $('<li '+sClass+'><a href="javascript:void(0)">'+j+'</a></li>')
                        .insertBefore( $('li.next:first', an[i])[0] )
                        .bind('click', function (e) {
                            e.preventDefault();
                            oSettings._iDisplayStart = (parseInt($('a', this).text(),10)-1) * oPaging.iLength;
                            fnDraw( oSettings );
                        } );
                }
 				
                // Add / remove disabled classes from the static elements
                if ( oPaging.iPage === 0 ) {
                    $('li.prev', an[i]).addClass('disabled');
                } else {
                    $('li.prev', an[i]).removeClass('disabled');
                }
 				
                if ( oPaging.iPage === oPaging.iTotalPages-1 || oPaging.iTotalPages === 0 ) {
                    $('li.next', an[i]).addClass('disabled');
                } else {
                    $('li.next', an[i]).removeClass('disabled');
                }
            }
        }
    }
} );

/*
 * TableTools Bootstrap compatibility
 * Required TableTools 2.1+
 */
if ( $.fn.DataTable.TableTools ) {
	// Set the classes that TableTools uses to something suitable for Bootstrap
	$.extend( true, $.fn.DataTable.TableTools.classes, {
		"container": "DTTT btn-group",
		"buttons": {
			"normal": "btn",
			"disabled": "disabled"
		},
		"collection": {
			"container": "DTTT_dropdown dropdown-menu",
			"buttons": {
				"normal": "",
				"disabled": "disabled"
			}
		},
		"print": {
			"info": "DTTT_print_info modal"
		},
		"select": {
			"row": "active"
		}
	} );

	// Have the collection use a bootstrap compatible dropdown
	$.extend( true, $.fn.DataTable.TableTools.DEFAULTS.oTags, {
		"collection": {
			"container": "ul",
			"button": "li",
			"liner": "a"
		}
	} );
}

/* Table initialisation */
function initDataTables(dtId, dtUrl, dtSort, dtRow, dtClass) {
	$(dtId).ready(function() {
		var dt = $(dtId).DataTable( {
			"sDom": "<'fix-top'<'pull-left'r><'form-inline form-search'f>> t <'fix-bottom'<'pull-left'T > <'form-inline'l> <'pull-right'p> <'pull-right'r>>",
			"sPaginationType": "bootstrap",
			"bProcessing": true,
			"bServerSide": true,
	        "sAjaxSource": dtUrl,
	        "columnDefs": [
	        	{ "targets": "no-sort", "orderable": false },
	        	{ "targets": "no-search", "searchable": false },
	        	{ "targets": "no-visible", "visible": false } ],
	        "aaSorting": [[ 1, dtSort ]],
	        "bSortClasses": false,
	        "scrollY": "100vh",
	        "scrollX": true,
            "scrollCollapse": false,
	        "bDeferRender": true,
	        "aLengthMenu": [[50, 100, 200, 300, 400, 500], [50, 100, 200, 300, 400, 500]],
			"language": {
	            "sSearch": "SEARCH : ",
	            "sSearchPlaceholder": "KEYWORDS",
	            "zeroRecords": "NO MATCHING RECORDS FOUND",
	            "infoEmpty": "NO MATCHING RECORDS FOUND",
	            "lengthMenu": "_MENU_ RECORDS",
	            "sProcessing": "<img class='loader' src='assets/img/loading.gif'>",
	            "oPaginate": { "sFirst": "FIRST", "sPrevious": "PREV", "sNext": "NEXT", "sLast": "LAST" }
	        },
	        "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
	        	$(dtRow, nRow).addClass(dtClass);
	        },
	        "tableTools": {
	            "sSwfPath": "assets/swf/copy_csv_xls_pdf.swf",
	            "aButtons": [
	                {
	                    "sExtends": "xls",
	                    "sButtonText": "EXPORT EXCEL",
	                    "sButtonClass": "btn-success export",
	                    "mColumns": "visible"
	                }
		        ]
	        }
		});
		setInterval( function () {
			var scrollPos = $(".dataTables_scrollBody").scrollTop();
		    dt.ajax.reload(function() {
		        $(".dataTables_scrollBody").scrollTop(scrollPos);
		    }, false);
		}, 30000);
	});
}

function isDuplicate(array) {
	var valuesSoFar = {};
    for (var i = 0; i < array.length; ++i) {
        var value = array[i];
        if (Object.prototype.hasOwnProperty.call(valuesSoFar, value)) {
            return true;
        }
        valuesSoFar[value] = true;
    }
    return false;
}

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"), results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

/* Table load */
initDataTables("#dt-user", "modules/user/user_action.php?page=user&act=list", "desc", "td:eq(0),td:eq(4),td:eq(5)", "text-center");
initDataTables("#dt-aviation", "modules/aviation/aviation_action.php?page=aviation&act=list", "desc", "td:eq(0),td:eq(1),td:eq(3),td:eq(4)", "text-center");
initDataTables("#dt-aircraft", "modules/aircraft/aircraft_action.php?page=aircraft&act=list", "desc", "td:eq(0),td:eq(2),td:eq(3)", "text-center");
initDataTables("#dt-equipment", "modules/equipment/equipment_action.php?page=equipment&act=list", "desc", "td:eq(0),td:eq(2),td:eq(3)", "text-center");
initDataTables("#dt-airline", "modules/airline/airline_action.php?page=airline&act=list", "desc", "td:eq(0),td:eq(1),td:eq(3),td:eq(4)", "text-center");
initDataTables("#dt-flight", "modules/flight/flight_action.php?page=flight&act=list", "desc", "td:eq(0),td:eq(2),td:eq(3),td:eq(4),td:eq(5),td:eq(6)", "text-center");
initDataTables("#dt-config", "modules/config/config_action.php?page=config&act=list", "desc", "td:eq(0),td:eq(1),td:eq(2),td:eq(3),td:eq(4),td:eq(5),td:eq(6),td:eq(7),td:eq(8),td:eq(9),td:eq(10)", "text-center");
initDataTables("#dt-status", "modules/status/status_action.php?page=status&act=list", "desc", "td:eq(0),td:eq(1),td:eq(3),td:eq(4)", "text-center");
initDataTables("#dt-mealorderpob", "modules/mealorderpob/mealorderpob_action.php?page=mealorderpob&act=list", "desc", "td:eq(0),td:eq(1),td:eq(2),td:eq(3),td:eq(4),td:eq(5),td:eq(6),td:eq(7),td:eq(8),td:eq(9),td:eq(10),td:eq(11),td:eq(12),td:eq(13),td:eq(14),td:eq(15),td:eq(16),td:eq(17),td:eq(18),td:eq(19),td:eq(20),td:eq(21),td:eq(22),td:eq(23),td:eq(24)", "text-center");
initDataTables("#dt-mealorderpob-new", "modules/mealorderpob/mealorderpob_action.php?page=mealorderpob&act=new&date=" + getParameterByName("date"), "desc", "td:eq(0),td:eq(1),td:eq(2),td:eq(3),td:eq(4),td:eq(5),td:eq(6),td:eq(7),td:eq(8),td:eq(9),td:eq(10),td:eq(11),td:eq(12),td:eq(13),td:eq(14),td:eq(15),td:eq(16),td:eq(17)", "text-center");
initDataTables("#dt-mealorderpob-detail", "modules/mealorderpob/mealorderpob_action.php?page=mealorderpob&act=detail&date=" + getParameterByName("date"), "desc", "td:eq(0),td:eq(1),td:eq(2),td:eq(3),td:eq(4),td:eq(5),td:eq(6),td:eq(7),td:eq(8),td:eq(9),td:eq(10),td:eq(11),td:eq(12),td:eq(13),td:eq(14),td:eq(15),td:eq(16),td:eq(17)", "text-center");
initDataTables("#dt-mealorderpob-edit", "modules/mealorderpob/mealorderpob_action.php?page=mealorderpob&act=edit&date=" + getParameterByName("date"), "desc", "td:eq(0),td:eq(1),td:eq(2),td:eq(3),td:eq(4),td:eq(5),td:eq(6),td:eq(7),td:eq(8),td:eq(9),td:eq(10),td:eq(11),td:eq(12),td:eq(13),td:eq(14),td:eq(15),td:eq(16),td:eq(17)", "text-center");
initDataTables("#dt-production", "modules/production/production_action.php?page=production&act=list", "desc", "td:eq(0),td:eq(1),td:eq(2),td:eq(3),td:eq(4),td:eq(5),td:eq(6),td:eq(7),td:eq(8),td:eq(9),td:eq(10),td:eq(11),td:eq(12),td:eq(13),td:eq(14),td:eq(15)", "text-center");
initDataTables("#dt-production-detail", "modules/production/production_action.php?page=production&act=detail&date=" + getParameterByName("date"), "desc", "td:eq(0),td:eq(1),td:eq(2),td:eq(3),td:eq(4),td:eq(5),td:eq(6),td:eq(7),td:eq(8),td:eq(9),td:eq(10),td:eq(11),td:eq(12),td:eq(13)", "text-center");
initDataTables("#dt-production-edit", "modules/production/production_action.php?page=production&act=edit&date=" + getParameterByName("date"), "desc", "td:eq(0),td:eq(1),td:eq(2),td:eq(3),td:eq(4),td:eq(5),td:eq(6),td:eq(7),td:eq(8),td:eq(9),td:eq(10),td:eq(11),td:eq(12),td:eq(13),td:eq(14)", "text-center");
initDataTables("#dt-mealuplift", "modules/mealuplift/mealuplift_action.php?page=mealuplift&act=list", "desc", "td:eq(0),td:eq(1),td:eq(2),td:eq(3),td:eq(4),td:eq(5),td:eq(6),td:eq(7),td:eq(8),td:eq(9),td:eq(10)", "text-center");
initDataTables("#dt-mealuplift-detail", "modules/mealuplift/mealuplift_action.php?page=mealuplift&act=detail&date=" + getParameterByName("date") + "&airline=" + getParameterByName("airline"), "desc", "td:eq(0),td:eq(1),td:eq(2),td:eq(3),td:eq(4),td:eq(5),td:eq(6),td:eq(7)", "text-center");
initDataTables("#dt-mealuplift-edit", "modules/mealuplift/mealuplift_action.php?page=mealuplift&act=edit&date=" + getParameterByName("date") + "&airline=" + getParameterByName("airline"), "desc", "td:eq(0),td:eq(1),td:eq(2),td:eq(3),td:eq(4),td:eq(5),td:eq(6),td:eq(7),td:eq(8)", "text-center");
initDataTables("#dt-flightmeal", "modules/flightmeal/flightmeal_action.php?page=flightmeal&act=list", "desc", "td:eq(0),td:eq(1),td:eq(2),td:eq(3),td:eq(4),td:eq(5),td:eq(6),td:eq(7),td:eq(8),td:eq(9),td:eq(10),td:eq(11),td:eq(12),td:eq(13),td:eq(14),td:eq(15),td:eq(16),td:eq(17),td:eq(18),td:eq(19),td:eq(20),td:eq(21),td:eq(22),td:eq(23),td:eq(24),td:eq(25),td:eq(26),td:eq(27),td:eq(28),td:eq(29)", "text-center");
initDataTables("#dt_flightmeal_detail", "modules/flightmeal/flightmeal_action.php?page=flightmeal&act=detail&date=" + getParameterByName("date"), "desc", "td:eq(0),td:eq(1),td:eq(2),td:eq(3),td:eq(4),td:eq(5),td:eq(6),td:eq(7),td:eq(8),td:eq(9),td:eq(10),td:eq(11),td:eq(12),td:eq(13),td:eq(14),td:eq(15),td:eq(16),td:eq(17),td:eq(18),td:eq(19),td:eq(20),td:eq(21),td:eq(22),td:eq(23),td:eq(24),td:eq(25),td:eq(26),td:eq(27),td:eq(28),td:eq(29),td:eq(29),td:eq(30),td:eq(31),td:eq(32),td:eq(33)", "text-center");
initDataTables("#dt_overcost", "modules/overcost/overcost_action.php?page=overcost&act=list", "desc", "td:eq(0),td:eq(1),td:eq(2),td:eq(3),td:eq(4),td:eq(5),td:eq(6),td:eq(7),td:eq(8),td:eq(9),td:eq(10),td:eq(11),td:eq(12),td:eq(11),td:eq(12),td:eq(13),td:eq(14),td:eq(15),td:eq(16),td:eq(17),td:eq(18),td:eq(19),td:eq(20),td:eq(21)", "text-center");
initDataTables("#dt_overcost_detail", "modules/overcost/overcost_action.php?page=overcost&act=detail&date=" + getParameterByName("date"), "desc", "td:eq(0),td:eq(1),td:eq(2),td:eq(3),td:eq(4),td:eq(5),td:eq(6),td:eq(7),td:eq(8),td:eq(9),td:eq(10),td:eq(11),td:eq(12),td:eq(13),td:eq(14),td:eq(15),td:eq(16),td:eq(17),td:eq(18),td:eq(19),td:eq(20),td:eq(21),td:eq(22),td:eq(23),td:eq(24),td:eq(25),td:eq(26)", "text-center");
initDataTables("#dt_loadfactor", "modules/loadfactor/loadfactor_action.php?page=loadfactor&act=list", "desc", "td:eq(0),td:eq(1),td:eq(2),td:eq(3),td:eq(4),td:eq(5),td:eq(6),td:eq(7),td:eq(8),td:eq(9),td:eq(10),td:eq(11),td:eq(12),td:eq(13),td:eq(14),td:eq(15),td:eq(16),td:eq(17),td:eq(18),td:eq(19),td:eq(20),td:eq(21)", "text-center");
initDataTables("#dt_loadfactor_detail", "modules/loadfactor/loadfactor_action.php?page=loadfactor&act=detail&date=" + getParameterByName("date"), "desc", "td:eq(0),td:eq(1),td:eq(2),td:eq(3),td:eq(4),td:eq(5),td:eq(6),td:eq(7),td:eq(8),td:eq(9),td:eq(10),td:eq(11),td:eq(12),td:eq(13),td:eq(14),td:eq(15),td:eq(16),td:eq(17),td:eq(18),td:eq(19),td:eq(20),td:eq(21),td:eq(22)", "text-center");
initDataTables("#dt_log", "modules/log/log_action.php?page=log&act=list", "desc", "td:eq(0),td:eq(4)", "text-center");