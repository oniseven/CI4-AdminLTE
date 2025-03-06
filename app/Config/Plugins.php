<?php
namespace Config;

use CodeIgniter\Config\BaseConfig;

class Plugins extends BaseConfig {
  public $datatables = [
    'css' => [
      'assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css',
      'assets/dist/css/custom.datatables.css',
    ],
    'js' => [
      'assets/plugins/datatables/jquery.dataTables.min.js',
      'assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js',
      'assets/dist/js/dtutils.js'
    ]
  ];

  public $dt_buttons = [
    'css' => [
      'assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css'
    ],
    'js' => [
      'assets/plugins/datatables-buttons/js/dataTables.buttons.min.js',
      'assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js',
      'assets/plugins/datatables-buttons/js/buttons.colVis.min.js',
      'assets/plugins/datatables-buttons/js/buttons.flash.min.js',
      'assets/plugins/datatables-buttons/js/buttons.html5.min.js',
      'assets/plugins/datatables-buttons/js/buttons.print.min.js',
    ]
  ];

  public $dt_colreorder = [
    'css' => [
      'assets/plugins/datatables-colreorder/css/colReorder.bootstrap4.min.css'
    ],
    'js' => [
      'assets/plugins/datatables-buttons/js/dataTables.colReorder.min.js',
      'assets/plugins/datatables-buttons/js/colReorder.bootstrap4.min.js'
    ]
  ];

  public $jstree = [
    'css' => [
      'assets/plugins/jstree/jstree.bundle.css'
    ],
    'js' => [
      'assets/plugins/jstree/jstree.bundle.js'
    ]
  ];

  public $validation = [
    'css' => [],
    'js' => [
      'assets/plugins/jquery-validation/jquery.validate.min.js',
      'assets/plugins/jquery-validation/additional-methods.min.js',
    ]
  ];

  public $select2 = [
    'css' => [
      'assets/plugins/select2/css/select2.min.css',
      'assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css',
    ],
    'js' => [
      'assets/plugins/select2/js/select2.min.js',
    ]
  ];
}