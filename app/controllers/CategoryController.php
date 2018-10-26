<?php
/**
* 
*/
class CategoryController extends Controller
{
	
	function __construct($controller, $action)
	{
		# code...
		parent::__construct($controller, $action);
		$this->load_model('Category');
		  
		 if(!Auth::check()): Router::redirect('auth/login'); endif;
		 	$this->org_id = Auth::auth('org_id'); 
	}


public function index()
{
	 $this->view->displayErrors = $this->validate->displayErrors(); 
		$this->view->render('category/index'); 
		$this->view->extra('layouts/beedy_kaydee');  
}



public function list()
{

	 $data  = $this->Category->paginate(PAGE_LIMIT,['conditions'=> 'org_id = ?', 'bind' => [$this->view->Beedy->getCompanyId()] ]);
 	$x = 1;
   foreach ($data as $category)
                   {
                    # code...
                 ?>

<tr> 
<td><?=$x;?></td>
<td><?php echo $category->cat_name; ?> </td> 
<td><?php echo $category->created_at; ?> </td> 
<td><?php echo $category->updated_at; ?> </td> 

<td> 
  <?php 
          if(actionAcl('Category', 'u')):
           
           ?>
<button type="button" name="modCategory" id="<?php echo $category->id; ?>" class="btn btn-primary btn-xs modCategory">
	<i class="fas fa-pencil-alt fa-fw"></i> Edit</button>
	<?php endif; ?>
	</td>
 		 
	<td>
	<?php   if(actionAcl('Category', 'd')):  ?>
	  <button type="button" name="delCategory" id="<?php echo $category->id; ?>" class="btn btn-danger btn-xs delCategory">
	<i class="fas fa-trash fa-fw"></i> Delete</button>
<?php endif; ?>
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
	 $this->view->displayErrors = $this->validate->displayErrors();
		$this->view->extra('category/create');
}

public function store()
{
  
 
		if($_POST)
		{
			$data = array();
 
			$this->validate->check($_POST, [ 
										'cat_name'=> [
													'display'=> 'Category Name',
													'required'=> true,
													'max' => 30,
													'insert_unique' =>['categories',$this->view->Beedy->getCompanyId(), Input::get('cat_name')]
												]
										]);
		  
		   if($this->validate->passed())
				{
					 
						  	$fields = [										 
										'cat_name' => Input::get('cat_name'),									 
										'org_id' => $this->view->Beedy->getCompanyId(),		 
										'created_at' => '',		 
										'updated_at' => ''		 
							];	
 
				  
						$send = $this->Category->insert($fields);
						
						if($send): 
							//Session::flash('success', 'New Category has been added successfully');
						  	$data['status'] = "success";
							$data['msg']  =   'New Category has been added successfully';
   
				  		else:
				  		$data['status'] = "db_error";
						$data['msg'] = "Error: Category was not added. Please try again later";
				 			

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
	$this->view->data = $this->Category->findById($id);
	 $this->view->displayErrors = $this->validate->displayErrors();
		$this->view->extra('category/edit');
}


public function update()
{
  
		if($_POST)
		{
			$data = array(); 
			$this->validate->check($_POST, [  'cat_name'=> [
													'display'=> 'Category Name',
													'required'=> true,
													'max' => 30
												]
										]);
		  
		   if($this->validate->passed())
		   {
		   		$Category = $this->Category->findById(Input::get('id'));
		   		
		   		if($Category->cat_name != Input::get('cat_name'))
		   		{ 
		   			
		   			//compute the fields
		   			$fields = ['cat_name' => Input::get('cat_name'),  'updated_at' => '' ];	
		   			//update the db
		   			$send = $this->Category->update($fields, (int)Input::get('id'));
		   			//check if updated
		   			if($send)
		   			{
							$data['status'] = "success";
							$data['msg']  =   'Category has been updated successfully';
   				  
		   			}
		   			else
		   			{
		   				$data['status'] = "db_error";
		   				$data['msg'] = "Error: Category was not saved. Please try again later";
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
       $del = $this->Category->delete($id); 
      if($del): echo "Event Deleted Successfully"; else: "Error deleting this data... Please try again later"; endif;
	

    }


}