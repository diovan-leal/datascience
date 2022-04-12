<?php

class LinearRegression
{
	private $dataSetOriginal = [
		'engine' => [2, 2.4, 1.5, 3.5, 3.5, 3.5, 3.7, 3.7, 3.7, 2.4, 2.4, 3.5, 5.9, 5.9, 4.7, 4.7, 4.7, 4.7],
		'emissionCo2' => [196, 221, 136, 255, 244, 230, 232, 255, 267, 212, 225, 239, 359, 359, 338, 354, 338, 354],
	];
	
	private $dataSetTrain = [
		'engine' => [2, 2.4, 3.5, 3.5, 3.5, 3.7, 3.7, 2.4, 5.9, 5.9, 4.7, 4.7, 4.7],
		'emissionCo2' => [196, 221, 255, 244, 230, 232, 255, 225, 359, 359, 338, 354, 338],
	];
	
	private $dataSetTest = [
		'engine' => [2.4, 2.5, 4.7, 1.5, 3.7],
		'emissionCo2' => [ 212, 239, 354, 136, 267],
	];
	
	private $n = 0;
	private $somatoriaXY = 0;
	private $somatoriaX = 0;
	private $somatoriaY = 0;
	private $somatoriaXSquare = 0;
	
	public function initVariables()
	{
		$this->n = count($this->dataSetTrain['engine']);
		
		foreach ($this->dataSetTrain['engine'] as $key => $value) {
			$this->somatoriaXY += $value * $this->dataSetTrain['emissionCo2'][$key]; 
			$this->somatoriaX += $value;
			$this->somatoriaY += $this->dataSetTrain['emissionCo2'][$key];
			$this->somatoriaXSquare += $value * $value;
		}
	}
	
	public function decliveB()
	{
		/*
			inclinaçao do coeficiente b (slope)
			Declive(b) = 
			(NΣXY - (ΣX)(ΣY)) / (NΣX2 - (ΣX)2)
		*/
		$numeratorFraction = ($this->n * $this->somatoriaXY - ($this->somatoriaX * $this->somatoriaY));
		$denominatorFraction = ($this->n * $this->somatoriaXSquare - pow($this->somatoriaX, 2));
		
		return $numeratorFraction / $denominatorFraction;
	}
	
	public function interceptorA()
	{
		return ($this->somatoriaY - ($this->decliveB() * $this->somatoriaX)) / $this->n;
	}
}

$linearRegression = new LinearRegression();

$linearRegression->initVariables();

//formulas
//Equação de regressão(y) = a + bx
//Declive(b) = (NΣXY - (ΣX)(ΣY)) / (NΣX2 - (ΣX)2)
//Interceptar(a) = (ΣY - b(ΣX)) / N
//

print_r([
	'b'	 => $linearRegression->decliveB(),
	'a' => $linearRegression->interceptorA(),
]);

//https://www.easycalculation.com/pt/statistics/regression.php
//https://www.desmos.com/calculator/rwuxzpl5e2?lang=pt-BR

/*
- técnica estatistica usada em IA machine learning
	simples 2 variáveis
	multipla mais de 2 variávies envolvidas
- trabalha com dados continuos
- relacionamento entre variáveis x e y ou outras
- procura determinar reta (modelo) para predição dos futuros valores

função da reta
Y= a + b *x

x variável independente|preditora (tamanho do motor)
y variável dependente|

da para melhorar?


performance
fonts:
https://www.mit.edu/~6.s085/notes/lecture3.pdf
https://www.youtube.com/watch?v=J9HuFIYcFWU&t=1891s
https://www.youtube.com/watch?v=n--K70T6c3A
https://dev.to/thormeier/algorithm-explained-linear-regression-using-gradient-descent-with-php-1ic0

tools:
https://www.desmos.com/
https://www.easycalculation.com/pt/statistics/regression.php
https://sandbox.onlinephpfunctions.com/

*/
