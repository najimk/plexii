# PDF Preview Image

PDF Preview Image module provides an image preview from a pdf file and save it on a field image type.

# Requirements

  * File field, Image field
  * Package 'Convert a pdf to an image' (spatie/pdf-to-image)
  * Imagick and Ghostscript installed on server.

# Install


## 3.x Branch

The 3.x version of package 'Convert a pdf to an image' is included automatically when installing the module with composer:

## 2.x Branch

The 2.x branch requires explicitly installing the 2.x version of package 'Convert a pdf to an image' via 
  ```
  $ composer require 'spatie/pdf-to-image:^2.2' 'drupal/pdf_preview_image:^2.1'
  ```

Make sure to require the module and the package at the same time since [a composer require of vendor code does not update Drupal's autoloader](https://www.drupal.org/project/drupal/issues/3230708).

# Using the module

  * Go on a content type, create a file type field and set up on "Allowed file extensions" the pdf extension.
  * On the bottom of file field settings will appear "PDF preview auto-generation"; Check the box.
  * There is an option to set up the image field where should be stored image from PDF.
  * If there isn't an image field created in content type, go ahead create one, and then set up on "PDF Preview Auto-generation".

# Contributors

  * Ionut Stan (https://drupal.org/u/ionut.stan)
  * Alexandru Tulbure (https://drupal.org/u/alex_tulbure)
