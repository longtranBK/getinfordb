<?php
$servername = "localhost";
$username = "root";
$password = "";
$namedb = "nguyenquanganh_test";

// Create connection
$conn = new mysqli($servername, $username, $password, $namedb);
//  Check connection
if ($conn->connect_error) {
    die("Connection failed : " . $conn->connect_error);
}
echo "Connection thanh cong" . "<br>";
// Tạo câu truy vấn
$sqlGetTableName = "SELECT table_name FROM information_schema.tables WHERE table_type = 'base table' AND table_schema = '$namedb'";
// Thực thi truy vấn
$result = $conn->query($sqlGetTableName);
if(!$result){
 die("Loi " . mysqli_error($conn));
}
// Nếu kết quả có nhiều hơn 1 hàng trả về
if ($result->num_rows > 0) {
while($tableName = $result->fetch_assoc()){
    // Tạo câu truy vấn
    $sqlCountRecord = "SELECT COUNT(*) AS SoRecord FROM " .$tableName["table_name"];
    // Thực thi truy vấn
    $result1 = $conn->query($sqlCountRecord);
    if(!$result1){
        die("Loi " . mysqli_error($conn));
       }
    //    Gọi đến hàm fetch_assoc() trong php đưa kết quả vào 1 mảng $count
       $count = $result1->fetch_assoc()["SoRecord"];
    // Ghi dữ liệu vào file
    writefile("list_table.txt", $tableName["table_name"], $count);
            
    $sqlGetColumnName = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '$namedb' AND TABLE_NAME = '" .$tableName["table_name"] ."'";
      $result2 = $conn->query($sqlGetColumnName);   
      if(!$result2){
        die("Loi " . mysqli_error($conn));
       }
    //    Nếu kết quả có nhiều hơn 1 hàng trả về
      if($result2->num_rows > 0){
            while( $columnName = $result2->fetch_assoc()){
                    // Ghi dữ liệu vào filefile
                    writefile("List_column.txt", $tableName["table_name"],$columnName["COLUMN_NAME"], "");
                }
            }
    }
}else{
    echo "0 result";
}
// Hàm dùng chung thực hiện việc ghi file
function writefile($str1, $str2, $str3){
    $file = fopen($str1,"a");
    fwrite($file, $str2 . "\t" .$str3 . "\r\n");
    fclose($file);
}
$conn->close();
echo "finish";
?>