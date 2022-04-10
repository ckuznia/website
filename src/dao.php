<?php
require_once 'logger.php';

// class for saving and getting data from MySQL
class Dao {
    private $logger = null;
    
    private $host = "localhost";
    private $databaseName;
    private $user;
    private $pass;
    
    public function __construct() {
        $this->logger = new KLogger("log.txt", KLogger::DEBUG);
        
        // Update variables with values from local file
        $file = fopen("config/database-config.txt", "r");
        $this->databaseName = rtrim(fgets($file), "\r\n");
        $this->user = rtrim(fgets($file), "\r\n");
        $this->pass = rtrim(fgets($file), "\r\n");
    }

    public function getConnection () {
        try {
            return new PDO("mysql:host={$this->host};dbname={$this->databaseName}", $this->user, $this->pass);
        } catch (Exception $e) {
            $this->logger->LogFatal("The database exploded " . print_r($e,1));
            exit;
        }
    }
  
    public function addUser($firstName, $lastName, $username, $password) {
        $this->logger->LogDebug("addUser() called with username ".$username);
        $conn = $this->getConnection();
        $saveQuery =
            "INSERT INTO user
            (first_name, last_name, username, password)
            VALUES
            (:first_name, :last_name, :username, :password)";
        $stmt = $conn->prepare($saveQuery);
        $stmt->bindParam(":first_name", $firstName);
        $stmt->bindParam(":last_name", $lastName);
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":password", $password);
        if(!$stmt->execute()) {
            $this->logger->LogFatal("Failed to add user $username");
            $this->logger->LogDebug(print_r($stmt->errorInfo(), true));
            return false;
        }
        return true;
    }
    
    public function deleteUser($username) {
        $this->logger->LogDebug("deleteUser() called with username ".$username);
        $conn = $this->getConnection();
        // Delete listing photos
        $query = "
            DELETE FROM listing_photo
            WHERE username = :username";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":username", $username);
        if(!$stmt->execute()) {
            $this->logger->LogFatal("Failed to remove listing photos with for user ".$username);
            $this->logger->LogDebug(print_r($stmt->errorInfo(), true));
        }
        
        // Delete listings
        $query = "
            DELETE FROM listing
            WHERE username = :username";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":username", $username);
        if(!$stmt->execute()) {
            $this->logger->LogFatal("Failed remove listings for user ".$username);
            $this->logger->LogDebug(print_r($stmt->errorInfo(), true));
        }
        
        // Delete account
        $query = "
            DELETE FROM user
            WHERE username = :username";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":username", $username);
        if(!$stmt->execute()) {
            $this->logger->LogFatal("Failed remove user ".$username);
            $this->logger->LogDebug(print_r($stmt->errorInfo(), true));
        }
    }
    
    public function getUserInfo($username) {
        $this->logger->LogDebug("getUserInfo() called with username ".$username);
        $conn = $this->getConnection();
        $query = "
            SELECT first_name, last_name, account_creation_date
            FROM user 
            WHERE username = :username";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(":username", $username, PDO::PARAM_INT);
        if(!$stmt->execute()) {
            $this->logger->LogFatal("Failed to get user info for user $username");
            $this->logger->LogDebug(print_r($stmt->errorInfo(), true));
        }
        $row = $stmt->fetchAll();
        if($row == null || $row == "") {
            return null;
        }
        return $row;
    }
    
    public function verifyAccount($username, $password) {
        $this->logger->LogDebug("verifyAccount() called with username ".$username." and password ".$password);
        $conn = $this->getConnection();
        $query = "
            SELECT username, password 
            FROM user";
        return $conn->query($query);
    }
    
    public function saveListing($username, $propertyID, $zipCode, $city, $state, $address, $name,
        $listPrice, $size, $beds, $baths, $assignedParking, $yearBuilt, $neighborhood, $propertyType,
        $cats, $dogs, $nearbySchools, $contactNumber, $leaseTerms, $description, $photos, $main_photo) {
            
        $this->logger->LogDebug("saveListing() called with username ".$username." and property id ".$propertyID);
        
        // Save listing as a row in the listing table
        $conn = $this->getConnection();
        $saveQuery =
            "INSERT INTO listing
            (property_id, username, zip_code, city, state, address, name, list_price, size,
            beds, baths, assigned_parking, year_built, neighborhood, property_type, cats_allowed,
            dogs_allowed, nearby_schools, contact_number, lease_terms, description, main_photo)
            VALUES
            (:property_id, :username, :zip_code, :city, :state, :address, :name, :list_price, :size,
            :beds, :baths, :assigned_parking, :year_built, :neighborhood, :property_type, :cats_allowed,
            :dogs_allowed, :nearby_schools, :contact_number, :lease_terms, :description, :main_photo)";
        $stmt = $conn->prepare($saveQuery);
        $stmt->bindParam(":property_id", trim($propertyID));
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":zip_code", $zipCode);
        $stmt->bindParam(":city", $city);
        $stmt->bindParam(":state", $state);
        $stmt->bindParam(":address", $address);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":list_price", $listPrice);
        $stmt->bindParam(":size", $size);
        $stmt->bindParam(":beds", $beds);
        $stmt->bindParam(":baths", $baths);
        $stmt->bindParam(":assigned_parking", $assignedParking);
        $stmt->bindParam(":year_built", $yearBuilt);
        $stmt->bindParam(":neighborhood", $neighborhood);
        $stmt->bindParam(":property_type", $propertyType);
        $stmt->bindParam(":cats_allowed", $cats);
        $stmt->bindParam(":dogs_allowed", $dogs);
        $stmt->bindParam(":nearby_schools", $nearbySchools);
        $stmt->bindParam(":contact_number", $contactNumber);
        $stmt->bindParam(":lease_terms", $leaseTerms);
        $stmt->bindParam(":description", $description);
        $stmt->bindParam(":main_photo", $main_photo);
        if(!$stmt->execute()) {
            $this->logger->LogFatal("Failed to add listing with property_id $propertyID");
            $this->logger->LogDebug(print_r($stmt->errorInfo(), true));
        }
        
        // Save listing photos as rows in listing_photo table
        foreach($photos as $photo) {
            $query =
                "INSERT INTO listing_photo
                (username, property_id, house_photo_path)
                VALUES
                (:username, :property_id, :house_photo_path)";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(":username", $username);
            $stmt->bindParam(":property_id", trim($propertyID));
            $stmt->bindParam(":house_photo_path", $photo['href']);
            if(!$stmt->execute()) {
                $this->logger->LogFatal("Failed to add photo to listing_photo table");
                $this->logger->LogDebug(print_r($stmt->errorInfo(), true));
            }
        }
        
    }
    
    public function deleteListing($username, $propertyID) {
        $this->logger->LogDebug("deleteListing() called with username ".$username." and property id ".$propertyID);
        $conn = $this->getConnection();
        $query = "
            DELETE FROM listing
            WHERE property_id = :property_id AND username = :username";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":property_id", trim($propertyID));
        $stmt->bindParam(":username", $username);
        if(!$stmt->execute()) {
            $this->logger->LogFatal("Failed remove listing with id ".$propertyID);
            $this->logger->LogDebug(print_r($stmt->errorInfo(), true));
        }
        
        // Delete listing photos as well
        $query = "
            DELETE FROM listing_photo
            WHERE property_id = :property_id AND username = :username";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":property_id", trim($propertyID));
        $stmt->bindParam(":username", $username);
        if(!$stmt->execute()) {
            $this->logger->LogFatal("Failed to remove listing photos with id ".$propertyID);
            $this->logger->LogDebug(print_r($stmt->errorInfo(), true));
        }
    }
    
    public function getSavedListings($username) {
        $this->logger->LogDebug("getSavedListings() called with username ".$username);
        $conn = $this->getConnection();
        $query = "
            SELECT property_id, zip_code, city, state, address, name, list_price, size,
                beds, baths, assigned_parking, year_built, neighborhood, property_type, cats_allowed,
                dogs_allowed, nearby_schools, contact_number, lease_terms, description, main_photo
            FROM listing 
            WHERE username = :username";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(":username", $username, PDO::PARAM_INT);
        if(!$stmt->execute()) {
            $this->logger->LogFatal("Failed to get listings for user $username");
            $this->logger->LogDebug(print_r($stmt->errorInfo(), true));
        }
        $rows = $stmt->fetchAll();
        if($rows == null || $rows == "") {
            return null;
        }
        return $rows;
    }
    
    public function getPhotos() {
        $this->logger->LogDebug("getPhotos() called");
        $conn = $this->getConnection();
        $query = "
            SELECT property_id, house_photo_path
            FROM listing_photo";
        $stmt = $conn->prepare($query);
        if(!$stmt->execute()) {
            $this->logger->LogFatal("Failed to get photos");
            $this->logger->LogDebug(print_r($stmt->errorInfo(), true));
        }
        $rows = $stmt->fetchAll();
        if($rows == null || $rows == "") {
            return null;
        }
        return $rows;
    }
    
    public function isSavedListing($propertyID) {
        $this->logger->LogDebug("isSavedListing() called with property id ".$propertyID);
        $conn = $this->getConnection();
        $query = "
            SELECT property_id 
            FROM listing 
            WHERE property_id = :property_id";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(":property_id", trim($propertyID), PDO::PARAM_INT);
        if(!$stmt->execute()) {
            $this->logger->LogFatal("Failed to check if property_id $propertyID is saved");
            $this->logger->LogDebug(print_r($stmt->errorInfo(), true));
        }
        $row = $stmt->fetchAll();
        if($row == null || $row == "") {
            return false;
        }
        return true;
    }
}