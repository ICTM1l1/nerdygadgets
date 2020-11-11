<?php

/**
 * Gets the database connection.
 *
 * @return PDO
 *   The database connection.
 */
function getDatabaseConnection(){
    $dsn = config_get('server') . ';';
    $dsn .= 'dbname=' . config_get('database') . ';';
    $dsn .= 'charset=' .  config_get('charset') . ';';
    $dsn .= 'port=' . config_get('port') . ';';

    $username = config_get('user');
    $password = config_get('password');

    $options = [];
    $debug = config_get('debug', false);
    if ($debug) {
        $options[PDO::ATTR_EMULATE_PREPARES] = false;
        $options[PDO::ATTR_ERRMODE] = 2;
    }

    $connection = new PDO($dsn, $username, $password, $options);

    if (!$connection) {
        throw new RuntimeException('Database connection is invalid.');
    }

    return $connection;
}

function executeQuery(string $query, array $parameters = []) {
    $connection = getDatabaseConnection();

    $statement = $connection->prepare($query);

    foreach ($parameters as $column => $value) {
        $statement->bindValue(":{$column}", $value, PDO::PARAM_STR);
    }

    $statement->execute();

    return $statement;
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
    $statement = executeQuery($query, $parameters);

    $values = $statement->fetchAll(PDO::FETCH_NAMED);
    if (count($values) > 1) {
        return $values;
    }

    return $values[array_key_first($values)] ?? [];
}

/**
 * Insert data into the database.
 *
 * @param string $table
 *   The table to insert data in.
 * @param array $parameters
 *   The parameters of the query.
 *
 * @return int
 *   The result of executing the query.
 */
function insert(string $table, array $parameters = []) {
    $columns = '';
    $values = '';

    $last_column = array_key_last($parameters);
    foreach ($parameters as $column => $value) {
        $columns .= $column === $last_column ? $column : "{$column}, ";
        $values .= $column === $last_column ? ":{$column}" : ":{$column}, ";
    }

    $query = "INSERT INTO {$table} ({$columns}) VALUES ({$values})";
    $statement = executeQuery($query, $parameters);

    // Checks if the query has been executed successfully.
    return empty($statement->errorInfo());
}

/**
 * Update data in the database.
 *
 * @param string $table
 *   The table to update data in.
 * @param array $parameters
 *   The parameters of the query.
 * @param array $conditions
 *   The conditions of the query.
 *
 * @return int
 *   The result of executing the query.
 */
function update(string $table, array $parameters = [], array $conditions = []) {
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
    $statement = executeQuery($query, $query_values);

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
    $statement = executeQuery($query, $conditions);

    return empty($statement->errorInfo());
}