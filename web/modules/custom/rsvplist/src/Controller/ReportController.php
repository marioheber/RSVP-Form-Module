<?php
/**
 * @file
 * Contains \Drupal\rsvplist\Controller\ReportController.
 */
namespace Drupal\rsvplist\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;

/**
 * Controller for RSVP List Report
*/
class ReportController extends ControllerBase {

  /**
   * Gets all RSVPs for all nodes.
   *
   * @return array
   */
  protected function load() {
    $select = Database::getConnection()->select('rsvplist', 'r');
    // Join the node table, so we can get the event's name.
    $select->join('node_field_data', 'n', 'r.nid = n.nid');
    // Select these specific fields for the output.
    $select->addField('r', 'name');
    $select->addField('n', 'title');
    $select->addField('r', 'mail');
    $select->addField('r', 'attendee_list_hidden');
    $entries = $select->execute()->fetchAll(\PDO::FETCH_ASSOC);
    return $entries;
  }

  /**
   * Creates the report page.
   *
   * @return array
   *  Render array for report output.
   */
  public function report() {
    $content = array();
    $content['message'] = array(
      '#markup' => $this->t('Below is a list of all Event RSVPs including username, event name, email address and Hidden from list of attendees.'),
    );
    $headers = array(
      t('Name'),
      t('Event'),
      t('Email'),
      t('Hidden from list of attendees'),
    );
    $rows = array();
    foreach ($entries = $this->load() as $entry) {
      // Sanitize each entry.
      $rows[] = array_map('Drupal\Component\Utility\SafeMarkup::checkPlain', $entry);
    }
    $content['table'] = array(
      '#type' => 'table',
      '#header' => $headers,
      '#rows' => $rows,
      '#empty' => t('No entries available.'),
    );
    // Don't cache this page.
    $content['#cache']['max-age'] = 0;
    return $content;
  }

}
