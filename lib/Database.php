<?php

//******************************************************************
//Classe: Database
//Objetivo: classe estatica, responsavel pelas operacoes repetitivas
//          de um banco de dados, como abertura, transacoes,
//          execucao de insercao, alteracao, consulta e exclusao de
//          um ou mais registros. Ela tambem eh responsavel por 
//          armazenar todos os dados ara a conexao com o banco de
//          dados.
//******************************************************************
class Database 
{
    //private static $DATABASE_NAME = "wiseevol_mes";
    private static $DATABASE_NAME = "tjspsip";
    //private static $DATABASE_USER = "wiseevol_mes";
    //private static $DATABASE_PWD = "o49d!4AbMst4";
    private static $DATABASE_USER = "root";
    private static $DATABASE_PWD = "12345678";    
    private static $DATABASE_URL = "localhost";    
    private static $DB = NULL;
    
    //******************************************************************
    //Metodo: getNewId()
    //Objetivo: se um registro foi adicionado, caso  a tabela tenha uma
    //          uma chave primaria com autonumeracao esse metodo retor-
    //          nara o codigo do registro recem inserido
    //******************************************************************
    public static function getNewId()
    {
        return self::$DB->lastInsertId();
    }
    
    //******************************************************************
    //Metodo: open()
    //Objetivo: configura e abre um banco de dados
    //******************************************************************
    public static function open()
    {
        try
        {
            $dsn = "mysql:host=" . self::$DATABASE_URL . ";dbname=" . self::$DATABASE_NAME . ";charset=utf8";
            self::$DB = new PDO($dsn, self::$DATABASE_USER, self::$DATABASE_PWD);
            self::$DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$DB->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        }
        catch (PDOException $e)
        {
            throw new Exception($e->getMessage());           
        }
    }   

    //******************************************************************
    //Metodo: beginTransaction()
    //Objetivo: inicia uma transacao
    //******************************************************************
    public static function beginTransaction()
    {
        self::$DB->beginTransaction();
    }
    
    //******************************************************************
    //Metodo: rollback()
    //Objetivo: cancela uma transacao
    //******************************************************************
    public static function rollback()
    {
        self::$DB->rollback();
    }
    
    //******************************************************************
    //Metodo: commit()
    //Objetivo: finaliza uma transacao
    //******************************************************************
    public static function commit()
    {
        self::$DB->commit();
    }

    //******************************************************************
    //Metodo: execute()
    //Objetivo: executa uma instraucao de insert, update e delete sem
    //          valor de retorno 
    //******************************************************************
    public static function execute($sql, $params = NULL)
    {
        try 
        {
            $stmt = self::$DB->prepare($sql);
            $stmt->execute($params);
        }
        catch (PDOException $e)
        {
            throw new Exception($e->getMessage());
        }
    }
    
    //******************************************************************
    //Metodo: query()
    //Objetivo: executa sempre uma operacao de consulta e retorna uma
    //          ou mais linhas de registros
    //******************************************************************
    public static function query($sql, $params = NULL)
    {
        try
        {
            $stmt = self::$DB->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        }
        catch (PDOException $e)
        {
            throw new Exception($e->getMessage());
        }        
    }    
    
    //******************************************************************
    //Metodo: get()
    //Objetivo: executa sempre uma operacao de consulta e retorna somente
    //          uma linha de registro
    //******************************************************************
    public static function get($sql, $params = NULL)
    {
        try
        {
            $stmt = self::$DB->prepare($sql);
            $stmt->execute($params); 
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            throw new Exception($e->getMessage());
        }
    }
    
    //******************************************************************
    //Metodo: close()
    //Objetivo: fecha e libera o banco de dados
    //******************************************************************
    public static function close()
    {
        Database::$DB = NULL;
    }
}
