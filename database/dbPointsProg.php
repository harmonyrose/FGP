<?php

include_once('dbinfo.php');
include_once(dirname(__FILE__).'/../domain/PointsProg.php');


function add_points_form($result_row) {
    $thePointsProg = new PointsProg(
        $result_row['id'],
        $result_row['name'],
        $result_row['address'],
        $result_row['freezer_meals'],
        $result_row['allergies'],
        $result_row['snacks'],
        $result_row['snack_notes'],
        $result_row['foodlion'],
        $result_row['giant'],
        $result_row['walmart'],
        $result_row['wegmans'],
        $result_row['sheetz'],
        $result_row['wawa'],
        $result_row['house_cleaning'],
        $result_row['lawn_care'],
        $result_row['AAA_membership'],
        $result_row['AAA_membership_name'],
        $result_row['AAA_membership_DOB'],
        $result_row['photography'],
        $result_row['house_projects'],
        $result_row['financial_relief'],
        $result_row['points_used']
    );   
    return $thePointsProg;
}