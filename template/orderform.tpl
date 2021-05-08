<div class="form-title">
	<h3>Новый заказ</h3>
</div>
<div class="form" id="neworder_form">
	<form id="neworder" method="post" action="/neworder/save">
		<div class="form-item">
			<span class="form-field-label">Номер заказа*</span>
			<input type="text" name="order_num" value="">
		</div>
		<div class="form-item">
			<span class="form-field-label">Организация*</span>
			<select name="organization">
				<option value="-1" disabled selected>Не выбрано</option>
				<?php
				if (isset($organizations) && is_array($organizations)) {
					foreach ($organizations as $key => $item) {
						?>
						<option value="<?=$key?>"><?=$item?></option>
						<?php
					}
				}
				?>
			</select>
		</div>
		<div class="form-item">
			<span class="form-field-label">Агент*</span>
			<select name="agent">
				<option value="-1" disabled selected>Не выбрано</option>
				<?php
				if (isset($agents) && is_array($agents)) {
					foreach ($agents as $key => $item) {
						?>
						<option value="<?=$key?>"><?=$item?></option>
						<?php
					}
				}
				?>
			</select>
		</div>
		<div class="form-item error">
		</div>
		<div class="btn-block">
			<button>Отправить</button>
		</div
	</form>	
</div>
<script type="text/javascript">
	$(function() {
		$('#neworder_form').on('submit', function() {
			let errDetect = [];
			$('.form-item input, .form-item select').each(function() {
				if (empty(this.value.trim()) || this.value.trim() == '-1') {
					errDetect.push(this);
				}
			});
			if (errDetect.length > 0) {
				for (let domItem of errDetect) {
					$(domItem).css({'border-color':'#cc0000'});
				}
				$('.error').html('Не заполнены поля');
				return false;
			}
		});
		
		$('.form-item input, .form-item select').on('change', function() {
			$(this).css({'border-color':'#ececec'});
			$('.error').html('');
		});
	});
</script>