<?php
include "../../includes/configuration.php";

if (empty($_SESSION["user_session"])) {
	header("location:../../index.php"); 
} else {
	include "../../includes/connection.php";
	include "../../includes/secure.php";
	include "../../includes/datetime.php";
	
    $page = $_GET["page"];
	$act = $_GET["act"];
    
    if ($page == "dashboard" and $act == "chart") {
        $from = !empty($_GET["from"]) ? $secure->sanitize($_GET["from"]) : date("d/m/Y");
	    $to = !empty($_GET["to"]) ? $secure->sanitize($_GET["to"]) : date("d/m/Y");
        $min_date = strtotime($datetime->database_date($from)." UTC")*1000;
        $max_date = strtotime($datetime->database_date($to)." UTC")*1000;
        
        $query = $mysqli->query("select * from view_statistic_overcost where date between '".$datetime->database_date($from)."' and '".$datetime->database_date($to)."'");
        while ($r = $query->fetch_assoc()) {
            $date = strtotime($r["date"]." UTC")*1000;
            $supply_fc[] = "[".$date.", ".$r["over_supply_fc"]."]";
            $supply_bc[] = "[".$date.", ".$r["over_supply_bc"]."]";
            $supply_yc[] = "[".$date.", ".$r["over_supply_yc"]."]";
            $supply_cr[] = "[".$date.", ".$r["over_supply_cr"]."]";
            $supply_cp[] = "[".$date.", ".$r["over_supply_cp"]."]";
            $production_fc[] = "[".$date.", ".$r["over_production_fc"]."]";
            $production_bc[] = "[".$date.", ".$r["over_production_bc"]."]";
            $production_yc[] = "[".$date.", ".$r["over_production_yc"]."]";
            $production_cr[] = "[".$date.", ".$r["over_production_cr"]."]";
            $production_cp[] = "[".$date.", ".$r["over_production_cp"]."]";
            $wasted_meal_fc[] = "[".$date.", ".$r["wasted_meal_fc"]."]";
            $wasted_meal_bc[] = "[".$date.", ".$r["wasted_meal_bc"]."]";
            $wasted_meal_yc[] = "[".$date.", ".$r["wasted_meal_yc"]."]";
            $wasted_meal_cr[] = "[".$date.", ".$r["wasted_meal_cr"]."]";
            $wasted_meal_cp[] = "[".$date.", ".$r["wasted_meal_cp"]."]";
        }

        $arr_supply_fc = is_null($supply_fc) ? "[0, 0]" : join(",", $supply_fc);
        $arr_supply_bc = is_null($supply_bc) ? "[0, 0]" : join(",", $supply_bc);
        $arr_supply_yc = is_null($supply_yc) ? "[0, 0]" : join(",", $supply_yc);
        $arr_supply_cr = is_null($supply_cr) ? "[0, 0]" : join(",", $supply_cr);
        $arr_supply_cp = is_null($supply_cp) ? "[0, 0]" : join(",", $supply_cp);

        $arr_production_fc = is_null($production_fc) ? "[0, 0]" : join(",", $production_fc);
        $arr_production_bc = is_null($production_bc) ? "[0, 0]" : join(",", $production_bc);
        $arr_production_yc = is_null($production_yc) ? "[0, 0]" : join(",", $production_yc);
        $arr_production_cr = is_null($production_cr) ? "[0, 0]" : join(",", $production_cr);
        $arr_production_cp = is_null($production_cp) ? "[0, 0]" : join(",", $production_cp);

        $arr_wasted_meal_fc = is_null($wasted_meal_fc) ? "[0, 0]" : join(",", $wasted_meal_fc);
        $arr_wasted_meal_bc = is_null($wasted_meal_bc) ? "[0, 0]" : join(",", $wasted_meal_bc);
        $arr_wasted_meal_yc = is_null($wasted_meal_yc) ? "[0, 0]" : join(",", $wasted_meal_yc);
        $arr_wasted_meal_cr = is_null($wasted_meal_cr) ? "[0, 0]" : join(",", $wasted_meal_cr);
        $arr_wasted_meal_cp = is_null($wasted_meal_cp) ? "[0, 0]" : join(",", $wasted_meal_cp);

        echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
			  	<html xmlns='http://www.w3.org/1999/xhtml' lang='en'>
					<head>
						<title>AVOS - CHART</title>
						<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
						<meta http-equiv='X-UA-Compatible' content='IE=edge, chrome=1'>
						<meta http-equiv='cache-control' content='max-age=0, no-cache, no-store, must-revalidate, post-check=0, pre-check=0' />
						<meta http-equiv='pragma' content='no-cache' />
						<meta http-equiv='expires' content='-1' />
						<meta http-equiv='expires' content='Tue, 01 Jan 1980 1:00:00 GMT' />
						<meta http-equiv='copyright' content='PT. AEROFOOD INDONESIA - ACS DENPASAR. ALL RIGHTS RESERVED.' />
						<meta name='author' content='COKORDA GDE AGUNG SMARA ADNYANA PUTRA' />
						<meta name='contact' content='COKORDA.SMARA@GMAIL.COM' />
				    	<meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1' />
				    	<link type='image/x-icon' rel='shortcut icon' href='../../assets/img/favicon.ico' media='all' />
				    	<link type='image/ico' rel='icon' href='../../assets/img/favicon.ico' media='all' />
                        <link type='text/css' rel='stylesheet' href='../../assets/css/bootstrap.min.css' media='all' />
                        <link type='text/css' rel='stylesheet' href='../../assets/css/style.css' media='all' />
					</head>
					<body class='panel'>
                        
                        <div id='chart_supply' style='height:350px;'></div>
                        
			            <div class='separator'></div>
                        
                        <div id='chart_production' style='height:350px;'></div>
                        
                        <div class='separator'></div>

                        <div id='chart_wasted_meal' style='height:350px;'></div>
                        
                        <script type='text/javascript' src='../../assets/js/jquery.min.js'></script>
                        <script type='text/javascript' src='../../assets/js/bootstrap.min.js'></script>
                        <script type='text/javascript' src='../../assets/js/highcharts.js'></script>
                        <script type='text/javascript' src='../../assets/js/highcharts/exporting.js'></script>
                        <script type='text/javascript' src='../../assets/js/highcharts/no-data-to-display.js'></script>
                        <script type='text/javascript'>
                            var chart_supply = new Highcharts.Chart({
                                chart: {
                                    renderTo: 'chart_supply',
                                    zoomType: 'x',
                                    type: 'column'
                                },
                                
                                title: {
                                    text: '<b>OVER SUPPLY</b>'
                                },
                                
                                subtitle: {
                                    text: '<i>DAILY OVERCOST DISPLAY STATISTIC</i>'
                                },
                                
                                colors: ['#ff0000', '#0000ff', '#00ff00', '#ff8000', '#ff00ff'],
                                
                                xAxis: {
                                    type: 'datetime',
                                    gridLineWidth: 1,
                                    labels: {
                                        rotation: -45,
                                        align: 'right'
                                    },
                                    min: new Date(".$min_date.").getTime(),
                                    max: new Date(".$max_date.").getTime()
                                },
                                
                                yAxis: {
                                    title: {
                                        text: 'QUANTITY'
                                    },         
                                    min: 0
                                },
                                
                                tooltip: {
                                    shared: true,
                                    crosshairs: true
                                },
                                
                                plotOptions: {
                                    line: {
                                        marker: {
                                            radius: 1
                                        },
                                        lineWidth: 2,
                                        states: {
                                            hover: {
                                                lineWidth: 2
                                            }
                                        }
                                    }
                                },
                                
                                series: [{
                                    name: 'F/C',
                                    data: [".$arr_supply_fc."]
                                }, {
                                    name: 'B/C',
                                    data: [".$arr_supply_bc."]
                                }, {
                                    name: 'Y/C',
                                    data: [".$arr_supply_yc."]
                                }, {
                                    name: 'C/R',
                                    data: [".$arr_supply_cr."]
                                }, {
                                    name: 'C/P',
                                    data: [".$arr_supply_cp."]
                                }],
                                
                                credits : {
                                    enabled : false
                                }
                            });
                            
                            var chart_production = new Highcharts.Chart({
                                chart: {
                                    renderTo: 'chart_production',
                                    zoomType: 'x',
                                    type: 'column'
                                },
                                
                                title: {
                                    text: '<b>OVER PRODUCTION</b>'
                                },
                                
                                subtitle: {
                                    text: '<i>DAILY OVERCOST DISPLAY STATISTIC</i>'
                                },
                                
                                colors: ['#ff0000', '#0000ff', '#00ff00', '#ff8000', '#ff00ff'],
                                
                                xAxis: {
                                    type: 'datetime',
                                    gridLineWidth: 1,
                                    labels: {
                                        rotation: -45,
                                        align: 'right'
                                    },
                                    min: new Date(".$min_date.").getTime(),
                                    max: new Date(".$max_date.").getTime()
                                },
                                
                                yAxis: {
                                    title: {
                                        text: 'QUANTITY'
                                    },
                                    min: 0
                                },
                                
                                tooltip: {
                                    shared: true,
                                    crosshairs: true
                                },
                                
                                plotOptions: {
                                    line: {
                                        marker: {
                                            radius: 1
                                        },
                                        lineWidth: 2,
                                        states: {
                                            hover: {
                                                lineWidth: 2
                                            }
                                        }
                                    }
                                },
                                
                                series: [{
                                    name: 'F/C',
                                    data: [".$arr_production_fc."]
                                }, {
                                    name: 'B/C',
                                    data: [".$arr_production_bc."]
                                }, {
                                    name: 'Y/C',
                                    data: [".$arr_production_yc."]
                                }, {
                                    name: 'C/R',
                                    data: [".$arr_production_cr."]
                                }, {
                                    name: 'C/P',
                                    data: [".$arr_production_cp."]
                                }],
                                
                                credits : {
                                    enabled : false
                                }
                            });
                            
                            var chart_supply = new Highcharts.Chart({
                                chart: {
                                    renderTo: 'chart_wasted_meal',
                                    zoomType: 'x',
                                    type: 'column'
                                },
                                
                                title: {
                                    text: '<b>WASTED MEAL</b>'
                                },
                                
                                subtitle: {
                                    text: '<i>DAILY OVERCOST DISPLAY STATISTIC</i>'
                                },
                                
                                colors: ['#ff0000', '#0000ff', '#00ff00', '#ff8000', '#ff00ff'],
                                
                                xAxis: {
                                    type: 'datetime',
                                    gridLineWidth: 1,
                                    labels: {
                                        rotation: -45,
                                        align: 'right'
                                    },
                                    min: new Date(".$min_date.").getTime(),
                                    max: new Date(".$max_date.").getTime()
                                },
                                
                                yAxis: {
                                    title: {
                                        text: 'QUANTITY'
                                    },         
                                    min: 0
                                },
                                
                                tooltip: {
                                    shared: true,
                                    crosshairs: true
                                },
                                
                                plotOptions: {
                                    line: {
                                        marker: {
                                            radius: 1
                                        },
                                        lineWidth: 2,
                                        states: {
                                            hover: {
                                                lineWidth: 2
                                            }
                                        }
                                    }
                                },
                                
                                series: [{
                                    name: 'F/C',
                                    data: [".$arr_wasted_meal_fc."]
                                }, {
                                    name: 'B/C',
                                    data: [".$arr_wasted_meal_bc."]
                                }, {
                                    name: 'Y/C',
                                    data: [".$arr_wasted_meal_yc."]
                                }, {
                                    name: 'C/R',
                                    data: [".$arr_wasted_meal_cr."]
                                }, {
                                    name: 'C/P',
                                    data: [".$arr_wasted_meal_cp."]
                                }],
                                
                                credits : {
                                    enabled : false
                                }
                            });
                        </script>
                    </body>
				</html>";
    }
}
?>