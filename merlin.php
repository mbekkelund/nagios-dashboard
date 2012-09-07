<? 

# 	 The nagios-dashboard was written by Morten Bekkelund & Jonas G. Drange in 2010 
#
#    Copyright (C) 2010 Morten Bekkelund & Jonas G. Drange
#
#    This program is free software: you can redistribute it and/or modify
#    it under the terms of the GNU General Public License as published by
#    the Free Software Foundation, either version 3 of the License, or
#    (at your option) any later version.
#
#    This program is distributed in the hope that it will be useful,
#    but WITHOUT ANY WARRANTY; without even the implied warranty of
#    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#    GNU General Public License for more details.
#
#    See: http://www.gnu.org/copyleft/gpl.html


$con = mysql_connect("localhost", "user", "password") or die("<h3><font color=red>Could not connect to the database!</font></h3>");
$db = mysql_select_db("merlin", $con);

?>
<div class="dash_unhandled hosts dash">
    <h2>Unhandled Hosts down</h2>
    <div class="dash_wrapper">
        <table class="dash_table">
            <? 
            #ALL down-hosts
            $query = "select host_name, alias, count(host_name) from host where last_hard_state = 1 and problem_has_been_acknowledged = 0 group by host_name";
            $result = mysql_query($query);
            $save = "";
            $output = "";
            while ($row = mysql_fetch_array($result)) {
                $output .=  "<tr class=\"critical\"><td>".$row[0]."</td><td>".$row[1]."</td></tr>";
                $save .= $row[0];
            }
            if($save):
            ?>
            <tr class="dash_table_head">
                <th>Hostname</th>
                <th>Alias</th>
            </tr>
            <?php print $output; ?>
            <?php
            else: 
                print "<tr class=\"ok\"><td>All problem hosts has been acknowledged.</td></tr>";
            endif;
            ?>
        </table>
    </div>
</div>
<div class="dash_tactical_overview tactical_overview hosts dash">
    <h2>Tactical overview</h2>
    <div class="dash_wrapper">
        <table class="dash_table">
            <tr class="dash_table_head">
                <th>Type</th>
                <th>Totals</th>
                <th>Percentage %</th>
            </tr>
            <? 
            # number of hosts down
            $query = "select count(1) as count from host where last_hard_state = 1";
            $result = mysql_query($query);
            $row = mysql_fetch_array($result);
            $hosts_down = $row[0];
            
            # total number of hosts
            $query = "select count(1) as count from host";
            $result = mysql_query($query);
            $row = mysql_fetch_array($result);
            $total_hosts = $row[0];
            
            $hosts_down_pct = round($hosts_down / $total_hosts * 100, 2);
            $hosts_up = $total_hosts - $hosts_down;
            $hosts_up_pct = round($hosts_up / $total_hosts * 100, 2);
            
            #### SERVICES
            #
            $query = "select count(1) as count from service where last_hard_state = 1";
            $result = mysql_query($query);
            $row = mysql_fetch_array($result);
            $services_down = $row[0];
            
            # total number of hosts
            $query = "select count(1) as count from service";
            $result = mysql_query($query);
            $row = mysql_fetch_array($result);
            $total_services = $row[0];
            
            $services_down_pct = round($services_down / $total_services * 100, 2);
            $services_up = $total_services - $services_down;
            $services_up_pct = round($services_up / $total_services * 100, 2);
            
            ?>
            <tr class="ok total_hosts_up">
                <td>Hosts up</td>
                <td><?php print $hosts_up ?>/<?php print $total_hosts ?></td>
                <td><?php print $hosts_up_pct ?></td>
            </tr>
            <tr class="critical total_hosts_down">
                <td>Hosts down</td>
                <td><?php print $hosts_down ?>/<?php print $total_hosts ?></td>
                <td><?php print $hosts_down_pct ?></td>
            </tr>
            <tr class="ok total_services_up">
                <td>Services up</td>
                <td><?php print $services_up ?>/<?php print $total_services ?></td>
                <td><?php print $services_up_pct ?></td>
            </tr>
            <tr class="critical total_services_down">
                <td>Services down</td>
                <td><?php print $services_down ?>/<?php print $total_services ?></td>
                <td><?php print $services_down_pct ?></td>
            </tr>
        </table>
    </div>
</div>
<div class="clear"></div>
<div class="dash_unhandled_service_problems hosts dash">
    <h2>Unhandled service problems</h2>
    <div class="dash_wrapper">
        <table class="dash_table">
            <tr class="dash_table_head">
                <th>
                    Host
                </th>
                <th>
                    Service
                </th>
                <th>
                    Output
                </th>
                <th>
                    Last statechange
                </th>
                <th>
                    Last check
                </th>
            </tr>
            <? 
            #ALL critical/warning services on hosts not being down
            $query = "select service.host_name,service.service_description,service.last_hard_state,service.output, service.last_hard_state_change,service.last_check ";
            $query = $query." from service,host where ";
            $query = $query." host.host_name = service.host_name and ";
            $query = $query." service.last_hard_state in (1,2) and ";
            $query = $query." service.problem_has_been_acknowledged = 0 and host.problem_has_been_acknowledged = 0 and ";
            $query = $query." host.last_hard_state not like 1 group by service.service_description order by service.last_hard_state";
            $result = mysql_query($query);
            ?>
            <? 
            while ($row = mysql_fetch_array($result)) {
                if ($row[2] == 2) {
                    $class = "critical";
                } elseif ($row[2] == 1) {
                    $class = "warning";
                }
                ?>
                <tr class="<?php print $class ?>">
                    <td><?php print $row[0] ?></td>
                    <td><?php print $row[1] ?></td>
                    <td><?php print $row[3] ?></td>
                    <td class="date date_statechange"><?php print date("d-m-Y H:i:s", $row[4]) ?></td>
                    <td class="date date_lastcheck"><?php print date("d-m-Y H:i:s", $row[5]) ?></td>
                </tr>
                <?php 
            }
            ?>
        </table>
    </div>
</div>
</body>
</html>
