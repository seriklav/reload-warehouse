<?php
	class ModelProducts extends Model{

		public function getProducts(){
			$products = $this->db->query("SELECT product_name, GROUP_CONCAT(DISTINCT warehouse) as warehouse, SUM(qty) as qty FROM `product` GROUP BY product_name HAVING qty > 0");

			return $products->rows;
		}

		public function issetProduct($product_name, $warehouse){
			$product = $this->db->query("SELECT * FROM `product` WHERE `product_name` = '" . $this->db->escape($product_name) ."' AND `warehouse` = '" . $this->db->escape($warehouse) . "' ");

			if($product->num_rows)
				return $product->row;
			else
				return false;
		}
		public function addProduct($data = array()){
			if($data['id']) {
				$this->db->query("UPDATE `product` SET qty = '" . (int)$data['qty'] . "' WHERE id = '" . (int)$data['id'] . "'");
				$id = $data['id'];
			} else {
				$this->db->query("INSERT INTO `product` 
									SET qty = '" . (int)$data['qty'] . "', 
									`product_name` = '" . $this->db->escape($data['product_name']) . "', 
									`warehouse` = '" . $this->db->escape($data['warehouse']) . "' ");
				$id = $this->db->getLastId();
			}
			return $id;
		}
	}
