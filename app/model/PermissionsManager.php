<?php

namespace App\Model;

use Nette;
use App\Model\VillageManager;

class PermissionsManager
{
    use Nette\SmartObject;

    /**
     * @var Nette\Database\Context
     */
    private $database;
    /** 
     * @var VillageManager 
     */
    private $villageManager;
    
    /** 
     * @array column 
     */
    private $column = array(
          "1" => "search",
          "2" => "contactlist",
      );

    public function __construct(Nette\Database\Context $database,VillageManager $villageManager)
    {
        $this->database = $database;
        $this->villageManager = $villageManager;
        $this->existing_villages = $this->villageManager->getVillages();
    }

    
     
    public function set(int $userId,array $values)
    {
            
        if ($userId>0) {
            $this->database->query('UPDATE user_admin SET', [
                        'search' => serialize($values["search"]),
                        'contactlist' => serialize($values["contactlist"]),
                         ], 'WHERE id = ?', $userId);
        } else {
           $this->database->query('INSERT INTO user_admin', [
                'name' => serialize($values["name"]),
                'search' => serialize($values["search"]), 
                'contactlist' => $values["contactlist"]);
        }
        
     }
     
     
    /** 
     * int permission_type sent from presenter, for our example we got "1" for Search and "2" for Contactlist
     * 
     * @return array of allowed villages IDs 
     */
     public function get(int $userId,int $permission_type)
      {
          if ($userId && isset($column[$permission_type])) {
           $permissions = $this->database->fetchField('SELECT '.$column[$permission_type].' FROM user_admin WHERE user_id = ?', $userId);
           $permissions=unserialize($permissions);
            
              /** Check if at least one value is true and count of "trues" 
               * is not same as total array count, this means that at least 
               * one false is in permission = no full access  
               */
             if(count(array_filter($permissions))>0 && count(array_filter($permissions))!=count(($permissions))) {
              return array_filter($permissions));   
             } else {
              return $this->existing_villages;   
             }
         } 
      
      }  
       
}