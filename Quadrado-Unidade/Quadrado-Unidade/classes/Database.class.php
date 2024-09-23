<?php

require_once('../config/config.inc.php'); // Inclui o arquivo de configuração com as credenciais do banco de dados

class Database {
    // Atributo estático para armazenar a instância da conexão PDO e o ID da última inserção realizada
    private static $instance = null;
    public static $lastId; 

    // Método estático para obter uma instância única da conexão PDO (Singleton)
    public static function getInstance() {
        if (self::$instance === null) {
            try {
                // Criando nova conexão PDO usando as constantes definidas no arquivo de configuração
                self::$instance = new PDO(DSN, USUARIO, SENHA);
                // Configura PDO para lançar exceções em caso de erro
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                // Exibe uma mensagem de erro em caso de falha de conexão
                die("Erro ao conectar ao banco de dados: " . $e->getMessage());
            }
        }
        return self::$instance; // Retorna a instância da conexão PDO
    }

    // Método estático para obter uma instância da conexão, alias para getInstance()
    public static function conectar() {
        return self::getInstance();
    }

    // Método estático para preparar uma consulta SQL usando a conexão fornecida
    public static function preparar($conexao, $sql) {
        try {
            return $conexao->prepare($sql); // Prepara e retorna a consulta SQL
        } catch (PDOException $e) {
            throw new Exception("Erro ao preparar a consulta: " . $e->getMessage());
        }
    }

    // Método estático para vincular parâmetros a uma consulta preparada
    public static function vincular($comando, $parametros = array()) {
        // Itera sobre o array de parâmetros e associa cada valor ao seu respectivo parâmetro na consulta
        foreach ($parametros as $key => $value) {
            $comando->bindValue($key, $value); 
        }
        return $comando; // Retorna o comando com os parâmetros vinculados
    }

    // Método estático para executar uma consulta SQL com parâmetros opcionais
    public static function executar($sql, $parametros = array()) {
        $conexao = self::conectar(); // Obtém uma instância da conexão

        // Prepara e vincula os parâmetros à consulta
        $comando = self::preparar($conexao, $sql); 
        $comando = self::vincular($comando, $parametros); 

        try {
            $comando->execute(); // Executa a consulta

            // Armazena o ID da última inserção realizada, se houver
            self::$lastId = $conexao->lastInsertId(); 

            return $comando; // Retorna o comando para buscar resultados, se necessário
        } catch (PDOException $e) {
            // Captura e lança uma exceção mais detalhada em caso de erro na execução da consulta
            throw new Exception("Erro ao executar o comando no banco de dados: " . $e->getMessage() . " - " . $comando->errorInfo()[2]);
        }
    }
}
