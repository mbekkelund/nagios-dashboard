<?php 
    $refreshvalue = 10; //value in seconds to refresh page
    $pagetitle = "Operations Nagios Dashboard";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <title><? echo($pagetitle); ?></title>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js">
        </script>
        <style type="text/css">
            * {
                margin: 0;
                padding: 0;
            }
            
            body {
                font-family: sans-serif;
                line-height: 1.4em;
        		overflow-x: hidden;
                background: #404040;
                padding: .5em 1em;
            }
            
            table {
               border-collapse: collapse;
               width: 100%;
            }
            
            td {
                padding: .1em 1em;
            }
            
            h1 {
                display: inline-block;
                margin-left: 10px;
            }
            h2 {
                margin: 0 0 .2em 0;
                color: white;
                text-shadow: 1px 1px 0 #000;
                font-size: 1em;
            }
            .clear {
                clear: both;
            }
            .head {
            }
            
            .head th {
            }
            
            .dash {
            }
            .dash_wrapper {
                background: white;
                padding: 1em;
                -moz-border-radius: .5em;
            }
            .dash_unhandled {
                width: 60%;
                float: left;
            }
                .dash_unhandled .dash_wrapper {
                    margin-right: 1em;
                    margin-bottom: 1em;
                }
            .dash_tactical_overview {
                width: 40%;
                float: left;
            }
            .dash_unhandled_service_problems {
                clear: both;
                margin-top: 0em;
            }
            
            .dash_table_head {
                background: -moz-linear-gradient(top center, #d3d3d3, #bdbdbd);
                color: #181818;
                text-shadow: 1px 1px 0 #ededed;
            }
            .dash_table_head th {
                padding: .2em 1em;
                border-bottom: 1px solid #757575;
                border-right: 2px groove #aaa;
            }
            .dash_table_head th:first-child {
                border-left: none;
            }
            .dash_table_head th:last-child {
                border-right: none;
            }
            
            .critical {
                background: -moz-linear-gradient(top center, #af1000 50%, #990000 50%);
                color: white;
                text-shadow: 1px 1px 0 #5f0000;
            }
                .critical td {
                    border-right: 1px solid #6f0000;
                    border-bottom: 1px solid #6f0000;
                }
            
            .ok {
                background: -moz-linear-gradient(top center, #00b400 50%, #018f00 50%);
                color: white;
                text-shadow: 1px 1px 0 #015f00;
            }
 
            .warning {
                background: -moz-linear-gradient(top center, yellow 50%, #edef00 50%);
                color: black;
                text-shadow: -1px -1px 0 #feff5f;
            }
                .critical td,
                .ok td,
                .warning td {
                }
            .warning td{
                border-bottom: 1px solid #bdbf00;
                border-right: 1px solid #bdbf00;
            }
            .ok td{
                border-bottom: 1px solid #016f00;
                border-right: 1px solid #016f00;
            }
            
            .date {
                white-space: nowrap;
                
            }
            
            
            
            .statusinfo {
                font-size: 14px !important;
            }
            
            .nagios_statusbar {
                background: -moz-linear-gradient(top center, #6a6a6a, #464646);
                position: fixed;
                bottom: 0;
                width: 100%;
                margin: 0 0 0 -1em;
                height: 40px;
                text-align: right;
                border-top: 1px solid #818181;
                opacity: .9;
            }
            .nagios_statusbar_item {
                border-left: 2px groove #000;
                height: 40px;
                line-height: 40px;
                padding: 0 1em;
                color: white;
                text-shadow: 1px 1px 0 black;
                position: relative;
                float: right;
            }
            
            #nagios_placeholder {
            }
            #loading {
                background: transparent url(throbber.gif) no-repeat center center;
                width: 24px;
                height: 40px;
                position: absolute;
            }
            #refreshing {
                padding-left: 35px;
            }
            #refreshing_countdown {
            }
            #timestamp_wrap {
                cursor: default;
                font-size: 2em;
            }
            .timestamp_stamp {
            }
        </style>
    </head>
    <body>
        <script type="text/javascript">

            var placeHolder,
            refreshValue = <?php print $refreshvalue; ?>;
            
            $().ready(function(){
                placeHolder = $("#nagios_placeholder");
                updateNagiosData(placeHolder);
                window.setInterval(updateCountDown, 1000);
            });
            
            
            
            // timestamp stuff
            
            function createTimeStamp() {
                // create timestamp
                var ts = new Date();
                ts = ts.toTimeString();
                ts = ts.replace(/\s+GMT.+/ig, "");
                ts = ts.replace(/\:\d+(?=$)/ig, "");
                $("#timestamp_wrap").empty().append("<div class=\"timestamp_drop\"></div><div class=\"timestamp_stamp\">" + ts +"</div>");
            }
            
            function updateNagiosData(block){
                $("#loading").fadeIn(200);
    			block.load("./merlin.php", function(response){
                    $(this).html(response);
                    $("#loading").fadeOut(200);
                    createTimeStamp();
                });
            }
            
            function updateCountDown(){
                var countdown = $("#refreshing_countdown"); 
                var remaining = parseInt(countdown.text());
                if(remaining == 1){
                    updateNagiosData(placeHolder);
                    countdown.text(remaining - 1);
                }
                else if(remaining == 0){
                    countdown.text(refreshValue);
                }
                else {
                    countdown.text(remaining - 1);
                }
            }
            
        </script>
	<div id="nagios_placeholder"></div>
    <div class="nagios_statusbar">
        <div class="nagios_statusbar_item">
            <div id="timestamp_wrap"></div>
        </div>
        <div class="nagios_statusbar_item">
            <div id="loading"></div>
            <p id="refreshing">Refresh in <span id="refreshing_countdown"><?php print $refreshvalue; ?></span> seconds</p>
        </div>
    </div>
    </body>
</html>
