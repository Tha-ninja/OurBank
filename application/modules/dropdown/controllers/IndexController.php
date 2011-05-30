<?php
class Dropdown_IndexController extends Zend_Controller_Action 
{
    public function init() 
    {
        $this->view->pageTitle='Drop Down Settings';
		$this->view->adm = new App_Model_Adm();   	
        $sessionName = new Zend_Session_Namespace('ourbank');

	$this->view->createdby = $sessionName->primaryuserid;

    }
  public function newtableAction() 
    {
		$addform=new Dropdown_Form_Dropdown();
        $this->view->form=$addform;

		if ($this->_request->isPost() && $this->_request->getPost('Create')) {
		$tablename=$this->_request->getParam('name');
		$description=$this->_request->getParam('description');

		if ((!$tablename) or (!$description)) {echo "Please enter the table name and description";} else {
		$settings = new Dropdown_Model_Dropdown;
		$table=$settings->Createtable($tablename);	
		$formdata1=array('id'=>'',
						'name'=>$tablename,
						'descriptions'=>$description);
		$id = $this->view->adm->addRecord('ourbank_master_mastertables',$formdata1);
		$this->_redirect('/dropdown');
		}


	}

}
    public function indexAction() 
    {
        $path =  $this->view->baseUrl();

        $addform=new Dropdown_Form_Drop($path);
        $this->view->form=$addform;
		$this->view->title = "Drop Down";
		//echo $tableName;
		$mastertable = $this->view->adm->viewRecord("ourbank_master_mastertables","id","DESC");
		foreach($mastertable as $mastertable) {
				$addform->name->addMultiOption($mastertable['name'],$mastertable['descriptions']);
			}		
// 			if ($this->_request->isPost() && $this->_request->getPost('Go')) {
// 	    	if ($this->_request->isPost()) {
// 				$formData = $this->_request->getPost();
// 					if ($addform->isValid($formData)) {
//   				$tablename=$this->_request->getParam('name');
// 	       $this->view->tableName = $tablename;
//  		$tabledata = new Dropdown_Model_Dropdown();
//         $tabledatas = $tabledata->tabledata($tablename);
// 
//         $this->view->tabledata = $tabledatas;
// 
// 
// 
// 			}
// 		}
// 	}
}
public function nameAction()
{


        $app = $this->view->baseUrl();
        $this->_helper->layout->disableLayout();
        $tablename = $this->_request->getParam('names');
        $this->view->tableName = $tablename;
        $tabledata = new Dropdown_Model_Dropdown();
        $tabledatas = $tabledata->tabledata($tablename);
// Zend_Debug::dump($tabledatas);
        $this->view->tabledata = $tabledatas;
        }

public function addAction() 
    {
 $path = $this->view->baseUrl();
$dropdownForm = new Dropdown_Form_Settings($path);
        $this->view->form = $dropdownForm;
$tName=$this->_request->getParam('name');
       

 		$id=$this->_request->getParam('id');
		$this->view->tableName =$tName;
		$this->view->id =$id;
        $dropdown = new Dropdown_Model_Dropdown();
		$statename = $this->view->adm->viewRecord("ourbank_master_state","id","DESC");
		foreach($statename as $statename){
				$dropdownForm->state->addMultiOption($statename['id'],$statename['name']);
			}
		$districtname = $this->view->adm->viewRecord("ourbank_master_districtlist","id","DESC");
		foreach($districtname as $districtname){
				$dropdownForm->district->addMultiOption($districtname['id'],$districtname['name']);
			}
		$taluklist = $this->view->adm->viewRecord("ourbank_master_taluklist","id","DESC");
		foreach($taluklist as $taluklist){
				$dropdownForm->taluk->addMultiOption($taluklist['id'],$taluklist['name']);
			}
		$gillapanchayath = $this->view->adm->viewRecord("ourbank_master_gillapanchayath","id","DESC");
		foreach($gillapanchayath as $gillapanchayath){
				$dropdownForm->gillapanchayath->addMultiOption($gillapanchayath['id'],$gillapanchayath['name']);
			}
		$village = $this->view->adm->viewRecord("ourbank_master_villagelist","id","DESC");
		foreach($village as $village){
				$dropdownForm->village->addMultiOption($village['id'],$village['name']);
			}
		$bank = $this->view->adm->viewRecord("ourbank_master_bank","id","DESC");
		foreach($bank as $bank){
				$dropdownForm->bank->addMultiOption($bank['id'],$bank['name']);
			}
		if ($this->_request->isPost() && $this->_request->getPost('Save')) {
			if($tName == 'ourbank_master_districtlist') {
 		$id=$this->_request->getParam('state');
 		$common=$this->_request->getParam('commonname');

									$formdata1=array('id'=>'',
									'state_id'=>$id,
									'name'=>$common);
						$id = $this->view->adm->addRecord($tName,$formdata1);
 			$this->_redirect('/dropdown');
}
	if($tName == 'ourbank_master_hoblilist') {
 		$id=$this->_request->getParam('taluk');
 		$common=$this->_request->getParam('commonname');

									$formdata1=array('id'=>'',
									'taluk_id'=>$id,
									'name'=>$common);
						$id = $this->view->adm->addRecord($tName,$formdata1);
 			$this->_redirect('/dropdown');


}
		if($tName == 'ourbank_master_villagelist') {
 		$id=$this->_request->getParam('gillapanchayath');
 		$common=$this->_request->getParam('commonname');

									$formdata1=array('id'=>'',
									'gillapanchayath_id'=>$id,
									'name'=>$common);
						$id = $this->view->adm->addRecord($tName,$formdata1);
 			$this->_redirect('/dropdown');


}
	if($tName == 'ourbank_master_gillapanchayath') {
 		$id=$this->_request->getParam('hobli');
 		$common=$this->_request->getParam('commonname');

									$formdata1=array('id'=>'',
									'hobli_id'=>$id,
									'name'=>$common);
						$id = $this->view->adm->addRecord($tName,$formdata1);
 			$this->_redirect('/dropdown');


}

if($tName == 'ourbank_master_branch') {
 		$bank=$this->_request->getParam('bank');
 		$common=$this->_request->getParam('commonname');

									$formdata1=array('id'=>'',
									'bank_id'=>$id,
									'name'=>$common);
						$id = $this->view->adm->addRecord($tName,$formdata1);
 			$this->_redirect('/dropdown');


}
if($tName == 'ourbank_master_habitation') {
 		$stateid=$this->_request->getParam('state');
 		$districtid=$this->_request->getParam('district');
 		$talukid=$this->_request->getParam('taluk');
 		$hobliid=$this->_request->getParam('hobli');
 		$gillapanchayath=$this->_request->getParam('gillapanchayath');
 		$village=$this->_request->getParam('village');
 		$common=$this->_request->getParam('commonname');

									$formdata1=array('id'=>'',
									'state_id'=>$stateid,
									'district_id'=>$districtid,
									'taluk_id'=>$talukid,
									'hobli_id'=>$hobliid,
									'gillapanchayath_id'=>$gillapanchayath,
									'village_id'=>$village,
									'created_by'=>$this->view->createdby,
									'name'=>$common);
						$id = $this->view->adm->addRecord($tName,$formdata1);
 			$this->_redirect('/dropdown');


}
if($tName == 'ourbank_master_mastertables') {
 		$description=$this->_request->getParam('description');
 		$commonname=$this->_request->getParam('commonname');
 		
									$formdata1=array('id'=>'',
									'descriptions'=>$description,
									'name'=>$commonname);
						$id = $this->view->adm->addRecord($tName,$formdata1);
$settings = new Dropdown_Model_Dropdown;
		$table=$settings->Createtable($commonname);	

 			$this->_redirect('/dropdown');


}
 		$commonname=$this->_request->getParam('commonname');

									$formdata1=array('id'=>'',
									'name'=>$commonname);
						$id = $this->view->adm->addRecord($tName,$formdata1);
 			$this->_redirect('/dropdown');

}

	}    
 public function commonviewAction() 
    {
		$tName=$this->_request->getParam('name');
 		$id=$this->_request->getParam('id');
		$settings = new Dropdown_Model_Dropdown;
		$this->view->details=$settings->getdetails($tName,$id);
		$this->view->tableName =$tName;
	}
public function editAction() 
    {
  							$tName=$this->_request->getParam('name');
							$this->view->tableName = $tName;


			$id=$this->_request->getParam('id');
			$this->view->id = $id;
			$settings = new Dropdown_Model_Dropdown;

 		$path = $this->view->baseUrl();
 		$dropdownForm = new Dropdown_Form_Settings($path);
        $this->view->form = $dropdownForm;
// //   		$tName=$this->_request->getParam('name');
// // 		$this->view->tableName =$tName;
 		if ($this->_request->isPost() && $this->_request->getPost('Update')) 
			{
	    		 echo $Name=$this->_request->getParam('commonname');

   				$tName=$this->_request->getParam('name');
				if ($Name=='') {
						 echo "value cant be empty"; 
				} else {
							$id=$this->_request->getParam('id');

  							$Name=$this->_request->getParam('commonname');
 							$formdata1=array('name'=>$Name);		
//Zend_Debug::dump($dropdownForm->getValues());
  							$previousdata = $this->view->adm->editRecord($tName,$id);
							$this->view->adm->updateRecord($tName,$id,$formdata1);
							$this->_redirect('/dropdown');
				}
		} else {
// // 			$dropdownForm = new Dropdown_Form_Settings($path);
// //        		 $this->view->form = $dropdownForm;
 			$statename = $this->view->adm->viewRecord("ourbank_master_state","id","DESC");
		foreach($statename as $statename){
				$dropdownForm->state->addMultiOption($statename['id'],$statename['name']);
			}
		$districtname = $this->view->adm->viewRecord("ourbank_master_districtlist","id","DESC");
		foreach($districtname as $districtname){
				$dropdownForm->district->addMultiOption($districtname['id'],$districtname['name']);
			}
		$taluklist = $this->view->adm->viewRecord("ourbank_master_taluklist","id","DESC");
		foreach($taluklist as $taluklist){
				$dropdownForm->taluk->addMultiOption($taluklist['id'],$taluklist['name']);
			}
		$gillapanchayath = $this->view->adm->viewRecord("ourbank_master_gillapanchayath","id","DESC");
		foreach($gillapanchayath as $gillapanchayath){
				$dropdownForm->gillapanchayath->addMultiOption($gillapanchayath['id'],$gillapanchayath['name']);
			}
		$village = $this->view->adm->viewRecord("ourbank_master_villagelist","id","DESC");
		foreach($village as $village){
				$dropdownForm->village->addMultiOption($village['id'],$village['name']);
			}
		$bank = $this->view->adm->viewRecord("ourbank_master_bank","id","DESC");
		foreach($bank as $bank){
				$dropdownForm->bank->addMultiOption($bank['id'],$bank['name']);
			}

		
			$tName=$this->_request->getParam('name');
			$this->view->ff = $tName;
			$namedetails = $settings->getdetails($tName,$id);
				foreach($namedetails as $holidaydetails) {
					$this->view->form->commonname->setValue($holidaydetails['name']);
				}
	}

}
public function deleteAction() 

    {
		$dropdownForm = new Dropdown_Form_Delete();
        $this->view->form = $dropdownForm;
 		$id=$this->_request->getParam('id');
		$this->view->id = $id;
		$tName=$this->_request->getParam('name');
		$this->view->ff = $tName;
		if($this->_request->isPost() && $this->_request->getPost('Delete')) {
			$formdata = $this->_request->getPost();
			if ($dropdownForm->isValid($formdata)) { 
 				$id=$this->_request->getParam('id');
				$this->view->id = $id;
				$tName=$this->_request->getParam('name');
				$this->view->ff = $tName;
				//deleting  record
       			$redirect = $this->view->adm->deleteRecord($tName,$id);
				//update
            	$this->_redirect('/dropdown');

			}
		}
	}
    public function districtAction() {
        $path = $this->view->baseUrl();
        $this->_helper->layout()->disableLayout();
		$dropdownForm = new Dropdown_Form_Settings($path);
        $this->view->form = $dropdownForm;
     	$state=$this->_request->getParam('state');
        $getdistrict = new Dropdown_Model_Dropdown();
        $district=$getdistrict->district($state);
 		foreach($district as $eacharraysent) {
        $dropdownForm->district->addMultiOption($eacharraysent['id'],$eacharraysent['name']);
        }
}
public function hobliAction() {
        $path = $this->view->baseUrl();
        $this->_helper->layout()->disableLayout();
		$dropdownForm = new Dropdown_Form_Settings($path);
        $this->view->form = $dropdownForm;
     	$taluk=$this->_request->getParam('taluk');
        $gethobli = new Dropdown_Model_Dropdown();
        $hobli=$gethobli->hobli($taluk);
 		foreach($hobli as $eacharraysent) {
        $dropdownForm->hobli->addMultiOption($eacharraysent['id'],$eacharraysent['name']);
        }}
		public function villageAction() {
        $path = $this->view->baseUrl();
        $this->_helper->layout()->disableLayout();
		$dropdownForm = new Dropdown_Form_Settings($path);
        $this->view->form = $dropdownForm;
     	$gillapanchayath=$this->_request->getParam('gillapanchayath');
        $getpanchayath = new Dropdown_Model_Dropdown();
        $panchayath=$getpanchayath->panchayath($gillapanchayath);
 		foreach($panchayath as $eacharraysent) {
        $dropdownForm->village->addMultiOption($eacharraysent['id'],$eacharraysent['name']);
        }



}

}
