<?php
/**
 * Encapsulated version of a dbGiftCardVendors entry.
 */
class Vendor {
    private $id;
    private $name;
    private $type;
    private $location;

    function __construct($id, $name, $type, $location) {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->location = $location;
    }

    function get_id() {
        return $this->id;
    }

    function get_name() {
        return $this->name;
    }

    function get_type() {
        return $this->type;
    }

    function get_location() {
        return $this->location;
    }


}