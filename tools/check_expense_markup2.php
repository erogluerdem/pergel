<?php
$c = file_get_contents('http://127.0.0.1:8000/portfolio/projects/7');
$terms = ['Gider Özeti','Proje Gider Özeti','expenseCategoryPie','expenseMonthlyLine','CSV İndir','CSV İndir (Giderler)'];
foreach($terms as $t){ echo $t . ': ' . (strpos($c,$t) !== false ? 'YES' : 'NO') . "\n"; }
file_put_contents('c:/pergel/tmp_page.html', $c);
echo "Wrote tmp_page.html\n";
