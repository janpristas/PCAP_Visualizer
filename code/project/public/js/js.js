$(document).ready(function() {
	$('#popup_bg').hide();
	$('#edge_info_wrapper').hide();
	resetFields();

	$('#popup_bg').click(function() {
		$(this).hide();
		$('#edge_info_wrapper').hide();
		resetFields();
	});

	$('#X-close').click(function() {
		$('#popup_bg').hide();
		$('#edge_info_wrapper').hide();
		resetFields();
	});
});

/**
  * Reset info table after close
  */
function resetFields() {
	$('#edge_header').html("");
	$('#count').html('Count of messages: ');
	$('#time').html('Time of communication: ');
	$('#bits').html('Bits transfered: ');
	$('#communication_table').html("\
					<tr class=\"first_row\"> \
						<td>Source IP</td> \
						<td>Destination IP</td> \
						<td>Time</td> \
						<td>Transfer ID</td> \
						<td>Unit ID</td> \
						<td>Function Code</td> \
						<td>Bit Count</td> \
						<td>Byte Count</td> \
						<td>IP Length</td> \
					</tr>");
}