<?php

require_once 'irasdonation.civix.php';
// phpcs:disable
use CRM_Irasdonation_ExtensionUtil as E;
// phpcs:enable

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function irasdonation_civicrm_config(&$config) {
  _irasdonation_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function irasdonation_civicrm_install() {
  _irasdonation_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_postInstall
 */
function irasdonation_civicrm_postInstall() {
  _irasdonation_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_uninstall
 */
function irasdonation_civicrm_uninstall() {
  _irasdonation_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function irasdonation_civicrm_enable() {
  _irasdonation_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_disable
 */
function irasdonation_civicrm_disable() {
  _irasdonation_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_upgrade
 */
function irasdonation_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _irasdonation_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_entityTypes
 */
function irasdonation_civicrm_entityTypes(&$entityTypes) {
  _irasdonation_civix_civicrm_entityTypes($entityTypes);
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_preProcess
 */
//function irasdonation_civicrm_preProcess($formName, &$form) {
//
//}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_navigationMenu
 */
function irasdonation_civicrm_navigationMenu(&$menu) {
 _irasdonation_civix_insert_navigation_menu($menu, 'Administer/CiviReport', [
   'label' => E::ts('IRAS Donation Report'),
   'name' => 'rias_donation',
   'url' => 'civicrm/irasconfiguration',
   'permission' => 'adminster CiviCRM',
   'operator' => 'OR',
   'separator' => 1,
   'is_active' => 1
 ]);
 _irasdonation_civix_navigationMenu($menu);
}
