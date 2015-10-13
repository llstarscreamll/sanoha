<?php

// Here you can initialize variables that will be available to your tests

require_once '_common/ActivityReports.php';
require_once '_common/CostCenters.php';
require_once '_common/Employees.php';
require_once '_common/MiningActivities.php';
require_once '_common/Permissions.php';
require_once '_common/Roles.php';
require_once '_common/SubCostCenters.php';
require_once '_common/User.php';
require_once '_common/Novelties.php';
require_once '_common/BaseTest.php';
require_once '_common/Vehicles.php';

\Codeception\Util\Autoload::registerSuffix('Page', __DIR__.DIRECTORY_SEPARATOR.'_pages/WorkOrders');