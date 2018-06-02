<?php

/**
 * This is an example of User Class using PDO
 *
 */

namespace models;
use PDO;
use Psr\Container\ContainerInterface;

class User {

	protected $dbh;
    protected $c;

	function __construct(PDO $db,ContainerInterface $ci) {
		//$this->core->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->dbh = $db;
        $this->c = $ci;
	}
	
	// Get all users
	public function getUsers() {
		$r = array();		

		$sql = "SELECT * FROM evnt_usuario";
		$stmt = $this->dbh->prepare($sql);
		//$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

		if ($stmt->execute()) {
			$r = $stmt->fetchAll(PDO::FETCH_ASSOC);		   	
		} else {
			$r = 0;
		}		
		return $r;
	}

    /**
     * 根据openid获取用户信息
     * @param $openid
     * @return mixed
     */
	public function getUserById($openid) {
		$sql = "SELECT * From t_user WHERE openid=:openid";
		$stmt = $this->dbh->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
		$stmt->bindParam(':openid', $openid, PDO::PARAM_STR);
        if($stmt->execute()){
            $user = $stmt->fetch();
            //$this->c->logger->info(implode(" ", $user));
            return $user;
        }else{
            return 0;
        }
	}

    // Get user by the Id
    public function getLastUser() {
        $r = array();
        try{
            $sql = "SELECT order_num From t_user ORDER BY id DESC limit 1";
            $stmt = $this->dbh->prepare($sql);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            if ($stmt->execute()) {
                $r = $stmt->fetch();
                $this->c->logger->info("execute ".$sql ." result ".json_encode($r));

            } else {
                $r = 0;
                $this->c->logger->info("execute ".$sql ." result fail");
            }
        }catch (PDOException $e){
            $this ->c ->logger ->error($e ->getMessage());
        }

        return $r;
    }


    // Get user by the Login
	public function getUserByLogin($email, $pass) {
		$r = array();		
		
		$sql = "SELECT * FROM user WHERE email=:email AND password=:pass";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(':email', $email, PDO::PARAM_STR);
		$stmt->bindParam(':pass', $pass, PDO::PARAM_STR);

		if ($stmt->execute()) {
			$r = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} else {
			$r = 0;
		}		
		return $r;
	}

	// Insert a new user
	public function insertUser($data) {

		try {
            $this ->dbh ->beginTransaction();
            $last = $this->getLastUser();
            if($last) {
                if(!isset($last['order_num'])){
                    $last['order_num'] = 0;
                }
                $sql = "INSERT INTO t_user (openid, r_name, join_time, order_num) 
                        VALUES (:openid, :r_name, now(), :order_num)";
    //            $data['join_time'] = date('Y-m-d H:i:s');
                $data['order_num'] = intval($last['order_num'])+1;
                $stmt = $this->dbh->prepare($sql);
                if ($stmt->execute($data)) {
                    $res =  $this->dbh->lastInsertId();
                    $this->c->logger->info("execute ".$sql ." result ".json_encode($res));

                    $this ->dbh ->commit();
                    return $res;
            } else {
                $this->c->logger->info("execute ".$sql ." result fail");

                return '0';
			}
            }else{
                return '0';
            }
        } catch(PDOException $e) {
            $this ->dbh ->rollBack();
            $this ->c ->logger ->error($e ->getMessage());
    	}
        return "0";

    }

	// Update the data of an user
	public function updateUser($data) {
		
	}

	// Delete user
	public function deleteUser($id) {
		
	}

}