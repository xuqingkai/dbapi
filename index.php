<?php
$pdo_connection = new PDO("mysql:dbname=cms_d6m_cn;host=127.0.0.1", 'cms_d6m_cn', 'cms_d6m_cn');
$prefix='sea_';
$arg=explode('/',urldecode($_SERVER['PATH_INFO']));
if($arg[1]){
    $method=$_SERVER['REQUEST_METHOD'];
    if($method=='GET'){
        $sql='SELECT * FROM `'.$prefix.$arg[1].'`';
        if(strlen($arg[2])>0){
            if(is_numeric(substr($arg[2],0,1))){
                $id=intval($arg[2]);
                if($id<0){
                    $sql='DELETE FROM `'.$prefix.$arg[1].'` WHERE `id`='.abs($id);
                    exit_json(pdo_query($sql));
                }else{
                    $sql.=' WHERE `id`='.$arg[2]; 
                    exit_json(pdo_find($sql));
                }
            }else{
                if(substr($arg[2],0,1)=='-'){
                    $sql='DELETE FROM `'.$prefix.$arg[1].'` WHERE '.substr($arg[2],1);
                    exit_json(pdo_query($sql));
                }else if(substr($arg[2],0,1)=='+'){
                    $page=explode(',',substr($arg[2],1).',10');
                    $page[0]=intval($page[0])-1;
                    if($page[0]<0){$page[0]=0;}
                    $page[1]=intval($page[1]);
                    if($arg[3]){$sql.=' WHERE '.$arg[3];}
                    $sql.=' LIMIT '.($page[0]*$page[1]).','.($page[0]*$page[1]+$page[1]);
                    //exit($sql);
                    exit_json(pdo_select($sql));
                }else{
                    if($arg[2]){$sql.=' WHERE '.$arg[2];}
                    exit_json(pdo_select($sql));
                }
            }
        }else{
            exit_json(pdo_select($sql));
        }
    }
    if($method=='POST'){
        $sql='SELECT * FROM `'.$prefix.$arg[1].'`';
        if(strlen($arg[2])>0){
            if(is_numeric($arg[2])){
                $id=abs(intval($arg[2]));
                $data = pdo_find($sql.' WHERE `id`='.$id);
   
                if($id==0){
                    $fields='';
                    $values='';
                    foreach ($_POST as $key=>$val){
                        $fields.=',`'.$key.'`';
                        $values.=",'".addslashes($val)."'";
                    }
                    if(strlen($fields)>0){$fields=substr($fields,1);}
                    if(strlen($values)>0){$values=substr($values,1);}
                    $sql='INSERT INTO `'.$prefix.$arg[1].'` ('.$fields.') VALUES ('.$values.')';
                }else{
                    $sql='UPDATE `'.$prefix.$arg[1].'` SET';
                    foreach ($_POST as $key=>$val){
                        $sql.=" `".$key."`='".addslashes($val)."'";
                    }
                    $sql.=' WHERE `id`='.$id;
                }
                exit_json(pdo_query($sql));
            }else{
                $sql='UPDATE `'.$prefix.$arg[1].'` SET';
                foreach ($_POST as $key=>$val){
                    $sql.=" `".$key."`='".addslashes($val)."'";
                }
                $sql.=' WHERE '.$arg[2];
                exit_json(pdo_query($sql));
            }
        }else{
            exit_json(pdo_select($sql));
        }
    }
}
function pdo_query($sql){
    global $pdo_connection;
    $pdoStatement=$pdo_connection->prepare($sql);
    $pdoStatement->execute(); 
    return true;
}
function pdo_find($sql){
    global $pdo_connection;
    $pdoStatement=$pdo_connection->prepare($sql);
    $pdoStatement->execute(); 
    $array=$pdoStatement->fetch(PDO::FETCH_ASSOC);
    return $array;
}
function pdo_select($sql){
    global $pdo_connection;
    $pdoStatement=$pdo_connection->prepare($sql);
    $pdoStatement->execute(); 
    $array=$pdoStatement->fetchAll(PDO::FETCH_ASSOC);
    return $array;
}
function exit_json($data){
    if($data===true){
        $data=array('id'=>0,'code'=>'SUCCESS','message'=>'成功');
    }else if($data==false){
        $data=array('id'=>1,'code'=>'FAIL','message'=>'失败');
    }else{
        $data=array('id'=>0,'code'=>'SUCCESS','message'=>'成功','data'=>$data);
    }
    exit(json_encode($data));
}

?>
