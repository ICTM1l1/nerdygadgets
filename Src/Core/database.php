<?php

/**
 * Gets the database connection.
 *
 * @param string|null $username
 *   The name of the user.
 * @param string|null $password
 *   The password of the user.
 *
 * @return PDO
 *   The database connection.
 */
function getDatabaseConnection(string $username, string $password) {
    $dsn = config_get('database_server') . ';';
    $dsn .= 'dbname=' . config_get('database_name') . ';';
    $dsn .= 'charset=' .  config_get('database_charset') . ';';
    $dsn .= 'port=' . config_get('database_port') . ';';

    /**
     * With PDO_MYSQL you need to remember about the PDO::ATTR_EMULATE_PREPARES option.
     * The default value is TRUE, like $connection->setAttribute(PDO::ATTR_EMULATE_PREPARES,true);
     *
     * This means that no prepared statement is created with $connection->prepare() call.
     * With execute() call PDO replaces the placeholders with values itself and sends
     * MySQL a generic query string.
     *
     * In order to be able to manually bind the values to the query, we must turn
     * the PDO::ATTR_EMULATE_PREPARES option off.
     */
    $options = [
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    $debug = config_get('debug', false);
    if ($debug) {
        $options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
    }

    try {
        return new PDO($dsn, $username, $password, $options);
    } catch (PDOException $exception) {
        throw new RuntimeException('Database connection is invalid.');
    }
}

/**
 * Begins the transaction.
 *
 * @param PDO $connection
 *   The database connection.
 */
function beginTransaction(PDO $connection) {
    $connection->beginTransaction();
}

/**
 * Commits the transaction.
 *
 * @param PDO $connection
 *   The database connection.
 */
function commitTransaction(PDO $connection) {
    $connection->commit();
}

/**
 * Rolls the transaction back.
 *
 * @param PDO $connection
 *   The database connection.
 */
function rollbackTransaction(PDO $connection) {
    $connection->rollBack();
}

/**
 * Executes a query.
 *
 * @param string $query
 *   The query.
 * @param array $parameters
 *   The parameters of the query.
 * @param PDO|null $connection
 *   The database connection.
 * @param string|null $username
 *   The name of the user.
 * @param string|null $password
 *   The password of the user.
 *
 * @return bool|PDOStatement
 *   The PDOStatement object on success or a boolean.
 */
function executeQuery(string $query, array $parameters = [], PDO $connection = null, string $username = null, string $password = null) {
    try {
        if (!$connection) {
            $connection = getDatabaseConnection($username, $password);
        }

        $statement = $connection->prepare($query);

        foreach ($parameters as $column => $value) {
            $statement->bindValue(":{$column}", $value, PDO::PARAM_STR);
        }

        $statement->execute();

        return $statement;
    } catch (Exception $exception) {
        throw new RuntimeException('Executing query failed.');
    }
}

/**
 * Select data from the database.
 *
 * @param string $query
 *   The query.
 * @param array $parameters
 *   The parameters of the query.
 *
 * @return array
 *   The selected data.
 */
function select(string $query, array $parameters = []) {
    $statement = executeQuery($query, $parameters, null, config_get('database_user_read'), config_get('database_password_read'));

    return $statement->fetchAll(PDO::FETCH_NAMED);
}

/**
 * Select the first piece of data from the database.
 *
 * @param string $query
 *   The query.
 * @param array $parameters
 *   The parameters of the query.
 *
 * @return array
 *   The selected data.
 */
function selectFirst(string $query, array $parameters = []) {
    $statement = executeQuery($query, $parameters, null, config_get('database_user_read'), config_get('database_password_read'));

    $values = $statement->fetch(PDO::FETCH_NAMED);
    return !empty($values) ? $values : [];
}

/**
 * Insert data into the database.
 *
 * @param string $table
 *   The table to insert data in.
 * @param array $parameters
 *   The parameters of the query.
 * @param PDO|null $connection
 *   The PDO connection.
 *
 * @return int
 *   The result of executing the query.
 */
function insert(string $table, array $parameters = [], PDO $connection = null) {
    $columns = '';
    $values = '';

    $last_column = array_key_last($parameters);
    foreach ($parameters as $column => $value) {
        $columns .= $column === $last_column ? $column : "{$column}, ";
        $values .= $column === $last_column ? ":{$column}" : ":{$column}, ";
    }

    $query = "INSERT INTO {$table} ({$columns}) VALUES ({$values})";

    try {
        if (!$connection) {
            $connection = getDatabaseConnection(config_get('database_user_create'), config_get('database_password_create'));
        }

        $statement = $connection->prepare($query);
        foreach ($parameters as $column => $value) {
            $statement->bindValue(":{$column}", $value, PDO::PARAM_STR);
        }

        $statement->execute();

        return $connection->lastInsertId();
    } catch (Exception $exception) {
        throw new RuntimeException('Executing query failed.');
    }
}

/**
 * Update data in the database.
 *
 * @param string $table
 *   The table to update data in.
 * @param array $parameters
 *   The parameters of the query.
 *   [coloumn => value]
 * @param array $conditions
 *   The conditions of the query.
 *   [column_id => column_id_value]
 * @param PDO|null $connection
 *   The PDO connection.
 *
 * @return int
 *   The result of executing the query.
 */
function update(string $table, array $parameters = [], array $conditions = [], PDO $connection = null) {
    $query_values = '';
    $last_param_column = array_key_last($parameters);
    foreach ($parameters as $param_column => $value) {
        $query_values .= $param_column === $last_param_column ? "{$param_column} = :{$param_column}" : "{$param_column} = :{$param_column}, ";
    }

    $query_conditions = '';
    $last_condition_column = array_key_last($conditions);
    foreach ($conditions as $condition_column => $condition) {
        $query_conditions .= $condition_column === $last_condition_column ? "{$condition_column} = :{$condition_column}" : "{$condition_column} = :{$condition_column} AND ";
    }

    $query = "UPDATE {$table} SET {$query_values} WHERE {$query_conditions}";

    $query_values = array_merge($parameters, $conditions);
    $statement = executeQuery($query, $query_values, $connection, config_get('database_user_update'), config_get('database_password_update'));

    // Checks if the query has been executed successfully.
    return empty($statement->errorInfo());
}

/**
 * Delete data from the database.
 *
 * @param string $table
 *   The table to delete data from.
 * @param array $conditions
 *   The conditions of the query.
 *
 * @return int
 *   The result of executing the query.
 */
function delete(string $table, array $conditions = []) {
    $query_conditions = '';
    $last_condition_column = array_key_last($conditions);
    foreach ($conditions as $condition_column => $condition) {
        $query_conditions .= $condition_column === $last_condition_column ? "{$condition_column} = :{$condition_column}" : "{$condition_column} = :{$condition_column} AND ";
    }

    $query = "DELETE FROM {$table} WHERE {$query_conditions}";
    $statement = executeQuery($query, $conditions, config_get('database_user_delete'), config_get('database_password_delete'));

    return empty($statement->errorInfo());
}