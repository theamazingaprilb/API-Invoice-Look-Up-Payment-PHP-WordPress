<?php // Add Shortcode For Paying Invoices
function invoice_payment() {
	if (isset($_POST['dn']))
	{
		$dn = $_POST['dn'];
		$showResults = 'style="display:block;"';
	}
	else {
		$showResults = 'style="display:none;"';
	}
	if (isset($_POST['en']))
	{
		$en = $_POST['en'];
	}
	
	
	$url = 'http://www.apisite.com/api.php';
	$args = array(
	'body' => array(
	'action' => 'get',
	'apikey' => '',
	'dn' => $dn,
	'en' => $en,
	)
	);
	$request = wp_remote_post( $url, $args );
	if( is_wp_error( $request ) ) {
		return false; // Bail early
	}
	$body = wp_remote_retrieve_body( $request );
	// $data = json_encode($body, true);
	if( ! empty( $body ) ) {
		
		echo '<div id="results" style="display:none;">';
			echo $body;
			echo '</div>';
		}
		?>
		<p>Enter your dealer name and estimate number to get your total payment due.</p>
		<form id="payment" class="invoice-form" method="POST" action="#">
			<fieldset class="one_half">
				<label>Dealer Name: </label><br />
				<input id="dn" type="text" required name="dn" placeholder="Dealer Name">
			</fieldset>
			<fieldset class="one_half">
				<label>Estimate Number: </label><br />
				<input id="en" type="text" required name="en" placeholder="Estimate Number">
			</fieldset>
			<fieldset class="fullwidth">
				<button id="search" class="invoice-search">Search</button>
			</fieldset>
		</form>
		<div id="payment-info" <?php echo $showResults; ?>>
			<div id="showResults"></div>
			<div id="resultsbox" style="display: none"></div>
			<script src="https://checkout.stripe.com/checkout.js"></script>
			<div class="payment-container">
				<p><b>Please enter your payment amount below and click "pay".</b></p>
				<form name="paymentform" id="myForm" action="#" method="POST">
					<input type="text" id="amountInDollars" />
					<input type="hidden" id="stripeToken" name="stripeToken" />
					<input type="hidden" id="stripeEmail" name="stripeEmail" />
					<input type="hidden" id="amountInCents" name="amountInCents" />
					<input type="hidden" id="dn" name="dn" value="<?php echo $dn; ?>" />
					<input type="hidden" id="en" name="en" value="<?php echo $en; ?>" />
					<input type="hidden" id="totalDue" name="totalDue" value=""/>
					<input type="hidden" id="amountRemaining" name="amountRemaining" value=""/>
					<input type="hidden" id="amountCharged" name="amountCharged" value=""/>
					
				</form>
				
				<input type="button" id="customButton" class="invoice-search" value="Pay">
			</div>
		</div>
		<script src="https://js.stripe.com/v3/"></script>
		<script>
		window.onload = function getResults(){
			var invoiceResults = <?php echo $body; ?>;
			var getSubTotal = invoiceResults.total;
			var getTax1 = invoiceResults.tax1;
			var getTax2 = invoiceResults.tax2;
			var SubTotal = parseFloat(Math.round(getSubTotal * 100) / 100).toFixed(2);
			var totalTax1 = parseFloat(Math.round(getTax1 * 100) / 100).toFixed(2);
			var totalTax2 = parseFloat(Math.round(getTax2 * 100) / 100).toFixed(2);
			var totalCharge = parseFloat(getSubTotal)+parseFloat(totalTax1)+parseFloat(totalTax2);
			document.getElementById("showResults").innerHTML = "<p class='subtotal'>SubTotal: $<span id='subtotal'>" + SubTotal + "</span><br />" + "Tax 1: $<span id='tax1'>" + totalTax1 + "</span><br />" + "Tax 2: $<span id='tax2'>" + totalTax2 + "</span></p><p class='total'>Total: $<span id='total'>"+parseFloat(Math.round(totalCharge * 100) / 100).toFixed(2)+"</span></p>";
			var handler = StripeCheckout.configure({
				key: 'pk_test_key',
				image: 'https://stripe.com/img/documentation/checkout/marketplace.png',
				locale: 'auto',
				token: function(token) {
					$("#stripeToken").val(token.id);
					$("#stripeEmail").val(token.email);
					$("#amountInCents").val(Math.floor($("#amountInDollars").val() * 100));
					$("#myForm").submit();
				}
			});
			
			$('#customButton').on('click', function(e) {
				var amountInCents = Math.floor($("#amountInDollars").val() * 100);
				var displayAmount = parseFloat(Math.floor($("#amountInDollars").val() * 100) / 100).toFixed(2);
				var amountRemaining = parseFloat(totalCharge)-parseFloat(displayAmount);
				document.paymentform.amountCharged.value = parseFloat(Math.floor($("#amountInDollars").val() * 100) / 100).toFixed(2);
				document.paymentform.totalDue.value = parseFloat(Math.round(totalCharge * 100) / 100).toFixed(2);
				document.paymentform.amountRemaining.value = parseFloat(Math.round(amountRemaining * 100) / 100).toFixed(2);
				
				// Open Checkout with further options
				handler.open({
					name: 'Business Name',
					description: 'Custom amount ($' + displayAmount + ')',
					amount: amountInCents,
				});
				e.preventDefault();
			});
			
			// Close Checkout on page navigation
			$(window).on('popstate', function() {
				handler.close();
			});
		}
		</script>
		<?php
	}
  add_shortcode( 'invoice-payment', 'invoice_payment' );

// Add Shortcode For Paying Invoices
function invoice_payment_confirmation() {
	if (isset($_POST['dn']))
	{
	   $dn = $_POST['dn'];
	}
	if (isset($_POST['en']))
	{
		 $en = $_POST['en'];
	}
	if (isset($_POST['amountCharged']))
	{
		 $amountCharged = $_POST['amountCharged'];
	}
	if (isset($_POST['totalDue']))
	{
		 $totalDue = $_POST['totalDue'];
	}
	if (isset($_POST['amountRemaining']))
	{
		 $amountRemaining = $_POST['amountRemaining'];
	}
	$url = 'http://coronet-debug.opticut.net/api.php';
	$args = array(
		'body' => array(
		'action' => 'set',
		'apikey' => '5d445rlklkjrnc545v431',
		'dn' => $dn,
		'en' => $en,
		'pt' => $amountCharged,
		'float' => $amountRemaining,
	)
	);
	$request = wp_remote_post( $url, $args );
	if( is_wp_error( $request ) ) {
		return false; // Bail early
	}
	$body = wp_remote_retrieve_body( $request );
	// $data = json_encode($body, true);
	if( ! empty( $body ) ) {

		echo '<div id="results" style="display:none;">';
			echo $body;
		echo '</div>';
		echo '<div id="confirmation">';
		echo '<h5><b>Payment Successful</b></h5>';
		echo '<p>Your payment of <b>$';
		echo $amountCharged;
		echo '</b> was completed successfully.</p>';
		echo '<p>Your remaining balance is: <b>$';
		echo $amountRemaining;
		echo '</b>.</p>';
		echo '<p>Thank you for your business!</p>';
	}
	?>
	<?php
}
	add_shortcode( 'invoice-payment-confirmation', 'invoice_payment_confirmation' );
