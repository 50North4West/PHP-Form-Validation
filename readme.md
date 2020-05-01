Fluent Validation Class
======
 A simple, flexible and easy to use PHP form validation class using fluent methodologies


**Note:** index.php  has a typical examples.<br>
2020 Update: I have updated the original code to better represent current best practices regarding validation; using filter_validate etc. I have also added the ability to use this class to validate uploaded files. 

**License:**

GPL v2 http://www.gnu.org/licenses/gpl-2.0.txt


**typical use:**
```
    include 'Validate.php';
    $valid = new Validate($_POST, $_FILES);
    $valid->name('user_name')->required('You must chose a user name!')->alfa()->minSize(5);
    $valid->name('user_email')->required()->email();
    $valid->name('birthdate')->date('please enter date in YYYY-MM-DD format');
    $valid->filename('fileToUpload')->fileSize(500000)->fileType(array('image/jpeg', 'image/gif'))
    if ($valid->isGroupValid()) echo 'Validation Passed!';
```

  **On HTML Form:**
```
  <form method="POST" enctype="multipart/form-data">
  	<input type="text" name="email" value="<?php echo $valid->getValue('email'); ?>" />
  	<span class="error"><?php echo $valid->getError('email'); ?></span>
```

#  To create new validation rules for POST DATA!

**1- define default error message**
```
  private static $error_myValidaton = 'my default error message';
```
**2- create new validation function**
```
    function myValidation($param , $errorMsg=NULL)
      {
      if ($this->isValid && (! empty($this->currentObj->value)))
	    {
	    	//
	    	//code to check if validation pass
	    	//
	   	$this->isValid = // TRUE or FALSE ;
		if (! $this->isValid)
		$this->setErrorMsg($errorMsg, self::$error_myValidation, $param);
    	}
      return $this;
      }
```
**3- use it**
```
    $Valid->name('testing')->myValidation(10, 'some error msg!');
```
#  To create new validation rules for FILES DATA!

**1- define default error message**
```
    private static $error_myValidaton = 'my default error message';
```
**2- create new validation function**
```
    function myValidation($param , $errorMsg=NULL)
      {
      if ($this->isValid && (! empty($this->currentFileObject->value)))
	    {
	    	//
	    	//code to check if validation pass
	    	//
	   	$this->isValid = // TRUE or FALSE ;
		if (! $this->isValid)
		$this->setFileErrorMsg($errorMsg, self::$error_myValidation, $param);
    	}
      return $this;
      }
```
**3- use it**
```
    $Valid->name('testing')->myValidation(10, 'some error msg!');
```
