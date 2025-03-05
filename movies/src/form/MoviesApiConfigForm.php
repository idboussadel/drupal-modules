<?php

namespace Drupal\movies\Form;

use Drupal\Core\Form\FormBase;

class MoviesApiConfigForm extends FormBase{
    const MOVIES_API_CONFIG = 'movies.api_config_page::values';

    public function getFormId(){
        return 'movies_api_config_form';
    }

    public function buildForm(array $form, \Drupal\Core\Form\FormStateInterface $form_state){
        $values = \Drupal::state()->get(self::MOVIES_API_CONFIG);
        $form = [];

        $form['api_base_url'] = [
            '#type' => 'textfield',
            '#title' => $this->t('API Base URL'),
            '#description' => $this->t('Enter the base URL of the API'),
            '#required' => TRUE,
            '#default_value' => $values['api_base_url'] ?: '',
        ];

        $form['api_key'] = [
            '#type' => 'textfield',
            '#title' => $this->t('API Key'),
            '#description' => $this->t('Enter the API key'),
            '#required' => TRUE,
            '#default_value' => $values['api_key'] ?? '',
        ];

        $form['actions']['#type'] = 'actions';
        $form['actions']['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Save configuration'),
            '#button_type' => 'primary',
        ];

        return $form;
    }

    public function submitForm(array &$form, \Drupal\Core\Form\FormStateInterface $form_state){
        $subbmitted_values = $form_state->cleanValues()->getValues();
        \Drupal::state()->set(self::MOVIES_API_CONFIG, $subbmitted_values);

        $messenger  = \Drupal::messenger();
        $messenger->addMessage($this->t('Movies API configuration saved successfully'));
    }
}