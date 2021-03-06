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
class Health_IndexController extends Zend_Controller_Action 
{
    public function init() 
    {
//it is create session and implement ACL concept...
        $this->view->pageTitle=$this->view->translate('Health');
        $globalsession = new App_Model_Users();
        $this->view->globalvalue = $globalsession->getSession();
        $this->view->createdby = $this->view->globalvalue[0]['id'];
        $this->view->username = $this->view->globalvalue[0]['username'];
        $storage = new Zend_Auth_Storage_Session();
        $data = $storage->read();
        if(!$data){
            $this->_redirect('index/login');
        }
        $this->view->adm = new App_Model_Adm();
        $this->view->familycommon = new Familycommonview_Model_familycommonview();

    }

    public function indexAction() 
    {
    }

//add family health add action
    public function addhealthAction() 
    {
        $this->view->title = $this->view->translate("Add health details");
        $this->view->memberid=$familyid=$this->_getParam('id');
        $this->view->membernames = $this->view->familycommon->getfamily($familyid);
        $this->view->insurance=$this->view->familycommon->getinsurance($familyid);
        $this->view->submoduleid = $this->_getParam('subId');

        $dbmodel=new Health_Model_health();
        $this->view->habitdetails = $dbmodel->gethabittypes();
        $this->view->challengedetails = $dbmodel->getchallengetypes();
        $this->view->membername = $membername = $dbmodel->getfamilymemberdetails($familyid);
        $disease = $this->view->adm->viewRecord('ourbank_master_diseasetypes','id','ASC');

        $nameid = array();
        foreach($membername as $name){
            $nameid[] = $name['memberid'];
        } 
        $this->view->form = new Health_Form_health($nameid);
        foreach($nameid as $nameids){
         $a='healthdisease'.$nameids;
            foreach($disease as $diseasetype){
                //$this->view->form->$a->addMultiOption($diseasetype['id'],$diseasetype['name']);
                $this->view->form->$a->addMultiOption($diseasetype->id,$diseasetype->id." -[".$diseasetype->name_regional."]");
            }
        }

//count number of family members
        $this->view->membercount = $totalmembers = count($this->view->membername);
// //insert the health details 
        if ($this->_request->isPost() && $this->_request->getPost('submit')) 
        {

 $flag = false;
//         $habit = $this->_getParam('habit');
//         $challenge = $this->_getParam('challenge');
        $submoduleid = $this->_getParam('subId');
        $familyid = $this->_getParam('memId');


        foreach($membername as $memberna){
         if($this->_getParam('healthdisease'.$memberna['memberid'])) {
                $flag = true;
                $disease = $this->_getParam('healthdisease'.$memberna['memberid']);
                    foreach($disease as $diseasetype){
                        $this->view->adm->addRecord("ourbank_healthdiseasedetails",
                                                array('id' => '',
                                                'submodule_id' => $submoduleid,
                                                'family_id'=>$this->view->memberid,
                                                'member_id'=>$memberna['memberid'],
                                                'healthdisease'=>$diseasetype,
                                                'created_by'=>$this->view->createdby,
                                                'created_date'=>date("y/m/d H:i:s")
                                                ));
                    }
            }
        }
        foreach($membername as $memberna){
            if($this->_getParam('habit-'.$memberna['memberid'])) {
                $flag = true;
                $habits = $this->_getParam('habit-'.$memberna['memberid']);
                    foreach($habits as $habitstype){
                        explode('_',$habitstype);
                        $this->view->adm->addRecord("ourbank_healthhabitdetails",
                                                                        array('id' => '',
                                                                        'submodule_id' => $submoduleid,
                                                                        'family_id'=>$this->view->memberid,
                                                                        'member_id'=>$habitstype[0],
                                                                        'habit_id'=>$habitstype[2],
                                                                        'created_by'=>$this->view->createdby,
                                                                        'created_date'=>date("y/m/d H:i:s")
                                                                        ));
                    }
            }
        }
        foreach($membername as $memberna){
            if($this->_getParam('challenge-'.$memberna['memberid'])) {
             $flag = true;
                $challenge = $this->_getParam('challenge-'.$memberna['memberid']);
                    foreach($challenge as $challengetype){
                        explode('_',$challengetype);

                        $this->view->adm->addRecord("ourbank_healthphychallenge",
                                                array('id' => '',
                                                'submodule_id' => $submoduleid,
                                                'family_id'=>$this->view->memberid,
                                                'member_id'=>$challengetype[0],
                                                'phychallenge_id'=>$challengetype[2],
                                                'created_by'=>$this->view->createdby,
                                                'created_date'=>date("y/m/d H:i:s")
                                                ));
                    }
            }

        }
                if($flag == true){
                                $this->_redirect('/familycommonview/index/commonview/id/'.$familyid);
                            } else {
                                $this->view->error = "Click back if you have not add any health details";
                                }
        }
    }

//edit family health details
    public function edithealthAction() 
    {
        $this->view->title = $this->view->translate("Edit health details");
        $this->view->memberid=$familyid=$this->_getParam('id');
        $this->view->membernames = $this->view->familycommon->getfamily($familyid);
        $this->view->insurance=$this->view->familycommon->getinsurance($familyid);
        $this->view->submoduleid = $submoduleid = $this->_getParam('subId');

        $dbmodel=new Health_Model_health();
        $this->view->habitdetails = $dbmodel->gethabittypes();
        $this->view->challengedetails = $dbmodel->getchallengetypes();
        $this->view->diseasedetails = $disease = $this->view->adm->viewRecord('ourbank_master_diseasetypes','id','ASC');

        $this->view->membername = $membername = $dbmodel->getfamilymemberdetails($familyid);
//count number of family members
        $this->view->membercount = count($this->view->membername);

  $nameid = array();
        foreach($membername as $name){
            $nameid[] = $name['memberid'];
        } 
$diseaseselect = array();
$this->view->form = new Health_Form_health($nameid);
        foreach($nameid as $nameids){
         $a='healthdisease'.$nameids;
            foreach($disease as $diseasetype){
               // $this->view->form->$a->addMultiOption($diseasetype['id'],$diseasetype['name_regional']);
                $this->view->form->$a->addMultiOption($diseasetype->id,$diseasetype->id." -[".$diseasetype->name_regional."]");
            }
        }
foreach($nameid as $namewithid){
        $diseasetype = $dbmodel->getselecteddisease($namewithid); // get selected disease id's
        $a='healthdisease'.$namewithid;
        if($diseasetype){
            foreach ($diseasetype as $diseasetypes){
                    $diseaseselect[] = $diseasetypes['healthdisease']; // set all avail disease list 
                }
            $this->view->form->$a->setValue($diseaseselect);
            unset($diseaseselect);

        }

}


        if ($this->_request->isPost() && $this->_request->getPost('update')) 
        {
        $submoduleid = $this->_getParam('subId');
        $familyid = $this->_getParam('memId');


        $habits = $this->view->adm->getRecord('ourbank_healthhabitdetails','family_id',$familyid);
         for ($j = 0 ; $j< count($habits); $j++) {
                $this->view->adm->addRecord("ourbank_healthhabitdetails_log",$habits[$j]);
            }

        $challenges = $this->view->adm->getRecord('ourbank_healthphychallenge','family_id',$familyid);
         for ($j = 0 ; $j< count($challenges); $j++) {
                $this->view->adm->addRecord("ourbank_healthphychallenge_log",$challenges[$j]);
            }

        $chronic = $this->view->adm->getRecord('ourbank_healthdiseasedetails','family_id',$familyid);
         for ($j = 0 ; $j< count($chronic); $j++) {
                $this->view->adm->addRecord("ourbank_healthdiseasedetails_log",$chronic[$j]);
            }

        $this->view->adm->deleteRecordwithparam('ourbank_healthhabitdetails','family_id',$familyid);
        $this->view->adm->deleteRecordwithparam('ourbank_healthphychallenge','family_id',$familyid);
        $this->view->adm->deleteRecordwithparam('ourbank_healthdiseasedetails','family_id',$familyid);

foreach($membername as $memberna){
         if($this->_getParam('healthdisease'.$memberna['memberid'])) {
                $disease = $this->_getParam('healthdisease'.$memberna['memberid']);
                    foreach($disease as $diseasetype){
                        $this->view->adm->addRecord("ourbank_healthdiseasedetails",
                                                array('id' => '',
                                                'submodule_id' => $submoduleid,
                                                'family_id'=>$this->view->memberid,
                                                'member_id'=>$memberna['memberid'],
                                                'healthdisease'=>$diseasetype,
                                                'created_by'=>$this->view->createdby,
                                                'created_date'=>date("y/m/d H:i:s")
                                                ));
                    }
            }
        }
        foreach($membername as $memberna){
            if($this->_getParam('habit-'.$memberna['memberid'])) {
                $habits = $this->_getParam('habit-'.$memberna['memberid']);
                    foreach($habits as $habitstype){
                        explode('_',$habitstype);
                        $this->view->adm->addRecord("ourbank_healthhabitdetails",
                                                                        array('id' => '',
                                                                        'submodule_id' => $submoduleid,
                                                                        'family_id'=>$this->view->memberid,
                                                                        'member_id'=>$habitstype[0],
                                                                        'habit_id'=>$habitstype[2],
                                                                        'created_by'=>$this->view->createdby,
                                                                        'created_date'=>date("y/m/d H:i:s")
                                                                        ));
                    }
            }
        }
        foreach($membername as $memberna){
            if($this->_getParam('challenge-'.$memberna['memberid'])) {
                $challenge = $this->_getParam('challenge-'.$memberna['memberid']);
                    foreach($challenge as $challengetype){
                        explode('_',$challengetype);

                        $this->view->adm->addRecord("ourbank_healthphychallenge",
                                                array('id' => '',
                                                'submodule_id' => $submoduleid,
                                                'family_id'=>$this->view->memberid,
                                                'member_id'=>$challengetype[0],
                                                'phychallenge_id'=>$challengetype[2],
                                                'created_by'=>$this->view->createdby,
                                                'created_date'=>date("y/m/d H:i:s")
                                                ));
                    }
            }

        }
//         foreach($habit as $habitid){
//         explode('_',$habitid);
//                     $this->view->adm->addRecord("ourbank_healthhabitdetails",
//                                                 array('id' => '',
//                                                 'submodule_id' => $submoduleid,
//                                                 'family_id'=>$this->view->memberid,
//                                                 'member_id'=>$habitid[0],
//                                                 'habit_id'=>$habitid[2],
//                                                 'created_by'=>$this->view->createdby,
//                                                 'created_date'=>date("y/m/d H:i:s")
//                                                 ));
//                     }
//      foreach($challenge as $challengeid){
//      explode('_',$challengeid);
//                 $this->view->adm->addRecord("ourbank_healthphychallenge",
//                                                 array('id' => '',
//                                                 'submodule_id' => $submoduleid,
//                                                 'family_id'=>$this->view->memberid,
//                                                 'member_id'=>$challengeid[0],
//                                                 'phychallenge_id'=>$challengeid[2],
//                                                 'created_by'=>$this->view->createdby,
//                                                 'created_date'=>date("y/m/d H:i:s")
//                                                 ));
// 		}
// 
//        foreach($membername as $memberna){
//          if($this->_getParam('healthdisease'.$memberna['memberid'])) {
//                 $disease = $this->_getParam('healthdisease'.$memberna['memberid']);
//                     foreach($disease as $diseasetype){
//                         $this->view->adm->addRecord("ourbank_healthdiseasedetails",
//                                                 array('id' => '',
//                                                 'submodule_id' => $submoduleid,
//                                                 'family_id'=>$this->view->memberid,
//                                                 'member_id'=>$memberna['memberid'],
//                                                 'healthdisease'=>$diseasetype,
//                                                 'created_by'=>$this->view->createdby,
//                                                 'created_date'=>date("y/m/d H:i:s")
//                                                 ));
//                     }
//             }
//         }

             $this->_redirect('/familycommonview/index/commonview/id/'.$familyid);
		}

        }
	
}
