<?php
class Database
{
    private $pdo;

    // __construct($path) - конструктор класса, принимает путь к файлу базы данных SQLite;
    public function __construct($path)
    {
        $this->pdo = new PDO('sqlite:' . $path);
    }
    
    // execute($sql) - выполняет запрос $sql;
    public function execute($sql)
    {
        $this->pdo->exec($sql);
    }

    // fetch($sql) - выполняет запрос $sql и возвращает все его результаты;
    public function fetch($sql)
    {
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Create($table, $data) - создает новую запись в таблице $table, используя данные $data;
    public function Create($table, $data)
    {
        $fields = implode(', ', array_keys($data));
        $values = implode(', ', array_map(function ($value) {
            return is_string($value) ? "'{$value}'" : $value;
        }, array_values($data)));
        $this->execute("INSERT INTO {$table} ({$fields}) VALUES ({$values})");
        return $this->pdo->lastInsertId();
    }

    // Read($table, $id) - возвращает запись из таблицы $table с идентификатором $id;
    public function Read($table, $id)
    {
        return $this->fetch("SELECT * FROM {$table} WHERE id = {$id}");
    }

    // Update($table, $id, $data) - обновляет запись в таблице $table с идентификатором $id, используя данные $data;
    public function Update($table, $id, $data)
    {
        $fields = implode(', ', array_map(function ($key, $value) {
            return "{$key} = " . (is_string($value) ? "'{$value}'" : $value);
        }, array_keys($data), array_values($data)));
        $this->execute("UPDATE {$table} SET {$fields} WHERE id = {$id}");
    }

    // Delete($table, $id) - удаляет запись из таблицы $table с идентификатором $id;
    public function Delete($table, $id)
    {
        $this->execute("DELETE FROM {$table} WHERE id = {$id}");
    }

    // Count($table) - возвращает количество записей в таблице $table;
    public function Count($table)
    {
        return $this->fetch("SELECT COUNT(*) as count FROM {$table}")[0]['count'];
    }
}
