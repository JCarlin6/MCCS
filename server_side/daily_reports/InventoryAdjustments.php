<?php
//Crib attendants and Maintenance Supervisor (Notify only IF there were any changes)

//Check to see if there were any manual changes

//Get costs

//Find users in groups

//Compile email text

//Send Email


// (A) CONNECT TO DATABASE
$dbhost = '10.162.0.40';
$dbname = 'MCCS';
$dbchar = 'utf8';
$dbuser = 'root';
$dbpass = 'Ilikecheese2';
try {
  $pdo = new PDO(
    "mysql:host=$dbhost;charset=$dbchar;dbname=$dbname",
    $dbuser, $dbpass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
  );
} catch (Exception $ex) {
  die($ex->getMessage());
}

// (B) PHPSPREADSHEET
require "/var/www/html/vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// (C) CREATE A NEW SPREADSHEET + POPULATE DATA
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('PastDueWO\'s');
$stmt = $pdo->prepare("SELECT * FROM getPastDueWO");
$stmt->execute();
$i = 1;
//SET HEADERS
$sheet->setCellValue('A'.$i, 'WorkOrderID');
$sheet->setCellValue('B'.$i, 'Days Overdue');
$sheet->setCellValue('C'.$i, 'Department');
$sheet->setCellValue('D'.$i, 'Description');
$sheet->setCellValue('E'.$i, 'Asset Name');
$sheet->setCellValue('F'.$i, 'Categorical Detail');
$sheet->setCellValue('G'.$i, 'First Name');
$sheet->setCellValue('H'.$i, 'Last Name');
$sheet->setCellValue('I'.$i, 'Requested End Date');
$i++;
//ROW DATA
while ($row = $stmt->fetch(PDO::FETCH_NAMED)) {
  $sheet->setCellValue('A'.$i, $row['WorkOrderID']);
  $sheet->setCellValue('B'.$i, $row['DaysOverdue']);
  $sheet->setCellValue('C'.$i, $row['Department']);
  $sheet->setCellValue('D'.$i, $row['Description']);
  $sheet->setCellValue('E'.$i, $row['AssetName']);
  $sheet->setCellValue('F'.$i, $row['CategoryDetail']);
  $sheet->setCellValue('G'.$i, $row['FirstName']);
  $sheet->setCellValue('H'.$i, $row['LastName']);
  $sheet->setCellValue('I'.$i, $row['RequestedEndDate']);
  $i++;
}

//Set Auto Size
$spreadsheet->getActiveSheet()->getColumnDimensionByColumn('A')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimensionByColumn('B')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimensionByColumn('C')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimensionByColumn('D')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimensionByColumn('E')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimensionByColumn('F')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimensionByColumn('G')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimensionByColumn('H')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimensionByColumn('I')->setAutoSize(true);

$today = date("Y-m-d");

// (D) SAVE
$writer = new Xlsx($spreadsheet);
$writer->save("/var/www/html/archive/overdue_workorders/Overdue Workorders $today.xlsx");
echo "OK";
?>