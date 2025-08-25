<?php
class Sales_model extends CI_Model {
	var $retVal = array();
    var $do_update = 0;
    protected $field_mapping = '';
    protected $related_tables = '';
    protected $ids_not_included = array(); //holder of already verified sales_id from main gong to sales
	public $show_less_field = 0; // this is called from main_model just to get the count and not all the details from the sales
	public function __construct()
	{
        $this->field_mapping ='';
        $this->related_tables = '';
		$this->load->database();
	}

    public function get_lookup_from_main_table($crm_id){
        $retValArr = array();
        $crmDetails = $this->my_utils->get_crm_by_id($crm_id);
        $newconn = $this->my_utils->change_conn($crmDetails);
        //all CRM should have the same lookup structure
        //lu_cat, lu_desc, lu_code is very important
        $result = $newconn->from('lookup')->where('is_active',1)->order_by('order_by')->get()->result_array();

        foreach($result as $details){
            $retValArr[$details['lu_cat']][$details['lu_code']] = $details['lu_desc'];
        }
        $newconn->close();
        return $retValArr;

    }

    //consolidate both sales from system_db and selected crm
    //identify if the sales from main_table is already verified then update the field from system_db
    public function get_sales($crm_id,$calldate=''){
        $crmDetails = $this->my_utils->get_crm_by_id($crm_id);

        //sales_details
        $mainSales['VERIFIED'] = $this->get_verified_sales($crm_id,$crmDetails,$calldate);

        //get new sales and dont include ids gathered from verified_sale
        $mainSales['NEW'] = $this->get_sales_from_main_table($crm_id,$crmDetails,$calldate);



        /*
         * TODO
         * Consolidate both sale from $sales, but need to identify what is available,currenlty verified or verified!
         *
        */

        $sales = $mainSales;
        return $sales;
    }

    //sales/syste_db
    public function get_sales_from_sales_table($crm_id,$crmDetails=''){
        if(empty($crmDetails))
            $crmDetails = $this->my_utils->get_crm_by_id($crm_id);

    }

    //main_table/selected crm
    public function get_sales_from_main_table($crm_id,$crmDetails='',$calldate=''){
        if(empty($crmDetails))
            $crmDetails = $this->my_utils->get_crm_by_id($crm_id);

        $newconn = $this->my_utils->change_conn($crmDetails);

        if(empty($calldate))
            $calldate = date('Y-m-d');

        $coverDate =date('Y-m-d',strtotime('+1 day'.$calldate));

        
		//if set to show less fields then get just the id this is to prevent the loading of data to much!
		//this is set from main_models in creating the dashboard
		if($this->show_less_field == 1)
			$select_fields = 'id';
		else{
			//if is_main_list then it called from sales_list then only this fields are needed
			$select_fields =$crmDetails['id_field'] . ','.
				$crmDetails['firstname_field'] . ' AS firstname,' .
				$crmDetails['lastname_field'].'  AS lastname ,'.
				$crmDetails['calldate_field'] . ','.
				$crmDetails['agent_field'];
		}

        $newconn->select($select_fields)->from($crmDetails['main_table'])
                ->where($crmDetails['calldate_field'] .' >=',$calldate)
                ->where($crmDetails['calldate_field'] .' <',$coverDate)
                ->where($crmDetails['callresult_field'],$crmDetails['sale_tag']);

        //array holder of exising table_recid from sales to main tabels
        //if found then dont include in query!!
        if(count($this->ids_not_included) >0){
            $newconn->where_not_in('id',$this->ids_not_included);
        }

        $sales = $newconn->get()->result_array();
		
        $newconn->close();

        return $sales;

    }

    public function get_verified_sales($crm_id,$crmDetails='',$calldate=''){
		$this->ids_not_included = array();
        $retValArr = array();
        $crmDetails = $this->my_utils->get_crm_by_id($crm_id);

        if(empty($calldate))
            $calldate = date('Y-m-d');

        $coverDate =date('Y-m-d',strtotime('+1 day'.$calldate));

        $whereArr = array('crm_code'=>$crmDetails['crm_code'],
                        'calldate >='=>$calldate,
                        'calldate <'=>$coverDate);
						
		

		if($this->show_less_field == 1)
			$this->db->select('table_recid');
			
        $result = $this->db->from('sales')->where($whereArr)->get()->result_array();

        foreach($result as $details)
            $this->ids_not_included[] = $details['table_recid'];

        return $result;
    }


    public function set_field_arr_to_str($crmDetails,$related_table_id=0){
        $select_fields_arr = $this->get_field_mappings($crmDetails['crm_code'],$related_table_id);
        $select_fields = '';
        foreach($select_fields_arr as $fieldDetails){
            $select_fields .= (!empty($select_fields) ? ',' : '') . $fieldDetails['field_name'];
        }

        return $select_fields;
    }

    //crm id and main_table_id
    public function get_sales_from_main_table_field_mapping($crm_id,$main_table_id){
        $crmDetails = $this->my_utils->get_crm_by_id($crm_id);
		
        $newconn = $this->my_utils->change_conn($crmDetails);


        //if is_main_list then it called from sales_list then only this fields are needed
        $select_fields =$crmDetails['id_field'] . ','.
            $crmDetails['firstname_field'] . ',' .
            $crmDetails['lastname_field'].','.
            $crmDetails['calldate_field'] . ','.
            $crmDetails['agent_field'];


        $select_fields .= ','.$this->set_field_arr_to_str($crmDetails,0);


        /*TODO
        * Add checker if no field mapping has been set
        */

        $sales = $newconn->select($select_fields)->from($crmDetails['main_table'])
            ->where($crmDetails['id_field'],$main_table_id)
            //->where($crmDetails['callresult_field'],$crmDetails['sale_tag'])
            ->get()->result_array();


        $newconn->close();

        return $sales;
    }

    public function get_agent_list_in_main_table($newconn,$crmDetails,$lowerusername=1, $fname_only = false){
        $agengList = array();

        //display even old or inactive agent!
        $result = $newconn->from($crmDetails['user_table'])->get()->result_array();

        foreach($result as $details){
            //user_name and user_password shall be used to all CAMPAIGN
				
			$username = ($details['user_name']);
			
            if($lowerusername){
				$username = strtolower($details['user_name']);
			}
			
			if($fname_only){
				//2020-03-03 get only the firstname of the agent list 
				//started from CHALLENGER
				$agengList[$username] = $details['firstname'];
			}else{
				$agengList[$username] = $details['firstname']. ' ' . $details['lastname'];	
			}
            
        } 
		
        return $agengList;

    }

    //the difference between this function and get_sales_details_by_id() is the table where it getting the data!
    public function get_sale_main_details($sale_id){
        $result = $this->db->from('sales')->where('id',$sale_id)->get()->result_array();
        return $result[0];
    }

    public function get_sales_details_by_id($sale_id,$crm_id){

        $retValArr = array();
        $result = $this->db->from('sales_details')
                            ->where('sales_id',$sale_id)
                            ->get()
                            ->result_array();

        foreach($result as $details){

            //group by according to the related_table_id
            //0 is always main!
            $retValArr['SALES'][$details['related_table_id']][$details['foreign_table_id']][$details['field_name']] = $details;
        }

        $relatedTblDetails= $this->set_field_mappings_for_view($crm_id);
        $retValArr['FIELD_MAPPINGS'] = $relatedTblDetails['FIELD_MAPPINGS'];
        $retValArr['TABLE_DETAILS'] = $relatedTblDetails['TABLE_DETAILS'];
        return $retValArr;
    }

    public function set_field_mappings_for_view($crm_id){
        $retValArry  = array();
        $crmDetails = $this->my_utils->get_crm_by_id($crm_id);
        $related_tables = $this->get_related_tables($crmDetails);

        //field mapping for main_table
        $field_mapping['FIELD_MAPPINGS'][0] = $this->get_field_mappings($crmDetails['crm_code']);

        foreach($related_tables as $details){
            //set the id of related_table as index
            $field_mapping['FIELD_MAPPINGS'][$details['id']] = $this->get_field_mappings($crmDetails['crm_code'],$details['id']);
            $field_mapping['TABLE_DETAILS'][$details['id']]= $details;
        }

        //sample return
        //[0] = array of field_mappings from main table
        //[1] = array of field_mappings from related table
        return $field_mapping;
    }


    //add or update sales from sales table and corresponding tables
    public function init_sale($crm_id,$main_table_id,$calldate){
        $sale_id = $this->check_if_main_table_id_exist($crm_id,$main_table_id,$calldate);
		
        $sale_id = $this->insert_sales_details($crm_id,$main_table_id,$sale_id);
        return $sale_id;
    }

    //check if the main_table_id is already taken from the same date
    //if yes then update the details
    //if no then insert the details
    public function check_if_main_table_id_exist($crm_id,$main_table_id,$calldate){
        $crmDetails = $this->my_utils->get_crm_by_id($crm_id);
        $calldate1Day = date('Y-m-d',strtotime('+1 day'.$calldate));
        $result = $this->db->select('id')->from('sales')
            ->where('calldate >=',$calldate)
            ->where('calldate <',$calldate1Day)
            ->where('table_recid',$main_table_id)
            ->where('crm_code',$crmDetails['crm_code'])
            ->get()->result_array();

        return (isset($result[0]['id']) ? $result[0]['id'] : 0);
    }

    //this function dictates the system if will going to update or insert depends on the action set!
    //if sales_id is set to anything not 0 it will do update but the sales_details table will check it first if the field is non existing
    //then it will add event do_update is set to 1!
    public function insert_sales_details($crm_id,$main_table_id,$sales_id=0){
        $logged_id=$this->session->userdata['username'];
        //mask name is used to hide the information of the SV
        $mask_name=$this->session->userdata['mask_name'];

        $crmDetails = $this->my_utils->get_crm_by_id($crm_id);
        $newconn = $this->my_utils->change_conn($crmDetails);
        $sales = $this->get_sales_from_main_table_field_mapping($crm_id,$main_table_id);
        $crmAgentList = $this->get_agent_list_in_main_table($newconn,$crmDetails);

        ///////////////////////////save sales/system_db///////////////////////

        //$sales_id is the id from sales/system_db will be used on the whole process
        $this->do_update=1;//do update
        if($sales_id==0){
            $insertData = array(
                'crm_code'=>$crmDetails['crm_code'],
                'table_recid'=>$main_table_id,
                'calldate'=>$sales[0][$crmDetails['calldate_field']],
                'user_id'=>$logged_id,
                'sv_code'=>$mask_name,
                'status'=>0,
                'is_locked'=>1, //set locked
                'firstname'=>$sales[0][$crmDetails['firstname_field']],
                'lastname'=>$sales[0][$crmDetails['lastname_field']],
                'agent_id'=>$sales[0][$crmDetails['agent_field']],
                'agent_name'=>$crmAgentList[strtolower($sales[0][$crmDetails['agent_field']])],
            );
            $this->db->insert('sales',$insertData);

            $sales_id = $this->db->insert_id();
            $this->do_update=0;
        }


        ///////////////////////////END OF save sales/system_db///////////////////////


        /////////////////////////save sales_details///////////////////////

        $this->update_sales_details($crmDetails,$sales_id,$sales[0]);

        /////////////////////////END OF sales_details///////////////////////



        ///////////////////////save related tables///////////////////////

        $relatedTables = $this->get_related_tables($crmDetails);


        foreach($relatedTables as $details){
            $selectFields ='id,';
            $selectFields .= $this->set_field_arr_to_str($crmDetails,$details['id']);
            $sales = $newconn->select($selectFields)
                ->from($details['foreign_table_name'])
                ->where($details['foreign_field_name'],$main_table_id)
                ->get()->result_array();

            foreach($sales as $sale){
                $this->update_sales_details($crmDetails,$sales_id,$sale,$details['id']);
            }

        }

        ///////////////////////END OF save related tables///////////////////////

        return $sales_id;
    }

    public function update_sales_details($crmDetails,$sales_id,$saleDetails,$relatedTableId=0){
        $select_fields_arr = $this->get_field_mappings($crmDetails['crm_code'],$relatedTableId);

        foreach($select_fields_arr as $fieldDetails){
            $salesDetails['sales_id'] = $sales_id;

            $foreign_id = 0;
            if($relatedTableId!=0)//applicable only for all foreign_tables
                $foreign_id = $saleDetails['id']; //this is the id/foreign_table (e.g: id/supplementary) and not the baserecid!!!

            $salesDetails['foreign_table_id'] = $foreign_id;
            $salesDetails['field_name'] = $fieldDetails['field_name'];
            $salesDetails['original_value'] = $saleDetails[$fieldDetails['field_name']];
            $salesDetails['related_table_id'] = $relatedTableId;

            if($this->do_update==1){
                $whereArr = array('sales_id'=>$sales_id,'field_name'=>$fieldDetails['field_name'],'related_table_id'=>$relatedTableId,'foreign_table_id'=>$foreign_id);

                //this is to check if there are new child record for this sale, if found then it will add instead of update
                $doInsert=0;
                #if($relatedTableId!=0){
                    $reCheck = $this->db->select('count(*) as CTR')->from('sales_details')->where($whereArr)->get()->result_array();

                    //if found the do insert
                    if($reCheck[0]['CTR']==0){
                        $doInsert = 1;
                    }
              #  }

                if($doInsert==1){
                    $this->db->insert('sales_details',$salesDetails);
                }else{
                    $this->db->update('sales_details',$salesDetails,$whereArr);
				}
            }else{
                $this->db->insert('sales_details',$salesDetails);
            }


        }
    }


    //get all the fields set from the seeting
    // $relatedTblId will be the basis on what fields to be extracted
    //$relatedTblId can any related_table (supplementary,card_details, etc. . . )
    //0 is default pertaining to main_table
    public function get_field_mappings($crmCode,$relatedTblId=0){
        $retValArr = array();
        //to avoid to much query
        if(isset($this->field_mapping[$crmCode][$relatedTblId])){
//            echo "i use this many times <br>";
            return $this->field_mapping[$crmCode][$relatedTblId];
        }else{
//            echo "i use this once <br>";
            $retValArr = $this->db->from('field_mapping')
                ->where('crm_code',$crmCode)
                ->where('related_table_id',$relatedTblId)
				->where('is_active',1)
				->order_by('order_by')
                ->get()->result_array();
//            echo $this->db->last_query() . '<br>';
            $this->field_mapping[$crmCode][$relatedTblId] = $retValArr;
            return $retValArr;
        }

    }



    //get the related tables information then insert/update all the information if needed
    public function get_related_tables($crmDetails){
        if(!empty($this->related_tables)){
//            echo "i used this many times <br>";
            return $this->related_tables;
        }else{
            $result = $this->db->from('related_tables')
                ->where('crm_code',$crmDetails['crm_code'])
                ->order_by('order_by')
                ->get()->result_array();
            $this->related_tables = $result;
//            echo "i used this onces <br>";
            return $result;
        }

    }

    //set it as the key is the id
    public function set_related_table_in_array($related_tables){
        $retValArr =array();
        foreach($related_tables as $details){
            $retValArr[$details['id']]  = $details;
        }

        return $retValArr;
    }


    /*
     * SAVE STARTS HERE (main table, related table and updates from sales_details)
     *
     * */
    //fields multi naming structure
    //foreign_table_id[baserecid/recid][field_name]
    //if recid id it came from related_id = 0 then get the id from table_recid/sales <-- recid of main_table (contact_list)
    //e.g: 1[123][firstname] <--- supple or any related table
    //e.g: 0[456][c_firstname] <-- main table [contact_list]
    public function save_sales($sale_id){
        $pdata = $this->input->post();
        $saleMainDetails = $this->get_sale_main_details($sale_id);

        //any non-related fields should be unset in $pdata! and simply set it to other variables

        //unset hiddent fields
        $hidden_fields = $pdata['hidden'];
        unset($pdata['hidden']);
        //set crm_id from hidden fields;
        $crm_id = $hidden_fields['crm_id'];

        //SV REMARKS
        //data from here will be used for sales table and remarks table
        $svRemarks = $pdata['SV'];
        unset($pdata['SV']);

        $crmDetails = $this->my_utils->get_crm_by_id($crm_id);
        $newconn = $this->my_utils->change_conn($crmDetails);


        $relatedTables = $this->get_related_tables($crmDetails);
        //re-initialize! set the id/related_tables to be used!
        $relatedTables = $this->set_related_table_in_array($relatedTables);

        //$table_id is from id/related_tables
        foreach($pdata as $table_id=>$related_table_ids){

            //$related_id is the id from main table or from foreign_table (e.g: baserecid/supplementary or id/contact_list)
            //since we set 0 to the sales_details.php if the table is main! we can easily get the real id/contact_list for this
            //since we have the sale_id we can use it to gather the infromation and it stored from here : $saleMainDetails
            foreach($related_table_ids as $orig_related_id=>$field_names){

                //if the related_id is 0 then get the table_recid so that you can update the details from the main table!!
                if($orig_related_id == 0 )
                    $related_id = $saleMainDetails['table_recid'];
                else
                    $related_id = $orig_related_id;

                //initialize array to that holds update fields for the related tables [main], and any foreign tables
                //every after related_table
                $updateArr = array();
                //whereArr for main and related table
                $whereArr = array();

                //whereArrSD for sales_details where
                $whereArrSD = array();

                $fieldID ='';

                //set the field_id
                //for both update main or foreign table the reference is ID
                //e.g: supplementary im not using BASERECID because this is general to all rows saved in the table
                //so better to use the unique ID
                $fieldID = 'id';

                $whereArr = array($fieldID=>$related_id);

                //set the affected table from main or foreign table!!
                $tableName = ($table_id==0) ? $crmDetails['main_table'] : $relatedTables[$table_id]['foreign_table_name'];;

                foreach($field_names as $field_name=>$value){
                    //if the value is empty then nothing to update
                    //if you allow this the original value from main or related tables will be turn to empty
                    //EMPTY_TAG is different case!!
                    if(empty($value)) continue;

                    //set empty if set as EMPTY_TAG from sv
                    if($value == EMPTY_TAG)
                        $value = '';

                    $updateArr[$field_name] = $value;

                    /*
                     * UPDATE of sales_details start here because one by one process
                     * compare to main and related table it used one whole row!
                     * */
                    $whereArrSD = array('sales_id'=>$sale_id,'field_name'=>$field_name,'foreign_table_id'=>$orig_related_id);
                    $updateArrSD = array('new_value'=>$value);
                    $this->db->update('sales_details',$updateArrSD,$whereArrSD);
//                    echo $this->db->last_query();
                    /*
                     * TODO
                     * ADD here the update logs for each field for sales details
                     * this is for tracking purposes!!
                     * */


                }

                //EXECUTE UPDATE for MAIN TABLE or RELATED TABLE
                //catcher if there is any field to update then do it! else do nothing!
                if(count($updateArr) > 0){
					
					if($orig_related_id == 0 ){
						//valid
						$saleTag = $crmDetails['sale_tag'];
						
						//PENDING!!					
						if($svRemarks['status']!='1')
							$saleTag = $saleTag .'-PE';
							
						$updateArr[$crmDetails['callresult_field']] = $saleTag;
					}
					
                    $newconn->update($tableName,$updateArr,$whereArr);
                }

            }
        }

        $this->update_sales_and_remarks($sale_id,$svRemarks,$saleMainDetails);
    }

    public function update_sales_and_remarks($sale_id,$svRemarks,$saleMainDetails){
		$date_now = date('Y-m-d H:i:s');
        $logged_id=$this->session->userdata['username'];
        $updateArr = array(
                'is_locked'=>0,
                'status'=>$svRemarks['status'],
                'alert'=>$svRemarks['alert'],
                'verified_date'=>$date_now
            );
        $whereArr = array('id'=>$sale_id);
        $this->db->update('sales',$updateArr,$whereArr);


        /*REMARS*/
        $insertArr = array(
            'crm_code'=>$saleMainDetails['crm_code'],
            'table_recid'=>$saleMainDetails['table_recid'],
            'remarks'=>$svRemarks['remarks'],
            'alert'=>$svRemarks['alert'],
			'status'=>$svRemarks['status'],
            'user_id'=>$logged_id,
			'mask_name'=>$this->session->userdata['mask_name']
        );

        $this->db->insert('remarks',$insertArr);
    }

    /*
     * SAVE ENDS HERE
     *
     * */
	 
	 
	 /*
	 *SET ALERTS HERE
	 */
	 public function get_alerts(){
		$retValArr = array();
		$result = $this->db->select('sales.*,crm.id AS crm_id')
                        ->from('sales')
                        ->join('crm',' crm.crm_code = sales.crm_code')
                        ->where('sales.alert !=',0)
						->order_by('sales.time_stamp,sales.crm_code')
						->get()->result_array();
						
		foreach($result as $details){
			$retVal[$details['alert']][$details['id']] = $details;
		}
		
		return $retVal;
	 }
	 
	public function get_all_sv(){
		$result = $this->db->select("users.*, CONCAT(users.firstname, ' ' , users.lastname) AS full_name",false)
							->from('users')
							->order_by('firstname')
							->get()
							->result_array();
			
		$retVal = array();
		foreach($result as $details){
			$retVal[strtolower($details['user_name'])] = $details;
		}
		
		return $retVal;
	}

}