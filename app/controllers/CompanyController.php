<?php
/**
* 
*/
class CompanyController extends Controller
{
	
	public function __construct($controller, $action)
	{
		# code...
		parent::__construct($controller, $action);
		$this->load_model('Software');
		$this->load_model('Setting');

		$this->view->setLayout('app');

	}

	 
 
public function profile()
{
 
 
 	$params = [	 'conditions'=> ['id = ?'],	 'bind' => [$this->org_id]];

 	$this->view->displayErrors = $this->validate->displayErrors();
 	$this->view->data  = $this->Software->findById($this->org_id);
 
 $this->view->render("company/profile");
}



public function update()
{
  
		if($_POST)
		{
			$data = array(); 
			$this->validate->check($_POST, [  

													'org_name'=> [
													'display'=> 'Organization Name',
													'required'=> true,
													'max' => 50
												],
													'org_num'=> [
													'display'=> 'Phone Number', 
													'max' => 30
												],
													'org_email'=> [
													'display'=> 'Organization Email',
													'required'=> true,
													'valid_email'=> true,
													'max' => 50
												],
													'org_address'=> [
													'display'=> 'Organization Address', 
													'max' => 100
												]
										]);
		  
		   if($this->validate->passed())
		   {
		   		$Software = $this->Software->findById(Input::get('id'));
		   		//search for all emails
		   		$params = [	'conditions'=> ['org_email = ?', 'id <> ?'], 'bind' => [  Input::get('org_email'), (int)Input::get('id')] ];	  

		   		// $check = new Software('softwares');
				$existing = $this->Software->find($params);
				 
				 
		   
		   		if($Software->org_name != Input::get('org_name') || $Software->org_num != Input::get('org_num') || $Software->org_email != Input::get('org_email') || $Software->org_address != Input::get('org_address')  )
		   		{ 
		   			
		   			//compute the fields
		   			$fields = [
		   			'org_name' => Input::get('org_name'),
		   			'org_num' => Input::get('org_num'),
		   			'org_email' => Input::get('org_email'),
		   			'org_address' => Input::get('org_address'),
		   			  'updated_at' => ''
		   			   ];	
		   			//update the db
		   			
		   			if(count($existing) < 1):

				   			$send = $this->Software->update($fields, (int)Input::get('id'));
				   			//check if updated
				   			if($send)
				   			{
									$data['status'] = "success";
									$data['msg']  =   'Profile updated successfully';
		   				  
				   			}
				   			else
				   			{
				   				$data['status'] = "db_error";
				   				$data['msg'] = "Error: Profile was not saved. Please try again later";
				   			}
				   	else:
				  			$data['status'] = "error";
							$data['msg'] = "Error: This Email may already exist. Please try again with a unique email";
				  		endif;
		   		}
			 
					unset($_POST);
				 
		   }
		   else
		   {
					 	$data['status'] = "error";
						$data['msg'] = $this->validate->displayErrors();
		   }
		   echo json_encode($data);  
 					
 		}
	 
 //update ends down here
}

 
public function settings()
{
 
 
 	$params = [	 'conditions'=> ['id = ?'],	 'bind' => [$this->org_id]];

 	$this->view->displayErrors = $this->validate->displayErrors();
 	$this->view->data  = $this->Setting->findById($this->org_id);
 
 	$this->view->render("company/settings");
}


//ends here
}