<?php

require_once 'CRM/Irasdonation/BAO/IrasDonation.php';
include_once("wp-config.php");
include_once("wp-includes/wp-db.php");

use Civi\Api4\IrasDonation;
use CRM_Irasdonation_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Irasdonation_Form_IrasConfiguration extends CRM_Core_Form {
  public function buildQuickForm() {

    // add form elements
    $this->add(
      'select', // field type
      'favorite_color', // field name
      'Old Reports', // field label
      $this->getColorOptions(), // list of options
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

  public function postProcess() {
    $values = $this->exportValues();
    $options = $this->getColorOptions();
    CRM_Core_Session::setStatus(E::ts('You picked color "%1"', array(
      1 => $options[$values['favorite_color']],
    )));
    parent::postProcess();
  }

  public function getColorOptions() {

    // $params = array('entityID' => $contribution_id, 'custom_34' => 'new val');
    // CRM_Core_BAO_CustomValueTable::setValues($params);
    
    // $options = array(
    //   '' => E::ts('- select -'),
    //   '#f00' => E::ts('Red'),
    //   '#0f0' => E::ts('Green'),
    //   '#00f' => E::ts('Blue'),
    //   '#f0f' => E::ts('Purple'),
    // );
    // foreach (array('1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e') as $f) {
    //   $options["#{$f}{$f}{$f}"] = E::ts('Grey (%1)', array(1 => $f));
    // }
    return $options;
  }

  /**
   * Get the fields/elements defined in this form.
   *
   * @return array (string)
   */
  public function getRenderableElementNames() {
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
