<?php
/**
* 
*/
class FeatureController extends Controller
{
	
	public function __construct($controller, $action)
	{
		# code...
		parent::__construct($controller, $action);
		$this->load_model('Feature');

		$this->view->setLayout('app');

	}

	 
 
public function index()
{
 
 
 $this->view->render("feature/index");
}


public function list2()
{
$db= DB::getInstance();
	$data = [];
	$out = array('error' => false);
	 $Feature = $db->find('features');

  	$out['Feature'] = $Feature;

  	
 	   header("Content-type: application/json");
echo json_encode($out);

  	die();
}

 
public function list()
{

	 $data  = $this->Feature->paginate(10);
 	$x = 1;
   foreach ($data as $Feature)
                   {
                    # code...
                 ?>

<tr> 
<td><?=$x;?></td>
<td><?php echo $Feature->name; ?> </td> 
<td><?php echo $Feature->description; ?> </td>  
<td> 
  
<a href="<?=base_url.'feature/edit/'.$Feature->id?>"  class="btn btn-primary btn-xs modFeature">
	<i class="fas fa-pencil-alt fa-fw"></i> Edit</a>
	 
	</td>
 		  
</tr>
 
<?php 
$x++; 
 } 
  ?> 
  <tr><td colspan="4"><?=pageLinks();?></td></tr>
  <?php
}

public function create()
{

$posted_values = [ 'name'=> '','description'=> '' ];


	// $permissions = array();
	$catSettings=[];
	$evtSettings=[];
	$paySettings=[];
	$userSettings=[];
	$FeatureSettings=[];
	$othSettings=NULL; 

		if($_POST)
		{

			//check for Feature
				if(isset($_POST['FeatureAll'])):
					$catSettings[] = "*";
				elseif(isset($_POST['Feature'])):
				$catSettings =  $_POST['Feature'];
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
		//check for Feature
				if(isset($_POST['FeatureAll'])):
					$FeatureSettings[] = "*";
				elseif(isset($_POST['Feature'])):
				$FeatureSettings =  $_POST['Feature'];
				// $FeatureSettings =  implode(",", $FeatureSettings);
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
'Feature'=>$catSettings,
'Event'=>$evtSettings,
'Payment'=>$paySettings,
'User'=>$userSettings,
'Feature'=>$FeatureSettings,
'Other'=>$othSettings

];
// dnd($permissions);
	//validate other required fields
	$this->validate->check($_POST,[

								'name'=> [
										'display'=> 'Feature Name',
										'required'=> true,
										'max' => 20,
										'unique' =>'features' 
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
										'permissions' => json_encode($permissions),		 
										'created_at' => '',		 
										'updated_at' => ''		 
							];	
 
			 
						$send = $this->Feature->insert($fields);
						
						if($send): 
							unset($_POST);
						   Session::flash('success','New Feature has been added successfully');
						   Router::redirect('feature/index');
   
				  		else:
				  		 Session::flash('Error', 'Feature was not added. Please try again later');
				 			

				  		endif;
				}
				 

		}
 
		$this->view->post = $posted_values;

		$this->view->displayErrors = $this->validate->displayErrors();
		$this->view->render('feature/create');

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
	$FeatureSettings=[];
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
		//check for Feature
				if(isset($_POST['FeatureAll'])):
					$FeatureSettings[] = "*";
				elseif(isset($_POST['Feature'])):
				$FeatureSettings =  $_POST['Feature'];
				// $FeatureSettings =  implode(",", $FeatureSettings);
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
'Feature'=>$FeatureSettings,
'Other'=>$othSettings

];
// dnd($permissions);
	//validate other required fields
	$this->validate->check($_POST,[

								'name'=> [
										'display'=> 'Feature\'s Name',
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
										'permissions' => json_encode($permissions) 
							];	
 
			 
						$send = $this->Feature->update($fields, (int)Input::get('id'));
						
						if($send): 
							unset($_POST);
						   Session::flash('success','Feature data has been updated successfully');
						   Router::redirect('feature/index');
   
				  		else:
				  		 Session::flash('error', 'Feature was not updated. Please try again later');
				 			

				  		endif;
				}
				/*else{
						$this->validate->displayErrors();
						$this->view->render('Features/create');
				}*/

		}
	$this->view->data = $this->Feature->findById($id);
	 $this->view->displayErrors = $this->validate->displayErrors();
		$this->view->render('feature/edit');
}


public function update()
{
  
		if($_POST)
		{
			$data = array(); 
			$this->validate->check($_POST, [  'name'=> [
													'display'=> 'Feature\'s Name',
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
		   		$Feature = $this->Feature->findById(Input::get('id'));
		   		
		   		if($Feature->name = Input::get('name'))
		   		{ 
		   			
		   			//compute the fields
		   			$fields = ['name' => Input::get('name'),  'description' => Input::get('description'),  'updated_at' => '' ];	
		   			//update the db
		   			$send = $this->Feature->update($fields, (int)Input::get('id'));
		   			//check if updated
		   			if($send)
		   			{
							$data['status'] = "success";
							$data['msg']  =   'Feature has been updated successfully';
   				  
		   			}
		   			else
		   			{
		   				$data['status'] = "db_error";
		   				$data['msg'] = "Error: Feature was not saved. Please try again later";
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
 
public function store()
{
$validation = new validate();
$db = DB::getInstance();
	$out = array('error' => false);
if($_POST)
{
  
  	$fields = [										 
'name' => $_POST['name'],									 
'description' => $_POST['description']		 
	];	
	$validation->check($_POST, [

											'name'=> [
											'display'=> 'Feature Name',
											'required'=> true
												],

											'description'=> [
											'display'=> 'Description',
											'required'=> true
											]
								]);
	if($validation->passed())
				{
				$send = $this->Feature->insert($fields);


					 
					if($send)
					{

						$out['message'] = "Member Added Successfully";

					}
					else
					{
							$out['error'] = true;
							$out['message'] = "Could not add Member";
						}
			
			} else{
						$out['error'] = true;	
						$out['message']  = $validation->displayErrors();
			}

	


 	   header("Content-type: application/json");
echo json_encode($out);

  
	die();
}



}


}