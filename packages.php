<?php

$lock = json_decode(file_get_contents('composer/composer.lock'), true);
$buffer = <<<EOF
<table class="table table-striped">
	<thead>
		<tr>
			<th>Package</th>
			<th>Version</th>
			<th>Time</th>
			<th>License</th>
		</tr>
	</thead>
	<tbody>

EOF;
foreach ($lock['packages'] as $package) {
	if (isset($package['source'])) {
		$url = preg_replace('#\.git$#', '', $package['source']['url']);
	}
	else {
		$url = false;
	}

	if (preg_match('#^https?://#', $url)) {
		$link = sprintf('<a href="%s" target="_blank">%s</a>', $url, $package['name']);
	}
	else {
		$link = $package['name'];
	}

	$version = $package['version'];

	if (preg_match('#(^dev-|-dev$)#', $package['version'])) {
		if (isset($package['source']['reference'])) {
			$version .= '&nbsp;@&nbsp;' . substr($package['source']['reference'], 0, 6);
		}
		else if (isset($package['dist']['reference'])) {
			$version .= '&nbsp;@&nbsp;' . substr($package['dist']['reference'], 0, 6);
		}
	}

	$time = $package['time'];
	$license = implode(', ', $package['license']);

	$buffer .= <<<EOF
		<tr>
			<td>{$link}</td>
			<td>{$version}</td>
			<td>{$time}</td>
			<td>{$license}</td>
		</tr>

EOF;
}
$buffer .= <<<EOF
	</tbody>
</table>

EOF;

file_put_contents('packages.html', $buffer);
