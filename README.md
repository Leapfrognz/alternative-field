alternative-field
=================

## Description
Simple dropdown field which displays a text field when 'Other' is selected. Great for Honorifics selects.

Add the dropdown options to the _config/AlternativeFormField.yml file.

You can also edit the empty string for the dropdown and the label for the 'other' field in the yml.


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