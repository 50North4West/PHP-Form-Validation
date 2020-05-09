Validation Class
======
A simple, flexible and easy to use PHP form validation class using fluent methodologies. Fluent design allows you to chain method calls, which results in less typed
characters when applying multiple operations on the same object. The validator has been forked from the original written by Andre Soares and found at
<a href="https://github.com/ASoares/PHP-Form-Validation">https://github.com/ASoares/PHP-Form-Validation</a>. This updated version is maintained by 50North 4West and can be
found at <a href="https://github.com/50North4West/PHP-Form-Validation">https://github.com/50North4West/PHP-Form-Validation</a>. This new version follows the PSR-2 Coding Standard,
uses PHP's native FILTER_VALIDATE_XXXX wherever possible and validates file uploads.


**Note:** index.php  has a examples and instructions for use.<br>


**License:**

GPL v2 http://www.gnu.org/licenses/gpl-2.0.txt

**Change Log:**

* 1.0:  Updated initial version

* 1.1:  Change to the Alpha & Text only validations - removed regex's and changed to PHP native functions ctype_alnum & ctype_alpha.

* 1.2:  Addition of Time validation using ->time('H:i:s', 'custom error message'). Default format is 'H:i:s' if none specified first variable to the format you want to validate against.

* 1.3:  Fix to File Type & Size checks to allow for the use of multiple files uploaded on the form.
