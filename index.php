<?php
try{
  $conn = new PDO("mysql:host=localhost;dbname=assignment",'root','');

  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}


function runQuery($col,$table,$where){
  global $conn;
   
   $query  = "SELECT ".$col." FROM ".$table.(!empty($where) ? " WHERE ".$where : '');
   $stmt   = $conn->prepare($query);
      
   $stmt->execute(); 
   if($stmt->rowCount() > 0){ 
       return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }else{
       return false;
    }
  

}


  $col   = '*';
  $table = 'categories';

  $getCategory = runQuery($col,$table,'');
  
  if($getCategory){

      $outPut = [];

      foreach($getCategory as $v){

           $col   = 'p.*';
           $table = 'category_product cp join products p on cp.product_id = p.id';
           $where = 'cp.category_id = '.$v['id'];
           
           $getProducts = runQuery($col,$table,$where);
           
           if($getProducts != null){
               $outPut[]=['id'=>$v['id'],'name'=>$v['name'],'products'=>$getProducts];
           }
             
      }
       header('Content-type: application/json');
        echo json_encode($outPut);
  }





