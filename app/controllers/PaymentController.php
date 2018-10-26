<?php
/**
* 
*/
class PaymentController extends Controller
{
	
	function __construct($controller, $action)
	{
		# code...
		parent::__construct($controller, $action);
		$this->load_model('Payment');
		  
		 if(!Auth::check()): Router::redirect('auth/login'); endif;
		 	$this->org_id = Auth::auth('org_id'); 
	}


public function index()
{

 
 	$params = [	 'conditions'=> ['org_id = ?'],	 'bind' => [$this->org_id] 	 ];	
 		$this->view->displayErrors = $this->validate->displayErrors();
	  	$this->view->data  = $this->Event->find($params);
		$this->view->render('event/index'); 
		$this->view->extra('layouts/beedy_kaydee');  
}

 

public function list($evt_id)
{
	 
	$User = new User('users');
	$Event = new Event('events'); 
	 $data  = $this->Payment->paginate(PAGE_LIMIT,[
	 							'conditions'=> ['org_id = ? AND', 'evt_id = ?'], 
	 							'bind' => [$this->view->Beedy->getCompanyId(), (int)$evt_id] ]);
 	$x = 1;
   foreach ($data as $Payment)
     {
                    # code...
     ?>

<tr> 
<td><input type="checkbox" name="reportCheck[]" value="<?=$Payment->id?>" class="reportCheckCase"> </td>
<td class="beedy-tooltip ">
<?php $cat = $Event->findById($Payment->evt_id)->evt_desc;  ?>
<?=(strlen($cat) < 15)? $cat : substr($cat, 0,15).'...' ?>
 <span class="top" ><?=$cat?></span>
 </td> 
<td><?php echo $Payment->pay_by; ?> </td> 
<td><?php echo $Payment->amount; ?> </td>  
<td><?php echo $Payment->phone; ?> </td>  
<td>
<?php 
$created =  $User->findById($Payment->received_by); 
		echo $created->acc_first_name. " ".$created->acc_last_name;?> 
</td> 
<td><?php echo $Payment->created_at; ?> </td> 
<td><?php echo $Payment->updated_at; ?> </td> 
<td>
<?php 
$updated =  $User->findById($Payment->updated_by); 
		echo $updated->acc_first_name. " ".$updated->acc_last_name;
  ?> 
  </td> 

<td> 
<?php   if(actionAcl('Payment', 'u')):  ?>
<button type="button" name="modPayment" id="<?php echo $Payment->id; ?>" class="btn btn-primary btn-xs modPayment">
	<i class="fas fa-pencil-alt fa-fw"></i> Edit</button>
<?php endif; ?>

	</td>


</tr>
 
<?php 
$x++; 
 } 
  ?> 
  <tr><td colspan="11"><?=pageLinks();?></td></tr>
  <?php
}




public function summary($evt_id)
{ 	
	$field = [ 	'conditions'=> ['org_id = ?', 'evt_id = ?'],  'bind' => [$this->view->Beedy->getCompanyId(), (int)$evt_id] ];
	$amount = 0;
	 $data  = $this->Payment->find($field);

	 if(count($data) < 1):
echo "No payment has been made so far";

	 else:
	foreach ($data as $value)
	 {
		$amount += removeComma($value->amount);
	 }

	$User = new User('users');
$last = array_values(array_slice($data, -1))[0];
  // $last->amount;
	echo '<div class="card">
<div class="card-header"> 
Payment Summary:
</div>
<div class="card-body"> 

<p class="card-title"><strong>Total Amount:</strong> Gh&cent '.formatMoney($amount, true).'</p>
<div class="subtitle"><strong>Last Transaction Amount:</strong>  Gh&cent '.$last->amount.'</div>
<div class="subtitle"><strong>Paid in By:</strong>  '.$last->pay_by.'</div>
<div class="subtitle"><strong>Received By:</strong>  '.$User->findById($last->received_by)->acc_first_name.' '.$User->findById($last->received_by)->acc_last_name.'</div>
</div> 
  
</div>';

endif;
 
 }

public function show($id)
{ 

	  	$this->view->data  = $this->Event->findById($id);
		$this->view->render('event/show'); 
		$this->view->extra('layouts/beedy_kaydee');  
}
 
 

public function createFund($id)
{
	 

 $this->view->id = $id; 
 
	 $this->view->displayErrors = $this->validate->displayErrors();
		$this->view->extra('payment/createFund');
}


public function payFund()
{
   	if($_POST)
		{
			
			$data = array();
 
			$this->validate->check($_POST, [ 
										'amount'=> [
													'display'=> 'Amount',
													'required'=> true,
													'max' => 100,
													'is_numeric' => true
													],

										'pay_by'=> [
													'display'=> 'Payer\'s Name', 
													'max' => 30
													],

										'phone'=> [
													'display'=> 'Phone Number', 
													'max' => 30
													]
												 
										]);
		  
		   if($this->validate->passed())
				{
					 

						  	$fields = [										 
										'evt_id' => Input::get('evt_id'),									 
										'pay_by' => ($_POST['pay_by'] !='')?  Input::get('pay_by') : 'Undisclosed',									 
										'amount' => formatMoney(Input::get('amount'),true),									 
										'phone' => Input::get('phone'),									 
										'org_id' => $this->view->Beedy->getCompanyId(),		 
										'received_by' => getUserId(),		 
										'created_at' => '',		 
										'updated_by' => getUserId(),		 
										'updated_at' => ''		 
							];	
 
				  
 	 
						$send = $this->Payment->insert($fields);
						
						if($send): 
							//Session::flash('success', 'New Category has been added successfully');
						  	$data['status'] = "success";
							$data['msg']  =   'Payment was successful';

							sendSMS2($_POST);
   
				  		else:
				  		$data['status'] = "db_error";
						$data['msg'] = "Error: Transaction error. Please try again later";
				 			

				  		endif;
				  		 
				}
				else{
					$data['status'] = "error";
						$data['msg'] = $this->validate->displayErrors();
				}
					 

				unset($_POST);
				echo json_encode($data);  		
 
		}	
 //store ends down here
}

/**
 * [edit function]
 * @param  [type] $id [primary key to be edited]
 * @return [type]     [view]
 */
public function edit($id)
{		
	 
 	$this->view->data = $this->Payment->findById($id);
	 $this->view->displayErrors = $this->validate->displayErrors(); 
		$this->view->extra('payment/edit');
}  

public function update()
{
  
			if($_POST)
		{
			
			$data = array();
 
			$this->validate->check($_POST, [ 
										'amount'=> [
													'display'=> 'Amount',
													'required'=> true,
													'max' => 100
													],

										'pay_by'=> [
													'display'=> 'Payer\'s Name', 
													'max' => 30
													],

										'phone'=> [
													'display'=> 'Phone Number', 
													'max' => 30
													]
												 
										]);
		  
		   if($this->validate->passed())
				{
					    	$fields = [										 
										'pay_by' => ($_POST['pay_by'] !='')?  Input::get('pay_by') : 'Undisclosed',			 
										'amount' => formatMoney(Input::get('amount'),true),									 
										'phone' => Input::get('phone'),					  
										'updated_by' => getUserId(),		 
										'updated_at' => ''		 
							];	
 
				  
 	 $Payment = $this->Payment->findById(Input::get('id'));
		   		 
		   	 
			if($Payment->amount != Input::get('amount') || $Payment->pay_by != Input::get('pay_by') || $Payment->phone != Input::get('phone')):


						$send = $this->Payment->update($fields, (int)Input::get('id'));
						
						if($send): 
							//Session::flash('success', 'New Category has been added successfully');
						  	$data['status'] = "success";
							$data['msg']  =   'Payment details updated successfully';
   
				  		else:
				  		$data['status'] = "db_error";
						$data['msg'] = "Error: Transaction error. Please try again later";
				 			

				  		endif;

				  		endif;
				  		 
				}
				else{
					$data['status'] = "error";
						$data['msg'] = $this->validate->displayErrors();
				}
					 

				unset($_POST);
				echo json_encode($data);  		
 
		}	
	 
 //update ends down here
}
 /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Category
     * 
     */
    public function destroy($id)
    {
       $del = $this->Delete->delete($id); 
      if($del): echo "Event Deleted Successfully"; else: "Error deleting this data... Please try again later"; endif;
	

    }


}