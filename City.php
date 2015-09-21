<?php 
mb_internal_encoding('UTF-8');
class Flat {
	public $number;//номер квартиры
	public $porch;//номер подъезда
	public $rooms;//кол-во комнат
	public $square;//площадь квартиры
	public $level;//этаж
	public $tenants;//кол-во жильцов
	public $balkons;//кол-во балконов
	//метод подсчета счета за электричество	
	public function count_electricity($price=0.75,$consuption=30){
		return $this->tenants*$price*$consuption;
		}
	//метод добовления жильца
	public function add_tenant($tenant=1){
		$this->tenants+=$tenant;
	}
	//метод подсчета счета за отопление
	public function count_heat($price=3,$height=2.4){
		return $this->square*$height*$price;	
	}
	//метод подсчета квартплаты
	public function count_flatTax($price=0.5){		
		return $this->square*$price*$this->tenants;
	}
	//метод подсчета всех комунальных услуг
	public function count_all_expenses(){
		return $this->count_flatTax()+$this->count_heat()+$this->count_electricity();		
	}
	public function __construct($number,$porch,$rooms,$square,$level,$tenants,$balkons){
		$this->number=$number;
		$this->porch=$porch;
		$this->rooms=$rooms;
		$this->square=$square;
		$this->level=$level;
		$this->tenants=$tenants;
		$this->balkons=$balkons;
			}
	/*public function __tostring(){
	return "Numbers of room:".$this->rooms."<br>Square:".$this->square."<br>№ofLevel:"$this->level."<br>Tenants:".$this->tenants."<br>Balkons:".$this->balkons;
	}*/
}
class Building{
	public $ownnumber;//номер дома
	public $numberoflevels;//кол-во этажей
	public $numberofporches;//кол-во подъездов
	public $numberofflatperlevel;//кол-во квартир на этаже
	public $spacesaroundthebuilding;//площадь околодомовой территории
	public $numberoffalts;//общее кол-во квартир в доме
	public $flats;//массив объектов квартир
	
	public function __construct($ownnumber,$numberoflevels,$numberofporches,$numberofflatperlevel,$spacesaroundthebuilding,$flats){
		$this->ownnumber=$ownnumber;
		$this->numberoflevels=$numberoflevels;
		$this->numberofporches=$numberofporches;
		$this->numberofflatperlevel=$numberofflatperlevel;
		$this->spacesaroundthebuilding=$spacesaroundthebuilding;
		$this->numberoffalts=$this->numberoflevels*$this->numberofporches*$this->numberofflatperlevel;
		$this->flats=$flats;
			}
	//метод подсчета налога на землю вокруг дома
	public function count_buildingTax($price=5){
	 return $this->spacesaroundthebuilding*$price;
	}
	//метод подсчета расходов на освещение подьездов
	public function count_electricity_expenses($price=0.75,$consuption=3){
	 return $this->numberofporches*$this->numberofflatperlevel*$price*$consuption;
	}
	//метод подсчета всех комунальных услуг для всего дома
	public function count_all_tenants_expenses(){
	foreach ($this->flats as $flat){
	$expenses+=$flat->count_all_expenses();
	}
	return $expenses;
	}
}

class Street{
	public $nameofthestreet;//название улицы
	public $length;//длина улицы
	public $start;//координаты начала
	public $end;//координаты конца
	public $numberofbuildings;//кол-во домов на улице
	public $buildings;//массив домов на улице
		public function __construct($nameofthestreet,$length,$start,$end,$numberofbuildings,$buildings){
		$this->nameofthestreet=$nameofthestreet;
		$this->length=$length;
		$this->start=$start;
		$this->end=$end;
		$this->numberofbuildings=$numberofbuildings;
		$this->buildings=$buildings;
		}
		//метод подсчета дворников для уборки внутридомовой территории
		public function count_yardman($norma=1000){
		$allspacearound=0;
		foreach ($this->buildings as $bulilding){
		$allspacearound+=$bulilding->spacesaroundthebuilding;
		}
		return ceil($allspacearound/$norma);
		}
		//метод подсчета все комуналки с улицы
		public function count_all_buildings_expenses(){
		$buildings_expenses='0';
		foreach ($this->buildings as $bulilding){
			$buildings_expenses+=$bulilding->count_all_tenants_expenses();
			}
			return $buildings_expenses;
		}
}
class City{
	public $townName;//имя населенного пункта
	public $created;//год основания
	public $latitude;
	public $longitude;
	public $streetcount;
	public $streets;//массив улиц
	public function __construct($name,$created,$latitude,$longitude,$streetcount,$C){
		$this->townName=$name;
		$this->created=$created;
		$this->latitude=$latitude;
		$this->longitude=$longitude;
		$this->streetcount=$streetcount;
		$this->streets=$C;
	}
	// рассчитывает бюджет населенного пункта в зависимости от размера налога на землю, полученного со всех домов;
	public function count_landTax(){
		$landTax=0;
		foreach($this->streets as $streets){
			foreach($streets->buildings as $buildings){
				$landTax+=$buildings->count_buildingTax();
				
			}
		}
		return $landTax;
	}
	public function count_citizens(){
		$citizens=0;
		foreach($this->streets as $streets){
			foreach($streets->buildings as $buildings){
				foreach($buildings->flats as $flat){
					$citizens+=$flat->tenants;
				}
				
			}
		}
		return $citizens;
	}
}

class Factory{
	
	static function createFlat($number,$porch,$level){
		$rooms=rand(1,3);
		$square=rand(25,60);
		$tenants=rand(1,5);
		$balkons=rand(1,2);
		return new Flat($number,$porch,$rooms,$square,$level,$tenants,$balkons);
		
	}
	
	static function createBuilding($number){
		$ownnumber=$number;
		$numberoflevels=rand(1,9);
		$numberofporches=rand(1,5);
		$numberofflatperlevel=rand(2,4);
		$spacesaroundthebuilding=rand(500,800);
		$numberoffalts=$numberofflatperlevel*$numberoflevels*$numberofporches;
		//$flats='';
		for ($number=1,$porch=1,$level=1;$number<=$numberoffalts;$number++){
		$B[]=Factory::createFlat($number,$porch,$level);
		if($number%$numberofflatperlevel==0){++$level;}
		if ($number%($numberofflatperlevel*$numberoflevels)==0){++$porch;}
		
		}
		return new Building($ownnumber,$numberoflevels,$numberofporches,$numberofflatperlevel,$spacesaroundthebuilding,$B);
		
		
	}
	static function createStreet($number){
	$nameofthestreet=$number.'th Avenue';
	$length=rand(300,1500);
	$start=(float)rand(33.2200000,33.2201000);
	$end=$start+(float)rand(0.00000000,0.0000999);
	$numberofbuildings=rand(1,50);
	for ($number=1;$number<=$numberofbuildings;$number++){
	$S[]=Factory::createBuilding($number);
	}
	return new Street($nameofthestreet,$length,$start,$end,$numberofbuildings,$S);
	}
	static function createCity(){
		$name="FakeCity";
		$created=rand(1945,1975);
		$latitude=rand(47.00000000,47.2300000);
		$longitude=rand(33.2200000,33.2201000);
		$streetcount=rand(1,40);
		for ($number=1;$number<=$streetcount;$number++){
			$C[]=Factory::createStreet($number);
			}
			return new City($name,$created,$latitude,$longitude,$streetcount,$C);
	}
}

?>