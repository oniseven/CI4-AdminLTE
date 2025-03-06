<?php
namespace App\Libraries;

use CodeIgniter\HTTP\RequestInterface;

class Datatables {
  public $columns = "*";
  public bool $escape = false;
  public array $joins = [];
  public array $conditions = [];
  public string $search_type = "simple"; // simple | column
  public array $orders = [];
  public bool $show_query = false;
  public bool $show_configs = false;

  protected $request;

  public function __construct(RequestInterface $request){
    $this->request = $request;
  }

  public function loadData($model, $escape = false) {
    // request data
    $start = $this->request->getPost('start') ?? [];
    $length = $this->request->getPost('length') ?? [];

    // load model
    $builder = model($model);

    // count all data
    $recordsTotal = $builder->countAllResults();
    $builder->select($this->columns, $escape);

    // populate datatable search
    if($this->search_type === 'column') {
      $this->individualSearch();
    } else {
      $this->simpleSearch();
    }
    
    // build join table
    foreach ($this->joins as $key => $join) {
      $builder->join($join[0], $join[1], $join[2] ?? 'inner');
    }

    // build condition
    foreach ($this->conditions as $key => $data) {
      switch ($key) {
        case 'where':
          $builder->where($data);
          break;
        
        case 'whereIn':
          foreach ($data as $key => $row) {
            $builder->whereIn(
              $row[0] ?? $row['column'], 
              $row[1] ?? $row['value']
            );
          }
          break;

        case 'whereNotIn':
          foreach ($data as $key => $row) {
            $builder->whereNotIn(
              $row[0] ?? $row['column'], 
              $row[1] ?? $row['value']
            );
          }
          break;

        case 'like':
          foreach ($data as $key => $row) {
            $builder->like(
              $row[0] ?? $row['column'], 
              $row[1] ?? $row['keyword'],
              $row[2] ?? $row['type'] ?? 'both'
            );
          }
          break;

        case 'orLike':
          foreach ($data as $key => $row) {
            $builder->orLike(
              $row[0] ?? $row['column'], 
              $row[1] ?? $row['keyword'],
              $row[2] ?? $row['type'] ?? 'both'
            );
          }
          break;

        case 'notLike':
          foreach ($data as $key => $row) {
            $builder->notLike(
              $row[0] ?? $row['column'], 
              $row[1] ?? $row['keyword'],
              $row[2] ?? $row['type'] ?? 'both'
            );
          }
          break;

        case 'groupBy':
          $builder->groupBy($data);
          break;

        case 'orderBy':
          if(!is_array($data)){
            $builder->orderBy($data);
          } else {
            foreach ($data as $key => $row) {
              $builder->orderBy(
                $row[0] ?? $row['column'],
                $row[1] ?? $row['dir'] ?? 'ASC'
              );
            }
          }
          break;
      }
    }

    // count filtered result
    $recordsFiltered = $builder->countAllResults(false);

    // order result
    $orderBy = $this->setOrder();
    if($orderBy) {
      $builder->orderBy($orderBy[0], $orderBy[1]);
    }

    // limit result
    if((int) $length) {
      $builder->limit((int) $length, (int) $start);
    }

    // get all data
    $data = $builder->findAll();

    // build response data
    $response = [
      "recordsTotal" => $recordsTotal,
      "recordsFiltered" => $recordsFiltered,
      "data" => $data,
      "conditions" => $this->conditions
    ];

    return $response;
  }

  private function simpleSearch() {
    // request data
    $columns = $this->request->getPost('columns') ?? [];
    $search = $this->request->getPost('search') ?? [];

    $keyword = trim($search['value']);
    if(!empty($keyword)){
      foreach ($columns as $key => $column) {
        if(!(bool) $column['searchable']) continue;

        $this->conditions['orLike'][] = [
          'column' => $column['data'],
          'keyword' => $keyword,
          'type' => 'both'
        ];
      }
    }
  }

  private function individualSearch() {
    // request data
    $columns = $this->request->getPost('columns') ?? [];
    $columnDefs = $this->request->getPost('columnDefs') ?? [];

    foreach ($columns as $key => $column) {
      if(!(bool) $column['searchable']) continue;
      if(array_key_exists('search', $column)
        && strlen(trim($column['search']['value']))
          && (int) $column['search']['value'] !== -1) 
      {
        $columnDef = $columnDefs[$key];
        switch ($columnDef['type']) {
          case 'string':
            $this->conditions['like'][] = [
              'column' => $columnDef['value'],
              'keyword' => $column['search']['value'],
              'type' => 'both'
            ];
            break;

          case 'date':
            $this->conditions['where'][$columnDef['value']] = date('Y-m-d', strtotime($column['search']['value']));
            break;

          case 'enum':
          case 'num':
          case 'int':
            if($column['search']['value'] !== 'null'){
              $this->conditions['where'][$columnDef['value']] = $column['search']['value'];
            } else {
              $this->conditions['where'][$columnDef['value']." IS NULL"] = null;
            }
            break;
          
          default:
            $this->conditions['where'][$columnDef['value']] = $column['search']['value'];
            break;
        }
      }
    }
  }

  private function setOrder() {
    $columns = $this->request->getPost('columns') ?? [];
    $order = $this->request->getPost('order') ?? [];

    $indexColumnOrder = $order[0]['column'];
    $columnOrderAble = $columns[$indexColumnOrder]['orderable'];
    $orderColumnName = $columns[$indexColumnOrder]['data'];
    $orderDirection = $order[0]['dir'];

    if($columnOrderAble !== "true") { return false; }

    return [
      $orderColumnName,
      $orderDirection
    ];
  }
}