<?php

$dataSetOriginal = [
	'engine' => [2, 2.4, 1.5, 3.5, 3.5, 3.5, 3.7, 3.7, 3.7, 2.4, 2.4, 3.5, 5.9, 5.9, 4.7, 4.7, 4.7, 4.7],
	'emissionCo2' => [196, 221, 136, 255, 244, 230, 232, 255, 267, 212, 225, 239, 359, 359, 338, 354, 338, 354],
];

//split dataSet in train and test tx .3

$dataSetTrain = [
	'engine' => [2, 2.4, 3.5, 3.5, 3.5, 3.7, 3.7, 2.4, 5.9, 5.9, 4.7, 4.7, 4.7],
	'emissionCo2' => [196, 221, 255, 244, 230, 232, 255, 225, 359, 359, 338, 354, 338],
];

$dataSetTest = [
	'engine' => [2.4, 2.5, 4.7, 1.5, 3.7],
	'emissionCo2' => [ 212, 239, 354, 136, 267],
]; //validation


//$testSize = 0.3;

//clone dataset
//$dataSetTrain = $dataSetOriginal;


//define sizes
//$sizeDataSetTrain = count($dataSetTrain['engine']);
//$sizeDataSetTest = round($sizeDataSetTrain * $testSize);
//$newSizeDataSetTrain = $sizeDataSetTrain - $sizeDataSetTest;

//generate DataSets
/*while (count($dataSetTest['engine']) <  $sizeDataSetTest) {
	$keysDataSetTrain = array_keys($dataSetTrain['engine']);
	$key = $keysDataSetTrain[rand(0, count($keysDataSetTrain) -1)];
	
	
	$valueEngine = $dataSetTrain['engine'][$key]; 
	$valueEmissionCo2 = $dataSetTrain['emissionCo2'][$key]; 
	
	array_push($dataSetTest['engine'],  $valueEngine);
	array_push($dataSetTest['emissionCo2'],  $valueEmissionCo2);
	
	unset($dataSetTrain['engine'][$key]);
	unset($dataSetTrain['emissionCo2'][$key]);
}*/

//definition linear function y=a+bx
//a=c0 coeficient 0
//b=c1 coeficient 1
function linearFunction (float $c0, float $x0, float $c1, float $x1):float
{
    return $c0 * $x0 + $c1 * $x1;
}

// define c0 engine

$c0 = 0;
foreach ($dataSetTrain['engine'] as $engine) {
	$c0+= $engine;
}

$c0 =round($c0/count($dataSetTrain['engine']), 2);
$c0 = rand(0, 100);

//define c1 emissionCO2
$c1 =0;
foreach ($dataSetTrain['emissionCo2'] as $emission) {
	$c1+= $emission;
}
//$c1 = rand(0, 100);

$c1 =round($c1/count($dataSetTrain['emissionCo2']), 2);
$c1 = rand(0, 100);

//definerError
function squaredError(float $c0, float $c1, array $data): float {
  $n = count($data['engine']);
  $total = 0;
  foreach ($data['engine'] as $key => $value) {
  	$total += ($data['emissionCo2'][$key] - linearFunction($c0, 1, $c1, $value)) **2;
  }
  
  return $total/$n;
}

function gradientDescent(int $m, float $c0, float $c1, array $data): float
{
  $total = 0;
  
  foreach ($data['engine'] as $key => $value) {
  	$valueOrOne = $m == 0 ? 1 : $value;
  	$total += ($data['emissionCo2'][$key] - linearFunction($c0, 1, $c1, $value)) * $valueOrOne;
  }
  
  return (-2/ count($data['engine'])) * $total;
}

function adaptC0(float $c0, float $c1, array $data, float $learningRate): float
{
    return $c0 - $learningRate * gradientDescent(0, $c0, $c1, $data);
}

function adaptC1(float $c0, float $c1, array $data, float $learningRate): float
{
    return $c1 - $learningRate * gradientDescent(1, $c0, $c1, $data);
}

// Rather small learning rate
$learningRate = 0.000001;

$errors = [];
for ($i = 0; $i < 50; $i++) {
    // Keep the errors so we can graph them later.
    $errors[] = squaredError($c0, $c1, $dataSetTrain);

    // Do not assign immediately, because otherwise 
    // the result of $c0 would influence the descent 
    // of $c1!
    $newC0 = adaptC0($c0, $c1, $dataSetTrain, $learningRate);
    $newC1 = adaptC1($c0, $c1, $dataSetTrain, $learningRate);

    $c0 = $newC0;
    $c1 = $newC1;
}

echo $c0 . ', ' . $c1; // 14.976594533192, 0.99210801880553

/*print_r([
	'c0Engine' => $c0,
	'c1Emission' => $c1,
	'squaredError' => squaredError($c0, $c1, $dataSetTrain),
	'dataSetTrain' => $dataSetTrain,
	'dataSetTest' => $dataSetTest,
]);*/



