<?php

  /**
  *       Validation class
  *       Forked from ValidFluent written by Andre Soares
  *       This version found at https://github.com/50North4West/PHP-Form-Validation
  *       Changes to original class include:
  *         • Renamed to Validate
  *         • Use of filter_var instead of regex for some checks
  *         • Inclusion of file validation
  *         • Add PSR2 Coding Standard
  **/

  //Helper class
  class validateObject
  {

    public $value;
    public $error;

    function __construct($value)
    {
      $this->value = $value;
      $this->error = '';
    }
  }//end validate object class

  //Helper class
  class validateFileObject
  {

    public $fileName;
    public $type;
    public $tmp_name;
    public $uploadError;
    public $size;
    public $error;

    function __construct($filename, $type, $tmp_name, $uploadError, $size)
    {
      $this->fileName = $filename;
      $this->type = $type;
      $this->tmpName = $tmp_name;
      $this->uploadError = $uploadError;
      $this->size = $size;
      $this->error = '';
    }
  }//end validate file object class


  //main Validate class
  class Validate
  {

    public $isValid = true;
    public $isGroupValid = true;
    public $validObjects; //array
    public $validFileObjects; //array
    public $currentObject; //pointer to the current object as set by ->name()
    public $currentFileObject; //pointer to the current object as set by ->name()


    //default error messages
    private static $error_required = 'This field is required';
    private static $error_date = 'Please enter a date in the YYYY-MM-DD format';
    private static $error_email = 'Please enter a valid email';
    private static $error_url = 'Please enter a valid url';
    private static $error_alfa = 'Only letters and numbers are permited';
    private static $error_text = 'Only letters are permited';
    private static $error_minSize = 'Please enter more than %s characters';
    private static $error_maxSize = 'Please enter less than %s characters';
    private static $error_numberFloat = 'Only numbers are permitted';
    private static $error_numberInteger = 'Only numbers are permitted';
    private static $error_numberMax = 'Please enter a value lower than %s ';
    private static $error_numberMin = 'Please enter a value greater than %s ';
    private static $error_oneOf = 'Please choose one of " %s "';
    private static $error_equal = 'Fields did not match';
    private static $error_regex = 'Please choose a valid value';
    private static $error_fileSize = 'The file size is too big';
    private static $error_fileType = 'The file is the wrong format';

    //regex's
    private $pattern_alpha = '/^(\d|\-|_|\.| |(\p{L}\p{M}*))+$/u';
    private $pattern_text = '/^( |(\p{L}\p{M}*)+$/u';


    function __construct($post, $files = null)
    {
      if ($files) {
        foreach ($files as $fileKey => $fileValue) {
          $this->validFileObjects[$fileKey] = new validateFileObject($fileValue['name'], $fileValue['type'], $fileValue['tmp_name'], $fileValue['error'], $fileValue['size']);
        }
      }

      foreach ($post as $key => $value) {
          $this->validObjects[$key] = new validateObject(trim($value));
      }
    }

    //used to return the last validation, true if passed, else false
    function isValid()
    {
      return $this->isValid;
    }


    //used to return all of the validations, true if passed, else false
    function isGroupValid()
    {
      return $this->isGroupValid;
    }


    //return the field's validation error
    function getError($name)
    {
      if (isset($this->validObjects[$name])) {
        return $this->validObjects[$name]->error;
      } else {
        return '';
      }
    }

    //return the file upload field's validation error
    function getFileError($name)
    {
      if (isset($this->validFileObjects[$name])) {
        return $this->validFileObjects[$name]->error;
      } else {
        return '';
      }
    }


    //return the field's value
    function getValue($name)
    {
      if (isset($this->validObjects[$name])) {
        return $this->validObjects[$name]->value;
      } else {
        return '';
      }
    }


    //used to set the starting values on form data - e.g. $valid->name('user_name)->setValue($data['pageData']['name']);
    function setValue($value)
    {
      $this->currentObject->value = $value;
      return $this;
    }


    //used to set error messages outside the scope of the Validate class - e.g. $valid->name('user_name')->setError('The Name "Andre" is already taken , please try another');
    function setError($error)
    {
      $this->currentObject->error = $error;
      $this->isGroupValid = false;
      $this->isValid = false;
      return $this;
    }


    //set the error message - 'custom error message', 'default message', 'extra parameter to default message'
    private function setErrorMsg($errorMsg, $default, $params = null)
    {
      $this->isGroupValid = false;
      if ($errorMsg == '') {
        $this->currentObject->error = sprintf($default, $params);
      } else {
        $this->currentObject->error = $errorMsg;
      }
    }


    //set the error message - 'custom error message', 'default message', 'extra parameter to default message'
    private function setFileErrorMsg($errorMsg, $default, $params = null)
    {
      $this->isGroupValid = false;
      if ($errorMsg == '') {
        $this->currentFileObject->error = sprintf($default, $params);
      } else {
        $this->currentFileObject->error = $errorMsg;
      }
    }


    //validation functions

    //used to set a pointer for the current validation object, if $name doesn't exist it will be created with an empty value
    //validation will always pass on empty, not required fields
    //name is supplied in an array format $name=>'user_name'
    function name($name)
    {
      if (!isset($this->validObjects[$name]))
        $this->validObjects[$name] = new validateObject('');
        $this->isValid = true;
        $this->currentObject = &$this->validObjects[$name];
        return $this;
    }

    //used to set a pointer for the current validation object, if $name doesn't exist it will be created with an empty value
    //validation will always pass on empty, not required fields
    //name is supplied in an array format $fileName=>'user_name'
    function fileName($name)
    {
      if (!isset($this->validFileObjects[$name])) {
        $this->validFileObjects[$name] = new validateObject('');
      }
        $this->isValid = true;
        $this->currentFileObject = &$this->validFileObjects[$name];
        return $this;
    }

    //if a field is required it must be called right after the name! e.g. $valid->name('user_name')->required()->text()->minSize(5);
    function required($errorMsg = null)
    {
      if ($this->isValid) {
        $this->isValid = ($this->currentObject->value != '') ? true : false;

        if (!$this->isValid) {
          $this->setErrorMsg($errorMsg, self::$error_required);
        }
      }
      return $this;
    }


    //used to validate a date, make sure to pass date format
    function date($format = 'Y-m-d', $errorMsg = null)
    {
      if ($this->isValid && (!empty($this->currentObject->value))) {
        $this->isValid = (DateTime::createFromFormat($format, $this->currentObject->value)) ? true : false;

        if (!$this->isValid) {
          $this->setErrorMsg($errorMsg, self::$error_date);
        }
      }
      return $this;
    }


    //used to validate an email
    function email($errorMsg = null)
    {
      if ($this->isValid && (!empty($this->curretnObject->value))) {
        $this->isValid = (filter_var($this->currentObject->value, FILTER_VALIDATE_EMAIL)) ? true : false;

        if (!$this->isValid) {
          $this->setErrorMsg($errorMsg, self::$error_email);
        }
      }
      return $this;
    }


    //used to validate an URL
    function url($errorMsg = null)
    {
      if ($this->isValid && (!empty($this->currentObject->value))) {
        $this->isValid = (filter_var($this->currentObject->value, FILTER_VALIDATE_URL)) ? true : false;

        if (!$this->isValid) {
          $this->setErrorMsg($errorMsg, self::$error_url);
        }
      }
      return $this;
    }

    //used to send a custom regex
    function regex($regex, $errorMsg = null)
    {
      if ($this->isValid && (!empty($this->currentObject->value))) {
        $this->isValid = (filter_var($this->currentObject->value, FILTER_VALIDATE_REGEXP, $regex)) ? true : false;

        if (!$this->isValid) {
          $this->setErrorMsg($errorMsg, self::$error_regex);
        }
      }
      return $this;
    }


    //used to check one field against another (e.g. password, password-confir0 | ->name('password')->equal('passwordConfirm', 'message: password doesn't match'))
    function equal($value2, $errorMsg = null)
    {
      if ($this->isValid && (!empty($this->currentObject->value))) {
        $this->isValid = ($value2 == $this->currentObject->value);

        if (!$this->isValid) {
          $this->setErrorMsg($errorMsg, self::$error_equal);
        }
      }
      return $this;
    }


    //used to check whether the object is one of something. e.g. ->oneOf('blue:red:green', 'only blue, red and green are permitted')
    function oneOf($items, $errorMsg = null)
    {
      if ($this->isValid && (!empty($this->currentObject->value))) {
        $item = explode(':', strtolower($items));
        $result = array_intersect($item, array(strtolower($this->currentObject->value)));
        $this->isValid = (!empty($result));

        if (!$this->isValid) {
          $itemsList = str_replace(':', ' / ', $items);
          $this_>setErrorMsg($errorMsg, self::$error_oneOf, $itemsList);
        }
      }
      return $this;
    }


    //used to allow only text
    function text($errorMsg = null)
    {
      if ($this->isValid && (!empty($this->currentObject->value))) {
        $this->isValid = (ctype_alpha($this->currentObject->value)) ? true : false;

        if (!$this->isValid) {
          $this->setErrorMsg($errorMsg, self::$error_text);
        }
      }
      return $this;
    }


    //used to allow text and numbers
    function alpha($errorMsg = null)
    {
      if ($this->isValid && (!empty($this->currentObject->value))) {
        $this->isValid = (ctype_alnum($this->currentObject->value)) ? true : false;

        if (!$this->isValid) {
          $this->setErrorMsg($errorMsg, self::$error_alpha);
        }
      }
      return $this;
    }


    //used to set the maximum string size
    function maxSize($size, $errorMsg = null)
    {
      if ($this->isValid && (!empty($this->currentObject->value))) {
        $this->isValid = (strlen($this->currentObject->value) <= $size);

        if (!$this->isValid) {
          $this->setErrorMsg($errorMsg, self::$error_maxSize, $size);
        }
      }
      return $this;
    }


    //used to set the mimimum string size
    function minSize($size, $errorMsg = null)
    {
      if ($this->isValid && (!empty($this->currentObject->value))) {
        $this->isValid = (strlen($this->currentObject->value) >= $size);

        if (!$this->isValid) {
          $this->setErrorMsg($errorMsg, self::$error_minSize, $size);
        }
      }
      return $this;
    }

    //used to check a number is under a maximum amount
    function numberMax($max, $errorMsg = null)
    {
      if ($this->isValid && (!empty($this->currentObject->value))) {
        $this->isValid = ($this->currentObject->value <= $max) ? true : false;

        if (!$this->isValid) {
          $this->setErrorMsg($errorMsg, self::$error_numberMax, $max);
        }
      }
      return $this;
    }

    //used to check a number is over a mimimum amount
    function numberMin($min, $errorMsg = null)
    {
      if ($this->isValid && (!empty($this->currentObject->value))) {
        $this->isValid = ($this->currentObject->value <= $min) ? true : false;

        if (!$this->isValid) {
          $this->setErrorMsg($errorMsg, self::$error_numberMax, $min);
        }
      }
      return $this;
    }


    //used to check if a float
    function numberFloat($errorMsg = null)
    {
      if ($this->isValid && (!empty($this->currentObject->value))) {
        $this->isValid = (filter_var($this->currentObject->value, FILTER_VALIDATE_FLOAT)) ? true : false;

        if (!$this->isValid) {
          $this->setErrorMsg($errorMsg, self::$error_numberFloat);
        }
      }
      return $this;
    }


    //used to check if a integer
    function numberInteger($errorMsg = null)
    {
      if ($this->isValid && (!empty($this->currentObject->value))) {
        $this->isValid = (filter_var($this->currentObject->value, FILTER_VALIDATE_INT)) ?  true : false;

        if (!$this->isValid) {
          $this->setErrorMsg($errorMsg, self::$error_numberInteger);
        }
      }
      return $this;
    }


    //used to check the filesize of an upload - ->fileSize(5000000)
    function fileSize($size, $errorMsg = null)
    {
      if ($this->isValid && (!empty($this->currentFileObject->fileName))) {
        $this->isValid = ($this->currentFileObject->size < $size) ? true : false;

        if (!$this->isValid) {
          $this->setFileErrorMsg($errorMsg, self::$error_fileSize);
        }
      }
      return $this;
    }


    //used to check the the file type is allowed - ->fileType(array('image/jpeg', 'image/gif'))
    function fileType($allowedTypes, $errorMsg = null)
    {
      if ($this->isValid && (!empty($this->currentFileObject->fileName))) {
        $finfo = new finfo();
        $fileMimeType = $finfo->file($this->currentFileObject->tmpName, FILEINFO_MIME_TYPE);
        $this->isValid = (in_array($fileMimeType, $allowedTypes)) ? true : false;

        if (!$this->isValid) {
          $this->setFileErrorMsg($errorMsg, self::$error_fileType);
        }
      }
      return $this;
    }


  }//end validate class
