<?php
  define("_VALID_PHP", True);
  require_once("../../init.php");
  ini_set('display_errors', 1);

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
$sheet->setTitle('PastDuePM\'s');
$stmt = $pdo->prepare("SELECT * FROM getPastDuePM");
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
foreach (range('A', $spreadsheet->getActiveSheet()->getHighestDataColumn()) as $col) {
  $spreadsheet->getActiveSheet()
          ->getColumnDimension($col)
          ->setAutoSize(true);
}

$today = date("Y-m-d");

// (D) SAVE
$writer = new Xlsx($spreadsheet);
$writer->save("/var/www/html/archive/overdue_pm/Overdue PMs $today.xlsx");
echo "OK";


$body = " Team, <br /><br /> The past due PM report has been automatically generated and can be retrieved at this <a href=\"http://10.162.0.40/archive/overdue_pm/Overdue PMs $today.xlsx\">link</a> for $today. <br /><br /> Thank you. ";
$body = $content->MailNotice($body);
$MailSubject = "MCS: Past Due PM Report";
$content->InternaltoExternalMailCall($body, $MailSubject);

?>