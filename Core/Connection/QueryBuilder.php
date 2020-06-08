<?php

namespace App\Core\Connection;

class QueryBuilder implements PDOConnection
{

	protected $select;
	protected $from;
	protected $where;
	protected $group;
	protected $order;
	protected $params;
	protected $innerJoin[];
	protected $leftJoin[];
	protected $rightJoin[];

	protected $pdo;


	public function __construct()
	{
		$this->pdo = new PDOConnection()
	} 

	public function select(string $colonne){
		$this->select = $colonne;
		return $this;
	}

	public function from(string $table){
		$this->from = $table;
		return $this;
	}

	public function where (string $condition){
		$this->where = $condition;
		return $this;
	}

	public function setParameter(array $params)
	{
		$this->params = $params;
		return $this;
	}

	public function innerJoin(string $firstTable, string $secondTable){
		$this->innerJoin['firstTable'] 	= $firstTable;
		$this->innerJoin['secondTable'] = $secondTable;
		return $this;
	}

	public function getQuery()
	{
		$query = ['SELECT'];

		if ($this->select){
			$query[] = join(', ', $this->select);
		}else 
		{
			$query[] = '*';
		}

		$query[] = 'FROM';
		$query[] = $this->table;

		if (! empty($this->innerJoin){
			$query[] = "INNER JOIN ON";
			$query[] = $this->innerJoin['firstTable'] . " = " . $this->innerJoin['secondTable'];
		}

		if (! empty($this->leftJoin){
			$query[] = "LEFT JOIN ON";
			$query[] = $this->leftJoin['firstTable'] . " = " . $this->leftJoin['secondTable'];
		}

		if (! empty($this->rightJoin){
			$query[] = "RIGHT JOIN ON";
			$query[] = $this->rightJoin['firstTable'] . " = " . $this->rightJoin['secondTable'];
		}

		if ($this->where){
			$query[] = "WHERE";
			$query[] = $this->where;
		}
		return join(' ', $query);
	}

	public function execute()
	{
		$finalQuery = $this->getQuery();

		if(isset($this->params))
		{
			$statement = $this->pdo->prepare($query);
			$statement->execute($this->params);
			return $statement;
		}else{
			return $this->pdo->query($finalQuery);
		}

		
	}


}
