<?php
$conn = mysqli_connect("localhost", "root", "", "seid_ac_inj");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
