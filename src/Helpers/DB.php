<?php

/**
 * 
 */

declare(strict_types=1);

namespace Helpers;

use PDO, PDOStatement, PDOException;
use Exception;

/**
 * 
 */
class DB
{
    public $pdo;
    public $connected_to;
    
    protected $configs;
    protected $options;

    /**
     * 
     */
    public function __construct(array $configs = [], array $options = [])
    {
        if (empty($configs)) {
            $this->configs = [
                'default' => [
                    'DB_DRIVER' =>  'mysql',
                    'DB_HOST' =>  '127.0.0.1',
                    'DB_PORT' =>  '3306',
                    'DB_NAME' =>  'default',
                    'DB_CHARSET' =>  'utf8mb4',
                    'DB_USER' =>  NULL,
                    'DB_PASSWORD' =>  NULL
                ]
            ];
        } else {
            $this->configs = $configs;
        }

        $default_options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        // $this->options = array_replace($default_options, $options);
        $this->options = $options + $default_options;

        /* lazy connect, when and if needed */
        $this->connected_to = '';
    }

    public function connect(?string $config_name = null): ?self
    {
        $config_name = $config_name ?? array_key_first($this->configs);
        if (isset($this->configs[$config_name])) {

            $dsn = $this->configs[$config_name]['DB_DRIVER']
                . ':host=' . $this->configs[$config_name]['DB_HOST']
                . ';port=' . $this->configs[$config_name]['DB_PORT']
                . ';dbname=' . $this->configs[$config_name]['DB_NAME']
                . ';charset=' . $this->configs[$config_name]['DB_CHARSET'];

            try {
                $this->pdo = new PDO(
                    $dsn,
                    $this->configs[$config_name]['DB_USER'],
                    $this->configs[$config_name]['DB_PASSWORD'],
                    $this->options
                );
            } catch (PDOException $e) {
                echo 'PDO Error : ' . $e->getMessage() . '<br/>';
                throw new PDOException($e->getMessage(), (int) $e->getCode());
                // die();
            }
            $this->connected_to = $config_name;
            return $this;
        }
        return null;
    }

    /**
     * 
     */
    public function query(string $query, ?array $args = NULL): PDOStatement
    {
        if (is_null($args)) {
            return $this->pdo->query($query);
        }
        $statement = $this->pdo->prepare($query);
        $statement->execute($args);
        return $statement;
    }

    /**
     * Wrap a single :sadface: query in a pdo transaction
     */
    public function transaction(
        string $query,
        ?array $args = NULL
    ): ?array {
        // echo '<pre>HelloPdo->transaction()</pre>';
        try {
            $this->pdo->beginTransaction();
            $statement = $this->query($query, $args);
            $this->pdo->commit();
            // echo '<pre>HelloPdo->transaction() commit : ok.</pre>';
            return $statement->fetchAll();
        } catch (Exception $e) {
            $this->pdo->rollback();
            // throw $e;
            // echo '<pre>HelloPdo->transaction() error : failed :/ .</pre>';
            return null;
        }
    }
}
