<?php

$lock = json_decode(file_get_contents('composer/composer.lock'), true);
$buffer = <<<EOF
<table class="table table-bordered">
	<thead>
		<tr>
			<th>Package</th>
			<th>Version</th>
			<th>Time</th>
		</tr>
	</thead>
	<tbody>

EOF;
foreach ($lock['packages'] as $package) {
	$buffer .= <<<EOF
		<tr>
			<td>{$package['name']}</td>
			<td>{$package['version']}</td>
			<td>{$package['time']}</td>
		</tr>

EOF;
}
$buffer .= <<<EOF
	</tbody>
</table>

EOF;

file_put_contents('packages.html', $buffer);
