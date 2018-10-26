<?php
/**
* 
*/
class FoodController extends Controller
{
	
	function __construct($controller, $action)
	{
		# code...
		parent::__construct($controller, $action);
		$this->load_model('Food');
		 
	}


public function index()
{
	
		$user = $this->Food->find();
		 $this->view->data = $user; 
		$this->view->render('food/index',$user); 
}


public function create()
{
		$user = $this->Food->findFirst();
		$this->view->with($user);
		$this->view->render('food/create');
}

public function store()
{
$db = DB::getInstance();
if($_POST)
{
  
  	$fields = [										 
'name' => $_POST['name'],									 
'description' => $_POST['description']		 
	];	

$send = $this->Food->insert($fields);
if($send)
{
	unset($_POST);

		$this->view->render('food/create');
}
else{
	echo "Insert Error";
}

}



}



}