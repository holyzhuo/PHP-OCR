#### 利用php进行ocr识别


#####Installation
* [Tesseract OCR](https://github.com/tesseract-ocr/tesseract) > 3.02
* composer require thiagoalessio/tesseract_ocr


#### Usage
<img align="right" width="50%" title="The quick brown fox jumps over the lazy dog." src="./demo-img/demo.png"/>

```php
$ocr = new Ocr($img);
$result = $ocr->getRecognition();
echo $result;
```

```
tesseract ocr"
```

<br/>
