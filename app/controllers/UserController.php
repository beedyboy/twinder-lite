<?php
/**
* 
*/
class UserController extends Controller 
{
	
	public function __construct($controller, $action)
	{
		# code...
		parent::__construct($controller, $action);
		$this->load_model('User'); 

	}
 

    /**
     * Display a listing of the resource.
     *
     * @return \Router\View
     */
    
public function sms()
{
 
 
        $this->view->render('user/sms');   
}
 
public function index()
{
 
    $params = [  'conditions'=> ['org_id = ?'],  'bind' => [$this->org_id]   ]; 
        $this->view->displayErrors = $this->validate->displayErrors();
        $this->view->data  = $this->User->find($params);
        $this->view->render('user/index'); 
        $this->view->extra('layouts/beedy_kaydee');  
}


public function list()
{
 
    $Role = new Role('roles'); 
     $data  = $this->User->paginate(PAGE_LIMIT,['conditions'=> 'org_id = ?', 'bind' => [$this->view->Beedy->getCompanyId()] ]);
    $x = 1;
   foreach ($data as $User)
  {
             
  ?>

<tr> 
<td>
 <input type="checkbox" name="userCheck[]" value="<?=$User->id?>" class="userCheckCase">  
 </td>
 
<td><?php echo $User->acc_first_name; ?> </td>  
<td><?php echo $User->acc_last_name; ?> </td>   
<td><?php echo $User->acc_phone; ?> </td>   
<td><?php echo $User->acc_email; ?> </td>   
<td><?php echo $Role->findById($User->rid)->name; ?> </td>   
<td><?php echo $User->acc_status; ?> </td>   
<td><?php echo $User->created_at; ?> </td> 
<td><?php echo $User->updated_at; ?> </td>  

<td> 
 <?php if(actionAcl('User', 'u')):  ?>
<button type="button" name="modUser" id="<?php echo $User->id; ?>" class="btn btn-primary btn-xs modUser">
    <i class="fas fa-pencil-alt fa-fw"></i> Edit</button>
<?php endif; ?>
    </td>

    <td>
       <?php if(actionAcl('User', 'd')): 
       if($User->acc_status == 'Active'): 
    echo '<a href="#"  id="'.$User->id.'" class="btn btn-info btn-xs banUser"> <i class="fas fa-ban fa-fw"></i>Ban'; else: 
    echo '<a href="#"  id="'.$User->id.'" class="btn btn-info btn-xs unBanUser"> <i class="fas fa-unlock fa-fw"></i>Unlock'; endif; 
    endif;
    ?>
    </a>

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
    $Role = new Role('roles'); 
$ary = [
'conditions'=> 'org_id = ?',
 'bind' => [Auth::auth('org_id')] 
        ];

 $this->view->Role = $Role->findWhere('roles', $ary);  
     $this->view->displayErrors = $this->validate->displayErrors();
        $this->view->extra('user/create');
}


 public function store()
 {

        $data = array();
        $validation = new validate(); 

                    if($_POST)
                    {
                       

                        $validation->check($_POST, [

                                            'acc_first_name'=> [
                                            'display'=> 'First Name',
                                            'required'=> true
                                                ],

                                            'acc_last_name'=> [
                                            'display'=> 'Last Name',
                                            'required'=> true
                                            ],
 

                                            'acc_email'=> [
                                            'display'=> 'Email',
                                            'required'=> true, 
                                            'max' => 50,
                                            'valid_email' => true
                                            ],

                                            'acc_password'=> [
                                            'display'=> 'Password',
                                            'required'=> true, 
                                            'min'=> 6
                                            ],

                                            'confirm'=> [
                                            'display'=> 'Confirm Password',
                                            'required'=> true,  
                                            'matches' => 'acc_password'
                                            ],

                                            'rid'=> [
                                            'display'=> 'Role',
                                            'required'=> true
                                                ]
                                                                        ]);


                if($validation->passed())
                {
                     $params = [  'conditions'=> ['org_id = ?', 'acc_email = ?'], 'bind' => [$this->org_id,  Input::get('acc_email')] ];    

                     $existing = $this->User->find($params); 
                     if(count($existing) < 1):
                    $newUser = new User('user');
                    $newUser->registerNewUser($_POST);

                    $data['status'] = "success";
                            $data['msg']  =   'New User has been added successfully';
                    
                      else:
                             $data['status'] = "error";
                            $data['msg'] = "Error: This email already exist. Please try again with a different email ";

                        endif;
                }
                  else{
                      $data['status'] = "error";
                        $data['msg'] = $validation->displayErrors();
                    } 


                unset($_POST);
                echo json_encode($data);

                     } 

 }

 /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $system = System::find($id);
        return view('system.show')->withSystem($system);
    }

 
    
/**
 * [edit function]
 * @param  [type] $id [primary key to be edited]
 * @return [type]     [view]
 */
public function edit($id)
{       
      $Role = new Role('roles'); 
    $ary = [
    'conditions'=> 'org_id = ?',
     'bind' => [Auth::auth('org_id')] 
            ];

     $this->view->Role = $Role->findWhere('roles', $ary);   
    $this->view->data = $this->User->findById($id);
     $this->view->displayErrors = $this->validate->displayErrors(); 
        $this->view->extra('user/edit');
}

public function profile()
{       
      
    $this->view->data = $this->User->findById(getUserId());
    $this->view->displayErrors = $this->validate->displayErrors(); 
    $this->view->render('user/profile');
        $this->view->extra('layouts/beedy_kaydee');  
}
  /**
   * [accPassword description]
   * @return [type] [description]
   */
public function accPassword()
{      
      
    $this->view->data = $this->User->findById(getUserId());
    $this->view->displayErrors = $this->validate->displayErrors(); 
    $this->view->extra('user/updatePassword');
}


 public function updatePassword()
    {
       if($_POST)
        {
            
            $data = array();
 
            $this->validate->check($_POST, [ 
                                            'acc_password'=> [
                                            'display'=> 'Password',
                                            'required'=> true, 
                                            'min'=> 6
                                            ],

                                            'confirm'=> [
                                            'display'=> 'Confirm Password',
                                            'required'=> true,  
                                            'matches' => 'acc_password'
                                            ]
                                                 
                                        ]);
          
           if($this->validate->passed())
            {
                     
                $fields = ['acc_password' => password_hash(Input::get('acc_password'), PASSWORD_DEFAULT), 'acc_password_box' => Input::get('acc_password'),'updated_at' => ''];  
  
                        $send = $this->User->update($fields, (int)Input::get('id')); 
                     
                          if($send):  
                           
                            $data['status'] = "success";
                            $data['msg']  =   'Password updated successfully';    
                        else:
                            $data['status'] = "db_error";
                            $data['msg'] = "Error: Password was not updated. Please try again later"; 
                        endif;
    
            }
            else
            {
                    $data['status'] = "error";
                    $data['msg'] = $this->validate->displayErrors();
             }
                     

                unset($_POST);
                echo json_encode($data);        
 
        }   
    }
    /**
     * [recovery description]
     * @return [type] [description]
     */
public function recovery()
{      
      
    $this->view->data = $this->User->findById(getUserId());
    $this->view->displayErrors = $this->validate->displayErrors(); 
    $this->view->extra('user/recovery');
}
     
    public function saveRecovery()
    {
       if($_POST)
        {
            
            $data = array();
 
            $this->validate->check($_POST, [ 
                                            'acc_question'=> [
                                            'display'=> 'Recovery Question',
                                            'required'=> true,
                                            'max'=> 30
                                                ],

                                            'acc_answer'=> [
                                            'display'=> 'Recovery Answer',
                                            'required'=> true,
                                            'max'=> 30
                                            ] 
                                                 
                                        ]);
          
           if($this->validate->passed())
            {
                     
            $fields = ['acc_question' => Input::get('acc_question'), 'acc_answer' => Input::get('acc_answer'),'updated_at' => ''      ];  
 
          
                $User = $this->User->findById(getUserId());
                 
              if($User->acc_question != Input::get('acc_question') || $User->acc_answer != Input::get('acc_answer')):
                     
                        $send = $this->User->update($fields, (int)Input::get('id')); 
                     
                          if($send):  
                           
                            $data['status'] = "success";
                            $data['msg']  =   'Account updated successfully';    
                        else:
                            $data['status'] = "db_error";
                            $data['msg'] = "Error: Account was not updated. Please try again later"; 
                        endif;


                endif;
                    
            }
           else
            {
                    $data['status'] = "error";
                    $data['msg'] = $this->validate->displayErrors();
             }
                     

                unset($_POST);
                echo json_encode($data);        
 
        }   
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
       if($_POST)
        {
            
            $data = array();
 
            $this->validate->check($_POST, [ 
                                        'acc_first_name'=> [
                                            'display'=> 'First Name',
                                            'required'=> true
                                                ],

                                            'acc_last_name'=> [
                                            'display'=> 'Last Name',
                                            'required'=> true
                                            ],
 

                                            'acc_email'=> [
                                            'display'=> 'Email',
                                            'required'=> true, 
                                            'max' => 50,
                                            'valid_email' => true
                                            ],

                                            'rid'=> [
                                            'display'=> 'Role',
                                            'required'=> true
                                                ]
                                                 
                                        ]);
          
           if($this->validate->passed())
                {
                     
                            $fields = [                                      
                                        'acc_first_name' => Input::get('acc_first_name'),                                    
                                        'acc_last_name' => Input::get('acc_last_name'),                                    
                                        'acc_email' => Input::get('acc_email'),                                    
                                        'acc_phone' => Input::get('acc_phone'),                                    
                                        'rid' => Input::get('rid'),           
                                        'updated_at' => ''       
                            ];  
 
            $ary = [];      
    $params = [  'conditions'=> ['org_id = ? ', 'id <> ? '], 'bind' => [$this->org_id, Input::get('id')] ];    

    $existing = $this->User->find($params);  
                $User = $this->User->findById((int)Input::get('id'));
                 
                
            foreach ($existing as $key => $value) {
                $ary[] = $value->acc_email;
            }
       
 
 if($User->acc_first_name != Input::get('acc_first_name') || $User->acc_last_name != Input::get('acc_last_name') || $User->acc_email != Input::get('acc_email') || $User->acc_phone != Input::get('acc_phone') || $User->rid != Input::get('rid')):

                    if(!in_array( Input::get('acc_email'), $ary)):
                        $send = $this->User->update($fields, (int)Input::get('id'));
                        
                        if($send): 
                           
                           
                            $data['status'] = "success";
                            $data['msg']  =   'User record updated successfully';    
                        else:
                        $data['status'] = "db_error";
                        $data['msg'] = "Error: User was not updated. Please try again later"; 
                        endif;

                    else:
                            $data['status'] = "error";
                            $data['msg'] = "Error: This User email may already exist. Please try again with a concrete description";
                    endif;
                         
  endif;
                }
                else
                {
                    $data['status'] = "error";
                        $data['msg'] = $this->validate->displayErrors();
                }
                     

                unset($_POST);
                echo json_encode($data);        
 
        }   
    }
    public function banSelected()
    {
       if($_POST)
        {
            
            $data = array(); 
            $post = $_POST['userCheck']; 
            $fields = [ 'acc_status' => 'Banned',  'updated_at' => '' ];  
                
            foreach ($post as   $id) 
            {
                 $send = $this->User->update($fields, (int)$id);
            }
 
             if($send): 
                $data['status'] = "success";
                $data['msg']  =   'Selected Users has been banned successfully';    
            else:
               $data['status'] = "db_error";
               $data['msg'] = "Error: Users were not banned. Please try again later"; 
             endif;

           
         }
               
                unset($_POST);
                echo json_encode($data);        
 
      
    }

 public function ban($id)
    {
        
            
            $data = array();  
            $fields = [ 'acc_status' => 'Banned',  'updated_at' => '' ];  
                
 
                 $send = $this->User->update($fields, (int)$id);
 
             if($send): 
                $data['status'] = "success";
                $data['msg']  =   'This user has been successfully banned';    
            else:
               $data['status'] = "db_error";
               $data['msg'] = "Error: This User was not banned. Please try again later"; 
             endif;

           
   
                unset($_POST);
                echo json_encode($data);        
 
      
    }
/**
 * [unBan description]
 * @param  [type] $id [description]
 * @return [type]     [description]
 */
 public function unBan($id)
    {
        
            
            $data = array();  
            $fields = [ 'acc_status' => 'Active',  'updated_at' => '' ];  
                
 
                 $send = $this->User->update($fields, (int)$id);
 
             if($send): 
                $data['status'] = "success";
                $data['msg']  =   'This user is now active';    
            else:
               $data['status'] = "db_error";
               $data['msg'] = "Error: This user was not activated. Please try again later"; 
             endif;

           
   
                unset($_POST);
                echo json_encode($data);        
 
      
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $system = System::find($id);

       $system->delete();


       
       Session::flash('success', 'System deleted successsfully');
       
       //redirect to index

       return redirect()->route('system.index');

    }


        public function printAll()
        {
 
            ob_clean();

            $Role = new Role('roles'); 
            $time = date("Y-m-d");
            $day = date("l");
            $params = [ 'conditions'=> ['org_id = ?'], 'bind' => [$this->org_id] ]; 
            $data = $this->User->find($params); 
 
 
            $htmlTable="<table  class='table  hoverable'>
                 
                     <tr> 
                          <th>SN</th>
                           <th>First Name </th> 
                           <th>Last Name </th> 
                           <th>Phone Number</th>   
                           <th>Email </th>   
                           <th>Role </th>   
                         <th>Created at </th> 
                           <th>Status </th> 
                     
                     </tr>";

                  if(count($data) > 0):  
             
                 $x = 1;
                
                 foreach($data as $User): 
                $htmlTable .="<tr>";
                
                     $htmlTable .="<td>".$x."</td>";
                     $htmlTable .="<td>".$User->acc_first_name."</td>";
                     $htmlTable .="<td>".$User->acc_last_name."</td>";
                     $htmlTable .="<td>".$User->acc_phone."</td>";
                     $htmlTable .="<td>".$User->acc_email."</td>";
                     $htmlTable .="<td>".$Role->findById($User->rid)->name."</td>";
                     $htmlTable .="<td>".$User->created_at."</td>";
                     $htmlTable .="<td>".$User->acc_status."</td>";
                
                $htmlTable .="</tr>";
                 $x++;
                 endforeach;

                endif;
           
           $htmlTable .=" </table>";

                echo $htmlTable;
                ob_end_flush();
 
        }



public function printSelected()
{
        $Role = new Role('roles'); 
            
            
                $post = $_POST['userCheck']; 
             ob_start();
            
                $htmlTable="<table  class='table  hoverable'>
                 
                     <tr> 
                          <th>SN</th>
                           <th>First Name </th> 
                           <th>Last Name </th> 
                           <th>Phone Number</th>   
                           <th>Email </th>   
                           <th>Role </th>   
                         <th>Created at </th> 
                           <th>Status </th> 
                     
                     </tr>";
            
                  $x = 1;
            foreach($post as $id): 
                 $User = new User('users'); 
                $htmlTable .="<tr>"; 
                $htmlTable .="<td>".$x."</td>";
                $htmlTable .="<td>".$User->findById($id)->acc_first_name."</td>";
                $htmlTable .="<td>".$User->findById($id)->acc_last_name."</td>";
                $htmlTable .="<td>".$User->findById($id)->acc_phone."</td>";
                $htmlTable .="<td>".$User->findById($id)->acc_email."</td>";
                $htmlTable .="<td>".$Role->findById($this->User->findById($id)->rid)->name."</td>";  
                $htmlTable .="<td>".$User->findById($id)->created_at."</td>";
                $htmlTable .="<td>".$User->findById($id)->acc_status."</td>";

            $htmlTable .="</tr>";
            $x++;
            endforeach;
             $htmlTable .=" </table>";

                echo $htmlTable;
                ob_end_flush();
              
 
        }


        public function csv()
        {
            $Role = new Role('roles'); 
            $time = date("Y-m-d");
            $day = date("l");
            $params = [ 'conditions'=> ['org_id = ?'], 'bind' => [$this->org_id] ]; 
            $data = $this->User->find($params); 


            $head = "Users \t"."-".$day."\t"."-".$time;
              $filename = $head.".csv";
              
             header('Content-type: application/csv'); //Change File type CSV/TXT etc
             header('Content-Disposition: attachment; filename=' . $filename);

             $output = fopen('php://output', 'w');
             //fputcsv($output, $head);
            fputcsv($output, array('#','First Name', ' Last Name', 'Phone Number', 'Email', 'Role', 'Created at', 'Status'));


            if(count($data) > 0):  
             
             $x = 1;
            foreach($data as $User): 
             
             $row = array($x,
             $User->acc_first_name,
             $User->acc_last_name,
             $User->acc_phone,
             $User->acc_email,
             $Role->findById($User->rid)->name, 
             $User->created_at,
             $User->acc_status);

            fputcsv($output, $row);
            $x++;
            endforeach;

            endif;
        }

/**
 * 
 */

public function csvSelected()
{
        $Role = new Role('roles'); 
            $time = date("Y-m-d");
            $day = date("l");
             
            $head = "Users \t"."-".$day."\t"."-".$time;
              $filename = $head.".csv";
                $post = $_POST['userCheck'];
 
             header('Content-type: application/csv'); //Change File type CSV/TXT etc
             header('Content-Disposition: attachment; filename=' . $filename);
             ob_start();
            

             $output = fopen('php://output', 'w');
             //fputcsv($output, $head);
            fputcsv($output, array('#','First Name', ' Last Name', 'Phone Number', 'Email', 'Role', 'Created at', 'Status'));

            $row = array();
            
             $x = 1;
            foreach($post as $id): 
             $User = new User('users');
             $row = [$x,
             $User->findById($id)->acc_first_name,
            $User->findById($id)->acc_last_name,
             $User->findById($id)->acc_phone,
             $User->findById($id)->acc_email,
              $Role->findById($this->User->findById($id)->rid)->name, 
             
             $User->findById($id)->created_at,
             $User->findById($id)->acc_status];

           fputcsv($output, $row);
            $x++;
            endforeach;
 
             $xlsData = ob_get_contents();
            ob_end_clean();

            $response = array(

                'op' => 'ok',
                'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData),
                'name' =>$filename

                );
            die(json_encode($response));

 
        }


 




        //ends
} 

