alternative-field
=================

## Introduction
Simple dropdown field which displays a text field when 'Other' is selected. Great for Honorific selects.


## How to use
Add the dropdown options to the _config/AlternativeFormField.yml file.

You can also edit the empty string for the dropdown and the label for the 'other' field in the yml.


## Requirements
SilverStripe 3.0 or higher is required.

## Example
```php
<?php

	public function getCMSFields() {
		$fields = parent::getCMSFields();
		$fields->addFieldsToTab("Root.Whatever", array(
			AlternativeFormField::create('Honorific', 'Honorific'),
			TextField::create('FirstName', 'First name'),
			TextField::create('Surname', 'Last name')
		));

		return $fields;
	}
```