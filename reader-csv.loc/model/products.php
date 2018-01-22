<?php
	class ModelProducts extends Model{

		public function getProducts(){
			$this->clearZeroRecord();
			$products = $this->db->query("SELECT * FROM `product`");
			$formated_array = array();

			foreach($products->rows as $item_arr){
				if(!array_key_exists($item_arr['product_name'], $formated_array)){
					$formated_array[$item_arr['product_name']] = array(
						'product_name' => $item_arr['product_name'],
						'qty' => $item_arr['qty'],
						'warehouse' => ''
					);
					$qty_sum = $this->db->query("SELECT SUM(qty) AS value_sum FROM `product` WHERE product_name = '" . $item_arr['product_name'] . "' ");
					$formated_array[$item_arr['product_name']]['qty'] = $qty_sum->row['value_sum'];
					$warehouse = $this->db->query("SELECT DISTINCT(warehouse),qty FROM `product` WHERE product_name = '" . $item_arr['product_name'] . "' ");
					foreach($warehouse->rows as $item_w){
						$formated_array[$item_arr['product_name']]['warehouse'] .= $item_w['warehouse'] . ',';
					}
					$formated_array[$item_arr['product_name']]['warehouse'] = substr($formated_array[$item_arr['product_name']]['warehouse'], 0, -1);
				}
			}
			return $formated_array;
		}

		public function issetProduct($product_name, $warehouse){
			$product = $this->db->query("SELECT * FROM `product` WHERE `product_name` = '" . $this->db->escape($product_name) ."' AND `warehouse` = '" . $this->db->escape($warehouse) . "' ");

			if($product->num_rows)
				return $product->row;
			else
				return false;
		}

		public function addProduct($data = array()){
			if($data['update']) {
				if($data['qty'] > 0){
					$this->db->query("UPDATE `product` SET qty = '" . (int)$data['qty'] . "' WHERE id = '" . (int)$data['id'] . "'");
					$id = $data['id'];
				} else {
					$this->db->query("DELETE FROM `product` WHERE id = '" . (int)$data['id'] . "' ");
					$id = 0;
				}
			} else {
				$this->db->query("  INSERT INTO `product` 
									SET qty = '" . (int)$data['qty'] . "', 
									`product_name` = '" . $this->db->escape($data['product_name']) . "', 
									`warehouse` = '" . $this->db->escape($data['warehouse']) . "' ");
				$id = $this->db->getLastId();
			}
			return $id;
		}

		public function clearZeroRecord(){
			$this->db->query("DELETE FROM `product` WHERE qty = '0' ");
		}
	}