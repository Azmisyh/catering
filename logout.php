<?php
require_once 'config.php';

session_unset();   // hapus semua session
session_destroy(); // hancurkan session

redirect('login.php');
