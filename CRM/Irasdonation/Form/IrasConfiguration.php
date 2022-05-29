<?php

use Civi\Api4\IrasDonation;
use CRM_Irasdonation_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Irasdonation_Form_IrasConfiguration extends CRM_Core_Form
{
  public function buildQuickForm()
  {

    // add form elements
    $this->add(
      'select', // field type
      'value', // field name
      'Old Reports', // field label
      $this->getDateOptions(), // list of options
      FALSE // is required
    );
    $this->addButtons(array(
      array(
        'type' => 'submit',
        'name' => E::ts('Generate report'),
        'isDefault' => TRUE,
      ),
    ));

    // export form elements
    $this->assign('elementNames', $this->getRenderableElementNames());
    parent::buildQuickForm();
  }

  public function postProcess()
  {
    $values = $this->exportValues();
    $reportDate = $values["value"];
    echo $reportDate;
    $inList = '1=1';
    if($reportDate==null) $inList = "contrib.id NOT IN (SELECT ci.contribution_id FROM civicrm_iras_donation ci)";
    else $inList = "contrib.id IN (SELECT ci.contribution_id FROM civicrm_iras_donation ci WHERE DATE(ci.create_date) = '$reportDate')";

    // $sql = "SELECT 
		// contrib.id, 
		// cont.sort_name, 
		// cont.external_identifier,
		// contrib.total_amount,
    // contrib.trxn_id,
		// contrib.receive_date
		// FROM civicrm_contribution contrib 
    // INNER JOIN civicrm_contact cont ON cont.id = contrib.contact_id 
    // WHERE $inList
    // AND contrib.contribution_status_id=1 
    // AND cont.external_identifier IS NOT NULL 
    // LIMIT 5000";

    $sql = "SELECT 
		contrib.id, 
		cont.sort_name, 
		cont.external_identifier,
		contrib.total_amount,
    contrib.trxn_id,
		contrib.receive_date
		FROM civicrm_contribution contrib 
    INNER JOIN civicrm_contact cont ON cont.id = contrib.contact_id 
    WHERE $inList
    AND contrib.contribution_status_id=1 
    LIMIT 5000";

    echo $sql;
    
    $result = CRM_Core_DAO::executeQuery($sql, CRM_Core_DAO::$_nullArray);
    $insert = '';
    $total = 0;
    $incer = 0;
    $genDate = date('Y-m-d H:i:s');
    while($val = $result->fetch()) {
      $idType = paseUENNumber($val->external_identifier);
      // echo $idType.PHP_EOL;
      if ($idType > 0) {
        // $contactDonation = $wpdb->get_results( "SELECT cc.id, cc.external_identifier, cc.sort_name FROM civicrm_contact cc WHERE cc.external_identifier is not null", OBJECT );
        $dataBody = [1, $idType, $val->external_identifier, str_replace(',', '', $val->sort_name), null, null, null, null, null, $val->total_amount, date("Ymd", $val->receive_date), $val->trxn_id, 'O', 'Z'];
        
        $insert +=  "INSERT INTO civicrm_iras_donation VALUES ($val->id,'$genDate') WHERE contrib.id NOT IN (SELECT ci.contribution_id FROM civicrm_iras_donation ci);";

        array_push($csvData, $dataBody);
var_dump($dataBody );
        $total += $val->total_amount;
        $incer++;
        //$result =  $wpdb->get_results( "SELECT cc.id, cc.external_identifier FROM civicrm_contact cc WHERE cc.external_identifier is not null", OBJECT );
      }
    }

    // $dataBottom = [2, $incer, $total, null, null, null, null, null, null, null, null, null, null, null];
    // array_push($csvData, $dataBottom);

    // $f = fopen('php://memory', 'w');

    // foreach ($csvData as $row) {
    //   fputcsv($f, $row, ",", '\'', "\\");
    // }
    // fseek($f, 0);
    // header('Content-Type: application/csv');
    // header('Content-Disposition: attachment; filename="report.csv";');
    // fpassthru($f);
    // CRM_Core_DAO::executeQuery($insert, CRM_Core_DAO::$_nullArray);

    // parent::postProcess();
  }

  function paseUENNumber($uen){
    $idTypes =["nric"=>1, "fin"=>2, "uenb"=>5, "uenl"=>6, "asgd"=>8, "itr"=>10, "ueno"=>35 ];
    if($uen==null) return 0;
    switch($uen){
      case ($uen[0]=='S' || $uen[0]=='T') && is_numeric(substr($uen, 1, 7)):
        return $idTypes['nric'];
      case ($uen[0]=='F' || $uen[0]=='G') && is_numeric(substr($uen, 1, 7)):
        return $idTypes['fin'];
      case (strlen($uen)<10 && is_numeric(substr($uen, 0, 8))):
        return $idTypes['uenb'];
      case (((int)substr($uen, 0, 4))>=1800 && ((int)substr($uen, 0, 4)) <= date("Y")) && is_numeric(substr($uen, 4, 5)):
        return $idTypes['uenl'];
      case ($uen[0]=='A' && is_numeric(substr($uen, 1, 7))):
        return $idTypes['asgd'];
      case (is_numeric(substr($uen, 0, 9))):
        return $idTypes['itr'];
      case (($uen[0]=='T' || $uen[0]=='S' || $uen[0]=='R') && is_numeric(substr($uen, 1, 2)) && !is_numeric(substr($uen, 3, 2)) && is_numeric(substr($uen, 5, 4))):
        return $idTypes['ueno'];
      default:
        return 0;			
    }
    
    echo($idTypes['nric']);
  }
  
  public function getDateOptions()
  {
    $sql = 'SELECT cid.create_date FROM civicrm_iras_donation cid GROUP BY cid.create_date ORDER BY cid.create_date DESC';
    $result = CRM_Core_DAO::executeQuery($sql, CRM_Core_DAO::$_nullArray);
    $options = [null => E::ts('- select -')];

    while ($result->fetch()) {
      $options[$result->create_date] = E::ts(date('M d Y H:i:s a', strtotime($result->create_date)), array(1 => $result->create_date));
    }

    return $options;
  }

  /**
   * Get the fields/elements defined in this form.
   *
   * @return array (string)
   */
  public function getRenderableElementNames()
  {
    // The _elements list includes some items which should not be
    // auto-rendered in the loop -- such as "qfKey" and "buttons".  These
    // items don't have labels.  We'll identify renderable by filtering on
    // the 'label'.
    $elementNames = array();
    foreach ($this->_elements as $element) {
      /** @var HTML_QuickForm_Element $element */
      $label = $element->getLabel();
      if (!empty($label)) {
        $elementNames[] = $element->getName();
      }
    }
    return $elementNames;
  }
}
