<div id="company"></div>
<?php $tot_comps = 0; ?>
<?php
echo '<script language="JavaScript">';
echo 'var company = c3.generate({bindto : "#company",data: {columns: [';
foreach ($com_q->result_array() as $row){
	echo '["'.$row['company_type'].'", '.$row['counts'].'],';
	$tot_comps = $row['counts']+$tot_comps;
}
echo '],type : "donut",},donut: { title: "Total Companies: '.$tot_comps.'",onmouseover: function(d, i) {console.log(d, i);}, onmouseout: function (d, i) {console.log(d, i);}, onclick: function (d, i) {console.log(d, i);},}});';
echo '</script>';
?>