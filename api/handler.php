<?php
    $conn= new mysqli('localhost','root','','customers_db');
    if ($_SERVER['REQUEST_METHOD']=='GET'){
        if(isset($_GET['id'])){
            $id =$conn->real_escape_string($_GET['id']);
            $sql=$conn->query('SELECT name, age FROM customers WHERE id='.$id);
            $data=$sql->fetch_assoc();
        }else{
            $data =array();
            $sql=$conn->query('SELECT name, age FROM customers');
            while($d=$sql->fetch_assoc()){
                $data[]=$d;
            }
        }
        exit(json_encode($data));
    }elseif($_SERVER['REQUEST_METHOD']=='POST'){
        if(isset($_POST['name']) && isset($_POST['age'])){
            $name=$conn->real_escape_string($_POST['name']);
            $age=$conn->real_escape_string($_POST['age']);
            $qry='INSERT INTO customers (name, age) VALUES ("'.$name.'",'.$age.')';
            $sql=$conn->query($qry);
            exit(json_encode(array("status"=>'Success')));
        }else{
            exit(json_encode(array("status"=>'Failed',"message"=>'Please check your Input')));
        }
    }elseif($_SERVER['REQUEST_METHOD']=='PUT'){
        $allPairs=array();
        $data= urldecode(file_get_contents('PHP://input'));
        //data="name=sahil D&age=24"
        if (strpos($data,"=")!== False){
            $data=explode("&",$data);
            foreach ($data as $pair){
                $pair =explode("=",$pair);
                $allPairs[$pair[0]]=$pair[1];
            }
            $id = $conn->real_escape_string($_GET['id']);
            if(isset($allPairs['name']) && isset($allPairs['age'])){
                $qry='UPDATE customers SET name="' . $allPairs['name'] . '" , age='.$allPairs['age'] . ' WHERE id='.$id;
                $conn->query($qry);
            }elseif(isset($allPairs['name'])){
                $qry= 'UPDATE customers SET `name`="'.$allPairs['name'].'" WHERE id='.$id;
                $conn->query($qry);
            }elseif(isset($allPairs['age'])){
                $qry ='UPDATE cutomers SET `age`='.$allPairs['age'].' WHERE id='.$id;
                $conn->query($qry);
            }else{
                exit(json_encode(array("status"=>'Falied',"message"=>"Please check your input")));
            }
            exit (json_encode(array("status"=>'Success')));
        }
    }elseif($_SERVER['REQUEST_METHOD']=='DELETE'){
        if (isset($_GET['id'])){
            $id=$conn->real_escape_string($_GET['id']);
            $sql=$conn->query('DELETE FROM customers where id='.$id);
            exit(json_encode(array("status"=>'Success')));
        }else{
            exit(json_encode(array("status"=>'Failed',"message"=>'Please provide the id')));
        }
    }
?>