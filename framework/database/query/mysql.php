<?php
namespace Framework\Database\Query
{
    use Framework\Database as Database;
    use Framework\Database\Exception as Exception;

    class Mysql extends Database\Query{

        public function all()
        {
            $sql = $this->_buildSelect();
            $result = $this->connector->execute($sql);

            if ($result === false)
            {
                $error = $this->connector->lastError;
                throw new Exception\Sql("There was an error with your SQL query: {$error}");
            }

            $rows = array();
            $count = $result->rowCount();
            for ($i = 0; $i < $count; $i++)
            {
                $rows[] = $result->fetch(\PDO::FETCH_ASSOC);
            }

            return $rows;
        }
    }
}
 