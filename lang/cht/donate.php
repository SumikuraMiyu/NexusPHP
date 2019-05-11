<?php
require "include/bittorrent.php";
dbconn();
require_once(get_langfile_path());
if ($enabledonation != 'yes')
	stderr($lang_donate['std_sorry'], $lang_donate['std_do_not_accept_donation']);

$do = $_GET['do'];

if ($do == 'thanks') {
	stderr($lang_donate['std_success'], $lang_donate['std_donation_success_note_one']."<a href=\"sendmessage.php?receiver=".$ACCOUNTANTID."\"><b>".$lang_donate['std_here']."</b></a>".$lang_donate['std_donation_success_note_two'], false);
}
else 
{
	$paypal = safe_email($PAYPALACCOUNT);
	if ($paypal && check_email($paypal))
		$showpaypal = true;
	else
		$showpaypal = false;
	$alipay = safe_email($ALIPAYACCOUNT);
	if ($alipay && check_email($alipay))
		$showalipay = true;
	else
		$showalipay = false;

	if ($showpaypal && $showalipay)
		$tdattr = "width=\"50%\"";
	elseif ($showpaypal || $showalipay)
		$tdattr = "colspan=\"2\" width=\"100%\"";
	else
		stderr($lang_donate['std_error'], $lang_donate['std_no_donation_account_available'], false);

	stdhead($lang_donate['head_donation']);
	begin_main_frame();
	print("<h2>".$lang_donate['text_donate']."</h2>");
	print("<table width=100%><tr>");
	print("<td colspan=2 class=text align=left>".$lang_donate['text_donation_ins']."</td></tr>");
	echo "<td colspan=2 class=text align=left>".sprintf($lang_donate['text_donate_link'], 'forums.php?action=viewtopic&forumid=4&topicid=306')."</td></tr>";
	print("<tr>");
	if ($showpaypal){
?>
<td class=text align=left valign=top <?php echo $tdattr?>>
<b><?php echo $lang_donate['text_donate_with_paypal']?></b><br /><br />
<?php echo $lang_donate['text_donate_paypal_note']?>
 <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
  <input type="hidden" name="cmd" value="_xclick">
  <input type="hidden" name="business" value="<?php echo $paypal;?>">
  <input type="hidden" name="item_name" value="Donation to <?php echo $SITENAME;?>">
  <p align="center">
<br />
  <?php echo $lang_donate['text_select_donation_amount']?>
<br />
   <select name="amount">
<option value="" selected><?php echo $lang_donate['select_choose_donation_amount']?></option>
<?php
$allowedDonationUsdAmounts = array(0, 10, 20, 70, 100, 200, 300, 600, 1000, 1500, 2000, 3000);
//$allowedDonationUsdAmounts = array(32, 64, 320);
foreach ($allowedDonationUsdAmounts as $amount) {
	if ($amount == 0) {
		echo '<option value="">'.$lang_donate['select_other_donation_amount'].'</option>';
	} else {
		$amount = number_format($amount, 2);
		echo '<option value='.$amount.'>'.$lang_donate['text_usd_mark'].$amount.$lang_donate['text_donation'].'</option>';
	}
}
?>
</select>
    <input type="hidden" name="image_url" value="">
    <input type="hidden" name="shipping" value="0">
    <input type="hidden" name="currency_code" value="USD">
    <input type="hidden" name="return" value="<?php echo  get_protocol_prefix() . $BASEURL;?>/donate.php?do=thanks">
<input type="hidden" name="cancel_return" value="<?php echo get_protocol_prefix() . $BASEURL;?>/donate.php">
<br />
</p>
<p align="center">
<input type="image" src="pic/paypalbutton.gif" border="0" name="I1" alt="Make payments with PayPal">
<br /><br /></p>
</form></td>
<?php
}
if ($showalipay){
?>
<td class=text align=left valign=top <?php echo $tdattr?>>
<b><?php echo $lang_donate['text_donate_with_alipay']?></b><br /><br />
<form action="https://www.alipay.com/trade/fast_pay.htm" method="get">
<?php echo $lang_donate['text_donate_alipay_note_one']."<b>".$alipay."</b>".$lang_donate['text_donate_alipay_note_two']?>
<br /><br /><br /><br /><br />
<p align="center">
<input type="image" src="pic/alipaybutton.gif" border="0" name="I2" alt="Make payments with Alipay" />
<br /><br /></p>
</form></td>
<?php
}
print("</tr>");
print("<tr><td class=text colspan=2 align=left>".$lang_donate['text_after_donation_note_one']
."<a href=\"sendmessage.php?receiver=".$ACCOUNTANTID."\"><font class=\"striking\"><b>".$lang_donate['text_send_us']."</b></font></a>".$lang_donate['text_after_donation_note_two']."</td></tr>");
print("</table>");
end_main_frame();
stdfoot();
}
?>
