<?php

namespace Drupal\examodule\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;

/**
 * Class main ExamForm.
 */
class ExamForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'exam-form';
  }

  /**
   * @var string[]
   *  Array of tables headers.
   */
  private $headers = [
    'Year', 'Jan','Feb', 'Mar', 'Q1',
    'Apr', 'May', 'Jun', 'Q2',
    'Jul', 'Aug', 'Sep', 'Q3',
    'Oct', 'Nov', 'Dec', 'Q4',
    'YTD'
  ];


  public function buildForm(array $form, FormStateInterface $form_state){
    $numYear = $form_state->get('numYear');
    if (empty($numYear)){
      $numYear = 1;
      $form_state->set('numYear', $numYear);
    }
    $numTable = $form_state->get('numTable');
    if (empty($numTable)){
      $numTable = 1;
      $form_state->set('numTable', $numTable);
    }
    $form['wrapper']['title'] = [
    '#type' => 'html_tag',
    '#tag' => 'h2',
    '#value' => 'Calendar',
    ];
    $form['#tree'] = TRUE;
    $form['wrapper'] = [
      '#type' => 'container',
      '#attributes' => [
        'id' => 'wrapper',
      ],
    ];
    $form['wrapper']['addRow'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add Row'),
      '#name' => 'addRow',
      '#submit' => ['::addRow'],
      '#ajax' => [
        'callback' => '::ajaxCallbackRow',
        'wrapper' => 'wrapper',
      ],
    ];
    for ($j = 0; $j < $numTable; $j++){
      $form['actions'] = [
        '#type' => 'actions',
      ];
      $form['wrapper']['table'][$j] = [
        '#type' => 'table',
        '#header' => $this->headers,
        '#attributes' => [
          'class' => ['module-table'],
        ],
        '#tree' => TRUE,
      ];
      for ($i=0; $i < $numYear; $i++) {
        $date = date('Y')-$i;
        $form['wrapper']['table'][$j][$i]['Year'] = [
          '#type' => 'number',
          '#value' => $date,
          '#attributes' => [
            'class' => ['td'],
            'readonly' => ['readonly'],
          ],
        ];
        $form['wrapper']['table'][$j][$i]["Jan"] = [
          '#type' => 'number',
          '#attributes' => [
            'class' => ['td'],
          ],
        ];
        $form['wrapper']['table'][$j][$i]['Feb'] = [
          '#type' => 'number',
          '#attributes' => [
            'class' => ['td'],
          ],
        ];
        $form['wrapper']['table'][$j][$i]['Mar'] = [
          '#type' => 'number',
//          '#default_value' => $this->gg,
          '#attributes' => [
            'class' => ['td'],
          ],
        ];
        $form['wrapper']['table'][$j][$i]['Q1'] = [
          '#type' => 'number',
          '#value' => $this->q1 ?? '',
          '#attributes' => [
            'class' => ['td'],
            'readonly' => ['readonly'],
          ],
        ];
        $form['wrapper']['table'][$j][$i]['Apr'] = [
          '#type' => 'number',
          '#attributes' => [
            'class' => ['td'],
          ],
        ];
        $form['wrapper']['table'][$j][$i]['May'] = [
          '#type' => 'number',
          '#attributes' => [
            'class' => ['td'],
          ],
        ];
        $form['wrapper']['table'][$j][$i]['Jun'] = [
          '#type' => 'number',
          '#attributes' => [
            'class' => ['td'],
          ],
        ];
        $form['wrapper']['table'][$j][$i]['Q2'] = [
          '#type' => 'number',
          '#attributes' => [
            'class' => ['td'],
            'readonly' => ['readonly'],
          ],
        ];
        $form['wrapper']['table'][$j][$i]['Jul'] = [
          '#type' => 'number',
          '#attributes' => [
            'class' => ['td'],
          ],
        ];
        $form['wrapper']['table'][$j][$i]['Aug'] = [
          '#type' => 'number',
          '#attributes' => [
            'class' => ['td'],
          ],
        ];
        $form['wrapper']['table'][$j][$i]['Sep'] = [
          '#type' => 'number',
          '#attributes' => [
            'class' => ['td'],
          ],
        ];
        $form['wrapper']['table'][$j][$i]['Q3'] = [
          '#type' => 'number',
          '#attributes' => [
            'class' => ['td'],
            'readonly' => ['readonly'],
          ],
        ];
        $form['wrapper']['table'][$j][$i]['Oct'] = [
          '#type' => 'number',
          '#attributes' => [
            'class' => ['td'],
          ],
        ];
        $form['wrapper']['table'][$j][$i]['Nov'] = [
          '#type' => 'number',
          '#attributes' => [
            'class' => ['td'],
          ],
        ];
        $form['wrapper']['table'][$j][$i]['Dec'] = [
          '#type' => 'number',
          '#attributes' => [
            'class' => ['td'],
          ],
        ];
        $form['wrapper']['table'][$j][$i]['Q4'] = [
          '#type' => 'number',
          '#attributes' => [
            'class' => ['td'],
            'readonly' => ['readonly'],
          ],
        ];
        $form['wrapper']['table'][$j][$i]['YTD'] = [
          '#type' => 'number',
          '#attributes' => [
            'class' => ['td'],
            'readonly' => ['readonly'],
          ],
        ];
      }

    }
    $form['addTable'] = [
      '#type' => 'submit',
      '#name' => 'add-table',
      '#value' => $this->t('Add Table'),
      '#submit' => [
//        '::addTable',
        '::validateForm',
      ],
      '#ajax' => [
        'callback' => '::ajaxCallbackRow',
        'wrapper' => 'wrapper',
      ],
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#name' => 'submit',
      '#value' => $this->t('Submit'),
      '#ajax' => [
        'callback' => '::ajaxCallbackRow',
        'wrapper' => 'wrapper',
      ],
    ];
      $form['#attached']['library'][] = 'examodule/exam';
    return $form;
  }

  function ajaxCallbackRow(array &$form, FormStateInterface $form_state) {
    return [
      $form['wrapper'],
    ] ;
  }
  function ajaxCallbackTable(array &$form, FormStateInterface $form_state) {
    return [
      $form['wrapper'],
    ];
  }

  /**
   * AJAX function to add rows.
   */
  function addRow(array &$form, FormStateInterface $form_state) {
    $numYear = $form_state->get('numYear');
    $numYear++;
    $form_state->set('numYear', $numYear);
    $form_state->setRebuild();
  }

  /**
   * AJAX function to add table.
   */
  function addTable(array &$form, FormStateInterface $form_state) {
    $numTable = $form_state->get('numTable');
    $numTable++;
    $form_state->set('numTable', $numTable);
    $form_state->setRebuild();
  }
  function count(array &$form, FormStateInterface $form_state){
    $numYear = $form_state->get('numYear');
    $numTable = $form_state->get('numTable');
    $value = $form_state->getValues()['wrapper']['table'];
    for ($j = 0; $j < $numTable; $j++) {
      for ($i = 0; $i < $numYear; $i++) {
        $q1 = round(((($value[$j][$i]['Jan'] + $value[$j][$i]['Feb'] +
              $value[$j][$i]['Mar']) + 1) / 3), 2);
        $form['wrapper']['table'][$j][$i]['Q1']['#value'] = $q1;
        $q2 = round(((($value[$j][$i]['Apr'] + $value[$j][$i]['May'] +
              $value[$j][$i]['Jun']) + 1) / 3), 2);
        $form['wrapper']['table'][$j][$i]['Q2']['#value'] = $q2;
        $q3 = round(((($value[$j][$i]['Jul'] + $value[$j][$i]['Aug'] +
              $value[$j][$i]['Sep']) + 1) / 3), 2);
        $form['wrapper']['table'][$j][$i]['Q3']['#value'] = $q3;
        $q4 = round(((($value[$j][$i]['Oct'] + $value[$j][$i]['Now'] +
              $value[$j][$i]['Dec']) + 1) / 3), 2);
        $form['wrapper']['table'][$j][$i]['Q4']['#value'] = $q4;
        $form['wrapper']['table'][$j][$i]['YTD']['#value'] =
          round(((($q1 + $q2 + $q3 + $q4) + 1) / 4), 2);
      }
    }
    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state){
    $numYear = $form_state->get('numYear');
    $numTable = $form_state->get('numTable');
    $values = $form_state->getValues()['wrapper']['table'];
    for ($j = 0; $j <= $numTable; $j++) {
      for ($i = 0; $i < $numYear; $i++) {
        foreach ($values as $value) {
          $g = $value;
          foreach ($value as $inputVal) {
            $h = $inputVal;
            $jan = $inputVal['Jan'];
          }
        }
      }
    }
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
   $this->count($form, $form_state);
    return $form;
  }
}
