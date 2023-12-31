<?php
	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use Cake\ORM\TableRegistry;
	use App\Controller\CustomersController;

class DmiChemistTrainingDetailsTable extends Table{

	var $name = "DmiChemistTrainingDetails";
	var $useTable = 'dmi_chemist_training_details';

	public function sectionFormDetails($chemist_id) {

		$result = array();

		$result = $this->find('all',array('conditions'=>array('customer_id IS'=>$chemist_id,'is_latest'=>1),'order'=>'id asc'))->toArray();
		//to get last record for status, as above query get order in ASC. on 28-04-2022 by Amol
		if(!empty($result)){
			$getLastStatus = $this->find('all',array('fields'=>'form_status','conditions'=>array('customer_id IS'=>$chemist_id,'is_latest'=>1),'order'=>'id desc'))->first();
			$result[0]['form_status'] = $getLastStatus['form_status'];
		}
		
		 if(empty($result)){
				$result = array();
				$result[0]['id'] = '';
				$result[0]['customer_id'] = '';
				$result[0]['name_of_training'] = '';
				$result[0]['name_of_institute'] = '';
				$result[0]['division'] = '';
				$result[0]['from_dt'] = '';
				$result[0]['to_dt'] = '';
				$result[0]['form_status'] = '';
				$result[0]['created'] = '';
				$result[0]['modified'] = '';
				$result[0]['is_latest'] = '';
				$result[0]['customer_reply'] = '';
				$result[0]['current_level'] = '';
				$result[0]['mo_comment'] = '';
				$result[0]['ro_reply_comment'] = '';
				$result[0]['delete_mo_comment'] = '';
				$result[0]['customer_reply_date'] = '';
				$result[0]['total'] = ''; 			//This new field is added to save the total no of days of training in the database as Varchar - Akash [17-05-2023]
				$result[0]['training_days'] = ''; 	//This new field is added to save the total numbers of day seprately in the database as integer - Akash [17-05-2023]
				$result[0]['training_month'] = '';	//This new field is added to save the total numbers of month seprately in the database as integer - Akash [17-05-2023]
				$result[0]['training_year'] = '';	//This new field is added to save the total numbers of year seprately in the database as integer - Akash [17-05-2023]


		}else{

			$section_id = $_SESSION['section_id'];
			$Dmi_chemist_comment = TableRegistry::getTableLocator()->get('DmiChemistComments');
			$commentDetails = $Dmi_chemist_comment->find('all',array('conditions'=>array('customer_id IS'=>$chemist_id,'section_id IS'=>$section_id,'is_latest'=>1)))->first();
			
			if(!empty($commentDetails)){
				$reffered_back_comment = $commentDetails['comments'];
				$reffered_back_date = $commentDetails['comment_dt'];
			}else{
				$reffered_back_comment = '';
				$reffered_back_date = '';
			}
			
			$result[0]['reffered_back_comment'] = $reffered_back_comment;
			$result[0]['reffered_back_date'] = $reffered_back_date;

		}

		$DmiDivisionGrades = TableRegistry::getTableLocator()->get('DmiDivisionGrades');
		$division_list = $DmiDivisionGrades->find('list', array('valueField'=>'division','conditions'=>array('OR'=>array('delete_status'=>'no')),'order'=>array('division')))->toArray();

		foreach ($division_list as $key => $value) {

			$division[] = array(
				'vall' => $key,
				'label' => $value
			);
		}

		// common add more Table Header Array
		$tableD['label'] = array(
			'0' => array(
				'0' => array(
					'col' 		=> 'Sr.no',
					'colspan' 	=> '1',
					'rowspan' 	=> '2'
				),
				'1' => array(
					'col' 		=> 'Name Of Training',
					'colspan' 	=> '1',
					'rowspan' 	=> '2'
				),
				'2' => array(
					'col' 		=> 'Name Of Institute',
					'colspan' 	=> '1',
					'rowspan' 	=> '2'
				),
				'3' => array(
					'col' 		=> 'Division /Grade',
					'colspan' 	=> '1',
					'rowspan' 	=> '2'
				),
				'4' => array(
					'col' 		=> 'Duration Of Training',
					'colspan' 	=> '3', //this is changed from '2' to '3' as new field is added to the table - Akash [17-05-2023]
					'rowspan' 	=> '1'
				),
			),
			'1' => array(
				'0' => array(
					'col' 		=> 'From',
					'colspan' 	=> '1',
					'rowspan' 	=> '1'
				),
				'1' => array(
					'col' 		=> 'To',
					'colspan' 	=> '1',
					'rowspan' 	=> '1'
				),

				//This Block is added to show the calculated total in table - Akash [17-05-2023]
				'2' => array(
					'col' 		=> 'Total',
					'colspan' 	=> '1',
					'rowspan' 	=> '1'
				)
			)
		);


		$loopC = "0";
		foreach($result as $row){

			$row = $row;


			$tableD['input'][$loopC] = array(

				'0' => array(
					'name'		=> null,
					'type'		=> null,
					'valid'		=> null,
					'length'	=> null
				),
				'1' => array(
					'name'		=> 'name_of_training',
					'type'		=> 'text',
					'valid'		=> 'text',
					'maxlength'	=> '200',
					'value'		=> $row['name_of_training'],
					'class'		=> 'cvOn cvNotReq cvMaxLen',
					'id'		=> 'name_of_training'
				),
				'2' => array(
					'name'		=> 'name_of_institute',
					'type'		=> 'text',
					'valid'		=> 'text',
					'maxlength'	=> '200',
					'value'		=> $row['name_of_institute'],
					'class'		=> 'cvOn cvNotReq cvMaxLen',
					'id'		=> 'name_of_institute'
				),
				'3' => array(
					'name'		=> 'division',
					'type'		=> 'select',
					'valid'		=> 'text',
					'option'	=> $division,
					'selected'	=> $row['division'],
					'class'		=> 'cvOn cvNotReq',
					'id'		=> 'division'
				),
				'4' => array(
					'name'		=> 'from_dt',
					'type'		=> 'date',
					//the below line is updated towards the new HTML 5 Format Datpicker applied to the from_date field to retrive the data from the database and populate the datepicker in yyyy-mm-dd format - Akash[17-05-2023]
					'value' 	=> 	isset($row['from_dt']) ? (date_create_from_format('d/m/Y H:i:s', $row['from_dt']) !== false ? date_create_from_format('d/m/Y H:i:s', $row['from_dt'])->format('Y-m-d') : null) : null,
					'class'		=>	'form-control input-field cvcalyear cvOn cvNotReq', // added class cvcalyear by shankhpal
					'id'		=> 'from_dt'
				),
				'5' => array(
					'name'		=> 'to_dt',
					'type'		=> 'date',
					//the below line is updated towards the new HTML 5 Format Datpicker applied to the from_date field to retrive the data from the database and populate the datepicker in yyyy-mm-dd format - Akash[17-05-2023]
					'value' 	=> 	isset($row['from_dt']) ? (date_create_from_format('d/m/Y H:i:s', $row['from_dt']) !== false ? date_create_from_format('d/m/Y H:i:s', $row['from_dt'])->format('Y-m-d') : null) : null,
					'class'		=>	'form-control input-field cvcalyear cvOn cvNotReq	', // added class cvcalyear by shankhpal
					'id'		=> 'to_dt'
				),

				//This Block is added to show the calculated total in table - Akash [17-05-2023]
				'6' => array(
					'name'		=> 'total',
					'type'		=> 'text',
					'valid'		=> 'text',
					'value'		=> $row['total'], 
					'class'		=> 'cvOn cvNotReq tot cvcalyear', 
					'id'		=> 'total'
				)

			);
			$loopC++;

		}


		$tableForm[] = $tableD;
		$jsonTableForm = json_encode($tableForm);

		$resultIndex = count($result); 
		return array($result[$resultIndex-$resultIndex],$jsonTableForm);
	}

	public function saveFormDetails($chemist_id,$forms_data) {

		$result = false;
		$dataValidatation = $this->postDataValidation($forms_data);
		$date = date('Y-m-d H:i:s');

		if($dataValidatation == 1 ){

			$section_form_details = $this->sectionFormDetails($chemist_id);
			$id = $section_form_details[0]['id'];
			$status = 'saved';
			$created = date('Y-m-d H:i:s');
			$CustomersController = new CustomersController;

			$row_count = count($forms_data['division']);
			
			$section_id = $_SESSION['section_id'];
			$Dmi_chemist_comment = TableRegistry::getTableLocator()->get('DmiChemistComments');

			$currCommentRecord =  $Dmi_chemist_comment->find('all',array('conditions'=>array('customer_id'=>$chemist_id,'section_id'=>$section_id,'is_latest'=>'1')))->first();
			if(!empty($currCommentRecord)){
				$commentid = $currCommentRecord['id'];
				$reply_to = $currCommentRecord['comment_by'];
			}else{
				$commentid ='';
				$reply_to = '';
			}


			if(!empty($reply_to))
			{
				$comment = htmlentities($forms_data['reffered_back_comment'], ENT_QUOTES);
				
				$newEntity = $Dmi_chemist_comment->newEntity(array(
					'id'=>$commentid,
					'reply_by'=>$chemist_id,
					'reply_to'=>$reply_to,
					'reply_comment'=>$comment,
					'reply_dt'=>date('Y-m-d H:i:s')			
				));
				$Dmi_chemist_comment->save($newEntity);
				
			}

			$this->deleteAll(array('customer_id'=>$chemist_id,'is_latest'=>1));

			for ($i=0;$i<$row_count;$i++) {

				$table = 'DmiDivisionGrades';
				$division = $forms_data['division'][$i];
				//$division = $this->dropdownSelectInputCheck($table,$post_input_request);//calling library function

				$name_of_training = htmlentities($forms_data['name_of_training'][$i], ENT_QUOTES);
				$name_of_institute = htmlentities($forms_data['name_of_institute'][$i], ENT_QUOTES);

				// In the both the from_dt and to_dt library function is changed from "changeDateFormat" to "changeDateFormatNew"
				// Reason:: to handle the case where the html5 date input is applied - Akash [17-05-2023]
				$from_dt = $CustomersController->Customfunctions->changeDateFormatNew($forms_data['from_dt'][$i]);
				$to_dt = $CustomersController->Customfunctions->changeDateFormatNew($forms_data['to_dt'][$i]);
				
				//This new field is added to save the total no of days of training in the database as Varchar - Akash [17-05-2023]
				$total = htmlentities($forms_data['total'][$i], ENT_QUOTES); 

				//to split the total experince into month and year to save in separate integer fields in database - Akash [17-05-2023]
				// Extracting years and months
				$pattern = '/(\d+)\s+Y?,\s+(\d+)\s+M?/i';
				$matches = [];
				if (preg_match($pattern, $total, $matches)) {
					$total_training_year = (int)$matches[1]; // Extracted years
					$total_training_month = (int)$matches[2]; // Extracted months
				} elseif (preg_match('/(\d+)\s+Months?/i', $total, $matches)) {
					$total_training_year = 0; // No years specified
					$total_training_month = (int)$matches[1]; // Extracted months
				} else {
					$total_training_year = null;
					$total_training_month = null;
				}

				// Extracting years, months, and days
				$pattern = '/(\d+)\s+Y?,\s+(\d+)\s+M?(\s+(\d+)\s+D)?/i';
				$matches = [];

				if (preg_match($pattern, $total, $matches)) {
					$training_year = (int)$matches[1]; // Extracted years
					$training_month = (int)$matches[2]; // Extracted months
					$training_days = ($training_year * 365) + ($training_month * 30); // Assuming 1 year = 365 days, 1 month = 30 days

					if (isset($matches[4])) {
						$training_days += (int)$matches[4]; // Add extracted days
					}
				} elseif (preg_match('/(\d+)\s+Months?(\s+(\d+)\s+D)?/i', $total, $matches)) {
					$training_year = 0; // No years specified
					$training_month = (int)$matches[1]; // Extracted months
					$training_days = $training_month * 30; // Assuming 1 month = 30 days

					if (isset($matches[3])) {
						$training_days += (int)$matches[3]; // Add extracted days
					}
				} elseif (preg_match('/(\d+)\s+D/i', $total, $matches)) {
					$training_year = 0; // No years specified
					$training_month = 0; // No months specified
					$training_days = (int)$matches[1]; // Extracted days
				} else {
					$training_year = null;
					$training_month = null;
					$training_days = null;
				}


				$DmiChemistTrainingDetailsEntity = $this->newEntity(array(

					'customer_id'=>$chemist_id,
					'name_of_training'=>$name_of_training,
					'name_of_institute'=>$name_of_institute,
					'division'=>$division,
					'from_dt'=>$from_dt,
					'to_dt'=>$to_dt,
					'form_status'=>$status,					
					'created'=>$created,
					'modified'=>date('Y-m-d H:i:s'),
					'is_latest'=>1,
					'total'=> $total, 			//This new field is added to save the total no of days of training in the database as Varchar - Akash [17-05-2023]
					'training_days'=>$training_days,	//This new field is added to save the total numbers of day seprately in the database as integer - Akash [17-05-2023]
					'training_month'=>$training_month,	//This new field is added to save the total numbers of month seprately in the database as integer - Akash [17-05-2023]
					'training_year'=>$training_year	//This new field is added to save the total numbers of year seprately in the database as integer - Akash [17-05-2023]

					
				));

				if($this->save($DmiChemistTrainingDetailsEntity)){

					$return = "true";
				}
			}
		} else {
			$return = "false";
		}

		if($return = "true"){

			return true;

		}else{
			return false;
		}


	}


	// To save 	RO/SO referred back  and MO reply comment
	public function saveReferredBackComment ($customer_id,$forms_data,$comment,$comment_upload,$reffered_back_to) {

		// Import another model in this model

		$logged_in_user = $_SESSION['username'];
		$current_level = $_SESSION['current_level'];

		$DmiOldApplicationDetails = TableRegistry::getTableLocator()->get('DmiOldApplicationCertificateDetails');

		$CustomersController = new CustomersController;
		$oldapplication = $CustomersController->Customfunctions->isOldApplication($customer_id);

		//added date function on 31-05-2021 by Amol to convert date format, as saving null
		$created_date = $CustomersController->Customfunctions->changeDateFormat($forms_data['created']);

		if($reffered_back_to == 'Level3ToApplicant'){

			$form_status = 'referred_back';
			$reffered_back_comment = $comment;
			$reffered_back_date = date('Y-m-d H:i:s');
			$rb_comment_ul = $comment_upload;
			$ro_current_comment_to = 'applicant';
			$mo_comment = null;
			$mo_comment_date = null;
			$mo_comment_ul = null;
			$ro_reply_comment = null;
			$ro_reply_comment_date = null;
			$rr_comment_ul = null;

		}elseif($reffered_back_to == 'Level1ToLevel3'){

			$form_status = $forms_data['form_status'];
			$reffered_back_comment = null;
			$reffered_back_date = null;
			$rb_comment_ul = null;
			$ro_current_comment_to = null;
			$mo_comment = $comment;
			$mo_comment_date = date('Y-m-d H:i:s');
			$mo_comment_ul = $comment_upload;
			$ro_reply_comment = null;
			$ro_reply_comment_date = null;
			$rr_comment_ul = null;

		}elseif($reffered_back_to == 'Level3ToLevel'){

			$form_status = $forms_data['form_status'];
			$reffered_back_comment = $forms_data['reffered_back_comment'];
			$reffered_back_date = $forms_data['reffered_back_date'];
			$rb_comment_ul = $forms_data['rb_comment_ul'];
			$ro_current_comment_to = 'mo';
			$mo_comment = null;
			$mo_comment_date = null;
			$mo_comment_ul = null;
			$ro_reply_comment = $comment;
			$ro_reply_comment_date = date('Y-m-d H:i:s');
			$rr_comment_ul = $comment_upload;

		}

		$newEntity = $this->newEntity(array(

			'customer_id'=>$customer_id,
			'name_of_training'=>$forms_data['name_of_training'],
			'name_of_institute'=>$forms_data['name_of_institute'],
			'division'=>$forms_data['division'],
			'from_dt'=>$forms_data['from_dt'],
			'to_dt'=>$forms_data['to_dt'],
			'marks'=>$forms_data['marks'],
			'form_status'=>$forms_data['form_status'],
			'is_latest'=>$forms_data['is_latest'],
			'created'=>$created_date,
			'modified'=>date('Y-m-d H:i:s'),
			'form_status'=>$form_status,
			'reffered_back_comment'=>$reffered_back_comment,
			'reffered_back_date'=>$reffered_back_date,
			'rb_comment_ul'=>$rb_comment_ul,
			'user_email_id'=>$_SESSION['username'],			
			'current_level'=>$current_level,
			'ro_current_comment_to'=>$ro_current_comment_to,	
			'mo_comment'=>$mo_comment,
			'mo_comment_date'=>$mo_comment_date,
			'mo_comment_ul'=>$mo_comment_ul,
			'ro_reply_comment'=>$ro_reply_comment,
			'ro_reply_comment_date'=>$ro_reply_comment_date,
			'rr_comment_ul'=>$rr_comment_ul,
			'total'=> $forms_data['total'], 			//This new field is added to save the total no of days of training in the database as Varchar - Akash [17-05-2023]
			'training_days'=>$forms_data['training_days'],	//This new field is added to save the total numbers of day seprately in the database as integer - Akash [17-05-2023]
			'training_month'=>$forms_data['training_month'],	//This new field is added to save the total numbers of month seprately in the database as integer - Akash [17-05-2023]
			'training_year'=>$forms_data['training_year']	//This new field is added to save the total numbers of year seprately in the database as integer - Akash [17-05-2023]

		));

		if($this->save($newEntity)){

			if($oldapplication == 'yes'){

					$old_certificate_details = $DmiOldApplicationDetails->oldApplicationCertificationDetails($customer_id);

				$DmiOldApplicationDetailsEntity = $DmiOldApplicationDetails->newEntity(array(
										'id'=>$old_certificate_details['id'],
										'old_certificate_pdf'=>$old_certificate_details['old_certificate_pdf'],
										'old_application_docs'=>$old_certificate_details['old_application_docs'],
				));

				if($DmiOldApplicationDetails->save($DmiOldApplicationDetailsEntity)){ return true;  }

			}else{ return true; }
		}

	}


	public function postDataValidation($forms_data){

			return true;

	}

} ?>
