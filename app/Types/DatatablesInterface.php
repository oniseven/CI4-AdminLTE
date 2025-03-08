<?php

namespace App\Types;

interface DatatablesInterface {
  /**
   * Method to set database group
   * 
   * @param string $group Database config group
   */
  public function dbGroup(string $group): object;

  /**
   * Method to set which column that gonna be use to show the data
   * 
   * @param string|array $columns Table column
   * @param bool $escape Columns escape string, default false
   */
  public function select($columns, bool $escape = false): object;

  /**
   * Method to set all the join table
   * 
   * @param array $joins List of join table
   */
  public function joins(array $joins): object;

  /**
   * Method to set all the condition of the query such as filtering, grouping
   * 
   * @param array $conditions List conditions
   */
  public function conditions(array $conditions): object;

  /**
   * Method to set the filtering/search type of the datatable
   * 
   * @param string $type There are 2 type, simple and column, default simple
   */
  public function searchType(string $type): object;

  /**
   * Method to set order by
   * 
   * @param array $orders list of order by
   */
  public function orderBy(array $orders): object;

  /**
   * Method to show sql query (last query) on returned result
   */
  public function showQuery(): object;

  /**
   * Method to show conditions data on returned result
   */
  public function showConfigs(): object;

  /**
   * Method to load the data base on the configs that been set
   * 
   * @param string $table Table name
   */
  public function loadData(string $table): array;

  /**
   * Method to load the data using sql query
   * 
   * @param string $sql Sql Query
   * @param array $binding Data binding
   */
  public function loadQuery(string $sql, array $binding = []): array;
}