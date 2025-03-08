<?php
namespace App\Libraries;

use CodeIgniter\HTTP\RequestInterface;
use App\Types\DatatablesInterface;

class Datatables implements DatatablesInterface {
  /**
   * @var string|list<string> $columns  selected column to show
   * @var bool $escape                  escape string for selected columns
   * @var array $joins                  list of joined table
   * @var string $conditions            list of filtering and grouping
   * @var string $search_type           search type of datatable, individual column search or simple search
   * @var array $orders                 list of order
   * @var bool $show_query              include last query data on returned result
   * @var bool $show_configs            include configs data on returned result
   * @var string $db_group              database group
   * 
   * @var RequestInterface $request
   */
  protected $columns = "*";
  protected bool $escape = false;
  protected array $joins = [];
  protected array $conditions = [];
  protected string $search_type = "simple"; // simple | column
  protected array $orders = [];
  protected bool $show_query = false;
  protected bool $show_configs = false;
  protected string $db_group = 'default';

  protected $request;

  public function __construct(RequestInterface $request) {
    $this->request = $request;
  }

  public function dbGroup($group): object {
    $this->db_group = $group;
    return $this;
  }

  public function select($columns, bool $escape = false): object {
    if(empty($columns)){
      throw new \Exception("Datatable selected columns cannot be empty.");
    }

    $this->columns = $columns;
    $this->escape = $escape;
    return $this;
  }

  public function joins(array $joins): object {
    if(empty($joins)){
      throw new \Exception("Datatable joins table cannot be empty.");
    }

    $this->joins = $joins;
    return $this;
  }

  public function conditions(array $conditions): object {
    if(empty($conditions)){
      throw new \Exception("Datatable conditions cannot be empty.");
    }

    $this->conditions = $conditions;
    return $this;
  }

  public function searchType(string $type): object {
    if(empty($type)){
      throw new \Exception("Datatable search type cannot be empty.");
    }

    if(!in_array($type, ['simple', 'column'])){
      throw new \Exception("Datatable search type must between 'simple' or 'column' type.");
    }

    $this->search_type = $type;
    return $this;
  }

  public function orderBy(array $orders): object {
    $this->orders = $orders;
    return $this;
  }

  public function showQuery(): object {
    $this->show_query = true;
    return $this;
  }

  public function showConfigs(): object {
    $this->show_configs = true;
    return $this;
  }

  public function loadData(string $table): array {
    $db = \Config\Database::connect($this->db_group);

    // request data
    $start = $this->request->getPost('start') ?? [];
    $length = $this->request->getPost('length') ?? [];

    // load model
    $builder = $db->table($table);

    // count all data
    $recordsTotal = $builder->countAllResults();
    $builder->select($this->columns, $this->escape);

    // populate datatable search
    if($this->search_type === 'column') {
      $this->individualSearch();
    } else {
      $this->simpleSearch();
    }
    
    // build join table
    foreach ($this->joins as $key => $join) {
      $builder->join(
        $join[0] ?? $join['table'], 
        $join[1] ?? $join['on'], 
        $join[2] ?? $join['type'] ?? 'inner'
      );
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

    // set order result
    $this->setOrder();
    foreach ($this->orders as $key => $order) {
      $builder->orderBy(
        $order[0] ?? $order['column'], 
        $order[1] ?? $order['dir'] ?? 'ASC'
      );
    }

    // limit result
    if((int) $length) {
      $builder->limit((int) $length, (int) $start);
    }

    // get all data
    $query = $builder->get();
    $data = $query ? $query->getResult() : [];

    // build response data
    $response = [
      "recordsTotal" => $recordsTotal,
      "recordsFiltered" => $recordsFiltered,
      "data" => $data,
    ];

    if($this->show_query) {
      $response['sql'] = (string) $db->getLastQuery();
    }

    if($this->show_configs){
      $response["conditions"] = $this->conditions;
    }

    return $response;
  }

  public function loadQuery(string $sql, array $binding = []): array {
    $db = \Config\Database::connect($this->db_group);

    $query = $db->query($sql, $binding);
    $data = $query->getResult();

    $response = [
      "recordsTotal" => 0,
      "recordsFiltered" => count($data),
      "data" => $data,
    ];

    return $response;
  }

  private function simpleSearch():void {
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

  private function individualSearch(): void {
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

    $this->orders[] = [$orderColumnName, $orderDirection];
  }
}