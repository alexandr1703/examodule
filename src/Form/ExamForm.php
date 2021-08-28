<?php

namespace Drupal\examodule\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\Messenger;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class main ExamForm.
 */
class ExamForm extends FormBase {

  /**
   * Create dependency injection.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   Container.
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('messenger'));
  }

  /**
   *
   * @param \Drupal\Core\Messenger\Messenger $messenger
   *   Variable for Messenger class.
   */
  public function __construct(Messenger $messenger) {
    $this->messenger = $messenger;
  }

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
    'Year', 'Jan', 'Feb', 'Mar', 'Q1',
    'Apr', 'May', 'Jun', 'Q2',
    'Jul', 'Aug', 'Sep', 'Q3',
    'Oct', 'Nov', 'Dec', 'Q4',
    'YTD',
  ];

  /**
   * @var int
   * Variable for length array between first value and the end off the array.
   */
  private $mainLength;

  /**
   * @var int
   * Variable for length array between first value and the end off the array
   * without empty values.
   */
  private $mainLengthWithoutEmpty;

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $numYear = $form_state->get('numYear');
    if (empty($numYear)) {
      $numYear = 1;
      $form_state->set('numYear', $numYear);
    }
    $numTable = $form_state->get('numTable');
    if (empty($numTable)) {
      $numTable = 1;
      $form_state->set('numTable', $numTable);
    }
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
        'callback' => '::ajaxCallbackTable',
        'wrapper' => 'wrapper',
      ],
    ];
    for ($j = 0; $j < $numTable; $j++) {
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
      for ($i = $numYear; $i >= 1; $i--) {
        $date = date('Y') - $i + 1;
        $form['wrapper']['table'][$j][$i]['Year'] = [
          '#type' => 'number',
          '#attributes' => [
            'readonly' => ['readonly'],
          ],
          '#value' => $date,
        ];
        $form['wrapper']['table'][$j][$i][0] = [
          '#type' => 'number',
          '#title_display' => 'invisible',
        ];
        $form['wrapper']['table'][$j][$i][1] = [
          '#type' => 'number',
        ];
        $form['wrapper']['table'][$j][$i][2] = [
          '#type' => 'number',
        ];
        $form['wrapper']['table'][$j][$i]['Q1'] = [
          '#type' => 'number',
          '#attributes' => [
            'readonly' => ['readonly'],
          ],
        ];
        $form['wrapper']['table'][$j][$i][3] = [
          '#type' => 'number',
        ];
        $form['wrapper']['table'][$j][$i][4] = [
          '#type' => 'number',
        ];
        $form['wrapper']['table'][$j][$i][5] = [
          '#type' => 'number',
        ];
        $form['wrapper']['table'][$j][$i]['Q2'] = [
          '#type' => 'number',
          '#attributes' => [
            'readonly' => ['readonly'],
          ],
        ];
        $form['wrapper']['table'][$j][$i][6] = [
          '#type' => 'number',
        ];
        $form['wrapper']['table'][$j][$i][7] = [
          '#type' => 'number',
        ];
        $form['wrapper']['table'][$j][$i][8] = [
          '#type' => 'number',
        ];
        $form['wrapper']['table'][$j][$i]['Q3'] = [
          '#type' => 'number',
          '#attributes' => [
            'readonly' => ['readonly'],
          ],
        ];
        $form['wrapper']['table'][$j][$i][9] = [
          '#type' => 'number',
        ];
        $form['wrapper']['table'][$j][$i][10] = [
          '#type' => 'number',
        ];
        $form['wrapper']['table'][$j][$i][11] = [
          '#type' => 'number',
        ];
        $form['wrapper']['table'][$j][$i]['Q4'] = [
          '#type' => 'number',
          '#attributes' => [
            'readonly' => ['readonly'],
          ],
        ];
        $form['wrapper']['table'][$j][$i]['YTD'] = [
          '#type' => 'number',
          '#attributes' => [
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
        '::addTable',
      ],
      '#ajax' => [
        'callback' => '::ajaxCallbackTable',
        'wrapper' => 'wrapper',
      ],
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#name' => 'submit',
      '#value' => $this->t('Submit'),
      '#ajax' => [
        'callback' => '::ajaxCallbackTable',
        'wrapper' => 'wrapper',
      ],
    ];
    $form['#attached']['library'][] = 'examodule/exam';
    return $form;
  }

  /**
   * Returns table via ajax.
   */
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

  /**
   * Counting values.
   */
  function count(array &$form, FormStateInterface $form_state) {
    $numYear = $form_state->get('numYear');
    $numTable = $form_state->get('numTable');
    $value = $form_state->getValues()['wrapper']['table'];
    for ($j = 0; $j < $numTable; $j++) {
      for ($i = $numYear; $i >= 1; $i--) {
        if (empty($value[$j][$i][0]) && empty($value[$j][$i][1]) &&
        empty($value[$j][$i][2])) {
          $q1 = 0;
        }
        else {
          $q1 = round(((($value[$j][$i][0] + $value[$j][$i][1] +
                $value[$j][$i][2]) + 1) / 3), 2);
        }
        $form['wrapper']['table'][$j][$i]['Q1']['#value'] = $q1;
        if (empty($value[$j][$i][3]) && empty($value[$j][$i][4]) &&
          empty($value[$j][$i][5])) {
          $q2 = 0;
        }
        else {
          $q2 = round(((($value[$j][$i][3] + $value[$j][$i][4] +
                $value[$j][$i][5]) + 1) / 3), 2);
        }
        $form['wrapper']['table'][$j][$i]['Q2']['#value'] = $q2;
        if (empty($value[$j][$i][6]) && empty($value[$j][$i][7]) &&
          empty($value[$j][$i][8])) {
          $q3 = 0;
        }
        else {
          $q3 = round(((($value[$j][$i][6] + $value[$j][$i][7] +
                $value[$j][$i][8]) + 1) / 3), 2);
        }
        $form['wrapper']['table'][$j][$i]['Q3']['#value'] = $q3;
        if (empty($value[$j][$i][9]) && empty($value[$j][$i][10]) &&
          empty($value[$j][$i][11])) {
          $q4 = 0;
        }
        else {
          $q4 = round(((($value[$j][$i][9] + $value[$j][$i][10] +
                $value[$j][$i][11]) + 1) / 3), 2);
        }
        $form['wrapper']['table'][$j][$i]['Q4']['#value'] = $q4;
        $form['wrapper']['table'][$j][$i]['YTD']['#value'] =
          round(((($q1 + $q2 + $q3 + $q4) + 1) / 4), 2);
        if ($q1 == 0 && $q2 == 0 && $q3 == 0 & $q4 == 0) {
          $form['wrapper']['table'][$j][$i]['Q1']['#value'] = '';
          $form['wrapper']['table'][$j][$i]['Q2']['#value'] = '';
          $form['wrapper']['table'][$j][$i]['Q3']['#value'] = '';
          $form['wrapper']['table'][$j][$i]['Q4']['#value'] = '';
          $form['wrapper']['table'][$j][$i]['YTD']['#value'] = '';
        }
      }
    }
    return $form;
  }

  /**
   * Get first not empty value.
   */
  function getNotEmpty($arr) {
    array_shift($arr);
    unset($arr['Q1']);
    unset($arr['Q2']);
    unset($arr['Q3']);
    unset($arr['Q4']);
    array_pop($arr);
    foreach ($arr as $value) {
      if ($value) {
        $key = array_search($value, $arr);
        return $key;
      }
    }
    return NULL;
  }

  /**
   * Validation by tables.
   */
  public function validateByTables(array &$form, FormStateInterface $form_state) {
    // Get number of tables and rows.
    $numYear = $form_state->get('numYear');
    $numTable = $form_state->get('numTable');
    for ($j = 1; $j < $numTable; $j++) {
      $values = $form_state->getValues()['wrapper']['table'];
      for ($i = 1; $i <= $numYear; $i++) {
        for ($n = 0; $n < 12; $n++) {
          if (($values[0][$i][$n] !== '' && $values[$j][$i][$n] == '') ||
            ($values[0][$i][$n] == '' && $values[$j][$i][$n] !== '')) {
            $this->messenger->addError($this->t('Invalid'));
            return FALSE;
          }
        }
      }
    }
    return TRUE;
  }

  /**
   * Validation table.
   */
  public function ValidateTable(array &$form, FormStateInterface $form_state) {
    // Get number of tables and rows.
    $numYear = $form_state->get('numYear');
    $numTable = $form_state->get('numTable');
    for ($j = 0; $j <= $numTable; $j++) {
      // All values from one table.
      $values = $form_state->getValues()['wrapper']['table'][$j];
      for ($i = $numYear; $i >= 1; $i--) {
        foreach ($values as $value) {
          // First value in the table.
          $first = $this->getNotEmpty($value);
          if ($first !== NULL) {
            // Remove quarter inputs from array.
            unset($value['Q1']);
            unset($value['Q2']);
            unset($value['Q3']);
            unset($value['Q4']);
            // First value in the year.
            $first = $this->getNotEmpty($value);
            // Last value in the year.
            $last = $this->getNotEmpty(array_reverse($value));
            // Length array between first and last values.
            $length = 11 - $first - $last + 1;
            $output = array_slice($value, $first + 1, $length);
            // Array between first and last values without empty values.
            $result = array_filter($output, 'strlen');
            $lengthWithoutEmpty = count($result);
            // Checking gaps in the year.
            if ($length !== $lengthWithoutEmpty) {
              $this->messenger->addError($this->t('Invalid'));
              return FALSE;
            }
            // Checking gaps in the table between years.
            if ($this->mainLength != $this->mainLengthWithoutEmpty) {
              $this->messenger->addError($this->t('Invalid'));
              return FALSE;
            }
            // Length array between first value and the end off the array.
            $this->mainLength = 11 - $first + 1;
            $mainOutput = array_splice($value, $first + 1, $this->mainLength);
            $mainResult = array_filter($mainOutput, 'strlen');
            $this->mainLengthWithoutEmpty = count($mainResult);
          }
        }
        break;
      }
      $this->mainLength = 0;
      $this->mainLengthWithoutEmpty = 0;
    }

    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $inputes = $form_state->getUserInput();
    if ($inputes["_triggering_element_value"] == "Submit") {
      if ($this->validateTable($form, $form_state) == TRUE) {
        if ($this->validateByTables($form, $form_state) == TRUE) {
          $this->count($form, $form_state);
        }
      }
    }
    return $form;
  }

}
