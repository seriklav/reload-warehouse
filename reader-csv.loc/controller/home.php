<?php
	class ControllerHome extends Controller{
		private function install(){
			$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "product` (
					`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
					`product_name` varchar(255) NOT NULL,
					`qty` int(10) NOT NULL,
					`warehouse` varchar(255) NOT NULL,
					PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=10;
			");
		}
		public function index(){
			$this->install();
			$data = array();
			$this->load->model('products');

			$data['action'] = $this->url->link('controller/home');

			if(!empty($_FILES['csv_file']['tmp_name'])){
				$csv = new CSV($_FILES['csv_file']['tmp_name']);
				$csv_rows = $csv->getCSV();
				array_shift($csv_rows);
				//echo '<pre>'; var_dump($csv_rows); echo '</pre>';
				if($csv_rows){
					foreach ($csv_rows as $item_csv){
						$qty = 0;
						$issetProduct = $this->model_products->issetProduct($item_csv[0], $item_csv[2]);

						if ($issetProduct) {
							$new_qty = (int)$issetProduct['qty'] + (int)$item_csv[1];
						} else {
							$new_qty = (int)$item_csv[1];
						}

						$product_name = $item_csv[0];
						$warehouse = $item_csv[2];

						$data = array(
							'id' => $issetProduct ? $issetProduct['id'] : 0,
							'product_name' => $product_name,
							'warehouse' => $warehouse,
							'qty' => $new_qty,
						);
						$id = $this->model_products->addProduct($data);
					}
				}
				$this->response->redirect($this->url->link('controller/home'));
			}
			$data['products'] = $this->model_products->getProducts();
			if (file_exists(VIEW .'/home.tpl')) {
				$this->response->setOutput($this->load->view('home.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('not_found.tpl', $data));
			}
		}
	}
