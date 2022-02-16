<?php

interface IStrategy {
	public function supports($strategyType): bool;
	public function calc($price);
}

class GeneralStrategy implements IStrategy {
	
	const TYPE = 'general';
	
	public function supports($strategyType): bool
	{
		return $strategyType === self::TYPE;
	}
	
	public function calc($price) {
		return $price * 1.1;
	}
}

class DiscountStrategy implements IStrategy {
	
	const TYPE = 'discount';
	
	public function supports($strategyType): bool
	{
		return $strategyType === self::TYPE;
	}
	
	public function calc($price) {
		return $price * 1.05;
	}
}

class Context {
	
	private $strategies = [];
	
	public function __construct()
	{
		$this->strategies[] = new DiscountStrategy();
		$this->strategies[] = new GeneralStrategy();
	}
	
	public function calc($strategyType, $price)
	{
		$calculatedPrice = null;
		foreach ($this->strategies  as $strategy) {
			if ($strategy->supports($strategyType)) {
				$calculatedPrice = $strategy->calc($price);
			}
		}
		
		return $calculatedPrice;
	}
	
}


$context = new Context();

$price_general = $context->calc('general', 100);

$price_discount = $context->calc('discount', 100);





