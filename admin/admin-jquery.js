var $ = jQuery.noConflict();


$(document).ready(function()
{
	/* Update Sub total
	--------------------*/
	function update_subtotal(detail)
	{
		var rate = detail.find('input#detail-rate').attr('value'); rate = parseFloat(rate);
		var duration = detail.find('input#detail-duration').attr('value'); duration = parseFloat(duration);
		
		var subtotal = 0.00;
		if(detail.find('select :selected').attr('value') == 'Fixed')
		{
			subtotal = rate;
			detail.find('span.hr').html('');
		}
		else
		{
			subtotal = rate * duration; subtotal = subtotal.toFixed(2);
			detail.find('span.hr').html('/hr');
		}

		detail.find('input#detail-subtotal').attr('value', subtotal);
		detail.find('p#detail-subtotal').html('$ '+subtotal);
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
                                    <li class="rate">$<input onblur="if (this.value == \'\') {this.value = \'0.00\';}" onfocus="if(this.value == \'0.00\') {this.value = \'\';}"  type="text" name="detail-rate[]" id="detail-rate" value="0.00"><span class="hr"></span></li>\
                                    <li class="duration"><input onblur="if (this.value == \'\') {this.value = \'0.0\';}" onfocus="if(this.value == \'0.0\') {this.value = \'\';}" type="text" name="detail-duration[]" id="detail-duration" value="0.00"></li>\
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
	
	
	
	/* Edit Page Icons
	--------------------*/
	var post_type = getUrlVars()["post_type"];
	var taxonomy = getUrlVars()["taxonomy"];
	
	if(taxonomy == 'client')
	{
		$('.icon32#icon-edit').css({'background-position' : '-600px -5px'});
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


