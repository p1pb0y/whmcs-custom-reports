<?php

/** 
 * WHMCS Report for Revenue Breakdown by State/Region
 * 
 * Note: Intended for U.S. states, does not account for currency conversions
 */

if (!defined("WHMCS"))
	die("This file cannot be accessed directly");

$reportdata['title'] = 'Revenue by State';
$reportdata['description'] = 'This report shows the breakdown of revenue by State/Region in a given year.';
$reportdata['yearspagination'] = true;

$reportdata['tableheadings'] = array('State/Region', 'Revenue');

$query = " SELECT Clients.state, SUM(Invoices.total) as revenue FROM tblinvoices Invoices "
       . " LEFT JOIN tblclients Clients ON Invoices.userid = Clients.id "
       . " WHERE Invoices.status = 'Paid' "
       . " AND Invoices.datepaid >= '$currentyear-01-01 00:00:00' "
       . " AND Invoices.datepaid <= '$currentyear-12-31 23:59:59' "
       . " GROUP BY Clients.state ";

$result = full_query($query);
$total_rev = 0.00;

while($data = mysql_fetch_row($result)) {
	$reportdata['tablevalues'][] = $data;
	$total_rev = bcadd($total_rev, $data[1], 2);
}

$reportdata['footertext'] = '<p align="center"><b>Total Revenue: ' . $total_rev . '</b></p>';

?>
