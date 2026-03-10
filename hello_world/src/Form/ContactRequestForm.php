<?php

namespace Drupal\hello_world\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

class ContactRequestForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'hello_world_contact_request_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['personal_info'] = [
      '#type' => 'details',
      '#title' => $this->t('Personal information'),
      '#open' => TRUE,
    ];

    $form['personal_info']['full_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Full name'),
      '#required' => TRUE,
      '#maxlength' => 100,
    ];

    $form['personal_info']['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email'),
      '#required' => TRUE,
    ];

    $form['request_info'] = [
      '#type' => 'details',
      '#title' => $this->t('Request details'),
      '#open' => TRUE,
    ];

    $form['request_info']['subject'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Subject'),
      '#required' => TRUE,
      '#maxlength' => 150,
    ];

    $form['request_info']['message'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Message'),
      '#required' => TRUE,
    ];

    $form['request_info']['contact_method'] = [
      '#type' => 'select',
      '#title' => $this->t('Preferred contact method'),
      '#options' => [
        'email' => $this->t('Email'),
        'phone' => $this->t('Phone'),
        'whatsapp' => $this->t('WhatsApp'),
      ],
      '#empty_option' => $this->t('- Select -'),
      '#required' => TRUE,
    ];

    $form['extra_info'] = [
      '#type' => 'details',
      '#title' => $this->t('Extra information'),
      '#open' => TRUE,
    ];

    $form['extra_info']['internal_note'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Internal note'),
      '#description' => $this->t('Visible only to authenticated users.'),
      '#access' => $this->currentUser()->isAuthenticated(),
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Send request'),
      '#button_type' => 'primary',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $full_name = trim($form_state->getValue('full_name'));
    $email = trim($form_state->getValue('email'));
    $subject = trim($form_state->getValue('subject'));
    $message = trim($form_state->getValue('message'));

    if (strlen($full_name) < 3) {
      $form_state->setErrorByName('full_name', $this->t('Full name must be at least 3 characters long.'));
    }

    if (!\Drupal::service('email.validator')->isValid($email)) {
      $form_state->setErrorByName('email', $this->t('Please enter a valid email address.'));
    }

    if (strlen($subject) < 5) {
      $form_state->setErrorByName('subject', $this->t('Subject must be at least 5 characters long.'));
    }

    if (strlen($message) < 10) {
      $form_state->setErrorByName('message', $this->t('Message must be at least 10 characters long.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->messenger()->addStatus($this->t('Your request has been submitted successfully.'));

    $form_state->setRedirect('hello_world.content');
  }

}