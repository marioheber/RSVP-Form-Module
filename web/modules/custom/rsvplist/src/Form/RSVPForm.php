<?php

namespace Drupal\rsvplist\Form;

use Drupal\Core\Database\Database;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;


/**
 * Provides an RSVP form.
 * */

class RSVPForm extends FormBase {
  /**
   * (@inheritdoc)
   *
   */
  public function getFormId() {
      return 'rsvplist_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['message'] = [
      '#type' => 'markup',
      '#markup' => '<div class="result_message"></div>'
    ];
    $node = \Drupal::routeMatch()->getParameter('node');
    $nid = $node->nid->value;
    $form['nid'] = array(
        '#type' => 'hidden',
        '#value' => $nid,
    );
    $form['name'] = array(
        '#title' => t('User name'),
        '#type' => 'textfield',
        '#size' => 25,
        '#description' => t("Your name."),
        '#required' => TRUE,
    );
    $form['email'] = array(
      '#title' => t('Email address'),
      '#type' => 'textfield',
      '#size' => 25,
      '#description' => t("We'll send updates to the email address you provide."),
      '#required' => TRUE,
    );

    $form['attendee_list_hidden'] = array(
      '#type' => 'checkbox',
      '#description' => t("Hidden your name in list of attendees on the event page."),
      '#return_value' => TRUE,
    );
    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('RSVP'),
      '#ajax' => array(
        'callback' => '::setMessage',
        'effect' => 'fade',
      ),
    );
    $form['#attached']['library'][] = 'rsvplist/rsvplist.location';

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $value = $form_state->getValue('email');
    if ($value == !\Drupal::service('email.validator')->isValid($value)) {
        $form_state->setErrorByName('email', t('The email address %mail is not valid.', array('%mail' => $value)));
        return;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());

    $query = \Drupal::database()->insert('rsvplist');
    $query->fields(array(
        'name',
        'mail',
        'nid',
        'attendee_list_hidden',
        'uid',
        'created',
    ));
    $query->values(array(
        $form_state->getValue('name'),
        $form_state->getValue('email'),
        $form_state->getValue('nid'),
        $form_state->getValue('attendee_list_hidden'),
        $user->id(),
        time(),
      )
    );
    $query->execute();

  }

  public function setMessage(array $form, FormStateInterface $form_state) {

    $response = new AjaxResponse();
    $response->addCommand(
      new HtmlCommand(
        '.result_message',
        '<div class="result_top_message">' . t('Thank you for your RSVP, you are on the list for the event.') . '</div>')
    );
    return $response;
  }
}
