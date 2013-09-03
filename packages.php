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
	$version = $package['version'];
	
	if (preg_match('#(^dev-|-dev$)#', $package['version'])) {
		if (isset($package['source']['reference'])) {
			$version .= '&nbsp;@&nbsp;' . substr($package['source']['reference'], 0, 6);
		}
		else if (isset($package['dist']['reference'])) {
			$version .= '&nbsp;@&nbsp;' . substr($package['dist']['reference'], 0, 6);
		}
	}
	
	$buffer .= <<<EOF
		<tr>
			<td>{$package['name']}</td>
			<td>{$version}</td>
			<td>{$package['time']}</td>
		</tr>

EOF;
}
$buffer .= <<<EOF
	</tbody>
</table>

EOF;

file_put_contents('packages.html', $buffer);
