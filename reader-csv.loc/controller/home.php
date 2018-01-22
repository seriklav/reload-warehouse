<?php
	class ControllerHome extends Controller{
		private function install(){
			$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "product` (
					`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
					`product_name` varchar(255) NOT NULL,
					`qty` int(10) NOT NULL,
					`warehouse` varchar(255) NOT NULL,
					PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;
			");
		}
		public function index(){
			$this->install();
			$data = array();
			$this->load->model('products');
			$data['products'] = $this->model_products->getProducts();
			$data['action'] = $this->url->link('controller/home');

			if(isset($_SESSION['error_file_csv'])){
				$data['error_file_csv'] = 'Не верный формат файл CSV!!!';
				unset($_SESSION['error_file_csv']);
			}

			if(!empty($_FILES['csv_file']['tmp_name'])){
				$mimes = array('text/csv');
				if(in_array($_FILES['csv_file']['type'],$mimes)) {
					$csv = new CSV($_FILES['csv_file']['tmp_name']);
					$csv_rows = $csv->getCSV();
					array_shift($csv_rows);
					if($csv_rows){
						foreach ($csv_rows as $item_csv){
							$qty = 0;
							$issetProduct = $this->model_products->issetProduct($item_csv[0], (int)$item_csv[2]);
							if($issetProduct['qty'])
								$qty = (int)$issetProduct['qty'];
							if($qty < 0) {
								$qty = abs($qty);
								$new_qty = $qty-$item_csv[1];
								if($new_qty < 0)
									$new_qty = 0;
								$product_name = $issetProduct['product_name'];
								$warehouse = $issetProduct['warehouse'];

							} else {
								$new_qty = $qty+$item_csv[1];
								if($new_qty < 0)
									$new_qty = 0;
								$product_name = $item_csv[0];
								$warehouse = $item_csv[2];
							}
							$data = array(
								'id' => $issetProduct['id'],
								'update' => $issetProduct['id'] ? 1 : 0,
								'product_name' => $product_name,
								'warehouse' => $warehouse,
								'qty' => $new_qty,
							);
							$id = $this->model_products->addProduct($data);
						}
					}
				} else {
					$_SESSION['error_file_csv'] = 1;
				}

				$this->response->redirect($this->url->link('controller/home'));
			}

			if (file_exists(VIEW .'/home.tpl')) {
				$this->response->setOutput($this->load->view('home.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('not_found.tpl', $data));
			}
		}
	}