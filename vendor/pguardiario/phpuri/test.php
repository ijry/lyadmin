<?php

require('../rel2abs.php');
require('../url_to_absolute.php');
require('phpuri.php');

$tests=array(
  array('rel' => 'g:h', 'result' => 'g:h'),
  array('rel' => 'g',   'result' => 'http://a/b/c/g'),
  array('rel' => './g', 'result' => 'http://a/b/c/g'),
  array('rel' => 'g/',  'result' => 'http://a/b/c/g/'),
  array('rel' => '/g',  'result' => 'http://a/g'),
  array('rel' => '//g', 'result' => 'http://g'),
  array('rel' => 'g?y', 'result' => 'http://a/b/c/g?y'),
  array('rel' => '#s', 'result' => 'http://a/b/c/d;p?q#s'),
  array('rel' => 'g#s', 'result' => 'http://a/b/c/g#s'),
  array('rel' => 'g?y#s', 'result' => 'http://a/b/c/g?y#s'),
  array('rel' => ';x', 'result' => 'http://a/b/c/;x'),
  array('rel' => 'g;x', 'result' => 'http://a/b/c/g;x'),
  array('rel' => 'g;x?y#s', 'result' => 'http://a/b/c/g;x?y#s'),
  array('rel' => '.', 'result' => 'http://a/b/c/'),
  array('rel' => './', 'result' => 'http://a/b/c/'),
  array('rel' => '..', 'result' => 'http://a/b/'),
  array('rel' => '../', 'result' => 'http://a/b/'),
  array('rel' => '../g', 'result' => 'http://a/b/g'),
  array('rel' => '../..', 'result' => 'http://a/'),
  array('rel' => '../../', 'result' => 'http://a/'),
  array('rel' =>'../../g','result' =>'http://a/g'),
  array('rel' =>'g.','result' =>'http://a/b/c/g.'),
  array('rel' =>'.g','result' =>'http://a/b/c/.g'),
  array('rel' =>'g..','result' =>'http://a/b/c/g..'),
  array('rel' =>'..g','result' =>'http://a/b/c/..g'),
  array('rel' =>'./../g','result' =>'http://a/b/g'),
  array('rel' =>'./g/.','result' =>'http://a/b/c/g/'),
  array('rel' =>'g/./h','result' =>'http://a/b/c/g/h'),
  array('rel' =>'g/../h','result' =>'http://a/b/c/h'),
  array('rel' =>'g;x=1/./y','result' =>'http://a/b/c/g;x=1/y'),
  array('rel' =>'g;x=1/../y','result' =>'http://a/b/c/y'),
  array('rel' =>'g?y/./x','result' =>'http://a/b/c/g?y/./x'),
  array('rel' =>'g?y/../x','result' =>'http://a/b/c/g?y/../x'),
  array('rel' =>'g#s/./x','result' =>'http://a/b/c/g#s/./x'),
  array('rel' =>'g#s/../x','result' =>'http://a/b/c/g#s/../x')
);

# rel2abs
$start = microtime();
$base = 'http://a/b/c/d;p?q';
list($successes, $failures) = array(0,0);
foreach($tests as $test){
  if(($r = rel2abs($test['rel'], $base)) == $test['result']){
    $successes++;
  } else {
    $failures++;
  }
}

$elapsed = microtime() - $start;
echo "rel2abs:         successes -> $successes, failures => $failures, elapsed time: $elapsed\n";

# url_to_absolute
$start = microtime();
$base = 'http://a/b/c/d;p?q';
list($successes, $failures) = array(0,0);
foreach($tests as $test){
  if(($r = url_to_absolute($base, $test['rel'])) == $test['result']){
    $successes++;
  } else {
    $failures++;
  }
}

$elapsed = microtime() - $start;
echo "url_to_absolute: successes -> $successes, failures => $failures, elapsed time: $elapsed\n";

# phpuri
$start = microtime();
$base = phpUri::parse('http://a/b/c/d;p?q');
list($successes, $failures) = array(0,0);
foreach($tests as $test){
  if(($r = $base->join($test['rel'])) == $test['result']){
    $successes++;
  } else {
    $failures++;
    echo "failure: $r instead of " . $test['result'] . " \n";
  }
}
$elapsed = microtime() - $start;
echo "phpuri:          successes -> $successes, failures => $failures, elapsed time: $elapsed\n";
?>