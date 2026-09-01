<?php
$config=require __DIR__.'/config.php';
try {
 $pdo=new PDO("mysql:host={$config['host']};charset={$config['charset']}",$config['user'],$config['pass'],[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
 $sql=file_get_contents(__DIR__.'/eventhub.sql');
 $statements=array_filter(array_map('trim',preg_split('/;\s*(?:\r?\n|$)/',$sql)));
 foreach($statements as $statement){if($statement!=='')$pdo->exec($statement);}
 echo '<h2>NSBM EventHub database installed successfully.</h2><p>You can now open <code>frontend/index.html</code> through your local web server.</p>';
} catch(Throwable $e){http_response_code(500);echo '<h2>Installation failed</h2><pre>'.htmlspecialchars($e->getMessage()).'</pre>';}
