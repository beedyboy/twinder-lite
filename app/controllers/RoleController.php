<?php
/**
* 
*/
class RoleController extends Controller
{
	
	function __construct($controller, $action)
	{
		# code...
		parent::__construct($controller, $action);
		$this->load_model('Role');
		  
		 if(!Auth::check()): Router::redirect('auth/login'); endif;
		 	$this->org_id = Auth::auth('org_id'); 
	}


public function index()
{

 
 	$params = [	 'conditions'=> ['org_id = ?'],	 'bind' => [$this->org_id] 	 ];	
 		$this->view->displayErrors = $this->validate->displayErrors();
	  	$this->view->data  = $this->Role->find($params);
		$this->view->render('roles/index'); 
		$this->view->extra('layouts/beedy_kaydee');  
}
 


public function show($id)
{ 

	  	$this->view->data  = $this->Role->findById($id);
		$this->view->render('roles/show'); 
		$this->view->extra('layouts/beedy_kaydee');  
}

public function create()
{

$posted_values = [ 'name'=> '','description'=> '' ];


	// $permissions = array();
	$catSettings=[];
	$evtSettings=[];
	$paySettings=[];
	$userSettings=[];
	$roleSettings=[];
	$othSettings=NULL; 

		if($_POST)
		{

			//check for category
				if(isset($_POST['categoryAll'])):
					$catSettings[] = "*";
				elseif(isset($_POST['category'])):
				$catSettings =  $_POST['category'];
				// $catSettings =  implode(",", $catSettings);
			endif;
		//check for event
				if(isset($_POST['eventAll'])):
					$evtSettings[] = "*";
				elseif(isset($_POST['event'])):
				$evtSettings =  $_POST['event'];
				// $evtSettings =  implode(",", $evtSettings);
			endif;
		//check for payment
				if(isset($_POST['paymentAll'])):
					$paySettings[] = "*";
				elseif(isset($_POST['payment'])):
				$paySettings =  $_POST['payment'];
				// $paySettings =  implode(",", $paySettings);
			endif;
		//check for user
				if(isset($_POST['userAll'])):
					$userSettings[] = "*";
				elseif(isset($_POST['user'])):
				$userSettings =  $_POST['user'];
				// $userSettings =  implode(",", $userSettings);
			endif;
		//check for role
				if(isset($_POST['roleAll'])):
					$roleSettings[] = "*";
				elseif(isset($_POST['role'])):
				$roleSettings =  $_POST['role'];
				// $roleSettings =  implode(",", $roleSettings);
			endif;
		//check for other
				if(isset($_POST['otherAll'])):
					$othSettings[] = "*";
				elseif(isset($_POST['other'])):
				$othSettings =  $_POST['other'];
				// $othSettings =  implode(",", $othSettings);
			endif;

//compile permissions
$permissions = [
'Category'=>$catSettings,
'Event'=>$evtSettings,
'Payment'=>$paySettings,
'User'=>$userSettings,
'Role'=>$roleSettings,
'Other'=>$othSettings

];
// dnd($permissions);
	//validate other required fields
	$this->validate->check($_POST,[

								'name'=> [
										'display'=> 'Role\'s Name',
										'required'=> true,
										'max' => 20,
										'insert_unique' =>['roles',$this->view->Beedy->getCompanyId(), Input::get('name')]
												],

								'description'=> [
										'display'=> 'Description Name', 
										'max' => 100 
												]

								]);
	//check if validation was passed

if($this->validate->passed())
				{
					 
						  	$fields = [										 
										'name' => Input::get('name'),									 
										'description' => Input::get('description'),									 
										'org_id' => $this->view->Beedy->getCompanyId(),		 
										'permissions' => json_encode($permissions),		 
										'created_at' => '',		 
										'updated_at' => ''		 
							];	
 
			 
						$send = $this->Role->insert($fields);
						
						if($send): 
							unset($_POST);
						   Session::flash('success','New Role has been added successfully');
						   Router::redirect('role/index');
   
				  		else:
				  		 Session::flash('Error', 'Role was not added. Please try again later');
				 			

				  		endif;
				}
				 

		}
 
		$this->view->post = $posted_values;

		$this->view->displayErrors = $this->validate->displayErrors();
		$this->view->render('roles/create');

}


 

/**
 * [edit function]
 * @param  [type] $id [primary key to be edited]
 * @return [type]     [view]
 */
public function edit($id)
{		
	$posted_values = [ 'name'=> '','description'=> '' ];


	// $permissions = array();
	$catSettings=[];
	$evtSettings=[];
	$paySettings=[];
	$userSettings=[];
	$roleSettings=[];
	$othSettings=NULL; 

		if($_POST)
		{

			//check for category
				if(isset($_POST['categoryAll'])):
					$catSettings[] = "*";
				elseif(isset($_POST['category'])):
				$catSettings =  $_POST['category'];
				// $catSettings =  implode(",", $catSettings);
			endif;
		//check for event
				if(isset($_POST['eventAll'])):
					$evtSettings[] = "*";
				elseif(isset($_POST['event'])):
				$evtSettings =  $_POST['event'];
				// $evtSettings =  implode(",", $evtSettings);
			endif;
		//check for payment
				if(isset($_POST['paymentAll'])):
					$paySettings[] = "*";
				elseif(isset($_POST['payment'])):
				$paySettings =  $_POST['payment'];
				// $paySettings =  implode(",", $paySettings);
			endif;
		//check for user
				if(isset($_POST['userAll'])):
					$userSettings[] = "*";
				elseif(isset($_POST['user'])):
				$userSettings =  $_POST['user'];
				// $userSettings =  implode(",", $userSettings);
			endif;
		//check for role
				if(isset($_POST['roleAll'])):
					$roleSettings[] = "*";
				elseif(isset($_POST['role'])):
				$roleSettings =  $_POST['role'];
				// $roleSettings =  implode(",", $roleSettings);
			endif;
		//check for other
				if(isset($_POST['otherAll'])):
					$othSettings[] = "*";
				elseif(isset($_POST['other'])):
				$othSettings =  $_POST['other'];
				// $othSettings =  implode(",", $othSettings);
			endif;

//compile permissions
$permissions = [
'Category'=>$catSettings,
'Event'=>$evtSettings,
'Payment'=>$paySettings,
'User'=>$userSettings,
'Role'=>$roleSettings,
'Other'=>$othSettings

];
// dnd($permissions);
	//validate other required fields
	$this->validate->check($_POST,[

								'name'=> [
										'display'=> 'Role\'s Name',
										'required'=> true,
										'max' => 20 ],

								'description'=> [
										'display'=> 'Description Name', 
										'max' => 100 
												]

								]);
	//check if validation was passed

if($this->validate->passed())
				{
					 
						  	$fields = [										 
										'name' => Input::get('name'),									 
										'description' => Input::get('description'),									 
										'org_id' => $this->view->Beedy->getCompanyId(),		 
										'permissions' => json_encode($permissions),	 
										'updated_at' => ''		 
							];	
 
			 
						$send = $this->Role->update($fields, (int)Input::get('id'));
						
						if($send): 
							unset($_POST);
						   Session::flash('success','Role data has been updated successfully');
						   Router::redirect('role/index');
   
				  		else:
				  		 Session::flash('error', 'Role was not updated. Please try again later');
				 			

				  		endif;
				}
				/*else{
						$this->validate->displayErrors();
						$this->view->render('roles/create');
				}*/

		}
	$this->view->data = $this->Role->findById($id);
	 $this->view->displayErrors = $this->validate->displayErrors();
		$this->view->render('roles/edit');
}


public function update()
{
  
		if($_POST)
		{
			$data = array(); 
			$this->validate->check($_POST, [  'name'=> [
													'display'=> 'Role\'s Name',
													'required'=> true,
													'max' => 20
												],

			 								   'description'=> [
													'display'=> 'Description Name', 
													'max' => 100
												]
										]);
		  
		   if($this->validate->passed())
		   {
		   		$Role = $this->Role->findById(Input::get('id'));
		   		
		   		if($Role->name = Input::get('name'))
		   		{ 
		   			
		   			//compute the fields
		   			$fields = ['name' => Input::get('name'),  'description' => Input::get('description'),  'updated_at' => '' ];	
		   			//update the db
		   			$send = $this->Role->update($fields, (int)Input::get('id'));
		   			//check if updated
		   			if($send)
		   			{
							$data['status'] = "success";
							$data['msg']  =   'Role has been updated successfully';
   				  
		   			}
		   			else
		   			{
		   				$data['status'] = "db_error";
		   				$data['msg'] = "Error: Role was not saved. Please try again later";
		   			}
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
 /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Category
     * 
     */
    public function destroy($id)
    {
       $del = $this->Role->delete($id); 
      if($del): echo "Role Deleted Successfully"; else: "Error deleting this data... Please try again later"; endif;
	

    }


}