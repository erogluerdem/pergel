<?php
$c = file_get_contents('http://127.0.0.1:8000/portfolio/projects/7');
echo (strpos($c,'expenseCategoryPie') !== false) ? "HAS_PIE\n" : "NO_PIE\n";
echo (strpos($c,'expenseMonthlyLine') !== false) ? "HAS_LINE\n" : "NO_LINE\n";
echo (strpos($c,'CSV İndir') !== false) ? "HAS_CSV\n" : "NO_CSV\n";
