<?php
/*
############################################################################
#  This file is part of OurBank.
############################################################################
#  OurBank is free software: you can redistribute it and/or modify
#  it under the terms of the GNU Affero General Public License as
#  published by the Free Software Foundation, either version 3 of the
#  License, or (at your option) any later version.
############################################################################
#  This program is distributed in the hope that it will be useful,
#  but WITHOUT ANY WARRANTY; without even the implied warranty of
#  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#  GNU Affero General Public License for more details.
############################################################################
#  You should have received a copy of the GNU Affero General Public License
#  along with this program.  If not, see <http://www.gnu.org/licenses/>.
############################################################################
*/
//commonview for all individual micro modules
class Familycommonview_IndexController extends Zend_Controller_Action
{
    public function init() 
    {
  	$this->view->pageTitle='Family Information';
       $storage = new Zend_Auth_Storage_Session();
        $data = $storage->read();
        if(!$data){
                $this->_redirect('index/login'); // once session get expired it will redirect to Login page
        }
        $sessionName = new Zend_Session_Namespace('ourbank');
        $userid=$this->view->createdby = $sessionName->primaryuserid; // get the stored session id

        $login=new App_Model_Users();
        $loginname=$login->username($userid);
        foreach($loginname as $loginname) {
            $this->view->username=$loginname['username']; // get the user name
        } 
	$this->view->adm = new App_Model_Adm();    
    }

    public function indexAction() 
    {
    }

//view and edit for member details, address, contact, family details
    public function commonviewAction()
    {
// //         //Acl
// //         //$access = new App_Model_Access();
// //         //$checkaccess = $access->accessRights('Individual',$this->view->globalvalue[0]['name'],'commonviewAction');
// //         //if (($checkaccess != NULL)) {

        $id=$this->_request->getParam('id');
        $this->view->memberid=$id;
        $familycommon=new Familycommonview_Model_familycommonview();
        $member_name=$familycommon->getfamily($id);
        $villageid=$member_name[0]['rev_village_id'];
        $parentid=$member_name[0]['parentoffice_id'];
        $talukname=$familycommon->gettalukname($villageid);
        if($talukname)
        {
          $this->view->villagename=$talukname[0]['name'];
        }

        $kootaname=$familycommon->getkoota($parentid);
        if($kootaname)
        {
          $this->view->kootaname=$kootaname[0]['name'];
        }
        //getting module id and submodule id
        $module=$familycommon->getmodule('Family');
        $this->view->mod_id=$module[0]['parent'];
        $this->view->sub_id=$module[0]['module_id'];
        $officemodule=$familycommon->getmodule('Office'); //print_r($officemodule);
        $officesub_id=$officemodule[0]['module_id'];
        $pincode1=$familycommon->getpincode($officesub_id,$villageid);
        if($pincode1)
        {
          $this->view->pincode1=$pincode1[0]['zipcode'];
        }

        $this->view->insurance=$familycommon->getinsurance($id);
//         //geting family details, family details, health, economic, education details
        $this->view->membername=$member_name;
// //      $this->view->family =$familycommon->getfamilydetails($id);
        $this->view->agriculture=$edit_agriculture =$familycommon->getagriculturedetails($id);
 		$this->view->acretotal =$familycommon->getacretotal($id);
        $this->view->guntatotal =$familycommon->getguntatotal($id);
        $this->view->family =$familycommon->getfamilydetails($id);
// //        $this->view->getentitlement=$familycommon->getentitlement();
        $this->view->crop=$familycommon->getcrop($id);
        $this->view->loandetails=$familycommon->getloandetails($id);
        $this->view->savingsdetails=$familycommon->getsavingsdetails($id);
        $this->view->gethabithealth=$familycommon->gethabithealth($id);
        $this->view->gethabitchallenge=$familycommon->gethabitphychallenge($id);
        $this->view->getchronicdisease=$familycommon->getchronicdisease($id);
        $this->view->getfamilymembers=$familycommon->getfamilyname($id);
        $this->view->livingassets=$familycommon->getlivingassetsdetails($id);
        $this->view->nonlivingassets=$familycommon->getnonlivingassetsdetails($id);


// 	//PRA Based Services
 	$this->view->praservice = $familycommon->praservice($id); 
        $this->view->expense=$familycommon->getexpense($id);

// 	// Basic Infrastructure
	$this->view->housingtype = $familycommon->housingtype($id);
	$this->view->ownership = $familycommon->ownership($id);
	$this->view->cookfuel = $familycommon->cookfuel($id);
// 	//Annual income details
         $this->view->incomedetails=$familycommon->getincomedetails($id);
         $this->view->incometotal=$familycommon->getincometotal($id);
// /*
// // 	$this->view->loandtotal=$individualcommon->getloantotal($id);
// //         $this->view->servicedetails=$individualcommon->servicedetails($id);
// //         $this->view->family =$individualcommon->getfamilydetails($id);
// //        $this->view->activities=$individualcommon->getactivities($id);
//         //}
//         //else {
//         //$this->_redirect('index/index');
//         //}*/
    }
}
