<?php
/*
 *	location: admin/view
 */
?>
<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="form-inline pull-right">
				<!-- <?php if(!empty($stores)){ ?>
				<select class="form-control" onChange="location='<?php echo $module_link; ?>&store_id='+$(this).val()">
					<?php foreach($stores as $store){ ?>
					<?php if($store['store_id'] == $store_id){ ?>
					<option value="<?php echo $store['store_id']; ?>" selected="selected" ><?php echo $store['name']; ?></option>
					<?php }else{ ?>
					<option value="<?php echo $store['store_id']; ?>" ><?php echo $store['name']; ?></option>
					<?php } ?>
					<?php } ?>
				</select>
				<?php } ?>
				<button id="save_and_stay" data-toggle="tooltip" title="<?php echo $button_save_and_stay; ?>" class="btn btn-success"><i class="fa fa-save"></i></button>
				<button type="submit" form="form" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button> -->
				<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
			</div>
			<h1><?php echo $heading_title; ?> <?php echo $version; ?></h1>
			<ul class="breadcrumb">
				<?php foreach ($breadcrumbs as $breadcrumb) { ?>
				<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
				<?php } ?>
			</ul>
		</div>
	</div>
	<div class="container-fluid">
		<?php if (!empty($error['warning'])) { ?>
		<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error['warning']; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>
		<?php if (!empty($success)) { ?>
		<div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>
		
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
			</div>
			<div class="panel-body">
				
					<!-- <ul	class="nav nav-tabs">
						<li class="active"><a href="#tab_customer" data-toggle="tab">
							<span class="fa fa-user"></span> 
							<?php echo $tab_customer; ?>
						</a></li>

						<li><a href="#tab_setting" data-toggle="tab">
							<span class="fa fa-cog"></span> 
							<?php echo $tab_setting; ?>
						</a></li>
						<?php if(isset($setting['debug'])){?>
						<li><a href="#tab_debug" data-toggle="tab">
							<span class="fa fa-bug"></span> 
							<?php echo $tab_debug; ?>
						</a></li>
						<?php } ?>
						<?php if(!empty($setting['support'])){?>
						<li><a href="#tab_support" data-toggle="tab">
							<span class="fa fa-support"></span> 
							<?php echo $tab_support; ?>
						</a></li>
						<?php } ?>
						<li><a href="#tab_instruction" data-toggle="tab">
							<span class="fa fa-graduation-cap"></span> 
							<?php echo $tab_instruction; ?>
						</a></li> 
					</ul>-->

					<div class="tab-content">
						<div class="tab-pane active" id="tab_customer" >
							<div class="tab-body">
								<div id="filter" class="well">
									<div class="row">
										<div class="col-sm-3">
											<div class="form-group">
												<label class="control-label" for="input-code"><?php echo $entry_code; ?></label>
												<input type="text" name="filter_code" value="<?php echo $filter_code; ?>" placeholder="<?php echo $entry_code; ?>" id="input-code" class="form-control" data-item="code"/>
											</div>
										</div>
										<div class="col-sm-3">
											<div class="form-group">
												<label class="control-label" for="input-trigger"><?php echo $entry_trigger; ?></label>
												<input type="text" name="filter_trigger" value="<?php echo $filter_trigger; ?>" placeholder="<?php echo $entry_trigger; ?>" id="input-trigger" class="form-control"	data-item="trigger"/>
											</div>
											
										</div>
										<div class="col-sm-3">
											<div class="form-group">
												<label class="control-label" for="input-event-action"><?php echo $entry_event_action; ?></label>
												<input type="text" name="filter_event_action" value="<?php echo $filter_event_action; ?>" placeholder="<?php echo $entry_event_action; ?>" id="input-event-action" class="form-control"	data-item="event_action"/>
											</div>
										</div>
										<div class="col-sm-3">
											<div class="form-group">
												<label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
												<select name="filter_status" id="input-status" class="form-control">
													<option value="*"></option>
													<?php if ($filter_status) { ?>
													<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
													<?php } else { ?>
													<option value="1"><?php echo $text_enabled; ?></option>
													<?php } ?>
													<?php if (!$filter_status && !is_null($filter_status)) { ?>
													<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
													<?php } else { ?>
													<option value="0"><?php echo $text_disabled; ?></option>
													<?php } ?>
												</select>
											</div>
											<button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
										</div>
									</div>
								</div>
								<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-customer">
									<div class="table-responsive">
										<table class="table table-bordered table-hover">
											<thead>
											<tr>
												<td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
												<td class="text-left">
													<?php if ($sort == 'code') { ?>
														<a href="<?php echo $sort_code; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_code; ?></a>
													<?php } else { ?>
														<a href="<?php echo $sort_code; ?>"><?php echo $column_code; ?></a>
													<?php } ?>
												</td>
												<td class="text-left">
													<?php if ($sort == 'trigger') { ?>
														<a href="<?php echo $sort_trigger; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_trigger; ?></a>
													<?php } else { ?>
														<a href="<?php echo $sort_trigger; ?>"><?php echo $column_trigger; ?></a>
													<?php } ?>
												</td>
												<td class="text-left">
													<?php if ($sort == 'event_action') { ?>
														<a href="<?php echo $sort_event_action; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_event_action; ?></a>
													<?php } else { ?>
														<a href="<?php echo $sort_event_action; ?>"><?php echo $column_event_action; ?></a>
													<?php } ?>
												</td>
												<td class="text-left">
													<?php if ($sort == 'status') { ?>
														<a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
													<?php } else { ?>
														<a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
													<?php } ?>
												</td>
												<td class="text-left">
													<?php if ($sort == 'date_added') { ?>
														<a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
													<?php } else { ?>
														<a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
													<?php } ?>
												</td>
												<td class="text-right"><?php echo $column_action; ?></td>
											</tr>
											</thead>
											<tbody>
											<?php if ($events) { ?>
												<?php foreach ($events as $event) { ?>
												<tr id="event_id_<?php echo $event['event_id']; ?>">
													<td class="text-center">
														<?php if (in_array($event['event_id'], $selected)) { ?>
															<input type="checkbox" name="selected[]" value="<?php echo $event['event_id']; ?>" checked="checked" />
														<?php } else { ?>
															<input type="checkbox" name="selected[]" value="<?php echo $event['event_id']; ?>" />
														<?php } ?>
													</td>
													<td class="text-left"><?php echo $event['code']; ?></td>
													<td class="text-left"><?php echo $event['trigger']; ?></td>
													<td class="text-left"><?php echo $event['action']; ?></td>
													<td class="text-left">
														<span class="label label-success disable" <?php echo (!$event['status']) ? 'style="display:none"' : '';?> ><?php echo $text_enabled; ?></span>
														<span class="label label-danger enable" <?php echo ($event['status']) ? 'style="display:none"' : '';?> ><?php echo $text_disabled; ?></span>
													</td>
													<td class="text-left"><?php echo $event['date_added']; ?></td>
													<td class="text-right">
														<a href="<?php echo $event['enable']; ?>" data-toggle="tooltip" title="<?php echo $button_enable; ?>" data-event_id="<?php echo $event['event_id']; ?>" class="btn btn-success action enable" <?php echo ($event['status']) ? 'style="display:none"' : '';?> ><i class="fa fa-thumbs-o-up"></i></a>
														<a href="<?php echo $event['disable']; ?>" data-toggle="tooltip" title="<?php echo $button_disable; ?>" data-event_id="<?php echo $event['event_id']; ?>" class="btn btn-danger action disable" <?php echo (!$event['status']) ? 'style="display:none"' : '';?>><i class="fa fa-thumbs-o-down"></i></a>
														<a href="<?php echo $event['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
													</tr>
												<?php } ?>
											<?php } else { ?>
												<tr>
													<td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
												</tr>
											<?php } ?>
											</tbody>
										</table>
									</div>
								</form>
								<div class="row">
									<div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
									<div class="col-sm-6 text-right"><?php echo $results; ?></div>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="tab_setting" >
							<div class="tab-body">
								<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
								
								<div class="form-group">
									<label class="col-sm-2 control-label" for="input_status"><?php echo $entry_status; ?></label>
									<div class="col-sm-10">										
										<input type="hidden" name="<?php echo $id;?>_status" value="0" />
										<input type="checkbox" name="<?php echo $id;?>_status" class="switcher" data-label-text="<?php echo $text_enabled; ?>"id="input_status" <?php echo (${$id.'_status'}) ? 'checked="checked"':'';?> value="1" />
									</div>
								</div><!-- //status -->

								<div class="form-group">
									<label class="col-sm-2 control-label" for="input_select"><?php echo $entry_select; ?></label>
									<div class="col-sm-10">
										<select name="<?php echo $id;?>_setting[select]" id="input_select" class="form-control">
											<?php foreach ($selects as $select) { ?>
											<option value="<?php echo $select; ?>" <?php if ($setting['select'] == $select) { ?> selected="selected" <?php } ?>><?php echo $select; ?></option>
											<?php } ?>
										</select>
										<?php if (!empty($error['select'])) { ?>
										<div class="text-danger"><?php echo $error['select']; ?></div>
										<?php } ?>
									</div>
								</div><!-- //select -->


								<div class="form-group">
									<label class="col-sm-2 control-label" for="input_text"><?php echo $entry_text; ?></label>
									<div class="col-sm-10">
										<input type="text" name="<?php echo $id;?>_setting[text]" value="<?php echo $setting['text']; ?>" placeholder="<?php echo $entry_text; ?>" id="input-width" class="form-control" />
										<?php if (!empty($error['text'])) { ?>
										<div class="text-danger"><?php echo $error['text']; ?></div>
										<?php } ?>
									</div>
								</div><!-- //text -->

								<div class="form-group">
									<label class="col-sm-2 control-label"><?php echo $entry_radio; ?></label>
									<div class="col-sm-10">
										
										<div class="btn-group" data-toggle="buttons">
											<?php foreach ($radios as $radio) { ?>
											<label class="btn btn-success <?php if ($setting['radio'] == $radio) { ?> active <?php } ?>">
												<input type="radio" name="<?php echo $id;?>_setting[radio]" value="<?php echo $radio; ?>" <?php if ($setting['radio'] == $radio) { ?> checked="checked" <?php } ?> />
												<?php echo ${'text_radio_'.$radio}; ?>
											</label>
											<?php } ?>
										</div>
									</div>
								</div><!-- //radio -->

								<div class="form-group">
									<label class="col-sm-2 control-label" for="input_checkbox"><?php echo $entry_checkbox; ?></label>
									<div class="col-sm-10">										
										<input type="hidden" name="<?php echo $id;?>_setting[checkbox]" value="0" />
										<input type="checkbox" class="switcher" data-label-text="<?php echo $text_enabled; ?>"id="input_checkbox" name="<?php echo $id;?>_setting[checkbox]" <?php echo ($setting['checkbox'])? 'checked="checked"':'';?> value="1" />
									</div>
								</div><!-- //checkbox -->

								<div class="form-group">
									<label class="col-sm-2 control-label" for="input_checkbox"><?php echo $entry_color; ?></label>
									<div class="col-sm-2">
										<div class="input-group color-picker">
											<input type="text" name="<?php echo $id;?>_setting[color]" class=" form-control" value="<?php echo $setting['color']; ?>">
											<span class="input-group-addon"><i></i></span>
										</div>
									</div>
								</div><!-- //colorpicker -->

								<div class="form-group">
									<label class="col-sm-2 control-label" for="input_image"><?php echo $entry_image; ?></label>
									<div class="col-sm-10">
										<a href="" id="thumb_image" data-toggle="image" class="img-thumbnail">
											<img src="<?php echo $image; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" />
										</a>
										<input type="hidden" name="<?php echo $id;?>_setting[image]" value="<?php echo $setting['image']; ?>" id="input-logo" />
									</div>
								</div><!-- //image -->

								<div class="form-group">
									<label class="col-sm-2 control-label" for="input_textarea"><?php echo $entry_textarea; ?></label>
									<div class="col-sm-10">
										<textarea class="summernote" name="<?php echo $id;?>_setting[textarea]" placeholder="<?php echo $entry_textarea; ?>" id="input_textarea"><?php echo isset($setting['textarea']) ? $setting['textarea'] : ''; ?></textarea>
									</div>
								</div><!-- //textarea -->

								<div class="form-group">
									<label class="col-sm-2 control-label" ><?php echo $entry_field; ?></label>
									<div class="col-sm-10">
										
										<div id="field" class="sortable form-inline ">
											<?php foreach($setting['field'] as $field) { ?>
											<div class="list-group-item" data-sort-order="<?php echo $field['sort_order']; ?>">
												<span class="drag-handle"><span class="fa fa-bars fa-fw"></span>&nbsp; </span> 
												<input type="hidden" name="<?php echo $id;?>_setting[field][<?php echo $field['id']; ?>][enabled]" value="0" />
												<label for="<?php echo $id;?>_setting_field_<?php echo $field['id']; ?>_enabled" class="m-b-none">
													<input type="checkbox"	data-size="mini" class="switcher" name="<?php echo $id;?>_setting[field][<?php echo $field['id']; ?>][enabled]" <?php echo ($field['enabled'])? 'checked="checked"':'';?> value="1" id="<?php echo $id;?>_setting_field_<?php echo $field['id']; ?>_enabled" />
													<?php echo ${'text_'.$field['id']}; ?>
												</label>
												<input type="hidden" name="<?php echo $id;?>_setting[field][<?php echo $field['id']; ?>][sort_order]" class="sort-value" value="<?php echo $field['sort_order']; ?>" />
												<label class="m-b-none m-l-md"><?php echo $entry_type; ?></label>
												<input type="text" class="form-control" name="<?php echo $id;?>_setting[field][<?php echo $field['id']; ?>][type]" value="<?php echo $field['type']; ?>" />
												<input type="hidden" name="<?php echo $id;?>_setting[field][<?php echo $field['id']; ?>][id]" value="<?php echo $field['id']; ?>" />
												
											</div>
											<?php } ?>
										</div>
										
									</div>
								</div><!-- //field -->

								<?php if(isset($setting['debug'])){?>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="input_debug"><?php echo $entry_debug; ?></label>
									<div class="col-sm-10">
										<input type="hidden" name="<?php echo $id;?>_setting[debug]" value="0" />
										<input type="checkbox" data-label-text="<?php echo $text_enabled; ?>" class="switcher" id="input_debug" name="<?php echo $id;?>_setting[debug]" <?php echo ($setting['debug'])? 'checked="checked"':'';?> value="1" />
									</div>
								</div>
								<?php } ?>
								<!-- //debug -->

								<?php if ($config_files) { ?>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="select_config"><?php echo $entry_config_files; ?></label>
									<div class="col-sm-10">
										<select id="select_config" name="<?php echo $id;?>_setting[config]"	class="form-control">
											<?php foreach ($config_files as $config_file) { ?>
											<option value="<?php echo $config_file; ?>" <?php echo ($config_file == $config)? 'selected="selected"' : ''; ?>><?php echo $config_file; ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<?php } ?>
								<!-- //config -->
								</form>
							</div>
						</div>
						<?php if(isset($setting['debug'])){?>
						<div class="tab-pane" id="tab_debug" >
							<div class="tab-body form-horizontal">

								<textarea id="textarea_debug_log" wrap="off" rows="15" readonly="readonly" class="form-control"><?php echo $debug_log; ?></textarea>
								<br/>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="input_debug_file"><?php echo $entry_debug_file; ?></label>
									<div class="col-sm-10">
										<input type="text" id="input_debug_file" name="<?php echo $id;?>_setting[debug_file]" value="<?php echo $setting['debug_file']; ?>"	class="form-control"/>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-10 col-sm-offset-2">
										<a class="btn btn-danger" id="clear_debug_file"><?php echo $button_clear; ?></a>
									</div>
								</div>


							</div>
						</div>
						<?php } ?>
						<div class="tab-pane" id="tab_support" >
							<div class="tab-body">

							</div>
						</div>
						<div class="tab-pane" id="tab_instruction" >
							<div class="tab-body"><?php echo $text_instruction; ?></div>
						</div>
					</div>
				</div>
			</div>
	</div>
</div>
<script type="text/javascript"><!--
	// sorting fields


	$(function () {
	//checkbox
	$(".switcher[type='checkbox']").bootstrapSwitch({
		'onColor': 'success',
		'onText': '<?php echo $text_yes; ?>',
		'offText': '<?php echo $text_no; ?>',
	});

	//colorpicker
	 $('.color-picker').colorpicker();

	//textarea
	$('.summernote').summernote({height: 300});

	//sort field
	$('#field > .list-group-item').tsort({attr:'data-sort-order'});
	Sortable.create(field, {
		group: "sorting",
		sort: true,
		animation: 150,
		handle: ".drag-handle",
		onUpdate: function (event){
			$('#field').find('.list-group-item').each(function (i, row) {
				console.log(i)
				$(row).find('.sort-value').val(i)
			})
		}
	});

	$('body').on('change', '#select_config', function(){
		console.log('#select_config changed')
		var config = $(this).val();
		$('body').append('<form action="<?php echo $module_link; ?><?php echo ($stores) ? "&store_id='+$('#store').val() +'" : ''; ?>" id="config_update" method="post" style="display:none;"><input type="text" name="config" value="' + config + '" /></form>');
		$('#config_update').submit();
	});

	$('body').on('click', '#save_and_stay', function(){

		$('.summernote').each( function() {
			$(this).val($(this).code());
		});
		$.ajax( {
			type: 'post',
			url: $('#form').attr('action') + '&save',
			data: $('#form').serialize(),
			beforeSend: function() {
				$('#form').fadeTo('slow', 0.5);
			},
			complete: function() {
				$('#form').fadeTo('slow', 1);	 
			},
			success: function( response ) {
				console.log( response );
			}
		});	
	});

	$('body').on('click', '#button_update', function(){ 
		$.ajax( {
			url: '<?php echo $update; ?>',
			type: 'post',
			dataType: 'json',

			beforeSend: function() {
				$('#button_update').find('.fa-refresh').addClass('fa-spin');
			},

			complete: function() {
				$('#button_update').find('.fa-refresh').removeClass('fa-spin');	 
			},

			success: function(json) {
				console.log(json);

				if(json['error']){
					$('#notification_update').html('<div class="alert alert-danger m-b-none">' + json['error'] + '</div>')
				}

				if(json['warning']){
					$html = '';

					if(json['update']){
						$.each(json['update'] , function(k, v) {
							$html += '<div>Version: ' +k+ '</div><div>'+ v +'</div>';
						});
					}
					$('#notification_update').html('<div class="alert alert-warning alert-inline">' + json['warning'] + $html + '</div>')
				}

				if(json['success']){
					$('#notification_update').html('<div class="alert alert-success alert-inline">' + json['success'] + '</div>')
				} 
			},
			error: function(xhr, ajaxOptions, thrownError) {
			console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});

	$('body').on('click', '#clear_debug_file', function(){ 
		$.ajax( {
			url: '<?php echo $clear_debug_file; ?>',
			type: 'post',
			dataType: 'json',
			data: 'debug_file=<?php echo $debug_file; ?>',

			beforeSend: function() {
				$('#form').fadeTo('slow', 0.5);
			},

			complete: function() {
				$('#form').fadeTo('slow', 1);	 
			},

			success: function(json) {
				$('.alert').remove();
				console.log(json);

				if(json['error']){
					$('#debug .tab-body').prepend('<div class="alert alert-danger">' + json['error'] + '</div>')
				}

				if(json['success']){
					$('#debug .tab-body').prepend('<div class="alert alert-success">' + json['success'] + '</div>')
					$('#textarea_debug_log').val('');
				} 
			}
		});
	});

});

//Customer
$('#button-filter').on('click', function() {

	url = 'index.php?route=<?php echo $route; ?>&token=<?php echo $token; ?>';
	$('#filter input, #filter select').each(function(index){
		var value = $(this).val()
		var name = $(this).attr('name')
		if(value && value != '*') { url += '&' + name + '=' + encodeURIComponent(value); }
	})
	
	location = url;
});

$('input[name=\'filter_code\'], input[name=\'filter_trigger\'], input[name=\'filter_event_action\']').autocomplete({
	'source': function(request, response) {
		that = this;
		$.ajax({
			url: 'index.php?route=<?php echo $route; ?>/autocomplete&token=<?php echo $token; ?>&'+ $(this).attr('name')+'=' +	encodeURIComponent(request),
			dataType: 'json',			
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item[$(that).attr('data-item')],
						value: item['event_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$(this).val(item['label']);
	}	
});

$('.date').datetimepicker({
	pickTime: false
});

$(document).on('click', '.action', function() {
	var that = this;
	var $event_id = $(that).data('event_id');
	$.ajax({
		url: $(that).attr('href'),
		dataType: 'json',
		success: function(json) {
			if(json['enabled']){
				$('#event_id_'+$event_id).find('.enable').hide();
				$('#event_id_'+$event_id).find('.disable').show();
			}else{
				$('#event_id_'+$event_id).find('.enable').show();
				$('#event_id_'+$event_id).find('.disable').hide();
			}
		}
	});
	
	return false;
});


//--></script>
<?php echo $footer; ?>