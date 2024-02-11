<?php
require '../vendor/autoload.php'; // เรียกใช้งานไฟล์ autoload.php ของ phpspreadsheet

use PhpOffice\PhpSpreadsheet\IOFactory;

// ตรวจสอบว่าไฟล์ถูกอัปโหลดสำเร็จหรือไม่
if(isset($_FILES["fileToUpload"])) {
    $target_dir = "../uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    
    // ตรวจสอบไฟล์ว่าเป็นไฟล์ Excel หรือไม่
    if($fileType != "xlsx" && $fileType != "xls") {
        echo "Sorry, only XLSX, XLS files are allowed.";
        $uploadOk = 0;
    }
    
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            // อ่านข้อมูลจากไฟล์ Excel
            $spreadsheet = IOFactory::load($target_file);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(true, true, true, true);
            
            include('../config.php');
            
            // เปลี่ยนชื่อตารางและชื่อคอลัมน์ตามที่ต้องการ
            $tableName = "test";
            $columnNames = array('colum1', 'colum2', 'colum3');
            
            // เพิ่มข้อมูลลงในฐานข้อมูล
            foreach ($sheetData as $row) {
                $sql = "INSERT INTO $tableName (".implode(',', $columnNames).") VALUES ('".$row['A']."', '".$row['B']."', '".$row['C']."')";
                if ($conn->query($sql) === TRUE) {
                    echo "New record created successfully<br>";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            }
            
            // ปิดการเชื่อมต่อ
            $conn->close();
            
            echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded and data has been inserted into MySQL.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>
