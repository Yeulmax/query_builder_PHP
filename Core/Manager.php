<?php

namespace App\Core;

use App\Core\Connection\BDDInterface;
use App\Core\Connection\PDOConnection;

class Manager
{
    private $table;
    private $connection;
    protected $class;

    public function __construct(string $class, string $table, BDDInterface $connection = null)
    {
        $this->class = $class;
        $this->table =  DB_PREFIXE.$table;
        
        $this->connection = $connection;
        if(NULL === $connection)
            $this->connection = new PDOConnection();
    }


    public function save($objectToSave)
    {

        $objectArray =  $objectToSave->__toArray();

        $columnsData = array_values($objectArray);
        $columns = array_keys($objectArray);
        // On met 2 points devant chaque clé du tableau
        $params = array_combine(
            array_map(function($k){ return ':'.$k; }, array_keys($objectArray)),
            $objectArray
        );;
        
        if (!is_numeric($objectToSave->getId())) {
            array_shift($columns);
            array_shift($params);
            //INSERT
            $sql = "INSERT INTO ".$this->table." (".implode(",", $columns).") VALUES (:".implode(",:", $columns).");";
            //foreach()
        } else {

            //UPDATE
            foreach ($columns as $column) {
                $sqlUpdate[] = $column."=:".$column;
            }

            $sql = "UPDATE ".$this->table." SET ".implode(",", $sqlUpdate)." WHERE id=:id;";
        }
        $this->connection->query($sql, $params);

    }

    public function find(int $id): ?Model
    {
        $sql = "SELECT * FROM $this->table where id = :id";

        $result = $this->connection->query($sql, [':id' => $id]);
        
        $row = $result->getOneOrNullResult();

        if ($row) {
            $object = new $this->class();
            return $object->hydrate($row);
        } else {
            return null;
        }
      
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM $this->table";

        $result = $this->connection->query($sql);

        $rows = $result->getArrayResult();
        
        $results = array();
        
        foreach($rows as $row) {

            $object = new $this->class();
            array_push($results, $object->hydrate($row));
        } 

        return $results;
      
    }

    public function findBy(array $params, array $order = null): array
    {
        $results = array();

        $sql = "SELECT * FROM $this->table where ";

        // Select * FROM users WHERE firstname LIKE :firstname ORDER BY id desc

        foreach($params as $key => $value) {
            if(is_string($value))
                $comparator = 'LIKE';
            else 
                $comparator = '=';

            $sql .= " $key $comparator :$key and"; 
            // Select * FROM users WHERE firstname LIKE :firstname and
            // [":firstname" => 'Fadyl%']
            // ["firstname" => 'Fadyl%']
            $params[":$key"] = $value;
            // ["firstname" => 'Fadyl%', ":firstname" => 'Fadyl%']
            unset($params[$key]);
           // [":firstname" => 'Fadyl%']
        }

        $sql = rtrim($sql, 'and');
        // Select * FROM users WHERE firstname LIKE :firstname

        if($order) {
            $sql .= "ORDER BY ". key($order). " ". $order[key($order)]; 
        }
        // Select * FROM users WHERE firstname LIKE :firstname ORDER BY id desc

        $result = $this->connection->query($sql, $params);
        $rows = $result->getArrayResult();
        
        foreach($rows as $row) {
            $object = new $this->class();
            array_push($results, $object->hydrate($row));
        } 

        return $results;

    }

    public function count(array $params): int
    {
       

        $sql = "SELECT COUNT(*) FROM $this->table where ";

        foreach($params as $key => $value) {
            if(is_string($value))
                $comparator = 'LIKE';
            else 
                $comparator = '=';
            $sql .= " $key $comparator :$key and"; 

            $params[":$key"] = $value;
            unset($params[$key]);
        }

        $sql = rtrim($sql, 'and');

        $result = $this->connection->query($sql, $params);
        return $result->getValueResult();


    }

    public function delete(int $id): bool
    {
       

        $sql = "DELETE FROM $this->table where id = :id";

        $result = $this->connection->query($sql, [':id' => $id]);

        return true;


    }

    /// Faire un find all  qui renvoie un tableau avec toutes les valeurs

    /// Faire un count qui renvoit un entier avec le nombre d'élèment

    /// Faire un findBy qui prend en paramètre un tableau de paramètres (clé = champs db, valeur = valeur en db)
    /// et qui renvoit un tableau d'objet correspondant à where champsDB1 = ValueDB1 && champsDB2 = ValueDB2
    /// Ensuite rajouter un deuxième paramètre qui gère l'ordre (order by champsDB valueOrder) valueORDER = ASC or DESC
    // function findBy(array $params, array $)
    // Faire un delete function delete(int $id)

    protected function sql($sql, $parameters = null)
    {
        if ($parameters) {
            $queryPrepared = $this->pdo->prepare($sql);
            $queryPrepared->execute($parameters);

            return $queryPrepared;
        } else {
            $queryPrepared = $this->pdo->prepare($sql);
            $queryPrepared->execute();

            return $queryPrepared;
        }
    }

    
    
}
