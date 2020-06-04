<?php
    namespace DAO\DAODB;
    use Models\Cinema as Cinema;
    use DAO\DAODB\Connection as Connection;
    use PDOException;
    use DAO\DAODB\IDao;

    class CinemaDao extends Singleton implements IDao{
        private $connection;
        public function __construct()
        {
            $this->connection = null;
        }
        
        public function create($cinema)
        {
            $sql = "INSERT INTO cinemas (id_movieTheater, number_cinema, capacity, ticket_value, available) VALUES (:id_movieTheater, :number_cinema, :capacity, :ticket_value, :available)";
            $parameters['id_movieTheater'] = $cinema->getIdMovieTheater();
            $parameters['number_cinema'] = $cinema->getNumberCinema();
            $parameters['capacity'] = $cinema->getCapacity();
            $parameters['ticket_value'] = $cinema->getTicket_value();
            $parameters['available'] = $cinema->getAvailable();
            try {
                $this->connection = Connection::getInstance();
                return $this->connection->ExecuteNonQuery($sql, $parameters);
            }
            catch(PDOException $e){
                throw $e;
            }
        }

        public function update($id,$capacity,$ticket_value,$available)
        {
            $sql = "UPDATE cinemas SET capacity = :capacity, ticket_value = :ticket_value, available = :available  WHERE id_cinema = :id";
            $parameters['id'] = $id;
            $parameters['capacity'] = $capacity;
            $parameters['ticket_value'] = $ticket_value;
            $parameters['available'] = $available;
            try
            {
                $this->connection = Connection::getInstance();
                return $this->connection->ExecuteNonQuery($sql, $parameters);
            }
            catch(PDOException $e)
            {
                throw $e;
            }
        }

        public function readAll()
        {
            $sql = "SELECT * FROM cinemas";
            try
            {
                $this->connection = Connection::getInstance();
                $resultSet = $this->connection->execute($sql);
            }
            catch(PDOException $e)
            {
                throw $e;
            }
                if(!empty($resultSet))
                    return $this->mapear($resultSet);
                else
                    return false;
        }

        public function read ($id)
        {
            $sql = "SELECT * FROM cinemas where id_cinema = :id";
            $parameters['id'] = $id;
            try
            {
                $this->connection = Connection::getInstance();
                $resultSet = $this->connection->execute($sql, $parameters);
            }
            catch(PDOException $e)
            {
                throw $e;
            }
                if(!empty($resultSet))
                    return $this->mapear($resultSet);
                else
                    return "false";
        }

        public function readFromMovieTheater($id_movieTheater){
            $sql = "SELECT * FROM cinemas WHERE id_movieTheater = :id_mt";
            $parameters['id_mt'] = $id_movieTheater;
            try
            {
                $this->connection = Connection::getInstance();
                $resultSet = $this->connection->execute($sql, $parameters);
            }
            catch(PDOException $e)
            {
                throw $e;
            }
                if(!empty($resultSet))
                    return $this->mapear($resultSet);
                else
                    return false;
        }

        public function delete ($id)
        {
            $sql = "DELETE FROM cinemas WHERE id_cinema = :id";
            $parameters['id'] = $id;
            try
            {
                $this->connection = Connection::getInstance();
                return $this->connection->ExecuteNonQuery($sql, $parameters);
            }
            catch(PDOException $e)
            {
                throw $e;
            }
        }
        public function mapear($value) {
            $value = is_array($value) ? $value : [];
            $resp = array_map(function($p){
                $newCinema = new Cinema($p['id_movieTheater'], $p['number_cinema'], $p['capacity'],$p['ticket_value'],$p['available']);
                $newCinema->setIdCinema($p['id_cinema']);
                return $newCinema;
            }, $value);
                /* devuelve un arreglo si tiene datos y sino devuelve nulo*/
                return count($resp) > 1 ? $resp : $resp['0'];
        }
        }



?>