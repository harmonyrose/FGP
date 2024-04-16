<?php

class Volunteer {
    private $id;
    private $firstName;
    private $lastName;
    private $email;

    public function __construct($id, $firstName, $lastName, $email) {
        $this->$id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
    }
    public function getid(){
        return $this->id;
    }
    public function setid($id){
        $this->id = $id;
    }
    // Getter for first name
    public function getFirstName() {
        return $this->firstName;
    }

    // Setter for first name
    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    // Getter for last name
    public function getLastName() {
        return $this->lastName;
    }

    // Setter for last name
    public function setLastName($lastName) {
        $this->lastName = $lastName;
    }

    // Getter for email
    public function getEmail() {
        return $this->email;
    }

    // Setter for email
    public function setEmail($email) {
        $this->email = $email;
    }
}

// Example usage:
// $volunteer = new Volunteer("John", "Doe", "john@example.com");
// echo $volunteer->getFirstName(); // Outputs: John
// echo $volunteer->getLastName(); // Outputs: Doe
// echo $volunteer->getEmail(); // Outputs: john@example.com

?>
