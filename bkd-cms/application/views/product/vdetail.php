<?php 
//if($EDIT['type_of_business_id']=='1')
$type_interest_rate = $EDIT['type_of_interest_rate'];
switch($type_interest_rate){
    case '1':
    //$('#tipe_loan_term').text('days');  
    $label_tenor = 'days';
    break;
    case '2':
     $label_tenor = 'months';
   // $('#tipe_loan_term').text('months'); 
    break; 
    case '3':
      $label_tenor = 'weeks';
    //$('#tipe_loan_term').text('weeks');
    break; 
}

 //$label_tenor = ($EDIT['type_of_business_id']=='1')? 'Hari' : 'Bulan';
?>
<div class="modal-body">
	<table class="display table table-striped dataTable">
		<tr>
			<td>Title</td><td><?php echo $EDIT['product_title']; ?></td>
		</tr>
		<tr>
			<td>Type of Business</td><td><?php echo $EDIT['Type_of_business']; ?></td>
		</tr>
		<tr>
			<td>Fundraising Period</td><td><?php echo $EDIT['Fundraising_period']; ?></td>
		</tr>
		<tr>
			<td>Product Sector</td><td><?php echo $EDIT['Product_sector']; ?></td>
		</tr>
		<tr>
			<td>Interest Rate</td><td><?php echo $EDIT['Interest_rate']; ?>%</td>
		</tr>
		<tr>
			<td>Loan Term</td><td><?php echo $EDIT['Loan_term'] .' '. $EDIT['type_of_interest_rate_name']; ?></td>
		</tr>
		<tr>
			<td>Max Loan</td><td><?php echo $EDIT['Max_loan']; ?></td>
		</tr>
		<tr>
			<td>Platform Fee</td><td><?php echo $EDIT['Platform_rate']; ?>%</td>
		</tr>
		<tr>
			<td>Loan Organizer</td><td><?php echo $EDIT['Loan_organizer']; ?></td>
		</tr>
		<tr>
			<td>Investor/Lender Return</td><td><?php echo $EDIT['Investor_return']; ?>%</td>
		</tr>
		<tr>
			<td>Fee Revenue Share</td><td><?php echo $EDIT['Fee_revenue_share']; ?>%</td>
		</tr>
		<tr>
			<td>Secured Loan / Administration Fee</td><td><?php echo $EDIT['Secured_loan_fee']; ?>%</td>
		</tr>
		<tr>
			<td>PPH</td><td><?php echo $EDIT['PPH']; ?>%</td>
		</tr>
	</table>
</div>