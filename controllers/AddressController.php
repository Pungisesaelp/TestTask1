<?php
use service\Address;

include_once ROOT . '/models/Address.php';

/**
 * Контроллер CabinetController
 * Кабинет пользователя
 */
class AddressController
{

    public function actionIndex()
    {
        $cityList = Address::getCities(); 
        $Addresses =     Address::getAddresses(); 
         
        if (isset($_POST["id"])) {
            $id = intval($_POST["id"]);
            if (! empty($id)) {
                $areas = Address::getAreaList($id);
                
                echo "<select name=\"area\">";
                $count = 0;
                foreach ($areas as $area) {
                    echo "<option class=\"plh\" value=" . $area["id"] . ">" . $area["name"] . "</option>";
                }
                echo "</select>";
            }
        } else {
            require_once (ROOT . '/views/user_office_address.php');
        }
    }

    public function actionCreate()
    {
        $name = htmlspecialchars($_POST["name"]); 
        $area = htmlspecialchars($_POST["area"]);
        $street = htmlspecialchars($_POST["street"]);
        $house = htmlspecialchars($_POST["house"]);
        $additionalInformation = htmlspecialchars($_POST["additionalInformation"]);
        
        Address::createAddress($name, $area, $street, $house, $additionalInformation);
    }
}
