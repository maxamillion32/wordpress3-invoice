var $ = jQuery.noConflict();
$(document).ready(function()
{
	/*-------------------------------------------------------------------------------
		Invoice edit page
	-------------------------------------------------------------------------------*/
	if($('#invoice_details').size() > 0 )
	{
		/* wp3i_rand
		-----------------------------------------*/
		function wp3i_rand(l,u) // lower bound and upper bound
		{
			 return Math.floor((Math.random() * (u-l+1))+l);
		}
		/* 
	
	
		wp3i_generate_permalink
		-----------------------------------------*/
		function wp3i_generate_permalink() {
			var length = 30;
			var characters = '0123456789abcdefghijklmnopqrstuvwxyz';
			var string = "";    
			for (var p = 0; p < length; p++) {
				string += characters[wp3i_rand(0, (characters.length-1))];
			}
			
			return string;
		}
		/* 
	
	
		wp3i_get_date_pretty
		-----------------------------------------*/
		function wp3i_get_date_pretty(date)
		{
			var months = new Array('','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
			return months[parseInt(date,16)];
		}
		/* 
	
	
		Generate Invoice Slug
		-----------------------------------------*/
		//alert($('#slugdiv input#post_name').attr('value').length);
		if($('#slugdiv input#post_name').attr('value').length < 30)
		{
			$('#slugdiv input#post_name').attr('value',wp3i_generate_permalink());
		}
		/* 
	
	
		Invoice Detail Functions
		-----------------------------------------*/
		$.fn.wp3i_detail_edit = function()
		{
			$(this).find('.front').css({'display':'none'});
			$(this).find('.back').css({'display':'inline'});	
		}
		$.fn.wp3i_detail_hide = function()
		{
			$(this).find('.front').css({'display':'inline'});
			$(this).find('.back').css({'display':'none'});	
		}
		
		$.fn.wp3i_detail_get_input = function()
		{
			var li = $(this);
			var result = '';
			if(li.find('input').size() > 0)
			{
				result = li.find('input').attr('value');
			}
			else if(li.find('select').size() > 0)
			{
				result = li.find('select').attr('value');
			}
			return  result;
		}
		
		$.fn.wp3i_detail_set_input = function(origInputValue)
		{
			var li = $(this);
			if(li.find('input').size() > 0)
			{
				li.find('input').attr('value', origInputValue);
			}
			else if(li.find('select').size() > 0)
			{
				li.find('select option').each(function(){
					if($(this).attr('value') == origInputValue){
						$(this).attr('selected','selected');
					}
				});
			}
		}
		
		
		/*-------------------------------------------------------------------------------
			Detail Normal
		-------------------------------------------------------------------------------*/
		$('#invoice_details li.normal-detail').each(function()
		{
			var li = $(this);
			var origInputValue;
			var inputType = 'input'; if(li.find('select').size > 0){inputType = 'option';}
			
			li.find('a.wp3i-edit').click(function(){
				origInputValue = li.wp3i_detail_get_input();
				li.wp3i_detail_edit();	
				return false;
			});	
			
			li.find('a.wp3i-ok').click(function(){
				var newInputValue = li.wp3i_detail_get_input();
				li.find('span').html(newInputValue);	
				li.wp3i_detail_hide();
				return false;
			});
			
			li.find('a.wp3i-cancel').click(function(){
				li.find('span').html(origInputValue);	
				//li.wp3i_detail_set_input(origInputValue);
				li.wp3i_detail_hide();
				return false;
			});
		});
		
		
		
		/*-------------------------------------------------------------------------------
			Detail Date
		-------------------------------------------------------------------------------*/
		$.fn.wp3i_detail_set_date = function(day, month, year)
		{
			$(this).find('select#mm option').each(function(){
				if($(this).attr('value') == month){
					$(this).attr('selected','selected');
				}
			});
			$(this).find('input#dd').attr('value', day);
			$(this).find('input#yyyy').attr('value', year);
		}
		
		$('#invoice_details li.date-detail').each(function()
		{
			var li = $(this);
			var hidden = li.find('input[type=hidden]');
			
			var currentTime = new Date();
			var month = currentTime.getMonth() + 1;
			var day = currentTime.getDate();
			var year = currentTime.getFullYear();
			
			if(!hidden.attr('value') == 'Not yet')
			{
				currentTime = li.find('input[type=hidden]').attr('value'); currentTime = currentTime.split('/');
				month = currentTime[1];
				day = currentTime[0];
				year = currentTime[2];
			}
			
			li.wp3i_detail_set_date(day, month, year);
			
			li.find('a.wp3i-edit').click(function(){
				li.wp3i_detail_edit();
				return false;
			});	
			
			li.find('a.wp3i-ok').click(function(){
				var newInputValue = li.find('input#dd').attr('value') +'/'+ li.find('select#mm').attr('value') +'/'+ li.find('input#yyyy').attr('value');
				var newInputValuePretty = li.find('select#mm option:selected').text() +' '+ li.find('input#dd').attr('value') +', '+ li.find('input#yyyy').attr('value');
				li.find('input[type=hidden]').attr('value', newInputValue);	
				li.find('span').html(newInputValuePretty);	
				li.wp3i_detail_hide();
				return false;
			});
			
			li.find('a.wp3i-clear').click(function(){
				/**/
				li.find('span').html('Not yet');	
				li.find('input[type=hidden]').attr('value', 'Not yet');	
				li.wp3i_detail_hide();
				return false;
			});
			
			li.find('a.wp3i-cancel').click(function(){
				if(hidden.attr('value') == 'Not yet')
				{
					hidden.attr('value', 'Not yet');	
					li.find('span').html('Not yet');	
				}
				else
				{
					currentTime = li.find('input[type=hidden]').attr('value'); currentTime = currentTime.split('/');
					month = currentTime[1];
					day = currentTime[0];
					year = currentTime[2];
					
					hidden.attr('value', day+'/'+month+'/'+year);	
					li.find('span').html(wp3i_get_date_pretty(month)+' '+day+', '+year);		
				}
				li.wp3i_detail_hide();
				return false;
			});
		});
		/* 
	
	
		Store Tax, Currency
		-----------------------------------------*/
		var wp3i_currenty = $('input#wp3i_hidden_currency').attr('value');
		var wp3i_tax = $('input#wp3i_hidden_tax').attr('value'); wp3i_tax = parseFloat(wp3i_tax);
		/* 
		
		
		Update Sub total
		-----------------------------------------*/
		function update_subtotal(detail)
		{
			var rate = detail.find('input#detail-rate').attr('value'); rate = parseFloat(rate);
			var duration = detail.find('input#detail-duration').attr('value'); duration = parseFloat(duration);
			
			var subtotal = 0.00;
			if(detail.find('select :selected').attr('value') == 'Fixed')
			{
				subtotal = rate;
				detail.find('span.hr').html('');
				detail.find('input#detail-duration').attr('value', 'N/A');
			}
			else
			{
				subtotal = rate * duration; subtotal = subtotal.toFixed(2);
				detail.find('span.hr').html('/hr');
			}
	
			detail.find('input#detail-subtotal').attr('value', subtotal);
			detail.find('p#detail-subtotal').html(wp3i_currenty+' '+subtotal);
			
			$('.detail-footer .invoice-subtotal').html(get_invoice_subtotal().toFixed(2));
			$('.detail-footer .invoice-tax').html(get_invoice_tax().toFixed(2));
			$('.detail-footer .invoice-total').html(get_invoice_total().toFixed(2));
		}
		
		
		
		function get_invoice_subtotal()
		{
			var temp_total = 0;
			$('input#detail-subtotal').each(function(){
				temp_total += parseFloat($(this).attr('value'));
			});	
			return temp_total;
		}
		
		function get_invoice_tax()
		{
			var temp_total = parseFloat(wp3i_tax * get_invoice_subtotal());
			return temp_total;
		}
		
		function get_invoice_total()
		{
			var temp_total = parseFloat(get_invoice_subtotal() + get_invoice_tax());
			return temp_total;
		}
		
		
		
		/* Init Sub total
		--------------------*/
		function initSubtotalUpdate()
		{
			$('.detail').each(function()
			{
				var detail = $(this);
				update_subtotal(detail);
				$(this).find('input#detail-rate').change(function(){update_subtotal(detail);}).bind("change keyup", function(){update_subtotal(detail);});
				$(this).find('input#detail-duration').change(function(){update_subtotal(detail);}).bind("change keyup", function(){update_subtotal(detail);});
				$(this).find('select#detail-type').change(function(){update_subtotal(detail);});
				$(this).find('a.delete').click(function(){detail.remove(); return false; });
				
			});
		}
		initSubtotalUpdate();
		
		
		
		/* add Detail Button
		---------------------*/
		$('a.add-detail').click(function()
		{
			var i = $('.detail').size() + 1;
			
			var append = '<div class="detail">\
						<table cellpadding="0" cellspacing="0" width="100%">\
							<tr>\
								<td>\
									<ul>\
										<li class="title"><input type="text" name="detail-title[]" id="detail-title"></li>\
										<li class="description"><textarea name="detail-description[]" id="detail-description"></textarea></li>\
									</ul>\
								</td>\
								<td width="340">\
									<ul>\
										<li class="type">\
										<select name="detail-type[]" id="detail-type">\
											<option value="Timed">Timed</option>\
											<option value="Fixed">Fixed</option>\
										</select>\
										</li>\
										<li class="rate">'+wp3i_currenty+'<input onblur="if (this.value == \'\') {this.value = \'0.00\';}" onfocus="if(this.value == \'0.00\') {this.value = \'\';}"  type="text" name="detail-rate[]" id="detail-rate" value="0.00"><span class="hr"></span></li>\
										<li class="duration"><input onblur="if (this.value == \'\') {this.value = \'0.0\';}" onfocus="if(this.value == \'0.0\') {this.value = \'\';}" type="text" name="detail-duration[]" id="detail-duration" value="0.0"></li>\
										<li class="subtotal">\
											<input type="hidden" name="detail-subtotal[]" id="detail-subtotal" value="0.00" />\
											<p id="detail-subtotal"></p>\
										</li>\
									</ul>\
								</td>\
							</tr>\
						</table>\
						<a class="delete" href="#" title="Remove Detail"></a>\
						<div class="grab"></div>\
					</div>';
					
			$('.details').append(append);
			initSubtotalUpdate();
			return false;
		});
		
		
		
		/* Sortable
		--------------------*/
		if($('div.details').length > 0)
		{
			$('div.details').sortable({
				accept : 'detail',
				opacity: 	0.5,
				fit :	false
			});
		}
		
		
	
	}// end if detail exists
	
	
	
	
	
	
	/* Edit Page Icons
	--------------------*/
	var post_type = getUrlVars()["post_type"];
	var taxonomy = getUrlVars()["taxonomy"];
	
	if(taxonomy == 'client')
	{
		$('.icon32#icon-edit').css({'background-position' : '-600px -5px'});
		$('label[for=description]').html('Email Address');
		$('textarea#description').attr('rows',1);
		$('label[for=tag-description]').html('Email Address');
		$('textarea#tag-description').attr('rows',1);
		$('th.column-description').html('Email Address');
	}
	else if(post_type == 'invoice')
	{
		$('.icon32#icon-edit').css({'background-position' : '-312px -5px'});
	}
	else if($('#wpbody-content h2').html() == 'Edit Invoice')
	{
		$('.icon32#icon-edit').css({'background-position' : '-312px -5px'});
	}
	
	function getUrlVars() {
		var vars = {};
		var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
			vars[key] = value;
		});
		return vars;
	}

	
	

			
});


