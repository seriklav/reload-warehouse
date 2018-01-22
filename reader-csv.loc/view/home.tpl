<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
	      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link href="<?=STYLES?>css/bootstrap.min.css" rel="stylesheet">
	<link href="<?=STYLES?>css/style.css" rel="stylesheet">
	<title>Home</title>
</head>
<body>
	<div class="container">
		<?php if(isset($error_file_csv)){ ?>
			<div class="alert alert-danger alert-dismissible" role="alert" style="margin-top: 20px;">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<strong>Ошибка!</strong> <?php echo $error_file_csv; ?>
			</div>
		<?php } ?>
		<form action="<?=$action?>" method="post" enctype="multipart/form-data" class="center-block text-center main-upload-form">
			<div class="form-group">
				<label for="csv_file">Загрузка CSV файла</label>
				<input type="file" id="csv_file" name="csv_file" class="center-block">
				<p class="help-block">Загрузите csv файл что бы обновить остатки на складе.</p>
			</div>
			<div class="">
				<button type="submit" class="btn btn-primary">
					<i class="glyphicon glyphicon-paperclip"></i>
					Загрузить
				</button>
			</div>
		</form>
		<h1 class="text-center">Обновление остатков</h1>
		<div class="table-responsive">
			<table class="table table-hover">
				<tr>
					<td class="text-center">Название продукта(product_name)</td>
					<td class="text-center">Количество(qty)</td>
					<td class="text-center">Название склада(warehouse)</td>
				</tr>
				<?php if($products){ ?>
					<?php foreach($products as $item_product){ ?>
						<tr>
							<td class="text-center"><?=$item_product['product_name']?></td>
							<td class="text-center"><?=$item_product['qty']?></td>
							<td class="text-center"><?=$item_product['warehouse']?></td>
						</tr>
					<?php } ?>
				<?php } else { ?>
					<tr>
						<td colspan="3" class="text-center">
							<div class="alert alert-warning alert-dismissible" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
								<strong>Внимание!</strong> Товаров нет на складах!
							</div>
						</td>
					</tr>
				<?php } ?>
			</table>
		</div>
	</div>
	<script type="text/javascript" src="<?=STYLES?>js/jquery-3.3.1.min.js"></script>
	<script type="text/javascript" src="<?=STYLES?>js/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?=STYLES?>js/main.js"></script>
</body>
</html>