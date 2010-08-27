<?php
/*--------------------------------------------------------------------------------------------
									Global Variables
--------------------------------------------------------------------------------------------*/
$firstInvoiceYear = '';
$lastInvoiceYear = '';

$financialYear = true;

$financialYearStart = '';
$financialYearEnd = '';

$filterMonth = '07';
$filterYearStart = '';
$filterYearEnd = '';

$invoices;

$graphTitles = array();
$graphDates = array();
$graphTotals = array();

$graphTitlesOutstanding = array();
$graphTotalsOutstanding = array();


/*--------------------------------------------------------------------------------------------
									wp3i_setup_variables
									
* This function gets all the global variables and assignes the default values
* It then works out $_POST data and overrides the defaults.
--------------------------------------------------------------------------------------------*/
function wp3i_setup_variables()
{
	global $financialYearStart, $financialYearEnd, $financialYear;
	global $firstInvoiceYear, $lastInvoiceYear;
	global $filterYearStart, $filterYearEnd, $filterMonth;
	
	
	
	/* Setup Invoice Years (First - Last)
	--------------------------------------*/
	$firstInvoiceYear = get_posts(array('post_type' => 'invoice', 'numberposts' => '1', 'orderby' => 'date', 'order' => 'ASC' )); 
	$firstInvoiceYear = $firstInvoiceYear[0]->post_date; $firstInvoiceYear = substr($firstInvoiceYear,0,4);
	
	$lastInvoiceYear = get_posts(array('post_type' => 'invoice', 'numberposts' => '1', 'orderby' => 'date', 'order' => 'DESC' )); 
	$lastInvoiceYear = $lastInvoiceYear[0]->post_date; $lastInvoiceYear = substr($lastInvoiceYear,0,4);
	
	if($lastInvoiceYear == $firstInvoiceYear){$lastInvoiceYear = $firstInvoiceYear + 1;}
	
	
	
	/* Setup Financial Year (Start - End)
	--------------------------------------*/
	
	if(date('m') >= 7)
	{
		$financialYearStart = date('Y');	
		$financialYearEnd = date('Y') + 1;
	}
	else
	{
		$financialYearStart = date('Y') - 1;	
		$financialYearEnd = date('Y');
	}
	
	
	
	/* Determin Filter Start - End
	--------------------------------------*/
	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		$financialYear = isset($_POST['financial-year'])? true: false;
		$filterMonth = isset($_POST['financial-year'])? '07': '01';
		$filterYearStart = $_POST['filter-year'];
		$filterYearEnd = $_POST['filter-year'] + 1;
	}
	else
	{
		$financialYear = true;
		$filterMonth = '07';
		$filterYearStart = $financialYearStart;
		$filterYearEnd = $financialYearStart + 1;
	}
		
}


/*--------------------------------------------------------------------------------------------
									wp3i_filter_invoices
										
* This function will use the filter variables (yearStart, month, yearEnd)
* It filters the invoices returned when using get_posts
--------------------------------------------------------------------------------------------*/
function wp3i_filter_invoices()
{
	function financialYearFilter($where = '') 
	{
		global $filterYearStart, $filterMonth, $filterYearEnd;
		$where .= " AND post_date >= '".$filterYearStart."-".$filterMonth."-01' AND post_date <= '".$filterYearEnd."-".$filterMonth."-01'";
		return $where;
	}
	add_filter('posts_where','financialYearFilter');
}




/*--------------------------------------------------------------------------------------------
									wp3i_get_last_financial_year
										
* If the current date is past June 30, this function will return the current year.
* If the current date is befor June 30, this function will return last year.
--------------------------------------------------------------------------------------------*/
function wp3i_get_last_financial_year()
{
	if(date('m') >= 7)
	{
		return date('Y');	
	}
	else
	{
		return date('Y') - 1;
	}
}




/*--------------------------------------------------------------------------------------------
									wp3i_setup_graph_data
										
* Loops though all available invoices and sets data for:
* Titles, Dates and Totals
--------------------------------------------------------------------------------------------*/
function wp3i_setup_graph_data()
{
	global $invoices, $graphTitles, $graphTitlesOutstanding, $graphDates, $graphTotals, $graphTotalsOutstanding;
	global $financialYear;
	
	$invoicesReversed = array_reverse($invoices);
	$total = 0.00;
	
	// create blank/default graph arrays
	$graphDates = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
	$graphTitles = array('', '', '', '', '', '', '', '', '', '', '', '');
	$graphTotals = array(0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00);
	$graphTitlesOutstanding = array('', '', '', '', '', '', '', '', '', '', '', '');
	$graphTotalsOutstanding = array(0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00);
	
	// add invoice data into array
	foreach($invoicesReversed as $invoice)
	{
		
		if(invoice_has_paid($invoice->ID))
		{
			$invoiceMonth = get_the_time('n',$invoice->ID);
			
			$graphTitles[$invoiceMonth-1] .= get_post_meta($invoice->ID, 'invoice_number', true) . ' - ' . $invoice->post_title . '<br>';
			$graphTotals[$invoiceMonth-1] += wp3i_get_invoice_total($invoice->ID);
		}
		elseif(invoice_has_sent($invoice->ID))
		{
			$invoiceMonth = get_the_time('n',$invoice->ID);
			$graphTitlesOutstanding[$invoiceMonth-1] .= get_post_meta($invoice->ID, 'invoice_number', true) . ' - ' . $invoice->post_title . '<br>';
			$graphTotalsOutstanding[$invoiceMonth-1] += wp3i_get_invoice_total($invoice->ID);
		}
	}
	
	// if financial year is selected, arange the arrays so they start from Jun
	if($financialYear == true)
	{
		$graphDates = array_merge(array_slice($graphDates,6,6), array_slice($graphDates,0,6));
		$graphTitles = array_merge(array_slice($graphTitles,6,6), array_slice($graphTitles,0,6));
		$graphTotals = array_merge(array_slice($graphTotals,6,6), array_slice($graphTotals,0,6));
		
		$graphTitlesOutstanding = array_merge(array_slice($graphTitlesOutstanding,6,6), array_slice($graphTitlesOutstanding,0,6));
		$graphTotalsOutstanding = array_merge(array_slice($graphTotalsOutstanding,6,6), array_slice($graphTotalsOutstanding,0,6));
	}
}




/*--------------------------------------------------------------------------------------------
									wp3i_stats_graph
										
* Creates graph of income per month
--------------------------------------------------------------------------------------------*/
function wp3i_stats_graph()
{
	global $firstInvoiceYear, $graphTitles, $graphTitlesOutstanding, $graphDates, $graphTotals, $graphTotalsOutstanding;
	global $filterMonthStart, $filterYearStart, $filterMonthEnd, $filterYearEnd;
	
	global $financialYear;
	
	
	/* Work out Graph Title
	------------------------*/
	$graphTitle = '';
	if($financialYear == true){$graphTitle .= 'Financial Year ';}else{$graphTitle .= 'Normal Year ';}
	$graphTitle .= $filterYearStart.' - '.$filterYearEnd;
	
	wp3i_setup_graph_data();
	?>
    
    <script type="text/javascript">
	var chart;
	var $ = jQuery.noConflict();
		
	$(document).ready(function() {
		chart = new Highcharts.Chart({
			chart: {
				renderTo: 'chart',
				defaultSeriesType: 'line',
				margin: [50, 0, 60, 60]
			},
			title: {
				text: '<?php echo $graphTitle;?>',
			},
			xAxis: {
				//categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],

				categories: [<?php foreach($graphDates as $graphDate){echo "'".$graphDate."', ";} ?>],
					title: {
						text: 'Month'
					},
					labels: {
						formatter: function() {
							return this.value;
						}
					},

				},
				yAxis: {
					title: {
						text: 'Income'
					},
					plotLines: [{
						value: 0,
						width: 1,
						color: '#808080'
					}]
				},
				tooltip: {
					formatter: function() {
			                return '<b>'+ this.point.name +'</b><br/>'+
							'Total '+this.series.name+' '+this.x + ': <?php wp3i_currency(); ?>'+this.y;
					}
				},
				legend: {
					layout: 'vertical',
					style: {
						left: 'auto',
						bottom: 'auto',
						right: '0px',
						top: '0px'
					}
				},
				colors: [
					'#5fb300', 
					'#e9bf00'
				],
				series: [{
					name: 'Income',
					data: [
					<?php
					for($i = 0; $i < count($graphDates); $i++)
					{
						echo '{';
						echo "name: '".$graphTitles[$i]."',";
						//echo "x: '".$graphDates[$i]."', ";
						echo "y: ".$graphTotals[$i]."";
						echo '}, ';
					}
					?>
					]
				},{
					name: 'Outstanding',
					data: [
					<?php
					for($i = 0; $i < count($graphDates); $i++)
					{
						echo '{';
						echo "name: '".$graphTitlesOutstanding[$i]."',";
						//echo "x: '".$graphDates[$i]."', ";
						echo "y: ".$graphTotalsOutstanding[$i]."";
						echo '}, ';
					}
					?>
					]
				}]
			});
			
			
		});
		</script>
	<div class="wp3i-stats-graph" id="chart"></div>
    <?php
}


/*--------------------------------------------------------------------------------------------
											Stats Total
--------------------------------------------------------------------------------------------*/
function wp3i_get_stats_total($invoiceStatus)
{
	$total = wp3i_get_stats_subtotal($invoiceStatus) + wp3i_get_stats_tax_total($invoiceStatus);
	return number_format($total, 2, '.', '');
}

/*--------------------------------------------------------------------------------------------
											Stats Tax Total
--------------------------------------------------------------------------------------------*/
function wp3i_get_stats_tax_total($invoiceStatus)
{
	$total = floatval(wp3i_get_stats_subtotal($invoiceStatus) * get_wp3i_tax());
	return number_format($total, 2, '.', '');
}


/*--------------------------------------------------------------------------------------------
											Stats Subtotal
--------------------------------------------------------------------------------------------*/
function wp3i_get_stats_subtotal($invoiceStatus)
{
	global $invoices;
	$total = 0.00;
	foreach($invoices as $invoice)
	{
		$invoiceStatusCF = '';
		if(invoice_has_paid($invoice->ID)){$invoiceStatusCF = 'paid';}
		elseif(invoice_has_sent($invoice->ID)){$invoiceStatusCF = 'sent';}
		if($invoiceStatusCF == $invoiceStatus)
		{
			$total += wp3i_get_invoice_subtotal($invoice->ID);
		}
	}
	return number_format($total, 2, '.', '');
}


function wp3i_stats_invoices()
{
	global $invoices;
	$counter = 0;
	echo '<table><tr><th>#</th><th>Invoice Name</th><th>Date</th><th>Total</th><th>Status</th></tr>';
	foreach($invoices as $invoice)
	{
		$counter++; ?>
		<tr <?php if($counter % 2 == 0){echo 'class="alternate"';} ?>>
            <td class="invoice-number"><?php echo get_post_meta($invoice->ID, 'invoice_number', true); ?></td>
            <td><?php edit_post_link($invoice->post_title,'','',$invoice->ID); ?></td>
            <td class="invoice-date"><?php echo get_the_time('d M Y',$invoice->ID); ?></td>
            <td class="total"><?php wp3i_currency(); ?><?php echo number_format(wp3i_get_invoice_total($invoice->ID), 2, '.', ''); ?></td>
            <td><?php
			if(invoice_has_paid($invoice->ID))
			{
				echo'<div class="tick"></div>';
			}
			elseif(invoice_has_sent($invoice->ID))
			{
				echo'<div class="cross"></div>';
			} ?></td>
        </tr>
		<?php 
	}
	echo '</table>';
}


/*--------------------------------------------------------------------------------------------
											Stats Page
--------------------------------------------------------------------------------------------*/
function wp3i_stats()
{
	/* Global Variable
	--------------------------------------*/
	global $invoices, $financialYearStart, $financialYear;
	global $filterYearStart, $filterYearEnd, $filterMonth;
	
	wp3i_setup_variables();
	wp3i_filter_invoices();
	
	
	/* Get Filtered Invoices
	--------------------------------------*/
	$invoices = get_posts(array(
		'post_type' => 'invoice', 
		'numberposts' => '-1', 
		'suppress_filters' => false 
	));
	
	?>
    
    
    <div class="wrap">
    	<!--<div class="icon32" id="icon-wp3i"><br></div>-->
    	<h2>WP3 Invoice Stats</h2>
        <form method="post" action="" id="wp3i-stats">
			<div id="poststuff">
            
                <ul class="subsubsub">
                    <li>Display from </li>
                    <li><select name="filter-year" id="filter-year">
                    	<?php global $firstInvoiceYear, $lastInvoiceYear; ?>
                    	<?php for($i= $lastInvoiceYear; $i >= $firstInvoiceYear; $i--): ?>
                    		<option value="<?php echo $i; ?>" <?php if($i == $filterYearStart){echo 'selected="selected"';} ?>><?php echo $i; ?> - <?php echo $i+1; ?></option>
                        <?php endfor; ?>
                    </select></li>
                    <li>
                    	<input type="checkbox" name="financial-year" id="financial-year" value="yes" <?php if($financialYear == true){echo 'checked="checked"';} ?> /> Financial Year
                    </li>
                    <li><input type="submit" class="button-secondary" value="Filter" id="post-query-submit"></li>
                </ul>
    
                
                <div class="postbox" id="wp3i-stats-graph">
                    <h3 class="hndle"><span>WP3 Invoice Stats</span></h3>
                    <div class="inside">
                        
                        <div style="overflow:hidden;">
							<?php wp3i_stats_graph(); ?>
                            
                            <div class="summary">
                                <div id="income" class="postbox">
                                    <h3 class="hndle"><span>Income</span></h3>
                                    <div class="inside">
                                        <h2><?php wp3i_currency(); ?><?php echo wp3i_get_stats_total('paid'); ?></h2>
                                        <?php if(wp3i_has_tax()): ?>
                                        	<h4>Subtotal: <?php wp3i_currency(); ?><?php echo wp3i_get_stats_subtotal('paid'); ?></h4>
                                    		<h4>Tax: <?php wp3i_currency(); ?><?php echo wp3i_get_stats_tax_total('paid'); ?></h4>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div id="outstanding" class="postbox">
                                    <h3 class="hndle"><span>Outstanding</span></h3>
                                    <div class="inside">
                                        <h2><?php wp3i_currency(); ?><?php echo wp3i_get_stats_total('sent'); ?></h2>
                                        <?php if(wp3i_has_tax()): ?>
                                        	<h4>Subtotal: <?php wp3i_currency(); ?><?php echo wp3i_get_stats_subtotal('sent'); ?></h4>
                                    		<h4>Tax: <?php wp3i_currency(); ?><?php echo wp3i_get_stats_tax_total('sent'); ?></h4>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="wp3i-stats-invoices">
                        	<?php wp3i_stats_invoices(); ?>
                        </div>
                    </div>
                </div>
                
            </div>
		</form>
    </div>
<?php
}




?>