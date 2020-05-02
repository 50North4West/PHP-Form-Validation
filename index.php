<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <title>PHP Form Validation</title>
  <meta name="description" content="A PHP Form Validation class">
  <meta name="author" content="50North 4West">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <link rel="stylesheet" href="css/styles.css?v=1.0">

</head>
<body>
  <div class="container">
    <div class="row mt-5">
      <div class="col-12 text-center">
        <h3 class="display-4">PHP Form Validation Class</h3>
      </div>
    </div>
    <div class="row">
      <div class="col-12 text-justify text-secondary">
        <p>
          A simple, flexible and easy to use PHP form validation class using fluent methodologies. Fluent design allows you to chain method calls, which results in less typed
          characters when applying multiple operations on the same object. The validator has been forked from the orignal written by Andre Soares and found at
          <a href="https://github.com/ASoares/PHP-Form-Validation">https://github.com/ASoares/PHP-Form-Validation</a>. This updated version is maintained by 50North 4West and can be
          found at <a href="https://github.com/ASoares/PHP-Form-Validation">https://github.com/ASoares/PHP-Form-Validation</a>. This new version follows the PSR-2 Coding Standard,
          uses PHP's native FILTER_VALIDATE_XXXX wherever possible and validates file uploads.
        </p>
      </div>
    </div>
    <div class="row">
      <div class="col-12 text-justify text-secondary">
        <p>
          <b>Licence:</b> <a href="http://www.gnu.org/licenses/gpl-2.0.txt">GPL v2 (GNU GENERAL PUBLIC LICENSE)</a>.
        </p>
      </div>
    </div>
    <div class="row">
      <div class="col-12 text-justify text-secondary">
        <hr />
        <h4>
          <b>How to use:</b>
        </h4>
        <p>
          <ul>
            <li>
              Inlcude the validate class using <code>include 'Validate.php';</code>
            </li>
            <li>
              In your form processing script Instantiate/create the Validate object <code>$valid = new Validate($_POST, $_FILES); </code>
            </li>
            <li>
              Set each fields checks by chaining together method calls (note: required should ALWAYS follow field name):
              <ul>
                <li>
                  <code>$valid->name('user_name')->required()->email();</code>
                </li>
                <li>
                  <code>$valid->name('password')->required()->alpha()->minSize(5);</code>
                </li>
                <li>
                  <code>$valid->name('password_confirm')->required()->alpha()->minSize(5)->equal('password');</code>
                </li>
              </ul>
            </li>
            <li>
              You can check if the fields have validated with <code>if ($valid->isGroupValid()) {echo 'Validation Passed!';}</code>
            </li>
            <li>
              You can get the custom or default error message's by using <code>echo $validate->getError('exampleInputNumber');</code>
            </li>
            <li>
              You can set or return the form fields' data with <code>value="echo $validate->getValue('exampleInputEmail');"</code>
            </li>
          </ul>
        </p>
      </div>
    </div>
    <div class="row">
      <div class="col-12 text-justify text-secondary">
        <hr />
        <h4>
          <b>Form Example:</b>
        </h4>
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <div class="card bg-light">
          <div class="card-body">
            <?php
              //Form Processing Script

              //include the Validate Class
              include 'Validate.php';

              //You can set the fields prior to submitting the form
              if (empty($_POST)) {

                //Instantiate the class
                $validate = new Validate(array());

                //Set a field value
                $validate->name('exampleInputEmail')->setValue('test@test.com');
              } else {
                //validate the $_POST & $_FILES data

                //Instantiate the class
                $validate = new Validate($_POST, $_FILES);

                //Set the field validations you want to do
                $validate->name('exampleInputEmail')->required()->email();
                $validate->name('exampleInputDate')->date('Y-m-d');
                $validate->name('exampleInputNumber')->numberFloat()->maxSize(5);
                $validate->name('exampleInputRequired')->required();

                //when using a file upload field ensure that you use ->filename('fieldName') not ->name('fieldName')
                $validate->fileName('exampleFormControlFile')->fileSize(500000)->fileType(array('image/jpeg', 'image/gif'));

                //Give a general failed or success message
                if ($validate->isGroupValid()): ?>
                <div class="alert alert-success" role="alert">
                  Form was ok.
                </div>
                <?php else: ?>
                <div class="alert alert-danger" role="alert">
                  There was something wrong on the form.
                </div>
                <?php endif;
              }
            ?>
            <form method="POST" enctype="multipart/form-data">
              <div class="row">
                <div class="col-6">
                  <div class="form-group">
                    <label for="exampleInputEmail">Example Email field (You can also set a value)</label>
                    <input type="email" class="form-control <?php echo ($validate->getError('exampleInputEmail')) ? 'is-invalid' : ''; ?>" name="exampleInputEmail" value="<?php echo $validate->getValue('exampleInputEmail'); ?>">
                    <div class="invalid-feedback"><?php echo $validate->getError('exampleInputEmail'); ?></div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label for="exampleInputDate">Example Date field</label>
                    <input type="date" class="form-control <?php echo ($validate->getError('exampleInputDate')) ? 'is-invalid' : ''; ?>" name="exampleInputDate" value="<?php echo $validate->getValue('exampleInputDate'); ?>">
                    <div class="invalid-feedback"><?php echo $validate->getError('exampleInputDate'); ?></div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-6">
                  <div class="form-group">
                    <label for="exampleInputNumber">Example Number field (Float, max 5)</label>
                    <input type="number" class="form-control <?php echo ($validate->getError('exampleInputNumber')) ? 'is-invalid' : ''; ?>" name="exampleInputNumber" value="<?php echo $validate->getValue('exampleInputNumber'); ?>">
                    <div class="invalid-feedback"><?php echo $validate->getError('exampleInputNumber'); ?></div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label for="exampleInputRequired">Example Required field</label>
                    <input type="text" class="form-control <?php echo ($validate->getError('exampleInputRequired')) ? 'is-invalid' : ''; ?>" name="exampleInputRequired" value="<?php echo $validate->getValue('exampleInputRequired'); ?>">
                    <div class="invalid-feedback"><?php echo $validate->getError('exampleInputRequired'); ?></div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-6">
                  <div class="form-group">
                    <label for="exampleFormControlFile">Example file input (jpeg or gif)</label>
                    <input type="file" class="form-control-file <?php echo ($validate->getFileError('exampleFormControlFile')) ? 'is-invalid' : ''; ?>" name="exampleFormControlFile">
                    <div class="invalid-feedback"><?php echo $validate->getFileError('exampleFormControlFile'); ?></div>
                  </div>
                </div>
              </div>
              <button type="submit" class="btn btn-primary mt-2">Submit</button>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="row mt-4">
      <div class="col-12 text-secondary">
        <hr />
        <h4>
          <b>Available Validations:</b>
        </h4>
        <p>
          You can create your own additional validations however the following lists are included in the Class. Please see further down the page for how to do this. All validations must start with
          <code>$validate->name('fieldName')</code>. Most functions allow a custom error message passed to them, alternatively you can set default messages in the Class. Date formats use any acceptable PHP date format.
        </p>
        <div class="row">
          <div class="col-md-6">
            <p>
              POST DATA
              <ul>
                <li>
                  Required: <code>->required('custom error message')</code>
                </li>
                <li>
                  Date: <code>->date('Y-m-d', 'custom error message')</code>
                </li>
                <li>
                  Email: <code>->email('custom error message')</code>
                </li>
                <li>
                  URL: <code>->url('custom error message')</code>
                </li>
                <li>
                  REGEX: <code>->regex(/^hello/', 'custom error message')</code>
                </li>
                <li>
                  Equal: <code>->equal('FieldNameOfFirstField', 'custom error message')</code>
                </li>
                <li>
                  OneOf: <code>->oneOf('blue:red:green', 'custom error message')</code>
                </li>
                <li>
                  Text: <code>->text('custom error message')</code> (only allows A-Z or a-Z)
                </li>
                <li>
                  Alpha: <code>->alpha('custom error message')</code> (alpha numeric)
                </li>
                <li>
                  Max Size: <code>->maxSize(5, 'custom error message')</code>
                </li>
                <li>
                  Min Size: <code>->minSize(6, 'custom error message')</code>
                </li>
                <li>
                  Number Max: <code>->numberMax(300, 'custom error message')</code>
                </li>
                <li>
                  Number Min: <code>->numberMin(50, 'custom error message')</code>
                </li>
                <li>
                  Number Float: <code>->numberFloat('custom error message')</code>
                </li>
                <li>
                  Number Interger: <code>->numberInteger('custom error message')</code>
                </li>
              </ul>
            </p>
          </div>
          <div class="col-md-6">
            <p>
              FILES DATA
              <ul>
                <li>
                  File Size: <code>->fileSize(500000, 'custom error message')</code>
                </li>
                <li>
                  File Type:  <code>->fileType(array('image/jpeg', 'image/gif'), 'custom error message')</code>
                </li>
              </ul>
              The Class has access to the following $_FILES data:
              <ul>
                <li>
                  <code>$this->currentFileObject->fileName</code>
                </li>
                <li>
                  <code>$this->currentFileObject->type</code>
                </li>
                <li>
                  <code>$this->currentFileObject->tmpName</code>
                </li>
                <li>
                  <code>$this->currentFileObject->type</code>
                </li>
                <li>
                  <code>$this->currentFileObject->uploadError</code>
                </li>
                <li>
                  <code>$this->currentFileObject->size</code>
                </li>
              </ul>
            </p>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-12 text-secondary">
        <hr />
        <h4>
          <b>How to create new validation rules for $_POST data:</b>
        </h4>
        <p>
          <ol>
            <li>
              Define the default error message.<br />
              <pre>
                <code>
private static $error_myValidaton = 'my default error message';
                </code>
              </pre>

            </li>
            <li>
              Create a new validation function.<br />
              <pre>
                <code>
function myValidation($param , $errorMsg=NULL)
{
  if ($this->isValid && (! empty($this->currentObject->value))) {

    //
    //Add your code to check if validation passes. You can use $this->currentObject->value to get the field value
    //

    $this->isValid = // TRUE or FALSE ;

    if (! $this->isValid) {
      $this->setErrorMsg($errorMsg, self::$error_myValidation, $param);
    }
  }
  return $this;
}
                </code>
              </pre>
            </li>
            <li>
              Use it in your code<br />
              <code>$Valid->name('testing')->myValidation(10, 'some error msg!');</code>
            </li>
          </ol>
        </p>
      </div>
    </div>
    <div class="row">
      <div class="col-12 text-secondary">
        <hr />
        <h4>
          <b>How to create new validation rules for $_FILES data:</b>
        </h4>
        <p>
          <ol>
            <li>
              Define the default error message.<br />
              <pre>
                <code>
private static $error_myValidaton = 'my default error message';
                </code>
              </pre>

            </li>
            <li>
              Create a new validation function.<br />
              <pre>
                <code>
function myValidation($param , $errorMsg=NULL)
{
  if ($this->isValid && (! empty($this->currentFileObject->value))) {

    //
    //Add your code to check if validation passes. You can use $this->currentFileObject->value to get the field value
    //

    $this->isValid = // TRUE or FALSE ;

    if (! $this->isValid) {
      $this->setFileErrorMsg($errorMsg, self::$error_myValidation, $param);
    }
  }
  return $this;
}
                </code>
              </pre>
            </li>
            <li>
              Use it in your code<br />
              <code>$Valid->name('testing')->myValidation(10, 'some error msg!');</code>
            </li>
          </ol>
        </p>
      </div>
    </div>
    <hr />
    <div class="row mb-5">

    </div>
  </div>



  <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>
