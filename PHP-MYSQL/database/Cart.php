<?php

// php cart class
class Cart
{
  public $db=null;

  public function __construct(DBCONtroller $db){
      if(!isset($db->con)) return null;
      $this->db=$db;
  }
  // insert into cart table
    public function insertintoCart($param=null, $table="cart"){
      if($this->db->con!=null){
          if($param !=null){
              // insert into cart(userid) value ()
              // get table columns
              $columns=implode(',',array_keys($param));


              $values=implode(',',array_values($param));


              // Create sql query
              $query_string=sprintf("INSERT INTO %s(%s) VALUES(%s)",$table,$columns,$values);

              //execute query
              $result = $this->db->con->query($query_string);
              return $result;
          }
      }
    }

    // to get user_id and item_id and insert into cart table
    public function addToCart($userid,$itemid){
      if(isset($userid) && isset($itemid)){
          $params=array(
              "user_id"=>$userid,
              "item_id"=>$itemid
          );
          $result=$this->insertintoCart($params);
          if($result){
              // Reload Pages
              header("Location:".$_SERVER['PHP_SELF']);
          }
      }
    }
    // delete cart item using cart item id
    public function deleteCart($item_id=null,$table='cart'){
      if($item_id!=null){
          $result=$this->db->con->query("DELETE FROM {$table} WHERE item_id={$item_id}");
          if($result){
              header("Location:" .$_SERVER['PHP_SELF']);
          }
          return $result;
      }
    }
    // caculate sub total
    public function getSum($arr){
      if(isset($arr)){
          $sum=0;
          foreach ($arr as $item){
              $sum+=floatval($item[0]);
          }
          return sprintf('%.2f', $sum);
      }
    }
    // get item_id of shopping cartlist
    public function getCartId($cartArray=null,$key='item_id'){
      if($cartArray!=null){
          $cart_id=array_map(function ($value)use($key){
              return $value[$key];
          },$cartArray);
          return $cart_id;
      }
    }

    // save for late
    public function saveForLater($item_id=null,$saveTable="wishlist",$fromTable="cart"){
        if($item_id!=null){
            $query="INSERT INTO {$saveTable} SELECT * FROM {$fromTable} WHERE item_id={$item_id};";
            $query .="DELETE FROM {$fromTable} WHERE item_id={$item_id};";
            //execute mulitple query'
         
            $result=$this->db->con->multi_query($query);
            if($result){
                header("Location:" .$_SERVER['PHP_SELF']);
            }
            return $result;
        }
    }
}