<?php
namespace service;
 
use Db;
use PDO;

class Address

{
    public static function getAddresses()
    {
        $db = Db::getConnection();
        $sql = 'SELECT * FROM address ORDER BY name';
        $result = $db->prepare($sql);
        
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();
        $messageList = array();
        $i = 0;
        while ($row = $result->fetch()) {
            $area = (Address::getArea($row['area_id']));
            $area = array_shift ($area); 
           $city = Address::getCity($area["city_id"])[0]; 
           
            $messageList[$i]['id'] = $row['id'];
            $messageList[$i]['name'] = $row['name'];  
            $messageList[$i]['area'] = $area;
            $messageList[$i]['city'] = $city; 
            $messageList[$i]['street'] = $row['street'];
            $messageList[$i]['house'] = $row['house'];
            $messageList[$i]['street'] = $row['street'];
            $messageList[$i]['additional_information'] = $row['additional_information'];
            $i ++;
        }
        $db = null;
        return $messageList;
    }
    public static function getArea($id)
    {
        $db = Db::getConnection();
        $sql = 'SELECT * FROM area where id=:id';
        $result = $db->prepare($sql);
 
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();
        $messageList = array();
        $i = 0;
        while ($row = $result->fetch()) {
            $messageList[$i]['id'] = $row['id'];
            $messageList[$i]['name'] = $row['name'];
            $messageList[$i]['city_id'] = $row['city_id'];
            
            $i ++;
        }
        $db = null;
        return $messageList;
    }
    public static function getCity($id)
    {
        $db = Db::getConnection();
        $sql = 'SELECT * FROM city where id=:id';
        $result = $db->prepare($sql);
        
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();
        $messageList = array();
        $i = 0;
        while ($row = $result->fetch()) {
            $messageList[$i]['id'] = $row['id'];
            $messageList[$i]['name'] = $row['name']; 
            
            $i ++;
        }
        $db = null;
        return $messageList;
    }
    
    
    
    
    public static function createAddress($name, $area, $street, $house, $additionalInformation)
    {   $db = Db::getConnection();
        $sql = "INSERT INTO address (name, area_id, street, house, additional_information )
         VALUES (:name, :area_id, :street, :house, :additional_information )";
        $result = $db->prepare($sql);
        $result->bindParam(':name', $name, PDO::PARAM_STR);
        $result->bindParam(':area_id', $area, PDO::PARAM_INT);
        $result->bindParam(':street', $street, PDO::PARAM_STR);
        $result->bindParam(':house', $house, PDO::PARAM_STR);
        $result->bindParam(':additional_information', $additionalInformation, PDO::PARAM_STR);
        $result->execute();
        $id = $db->lastInsertId();
        $db = null;
        return $id;
    }

    public static function getCities()
    {
        $db = Db::getConnection();
        $result = $db->query('SELECT * FROM city');
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();
        $addressList = array();
        $i = 0;
        while ($row = $result->fetch()) {
            $addressList[$i]['id'] = $row['id'];
            $addressList[$i]['name'] = $row['name']; 
            $i ++;
        }
        $db = null;
        return $addressList;
    }

    public static function getAreaList($id)
    {
        $id = intval($id);
        if ($id) { 
            $db = Db::getConnection();
            $result = $db->query('SELECT * FROM area WHERE city_id=' . $id);
            $result->setFetchMode(PDO::FETCH_ASSOC);
            $result->execute();
            $areaList = array();
            $i = 0;
            while ($row = $result->fetch()) {
                $areaList[$i]['id'] = $row['id'];
                $areaList[$i]['name'] = $row['name'];
                $i ++;
            }
            $db = null;
            return $areaList;
        }
    }
}

