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
				
					 <ul class="nav nav-tabs">
						<li class="active"><a href="#tab_event" data-toggle="tab">
							<span class="fa fa-plug"></span> 
							<?php echo $tab_event; ?>
						</a></li>

						<li><a href="#tab_setting" data-toggle="tab">
							<span class="fa fa-cog"></span> 
							<?php echo $tab_setting; ?>
						</a></li>
						<!-- <?php if(isset($setting['debug'])){?>
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
						</a></li>  -->
					</ul>

					<div class="tab-content">
						<div class="tab-pane active" id="tab_event" >
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
												<label class="control-label" for="input-event-action"><?php echo $entry_action; ?></label>
												<input type="text" name="filter_action" value="<?php echo $filter_action; ?>" placeholder="<?php echo $entry_action; ?>" id="input-event-action" class="form-control"	data-item="action"/>
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
													<?php if ($sort == 'action') { ?>
														<a href="<?php echo $sort_action; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_action; ?></a>
													<?php } else { ?>
														<a href="<?php echo $sort_action; ?>"><?php echo $column_action; ?></a>
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
												<tr id="event_id_<?php echo $event['event_id']; ?>" <?php echo ($event['status']) ? 'class="enabled"' : '';?>>
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
														<span class="label label-success disable" ><?php echo $text_enabled; ?></span>
														<span class="label label-danger enable" ><?php echo $text_disabled; ?></span>
													</td>
													<td class="text-left"><?php echo $event['date_added']; ?></td>
													<td class="text-right">
														<a href="<?php echo $event['enable']; ?>" data-toggle="tooltip" title="<?php echo $button_enable; ?>" data-event_id="<?php echo $event['event_id']; ?>" class="btn btn-success action enable"><i class="fa fa-thumbs-o-up"></i></a>
														<a href="<?php echo $event['disable']; ?>" data-toggle="tooltip" title="<?php echo $button_disable; ?>" data-event_id="<?php echo $event['event_id']; ?>" class="btn btn-danger action disable"><i class="fa fa-thumbs-o-down"></i></a>
														<a href="<?php echo $event['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary edit"><i class="fa fa-pencil"></i></a></td>
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
								
								<?php if(VERSION < '2.3.0.0') { ?>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="input_compatibility"><?php echo $entry_compatibility; ?></label>
									<div class="col-sm-10">										
										<input type="hidden" name="compatibility" value="0" />
										<input type="checkbox" name="compatibility" class="switcher" data-label-text="<?php echo $text_enabled; ?>"id="input_compatibility" <?php echo ($compatibility) ? 'checked="checked"':'';?> value="1" />
									</div>
								</div><!-- //compatibility -->
								<?php } ?>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="input_test"><?php echo $entry_test_toggle; ?></label>
									<div class="col-sm-10">
										<?php if(!$tests){?>
										<a class="btn btn-primary" href="<?php echo $install_test?>"><?php echo $text_install; ?></a> 
										<?php }else{ ?>
										<a class="btn btn-danger" href="<?php echo $uninstall_test; ?>"><?php echo $text_uninstall; ?></a>
										<?php } ?>
									</div>
								</div><!-- //text -->
								<?php if($tests){?>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="input_test"><?php echo $entry_test; ?></label>
									<div class="col-sm-10">
										<?php foreach($tests as $test => $result){ ?>
											<div class="alert <?php if($result) { ?>alert-success<?php }else{?> alert-danger<?php } ?>"><?php echo $test ?> <div class="pull-right"><span class="label <?php if($result) { ?>label-success<?php }else{?> label-danger<?php } ?>"><?php if($result) { ?>passed<?php }else{?>not passed<?php } ?></span></div></div>
										<?php } ?>
									</div>
								</div><!-- //test -->
								<?php } ?>
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
<template id="event_item">
	<tr id="event_id_<?php echo $event['event_id']; ?>" class="{{text_status}} flash">
		<td class="text-center"><input type="checkbox" name="selected[]" value="{{event_id}}" /></td>
		<td class="text-left">{{code}}</td>
		<td class="text-left">{{trigger}}</td>
		<td class="text-left">{{action}}</td>
		<td class="text-left">
			<span class="label label-success disable"><?php echo $text_enabled; ?></span>
			<span class="label label-danger enable"><?php echo $text_disabled; ?></span>
		</td>
		<td class="text-left">{{date_added}}</td>
		<td class="text-right">
			<a href="{{enable}}" data-toggle="tooltip" title="<?php echo $button_enable; ?>" data-event_id="{{event_id}}" class="btn btn-success action enable" ><i class="fa fa-thumbs-o-up"></i></a>
			<a href="{{disable}}" data-toggle="tooltip" title="<?php echo $button_disable; ?>" data-event_id="{{event_id}}" class="btn btn-danger action disable"><i class="fa fa-thumbs-o-down"></i></a>
			<a href="{{edit}}" data-toggle="tooltip" title="<?php echo $button_edit; ?>" data-event_id="{{event_id}}" class="btn btn-primary edit"><i class="fa fa-pencil"></i></a></td>
		</tr>
</template>
<template id="modal">
	<div id="myModal" class="modal fade" tabindex="-1" role="dialog">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title">Edit event <b>{{code}}</b> </h4>
	      </div>
	      <div class="modal-body">
	        <form id="event_form" class="form-horizontal">
	        	<div class="form-group">
					<label class="col-sm-2 control-label" for="input_text"><?php echo $entry_code; ?></label>
					<div class="col-sm-10">
						<input type="text" name="code" value="{{code}}" placeholder="<?php echo $entry_code; ?>" id="input-width" class="form-control" />
					</div>
				</div><!-- //text -->
				<div class="form-group">
					<label class="col-sm-2 control-label" for="input_text"><?php echo $entry_trigger; ?></label>
					<div class="col-sm-10">
						<input type="text" name="trigger" value="{{trigger}}" placeholder="<?php echo $entry_trigger; ?>" id="input-width" class="form-control" />
					</div>
				</div><!-- //text -->
				<div class="form-group">
					<label class="col-sm-2 control-label" for="input_action"><?php echo $entry_action; ?></label>
					<div class="col-sm-10">
						<input type="text" name="action" value="{{action}}" placeholder="<?php echo $entry_action; ?>" id="input-width" class="form-control" />
					</div>
				</div><!-- //text -->
				
	        </form>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        <a type="button" class="btn btn-primary save" href="{{save}}" data-event_id="{{event_id}}">Save changes</a>
	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
</template>
<style>
	.disable{
		display: none;
	}
	.enabled .disable{
		display: inline;
	}
	.enabled .enable{
		display: none
	}

	@-webkit-keyframes flash {
	   
	    50% {
	        background-color: rgba(0,100,0,0.1);
	    }
	   
	    100% {
	        background-color: rgba(0,100,0,0);
	    }
	}
	    
	.flash {
	    -webkit-animation-name: flash;
	    -webkit-animation-duration: 2000ms;
	    -webkit-animation-iteration-count: 1;
	    -webkit-animation-timing-function: ease-in-out;
	}    
</style>
<script type="text/javascript"><!--
	// sorting fields

	
	$(function () {
	//checkbox
	$(".switcher[type='checkbox']").bootstrapSwitch({
		'onColor': 'success',
		'onText': '<?php echo $text_yes; ?>',
		'offText': '<?php echo $text_no; ?>',
		onSwitchChange: function(event, state){
			if(state){
				window.location.href = '<?php echo $install_compatibility; ?>'
			}else{
				window.location.href = '<?php echo $uninstall_compatibility; ?>'
			}
		}
		
	})


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

$('input[name=\'filter_code\'], input[name=\'filter_trigger\'], input[name=\'filter_action\']').autocomplete({
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

$(document).on('click', '.edit', function() {
	var that = this;
	var $event_id = $(that).data('event_id');
	$('#myModal').remove();

	$.ajax({
		url: $(that).attr('href'),
		type: 'get',
		data: '',
		dataType: 'json',
		success: function(json) {
			var html = $('#modal').html();

			//templating like handlebars
		    var re = /{{([^}}]+)?}}/g, match;
		    while(match = re.exec(html)) {
		        html = html.replace(match[0], json[match[1]])
		    }

			$('body').append(html);
			$('#myModal').modal('show')
		}
	});
	
	return false;
});

$(document).on('click', '.save', function() {
	var that = this;
	var $event_id = $(that).data('event_id');
	var $data = $('#event_form').serialize();

	$.ajax({
		url: $(that).attr('href'),
		type: 'post',
		data: $data,
		dataType: 'json',
		success: function(json) {
			console.log(json);
			$('#myModal').modal('hide');
			if(json['status'] == 1) {
				json['text_status'] = 'enabled';
			}else{
				json['text_status'] = '';
			}
			var html = $('#event_item').html();

			//templating like handlebars
		    var re = /{{([^}}]+)?}}/g, match;
		    while(match = re.exec(html)) {
		        html = html.replace(match[0], json[match[1]])
		    }
			$('#event_id_'+$event_id).replaceWith(html);

		}
	});
	
	return false;
});

//--></script>
<?php echo $footer; ?>