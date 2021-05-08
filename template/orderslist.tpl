<div class="form-title">
	<h3>Список заказов</h3>
</div>
<div class="orders-list-table">
	<div class="table-head tt-row">
		<div class="tt-col">№ заказа</div>
		<div class="tt-col">Организация</div>
		<div class="tt-col">Агент</div>
		<div class="tt-col">Статус</div>
	</div>
	<div class="table-body">
		<?php
		$counter = 1;
		foreach ($orders as $key => $order) {
			$row_class = fmod($counter, 2) == 0 ? ' even' : ' odd';
			?>
			<div class="tt-row">
				<div class="tt-col<?=$row_class?>"><?=$order['order_num']?></div>
				<div class="tt-col<?=$row_class?>"><?=$order['organization']?></div>
				<div class="tt-col<?=$row_class?>"><?=$order['agent']?></div>
				<div class="tt-col state-control<?=$row_class?>" id="<?=$order['order_id']?>" data-current-state="<?=$order['state_id']?>" data-entity-id="<?=$key?>" data-order-id="<?=$order['order_id']?>"><?=$order['state']?></div>
			</div>
			<?
			$counter++;
		} ?>
	</div>
	<div class="table-pager">
		<div class="pager-prev<?=empty($pager['prev']) ? ' off' : ''?>" data-target-uri="<?=$pager['prev']?>"><<</div>
		<div class="pager-info"><?=$current_page?> из <?=$max_page?></div>
		<div class="pager-next<?=empty($pager['next']) ? ' off' : ''?>" data-target-uri="<?=$pager['next']?>">>></div>
	</div>
	<div class="popup-items states-list">
		<div class="popup-close"></div>
		<?php
		if (isset($states) && is_array($states)) {
			foreach ($states as $state_id => $state_name) {
				?>
				<div class="popup-item state-item" data-state-id="<?=$state_id?>"><?=$state_name?></div>
				<?php
			}
		}
		?>
	</div>
</div>
<script type="text/javascript">
	let order = {};
	let userData = {};
	$(function() {
		function hidePopup() {
			$('.states-list').css({"display": "none"});
		}
		$('.pager-prev, .pager-next').on('click', function() {
			if (!this.hasAttribute('off')) {
				window.location.href = this.dataset.targetUri;
			}
		});
		$('.state-control').on('click', function() {
			hidePopup();
			userData = {};
			order.entity_id = this.dataset.entityId;
			order.order_id = this.dataset.orderId;
			order.state_id = this.dataset.currentState;
			let topPos = this.offsetTop + this.offsetHeight / 2;
			let leftPos = this.offsetLeft + this.offsetWidth / 2;
			$('.states-list').css({"position": "absolute", "left": leftPos, "top": topPos, "display": "block"});
		});
		$('.state-item').on('click', function() {
			hidePopup();
			if (order.state_id != this.dataset.stateId) {
				if (confirm("Сохранить новый статус?")) {
					userData = {state_id: this.dataset.stateId, state_name: this.textContent};
					let ajaxData = {entity_id: order.entity_id, order_id: order.order_id, new_state: userData.state_id};
					$.ajax({
						url: '/ajax/state_change',
						type: 'POST',
						data: ajaxData,
						dataType: 'json',
						success: function(result) {
							if (!empty(result.success)) {
								let elem = document.getElementById(order.order_id);
								elem.dataset.currentState = userData.state_id;
								elem.textContent = userData.state_name;
							} else {
								console.log(result);
							}
						}
					});
				}
			}
		});
		$('.popup-close').on('click', function() {
			hidePopup();
		});
	});
</script>