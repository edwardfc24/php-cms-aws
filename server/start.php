<?php  

require_once  R_PATH.'vendor/autoload.php';

$config = require('config.php');
// S3

$s3 = new Aws\S3\S3Client([
	'region' => $config['s3']['region'],
	'version' => $config['s3']['version'],
	'credentials' => [
	'key'    => $config['s3']['key'],
	'secret' => $config['s3']['secret']
	],
	'scheme' => 'http'
	]);
	?>