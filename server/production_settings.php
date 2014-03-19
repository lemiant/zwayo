<?php

function connect_to_mch(){
    //return mysqli_connect("localhost","root","","mch"); //Local
    return mysqli_connect("localhost","root","safeway","mch"); //DigitalOcean
}
