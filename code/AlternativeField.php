<?php

class AlternativeField extends DBField implements CompositeDBField {

	/**
	 * @var string The selected value for this field
	 */
	protected $selectedValue;


	/**
	 * @var string The alternative value for this field
	 */
	protected $alternativeValue;

	/**
	 * @var boolean Is this record changed or not?
	 */
	protected $isChanged = false;

	static $composite_db = array(
		'SelectedValue' => 'Varchar(10)',
		'AlternativeValue' => 'Varchar(30)'
	);

	function setValue($value, $record = null, $markChanged = true) {

		if($value instanceof AlternativeField && $value->exists()) {
			$this->setSelectedValue($value->getSelectedValue(), $markChanged);
			$this->setAlternativeValue($value->getAlternativeValue(), $markChanged);
			if($markChanged) $this->isChanged = true;
		} else if ($record && (isset($record[$this->name . 'SelectedValue']) || isset($record[$this->name . 'AlternativeValue']))) {
			//var_dump($record[$this->name . 'SelectedValue']);
			//exit();
			$this->setSelectedValue((isset($record[$this->name . 'SelectedValue'])) ? $record[$this->name . 'SelectedValue'] : null, $markChanged);
			$this->setAlternativeValue((isset($record[$this->name . 'AlternativeValue'])) ? $record[$this->name . 'AlternativeValue'] : null, $markChanged);
			if($markChanged) $this->isChanged = true;
		} else if(is_array($value)) {
			if(array_key_exists('SelectedValue', $value)) {
				$this->setSelectedValue($value['SelectedValue'], $markChanged);
			}
			if(array_key_exists('AlternativeValue', $value)) {
				$this->setAlternativeValue($value['AlternativeValue'], $markChanged);
			}
			if($markChanged) $this->isChanged = true;
		} 

		// if not using the default then set alternative to null
		if($this->getSelectedValue() != 'Other') {
			$this->setAlternativeValue(null);
		}

	}
	
	function requireField(){
		$fields = $this->compositeDatabaseFields();
		if($fields) foreach($fields as $name => $type){
			DB::requireField($this->tableName, $this->name.$name, $type);
		}
	}

	function writeToManipulation(&$manipulation) {
		if($this->getSelectedValue()) {
			$manipulation['fields'][$this->name.'SelectedValue'] = $this->prepValueForDB($this->getSelectedValue());
		} else {
			$manipulation['fields'][$this->name.'SelectedValue'] = DBField::create_field('Varchar', $this->getSelectedValue())->nullValue();
		}
		
		if($this->getAlternativeValue()) {
			$manipulation['fields'][$this->name.'AlternativeValue'] = $this->prepValueForDB($this->getAlternativeValue());
		} else {
			$manipulation['fields'][$this->name.'AlternativeValue'] = DBField::create_field('Varchar', $this->getAlternativeValue())->nullValue();
		}
	}

	function addToQuery(&$query) {
		parent::addToQuery($query);
	}

	function compositeDatabaseFields(){
		return static::$composite_db;
	}

	function isChanged(){
		return $this->isChanged;
	}

	function exists(){
		return ($this->selectedValue !== null || $this->alternativeValue !== null);
	}
	
	public function getSelectedValue() {
		return $this->selectedValue;
	}
	
	public function setSelectedValue($selectedValue, $markChanged = true) {

		$this->isChanged = $markChanged;
		$this->selectedValue = $selectedValue;
	}
	
	public function getAlternativeValue() {
		return $this->alternativeValue;
	}
	
	public function setAlternativeValue($alternativeValue, $markChanged = true) {
		$this->isChanged = $markChanged;
		$this->alternativeValue = $alternativeValue;
	}

	public function scaffoldFormField($title = null) {
		$field = new AlternativeFormField($this->name);
		return $field;
	}

	public function actualValue() {
		if($this->selectedValue == 'Other') return $this->alternativeValue;
		else if($this->selectedValue) return $this->selectedValue;
		else return null;
	}

	public function __toString() {
		return $this->getActualValue();
	}
	
	function forTemplate() {
		return $this->getActualValue();
	}

}
