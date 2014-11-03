<?php

include 'lib/simple_html_dom.php';
class Moonbasa {

  public function __construct($options) {
  }

  public function parseUrl($url) {
    if (strpos($url, 'www.moonbasa.com') > 0) {
      $parser = new ParseMoon();
      $retMap = $parser->parse($url);
    } else if (strpos($url, 'item.moonbasa.com') > 0) {
      $parser = new ParseLady();
      $retMap = $parser->parse($url);
    } else if (strpos($url, 'item.moonbasa.com') > 0) {
      $parser = new ParseLady();
      $retMap = $parser->parse($url);
    } else if (strpos($url, 'www.korirl.com') > 0) {
      $url= str_replace( 'www.korirl.com', 'www.alaves.cn', $url );
      $parser = new ParseAlaves();
      $retMap = $parser->parse($url);

    } else if (strpos($url, 'www.rutisher.com') > 0) {
      $parser = new ParseRutisher();
      $retMap = $parser->parse($url);
    } else if (strpos($url, 'www.suorang.com') > 0) {
      $parser = new ParseSuorang();
      $retMap = $parser->parse($url);
    } else if (strpos($url, 'www.alaves.cn') > 0) {
      $parser = new ParseAlaves();
      $retMap = $parser->parse($url);
    } else if (strpos($url, 'www.ing2ing.com') > 0) {
      $parser = new ParseIng2ing();
      $retMap = $parser->parse($url);
    } else if (strpos($url, 'lingerie.moonbasa.com') > 0) {
      $parser = new ParseLingerie();
      $retMap = $parser->parse($url);
    } else if (strpos($url, 'www.monteamor.com') > 0) {
      $parser = new ParseMonteamor();
      $retMap = $parser->parse($url);
    } else {
      return "";
    }
    $retMap['success'] = 1;
    return $retMap;
  }
}

class ParseLady {
  
  var $retMap = array('success' => 0);

  public function __construct() {
  }

  public function parse($url) {
    $dom = file_get_html($url);
    $this->parseCodeAndPrice($dom);    
    $this->parseSizeAndColor($dom);    
    $this->parseProps($dom);    
    $this->parseImg($dom);    
    $this->parseProductImg($dom);    
    $this->parseSizeTable($dom);    
    $this->parseTryTable($dom);    

    return $this->retMap; 
  }

  public function parseCodeAndPrice($dom) {
    // code
    $code = '';
    $infoEle = $dom->find('div.p_info', 0);
    $h2Ele = $infoEle->find('h2', 0);
    if (!empty($h2Ele)) {
      $codeSpanEle = $h2Ele->find('span', 0);
      if (!empty($codeSpanEle)) {
        $code = $codeSpanEle->plaintext;
      }
    }
    $codePos1 = strpos($code, '(');
    if ($codePos1 >= 0) {
      $codePos2 = strpos($code, ')');
      $code = substr($code, $codePos1+1, $codePos2-$codePos1-1);
    }
    $this->retMap['code'] = $code;

    // price
    $price = '';
    $priceEle = $infoEle->find('div.cankao', 0);
    if (empty($priceEle)) {
      $priceEle = $infoEle->find('div.price', 0);
    }
    if (!empty($priceEle)) {
      $price = $priceEle->plaintext;
      $pricePos = strpos($price, '参考价：');
      if ($pricePos !== false) {
        $price = substr($price, $pricePos + strlen('参考价：'));
      }
    }
    $price = trim($price);
    $this->retMap['price'] = $price;


  }

  public function parseSizeAndColor($dom) {
    // size and color
    $sizeValue = $dom->find('input#hdColorSize', 0)->value;
    $sizeValue = preg_replace('/\{([a-zA-Z ]*):/', '{"\1":', $sizeValue);
    $sizeValue = preg_replace('/,([a-zA-Z ]*):/', ',"\1":', $sizeValue);
    $sizeValue = preg_replace('/\{\' /', '{"', $sizeValue);
    $sizeValue = str_replace('&#39;', '"', $sizeValue);
    $styleArrJson = json_decode($sizeValue);
    $sizeObjJson = $styleArrJson[0];
    $colorArrJson = $sizeObjJson->ColorArr;
    $colors = '';
    $sizes = '';
    $sizeList = array();
    foreach($colorArrJson as $colorJson) {
      if (!empty($colors)) {
        $colors = $colors . ' ';
      }
      $colors =  $colors . $colorJson->ColorName;
      $sizeArrJson = $colorJson->SpecArr;
      foreach($sizeArrJson as $sizeJson) {
        $size = $sizeJson->Spec;
        
        if (!in_array($size, $sizeList)) {
          if (!empty($sizes)) {
            $sizes = $sizes . ' ';
          }
          $sizes = $sizes . $size;
          $sizeList[] = $size;
        }
      }
      
    }
    $this->retMap['colors'] = $colors; 
    $this->retMap['sizes'] = $sizes;
  }

  public function parseProps($dom) {
    // props and desc
    $propList = array();
    $desc = '';
    $proPropEle = array();
    $foundDesc = false;

    $proInfoEles = $dom->find('div.pro_info');
    foreach($proInfoEles as $proInfoEle) {
      $infoStr = $proInfoEle->children[0]->plaintext;
      if (strpos($infoStr, '商品属性') !== false) {
        $proPropEle = $proInfoEle;
        $propEles = $proInfoEle->find('td.fenl');
        foreach($propEles as $propEle) {
          $propInfo = array();
          $propInfo[] = trim(str_replace('：', '', $propEle->children[0]->plaintext));
          $propInfo[] = trim($propEle->children[1]->plaintext);
          $propList[] = $propInfo;
        }
      } else if (strpos($infoStr, '商品描述') !== false) {
        $foundDesc = true;
        $descEles = $proInfoEle->find('p');
        foreach($descEles as $descEle) {
          $desc = $desc . $descEle->innertext . '<br>';
        }

      }
    }

    if (!$foundDesc && !empty($proPropEle)) {
      $infoStr = $proPropEle->nextSibling()->children[0]->plaintext;
      if (strpos($infoStr, '商品描述') !== false) {
        $descEles = $proInfoEle->nextSibling()->find('p');
        foreach($descEles as $descEle) {
          $desc = $desc . $descEle->innertext . '<br>';
        }
      }
    }
 
    $this->retMap['props'] = $propList;
    $this->retMap['desc'] = $desc;
  }

  public function parseImg($dom) {
    // img
    $img = '';
      $imgEle = $dom->find('#largeimg',0);
//    $imgEle = $dom->find('img#bigimg', 0);
    if (!empty($imgEle)) {
      $img = $imgEle->attr['src'];
    }
    $this->retMap['img'] = $img;

  }


  public function parseProductImg($dom) {
    $productImagesEle = array();
    $detailImagesEle = array();

//    $detailEle = $dom->find('div.ProductDetails', 0);
//    if (!empty($detailEle)) {
//      $productImagesEle = $detailEle->nextSibling();
//      $detailImagesEle = $detailEle->previousSibling();
//    } else {
//      $detailEle = $dom->find('div#contentbox1', 0)->find('div.add_ad1', 0);
//      $detailImagesEle = $detailEle->nextSibling()->nextSibling();
//      $productImagesEle = $detailImagesEle->nextSibling();
//    }
      $detailImagesEle = $dom->find('div.sunProject',0);
      $productImagesEle = $dom->find('div.detail_destrct',0);

      $productImageList = array();
    if (!empty($productImagesEle)) {
      $productImageEles = $productImagesEle->find('img');
      foreach($productImageEles as $imageEle) {
//        $productUrl = $imageEle->attr['data-original'];
          $productUrl = $imageEle->attr['src'];
          if(strpos($productUrl, 'global/grey.gif')==true)
            $productUrl = $imageEle->attr['data-original'];
        if (!empty($productUrl)) {
          $productImageList[] = $productUrl; 
        }
      }
    }
    $this->retMap['productImages'] = $productImageList;

    $detailImageList = array();
    if (!empty($detailImagesEle)) {
      $detailImageEles = $detailImagesEle->find('img');
      foreach($detailImageEles as $imageEle) {
        if ($imageEle->attr['width'] < 700) {
          continue;
        }
        $imageInfo = array();
          if(strpos($imageEle->attr['data-original'], 'global/grey.gif')==true)
              $imageInfo[] = $imageEle->attr['src'];
          else
            $imageInfo[] = $imageEle->attr['data-original'];
        $imageInfo[] = $imageEle->attr['height'];
        $detailImageList[] = $imageInfo;
        
      }
    }
    $this->retMap['detailImages'] = $detailImageList;
  }


  public function parseSizeTable($dom) {
    // sizeTable
    $sizeTable = array();
    $sizeHeaderList = array();
    $sizeWidth1 = 100;
    $sizeWidth2 = 0;

    $dataTableEle = array();
    $h3Eles = $dom->find('h3');
    foreach($h3Eles as $h3Ele) {
      $subTitle = $h3Ele->plaintext;
      if (strpos($subTitle, '尺码表') !== false) {
        $dataTableEle = $h3Ele->nextSibling();
        break;
      }
    }

    if (empty($dataTableEle)) {
      $sizeThEle = $dom->find('th[name=Size]', 0);
      if (!empty($sizeThEle)) {
        $dataTableEle = $sizeThEle->parent()->parent()->parent();
      }
    }
    if (!empty($dataTableEle)) {
      $rowIndex = 0;
      $trEles = $dataTableEle->find('tr');
      foreach($trEles as $trEle) {
        if ($rowIndex === 0) {
          $valueEles = $trEle->find('th');
          $valuesLength = count($valueEles);
          if ($valuesLength > 1) {
            $sizeWidth2 = (int) (80 / ($valuesLength - 1));
            $sizeWidth1 = 100 - $sizeWidth2 * ($valuesLength - 1);
          }
          foreach($valueEles as $valueEle) {
            $sizeHeaderList[] = $valueEle->plaintext;
          }
        } else {
          $valueEles = $trEle->find('td');
          $valueList = array();
          foreach($valueEles as $valueEle) {
            $valueList[] = $valueEle->plaintext;
          }
          $sizeTable[] = $valueList;
        }
        $rowIndex++;
      }
      
    }
    $this->retMap['sizeTable'] = $sizeTable;
    $this->retMap['sizeHeader'] = $sizeHeaderList;
    $this->retMap['sizeWidth1'] = $sizeWidth1;
    $this->retMap['sizeWidth2'] = $sizeWidth2;
  }

  public function parseTryTable($dom) {

    $tryTable = array();
    $tryHeaderList = array();
    $tryWidth1 = 100;
    $tryWidth2 = 0;

    $rowIndex = 0;
    $reportEle = $dom->find('div.ReportHeader', 0);
    $dataTableEle = array();
    if (!empty($reportEle)) {
      $dataTableEle = $reportEle->nextSibling()->find('table', 0);
    }
    if (empty($dataTableEle)) {
      $thEle = $dom->find('th[name=TryMan]', 0);
      if (!empty($thEle)) {
        $dataTableEle = $thEle->parent()->parent()->parent();
      }
    }

    if (!empty($dataTableEle)) {
      foreach ($dataTableEle->find('tr') as $trEle) {
        if ($rowIndex == 0) {
          $valueEles = $trEle->find('th');
          if (count($valueEles) > 1) {
            $tryWidth2 = (int) (90 / (count($valueEles) - 1));
	    $tryWidth1 = 100 - $tryWidth2 * (count($valueEles) - 1);
          }
          foreach($valueEles as $valueEle) {
            $tryHeaderList[] = $valueEle->plaintext;
          }
        } else {
          $valueEles = $trEle->find('td');
          $valueList = array();
          foreach($valueEles as $valueEle) {
            $valueList[] = $valueEle->plaintext;
          }
          $tryTable[] = $valueList;
        }
        $rowIndex++;
      }
    }
    
    $this->retMap['tryTable'] = $tryTable;
    $this->retMap['tryHeader'] = $tryHeaderList;
    $this->retMap['tryWidth1'] = $tryWidth1;
    $this->retMap['tryWidth2'] = $tryWidth2;

  }

}

class ParseLingerie extends ParseLady {
  public function parseProductImg($dom) {
    $productImagesEle = array();
    $detailImagesEle = array();

    $detailEle = $dom->find('div.ProductDetails', 0);
    if (!empty($detailEle)) {
      $productImagesEle = $detailEle->nextSibling();
      $detailImagesEle = $detailEle->previousSibling();      
    } else {
      $detailEle = $dom->find('div#contentbox1', 0);
      //$productImagesEle = $detailEle->children[4];
      //$detailImagesEle = $detailEle->children[3];      
    }

    $productImageList = array();
    $detailImageList = array();
    if (!empty($detailEle)) {
      $productImageEles = $detailEle->find('img');
      foreach($productImageEles as $imageEle) {
        $imgUrl = $imageEle->attr['data-original']; 
        if (strpos($imgUrl, 'ProductImg') > 0) {
          $productImageList[] = $imgUrl;
        } else {
          if ($imageEle->attr['width'] < 765) {
            continue;
          }
          $imageInfo = array();
          $imageInfo[] = $imageEle->attr['data-original'];
          $imageInfo[] = $imageEle->attr['height'];
          $detailImageList[] = $imageInfo;
        }
      }
    }
    $this->retMap['productImages'] = $productImageList;
    $this->retMap['detailImages'] = $detailImageList;
  }

  public function parse($url) {
    $dom = file_get_html($url);
    $this->parseCodeAndPrice($dom);    
    $this->parseSizeAndColor($dom);    
    $this->parseProps($dom);    
    $this->parseImg($dom);    
    $this->parseProductImg($dom);    
    $this->parseSizeTable($dom);    
    $this->parseTryTable($dom);    

    return $this->retMap; 
  }
}

class ParseKorirl extends ParseLady {
  public function parse($url) {
    $dom = file_get_html($url);
    $this->parseCodeAndPrice($dom);   
    $this->parseSizeAndColor($dom);    
    $this->parseProps($dom);    
    $this->parseImg($dom);    
    $this->parseProductImg($dom);    
    $this->parseSizeTable($dom);    
    $this->parseTryTable($dom);    

    return $this->retMap; 
  }

  public function parseCodeAndPrice($dom) {
    // code
    $code = '';
    $infoEle = $dom->find('div.p_info', 0);
    $h2Ele = $infoEle->find('div.tjzs', 0);
    if (!empty($h2Ele)) {
      $codeSpanEle = $h2Ele->find('span', 0);
      if (!empty($codeSpanEle)) {
        $code = $codeSpanEle->plaintext;
      }
    }
    $codePos1 = strpos($code, '(');
    if ($codePos1 >= 0) {
      $codePos2 = strpos($code, ')');
      $code = substr($code, $codePos1+1, $codePos2-$codePos1-1);
    }
    $this->retMap['code'] = $code;

    // price
    $price = '';
    $priceEle = $infoEle->find('div.cankao', 0);
    if (empty($priceEle)) {
      $priceEle = $infoEle->find('div.price', 0);
    }
    if (!empty($priceEle)) {
      $price = $priceEle->plaintext;
      $pricePos = strpos($price, '参考价：');
      if ($pricePos !== false) {
        $price = substr($price, $pricePos + strlen('参考价：'));
      }
    }
    $price = trim($price);
    $this->retMap['price'] = $price;


  }

  public function parseProps($dom) {
    // props and desc
    $propList = array();
    $desc = '';
    $proPropEle = array();
    $foundDesc = false;

    /*
    $proEles = $dom->find('div.xinxi_mid', 0)->find('a');
    foreach($propEles as $propEle) {
          $propInfo = array();
          $propInfo[] = $propEle->attr('title');
          $propInfo[] = trim($propEle->plaintext);
          $propList[] = $propInfo;
    }
    */
    $propEles = $dom->find('div.xinxi_mid', 0)->find('td');
    if (count($propEles) == 0) {
      $propEles = $dom->find('div.xinxi_mid', 0)->find('li');
    }
    foreach($propEles as $propEle) {
     // $propInfo = array();
     // $propInfo[] = trim(str_replace('：', '', $propEle->children[0]->plaintext));
     // $propInfo[] = trim($propEle->children[1]->plaintext);
     // $propList[] = $propInfo;
      $infoStr = $propEle->plaintext;
      if (strpos($infoStr, '：') !== false) {
        $propInfo = explode('：', $infoStr);
        $propList[] = $propInfo;
      }
    } 
   
    $descInfoEle = $dom->find('div.wzbox', 0); 
    $descEles = $descInfoEle->find('p');
    foreach($descEles as $descEle) {
      $desc = $desc . $descEle->innertext . '<br>';
    }

    $this->retMap['props'] = $propList;
    $this->retMap['desc'] = $desc;
  }

	public function parseProductImg($dom) {
    $productImagesEle = array();
    $detailImagesEle = array();

    $detailEle = $dom->find('div.ProductDetails', 0);
    if (!empty($detailEle)) {
      $productImagesEle = $detailEle->nextSibling();
      $detailImagesEle = $detailEle->previousSibling();      
    } else {
      $detailEle = $dom->find('div#contentbox1', 0);
      $productImagesEle = $detailEle->find('div.both', 0);     
      $detailImagesEle = $productImagesEle->previousSibling();
    }

    $productImageList = array();
    if (!empty($productImagesEle)) {
      $productImageEles = $productImagesEle->find('img');
      foreach($productImageEles as $imageEle) {
        $productUrl = $imageEle->attr['data-original'];
        if (!empty($productUrl)) {
          $productImageList[] = $productUrl; 
        }
      }
    }
    $this->retMap['productImages'] = $productImageList;
    

    $detailImageList = array();
    if (!empty($detailImagesEle)) {
      $detailImageEles = $detailImagesEle->find('img');
      foreach($detailImageEles as $imageEle) {
        if ($imageEle->attr['width'] < 765) {
          continue;
        }
        $imageInfo = array();
        $imageInfo[] = $imageEle->attr['data-original'];
        $imageInfo[] = $imageEle->attr['height'];
        $detailImageList[] = $imageInfo;
        
      }
    }
    $this->retMap['detailImages'] = $detailImageList;
  }



  public function parseTryTable($dom) {

    $tryTable = array();
    $tryHeaderList = array();
    $tryWidth1 = 100;
    $tryWidth2 = 0;

    $rowIndex = 0;
    $reportEle = $dom->find('div.SizeReportNew', 0);
    $dataTableEle = $reportEle->find('table', 0);
    if (!empty($dataTableEle)) {
      foreach ($dataTableEle->find('tr') as $trEle) {
        if ($rowIndex == 0) {
          $valueEles = $trEle->find('th');
          if (count($valueEles) > 1) {
            $tryWidth2 = (int) (90 / (count($valueEles) - 1));
	    $tryWidth1 = 100 - $tryWidth2 * (count($valueEles) - 1);
          }
          foreach($valueEles as $valueEle) {
            $tryHeaderList[] = $valueEle->plaintext;
          }
        } else {
          $valueEles = $trEle->find('td');
          $valueList = array();
          foreach($valueEles as $valueEle) {
            $valueList[] = $valueEle->plaintext;
          }
          $tryTable[] = $valueList;
        }
        $rowIndex++;
      }
    }
    
    $this->retMap['tryTable'] = $tryTable;
    $this->retMap['tryHeader'] = $tryHeaderList;
    $this->retMap['tryWidth1'] = $tryWidth1;
    $this->retMap['tryWidth2'] = $tryWidth2;


  }


}

class ParseRutisher extends ParseLady {

  public function parse($url) {
    $dom = file_get_html($url);
    $this->parseCodeAndPrice($dom);  
    $this->parseSizeAndColor($dom);    
    $this->parseProps($dom);    
    $this->parseImg($dom);    
    $this->parseProductImg($dom);    
    $this->parseSizeTable($dom);    
    $this->parseTryTable($dom);    

    return $this->retMap; 
  }

  public function parseCodeAndPrice($dom) {
    // code
    $code = '';
    $infoEle = $dom->find('div.p_info', 0);
    $h2Ele = $infoEle->find('h2', 0);
    if (!empty($h2Ele)) {
      $codeSpanEle = $h2Ele->find('span', 0);
      if (!empty($codeSpanEle)) {
        $code = $codeSpanEle->plaintext;
      }
    }
    $codePos1 = strpos($code, '(');
    if ($codePos1 >= 0) {
      $codePos2 = strpos($code, ')');
      $code = substr($code, $codePos1+1, $codePos2-$codePos1-1);
    }
    $this->retMap['code'] = $code;

    // price
    $price = '';
    $priceEle = $infoEle->find('div.cankao', 0);
    if (empty($priceEle)) {
      $priceEle = $infoEle->find('div.price', 0);
    }
    if (!empty($priceEle)) {
      $price = $priceEle->plaintext;
      $pricePos = strpos($price, '参考价：');
      if ($pricePos !== false) {
        $price = substr($price, $pricePos + strlen('参考价：'));
      }
    }
    $price = trim($price);
    if (strpos($price, ' ') > 0) {
      $price = substr($price, 0, strpos($price, ' ')); 
    }
    $this->retMap['price'] = $price;

  }

  public function parseProps($dom) {
    // props and desc
    $propList = array();
    $desc = '';
    $proPropEle = array();
    $foundDesc = false;

    $proInfoEles = $dom->find('div.pro_info');
    foreach($proInfoEles as $proInfoEle) {
      $infoStr = $proInfoEle->children[0]->plaintext;
      if (strpos($infoStr, '商品属性') !== false) {
        $proPropEle = $proInfoEle;
        $propEles = $proInfoEle->find('td.fenl');
        foreach($propEles as $propEle) {
          $propInfo = array();
          $propInfo[] = trim(str_replace('：', '', $propEle->children[0]->plaintext));
          $propInfo[] = trim($propEle->children[1]->plaintext);
          $propList[] = $propInfo;
        }
      } else if (strpos($infoStr, '商品描述') !== false) {
        $foundDesc = true;
        $descEles = $proInfoEle->find('p');
        foreach($descEles as $descEle) {
          $desc = $desc . $descEle->innertext . '<br>';
        }

      }
    }

    if (!$foundDesc && !empty($proPropEle)) {
      $desc = $proPropEle->nextSibling()->innertext;
      if (strpos($desc, '</h3>') > 0) {
        $desc = substr($desc, strpos($desc, '</h3>') + 5);
      }
    }
 
    $this->retMap['props'] = $propList;
    $this->retMap['desc'] = $desc;
  }

public function parseProductImg($dom) {
    $productImagesEle = array();
    $detailImagesEle = array();

    $detailEle = $dom->find('div.ProductDetails', 0);
    if (!empty($detailEle)) {
      $productImagesEle = $detailEle->nextSibling();
      $detailImagesEle = $detailEle->previousSibling();      
    } else {
      $detailEle = $dom->find('div#contentbox1', 0)->find('table', 0)->parent();
      $detailImagesEle = $detailEle->nextSibling()->nextSibling();     
      $productImagesEle = $detailImagesEle->nextSibling();
    }

    $productImageList = array();
    if (!empty($productImagesEle)) {
      $productImageEles = $productImagesEle->find('img');
      foreach($productImageEles as $imageEle) {
        $productUrl = $imageEle->attr['data-original'];
        if (!empty($productUrl)) {
          $productImageList[] = $productUrl; 
        }
      }
    }
    $this->retMap['productImages'] = $productImageList;

    $detailImageList = array();
    if (!empty($detailImagesEle)) {
      $detailImageEles = $detailImagesEle->find('img');
      foreach($detailImageEles as $imageEle) {
        if ($imageEle->attr['width'] < 765) {
          continue;
        }
        $imageInfo = array();
        $imageInfo[] = $imageEle->attr['data-original'];
        $imageInfo[] = $imageEle->attr['height'];
        $detailImageList[] = $imageInfo;
        
      }
    }
    $this->retMap['detailImages'] = $detailImageList;
  }


  public function parseTryTable($dom) {

    $tryTable = array();
    $tryHeaderList = array();
    $tryWidth1 = 100;
    $tryWidth2 = 0;

    $rowIndex = 0;
    $reportEle = $dom->find('div.ReportHeader', 0);
    $dataTableEle = $reportEle->nextSibling();
    if (!empty($dataTableEle)) {
      foreach ($dataTableEle->find('tr') as $trEle) {
        if ($rowIndex == 0) {
          $valueEles = $trEle->find('th');
          if (count($valueEles) > 1) {
            $tryWidth2 = (int) (90 / (count($valueEles) - 1));
	    $tryWidth1 = 100 - $tryWidth2 * (count($valueEles) - 1);
          }
          foreach($valueEles as $valueEle) {
            $tryHeaderList[] = $valueEle->plaintext;
          }
        } else {
          $valueEles = $trEle->find('td');
          $valueList = array();
          foreach($valueEles as $valueEle) {
            $valueList[] = $valueEle->plaintext;
          }
          $tryTable[] = $valueList;
        }
        $rowIndex++;
      }
    }
    $this->retMap['tryTable'] = $tryTable;
    $this->retMap['tryHeader'] = $tryHeaderList;
    $this->retMap['tryWidth1'] = $tryWidth1;
    $this->retMap['tryWidth2'] = $tryWidth2;

  }


}

class ParseSuorang extends ParseLady {
  public function parse($url) {
    $dom = file_get_html($url);
    $this->parseCodeAndPrice($dom);    
    $this->parseSizeAndColor($dom);    
    $this->parseProps($dom);    
    $this->parseImg($dom);    
    $this->parseProductImg($dom);    
    $this->parseSizeTable($dom);    
    $this->parseTryTable($dom);    

    return $this->retMap; 
  }


  public function parseProps($dom) {
    // props and desc
    $propList = array();
    $desc = '';
    $proPropEle = array();
    $foundDesc = false;

    $tableEle = $dom->find('div#contentbox1', 0)->find('table', 0);
    foreach($tableEle->find('td') as $proInfoEle) {
      $infoStr = $proInfoEle->plaintext;
      if (strpos($infoStr, '：') !== false) {
        //$propInfo = array();
        //$propInfo[] = trim(substr($infoStr, 0, strpos($infoStr, '：')));
        //$propInfo[] = trim(substr($infoStr, strpos($infoStr, '：')));
        $propInfo = explode('：', $infoStr);
        $propList[] = $propInfo;
      }
    }

    $desc = $tableEle->parent()->nextSibling()->innertext;
    if (strpos($desc, '</span>') > 0) {
      $desc = substr($desc, strpos($desc, '</span>') + 7);
    }

    $this->retMap['props'] = $propList;
    $this->retMap['desc'] = $desc;
  }

  public function parseProductImg($dom) {
    $productImagesEle = array();
    $detailImagesEle = array();

    $detailEle = $dom->find('div.ProductDetails', 0);
    if (!empty($detailEle)) {
      $productImagesEle = $detailEle->nextSibling();
      $detailImagesEle = $detailEle->previousSibling();      
    } else {
      $detailEle = $dom->find('div#contentbox1', 0)->find('table', 0)->parent();
      //$detailEle = $dom->find('div#contentbox1', 0)->find('table', 0);
      $productImagesEle= $detailEle->nextSibling()->nextSibling();    
      $detailImagesEle = $productImagesEle->nextSibling();
    }

    $productImageList = array();
    if (!empty($productImagesEle)) {
      $productImageEles = $productImagesEle->find('img');
      foreach($productImageEles as $imageEle) {
        $productUrl = $imageEle->attr['data-original'];
        if (!empty($productUrl)) {
          $productImageList[] = $productUrl; 
        }
      }
    }
    $this->retMap['detailImages'] = $productImageList;

    $detailImageList = array();
    if (!empty($detailImagesEle)) {
      $detailImageEles = $detailImagesEle->find('img');
      foreach($detailImageEles as $imageEle) {
        if ($imageEle->attr['width'] < 765) {
          continue;
        }
        $imageInfo = array();
        $imageInfo[] = $imageEle->attr['data-original'];
        $imageInfo[] = $imageEle->attr['height'];
        $detailImageList[] = $imageInfo;
        
      }
    }
    $this->retMap['productImages'] = $detailImageList;
  }


  public function parseTryTable($dom) {

    $tryTable = array();
    $tryHeaderList = array();
    $tryWidth1 = 100;
    $tryWidth2 = 0;

    $reportEle = $dom->find("div.ReportHeader", 0);
    $dataTableEle = array();
    if (!empty($reportEle)) {
      $dataTableEle = $reportEle->nextSibling()->find("table", 0);
    }
    if (empty($dataTableEle)) {
      $thEle = $dom->find('th[name=TryMan]', 0);
      if (!empty($thEle)) {
        $dataTableEle = $thEle->parent()->parent()->parent();
      }
    }
    if (!empty($dataTableEle)) {
      foreach ($dataTableEle->find("tr") as $trEle) {
        if ($rowIndex == 0) {
          $valueEles = $trEle->find("th");
          if (count($valueEles) > 1) {
            $tryWidth2 = (int) (90 / (count($valueEles) - 1));
	    $tryWidth1 = 100 - $tryWidth2 * (count($valueEles) - 1);
          }
          foreach($valueEles as $valueEle) {
            $tryHeaderList[] = $valueEle->plaintext;
          }
        } else {
          $valueEles = $trEle->find("td");
          $valueList = array();
          foreach($valueEles as $valueEle) {
            $valueList[] = $valueEle->plaintext;
          }
          $tryTable[] = $valueList;
        }
        $rowIndex++;
      }
    }

    $this->retMap['tryTable'] = $tryTable;
    $this->retMap['tryHeader'] = $tryHeaderList;
    $this->retMap['tryWidth1'] = $tryWidth1;
    $this->retMap['tryWidth2'] = $tryWidth2;

  } 
}

class ParseAlaves extends ParseLady {
  public function parse($url) {
    $dom = file_get_html($url);
    $this->parseCodeAndPrice($dom);    
    $this->parseSizeAndColor($dom);    
    $this->parseProps($dom);    
    $this->parseImg($dom);    
    $this->parseProductImg($dom);    
    $this->parseSizeTable($dom);    
    $this->parseTryTable($dom);    

    return $this->retMap; 
  }

  public function parseCodeAndPrice($dom) {
    // code
    $code = '';
    $infoEle = $dom->find('div.p_info', 0);
    $h2Ele = $infoEle->find('div.tjzs', 0);
    if (!empty($h2Ele)) {
      $codeSpanEle = $h2Ele->find('span', 0);
      if (!empty($codeSpanEle)) {
        $code = $codeSpanEle->plaintext;
      }
    }
    $codePos1 = strpos($code, '(');
    if ($codePos1 >= 0) {
      $codePos2 = strpos($code, ')');
      $code = substr($code, $codePos1+1, $codePos2-$codePos1-1);
    }
    $this->retMap['code'] = $code;

    // price
    $price = '';
    $priceEle = $infoEle->find('div.cankao', 0);
    if (empty($priceEle)) {
      $priceEle = $infoEle->find('div.price', 0);
    }
    if (!empty($priceEle)) {
      $price = $priceEle->plaintext;
      $pricePos = strpos($price, '参考价：');
      if ($pricePos !== false) {
        $price = substr($price, $pricePos + strlen('参考价：'));
      }
    }
    $price = trim($price);
    $this->retMap['price'] = $price;


  }

  public function parseProductImg($dom) {
    $productImagesEle = array();
    $detailImagesEle = array();

    $detailEle = $dom->find('div.ProductDetails', 0);
    if (!empty($detailEle)) {
      $productImagesEle = $detailEle->nextSibling();
      $detailImagesEle = $detailEle->previousSibling();      
    } else {
      $detailEle = $dom->find('div#contentbox1', 0);
      $productImagesEle = $detailEle->find('div#divProBigImages', 0);     
      $detailImagesEle = $productImagesEle->previousSibling();
    }

    $productImageList = array();
    if (!empty($productImagesEle)) {
      $productImageEles = $productImagesEle->find('img');
      foreach($productImageEles as $imageEle) {
        $productUrl = $imageEle->attr['src'];
        if (!empty($productUrl)) {
          $productImageList[] = $productUrl; 
        }
      }
    }
    $this->retMap['productImages'] = $productImageList;

    $detailImageList = array();
    if (!empty($detailImagesEle)) {
      $detailImageEles = $detailImagesEle->find('img');
      foreach($detailImageEles as $imageEle) {
        if ($imageEle->attr['width'] < 765) {
          continue;
        }
        $imageInfo = array();
        $imageInfo[] = $imageEle->attr['data-original'];
        $imageInfo[] = $imageEle->attr['height'];
        $detailImageList[] = $imageInfo;
        
      }
    }
    $this->retMap['detailImages'] = $detailImageList;
  }

  public function parseProps($dom) {
    // props and desc
    $propList = array();
    $desc = '';
    $proPropEle = array();
    $foundDesc = false;

    $proInfoEles = $dom->find('div.pro_info');
    foreach($proInfoEles as $proInfoEle) {
      $infoStr = $proInfoEle->children[0]->plaintext;
      if (strpos($infoStr, '商品属性') !== false) {
        $proPropEle = $proInfoEle;
        $propEles = $proInfoEle->find('td.fenl');
        foreach($propEles as $propEle) {
          $propInfo = array();
          $propInfo[] = trim(str_replace('：', '', $propEle->children[0]->plaintext));
          $propInfo[] = trim($propEle->children[1]->plaintext);
          $propList[] = $propInfo;
        }
      } else if (strpos($infoStr, '商品描述') !== false) {
        $foundDesc = true;
        $descEles = $proInfoEle->find('p');
        foreach($descEles as $descEle) {
          $desc = $desc . $descEle->innertext . '<br>';
        }

      }
    }

    if (!$foundDesc && !empty($proPropEle)) {
      $infoStr = $proPropEle->nextSibling()->innertext;
      if (strpos($infoStr, '</h3>') !== false) {
          $desc = substr($infoStr, strpos($infoStr, '</h3>')+5); 
      }
    }
 
    $this->retMap['props'] = $propList;
    $this->retMap['desc'] = $desc;
  }

 
  public function parseTryTable($dom) {

    $tryTable = array();
    $tryHeaderList = array();
    $tryWidth1 = 100;
    $tryWidth2 = 0;
    $rowIndex = 0;

    $reportEle = $dom->find('div.SizeReportNew', 0);
    $dataTableEle = $reportEle->find('table', 0);
    if (!empty($dataTableEle)) {
      foreach ($dataTableEle->find('tr') as $trEle) {
        if ($rowIndex == 0) {
          $valueEles = $trEle->find('th');
          if (count($valueEles) > 1) {
            $tryWidth2 = (int) (90 / (count($valueEles) - 1));
	    $tryWidth1 = 100 - $tryWidth2 * (count($valueEles) - 1);
          }
          foreach($valueEles as $valueEle) {
            $tryHeaderList[] = $valueEle->plaintext;
          }
        } else {
          $valueEles = $trEle->find('td');
          $valueList = array();
          foreach($valueEles as $valueEle) {
            $valueList[] = $valueEle->plaintext;
          }
          $tryTable[] = $valueList;
        }
        $rowIndex++;
      }
    }
    
    $this->retMap['tryTable'] = $tryTable;
    $this->retMap['tryHeader'] = $tryHeaderList;
    $this->retMap['tryWidth1'] = $tryWidth1;
    $this->retMap['tryWidth2'] = $tryWidth2;


  }
}

class ParseIng2ing extends ParseLady {
  public function parse($url) {
    $dom = file_get_html($url);
    $this->parseCodeAndPrice($dom);    
    $this->parseSizeAndColor($dom);    
    $this->parseProps($dom);    
    $this->parseImg($dom);    
    $this->parseProductImg($dom);    
    $this->parseSizeTable($dom);    
    $this->parseTryTable($dom);    

    return $this->retMap; 
  }

  public function parseCodeAndPrice($dom) {
    // code
    $code = '';
    $infoEle = $dom->find('div.p_info', 0);
    $h2Ele = $infoEle->find('div.tjzs', 0);
    if (!empty($h2Ele)) {
      $codeSpanEle = $h2Ele->find('span', 0);
      if (!empty($codeSpanEle)) {
        $code = $codeSpanEle->plaintext;
      }
    }
    $codePos1 = strpos($code, '(');
    if ($codePos1 >= 0) {
      $codePos2 = strpos($code, ')');
      $code = substr($code, $codePos1+1, $codePos2-$codePos1-1);
    }
    $this->retMap['code'] = $code;

    // price
    $price = '';
    $priceEle = $infoEle->find('div.cankao', 0);
    if (empty($priceEle)) {
      $priceEle = $infoEle->find('div.price', 0);
    }
    if (!empty($priceEle)) {
      $price = $priceEle->plaintext;
      $pricePos = strpos($price, '参考价：');
      if ($pricePos !== false) {
        $price = substr($price, $pricePos + strlen('参考价：'));
      }
    }
    $price = trim($price);
    $this->retMap['price'] = $price;


  }

  public function parseProps($dom) {
    // props and desc
    $propList = array();
    $desc = '';
    $proPropEle = array();
    $foundDesc = false;

    $propEles = $dom->find('div.xinxi', 0)->find('td.fenl');
    foreach($propEles as $propEle) {
      $propInfo = array();
      $propInfo[] = trim(str_replace('：', '', $propEle->children[0]->plaintext));
      $propInfo[] = trim($propEle->children[1]->plaintext);
      $propList[] = $propInfo;
    }
   
    $descInfoEle = $dom->find('div.wzbox', 0); 
    $descEles = $descInfoEle->find('p');
    foreach($descEles as $descEle) {
      $desc = $desc . $descEle->innertext . '<br>';
    }

    $this->retMap['props'] = $propList;
    $this->retMap['desc'] = $desc;
  }

  public function parseProductImg($dom) {
    $productImagesEle = array();
    $detailImagesEle = array();

    $detailEle = $dom->find('div.ProductDetails', 0);
    if (!empty($detailEle)) {
      $productImagesEle = $detailEle->nextSibling();
      $detailImagesEle = $detailEle->previousSibling();      
    } else {
      $detailEle = $dom->find('div#contentbox1', 0)->find('div.add_ad1', 0);
      $detailImagesEle = $detailEle->nextSibling();     
      $productImagesEle = $detailImagesEle->nextSibling();
    }

    $productImageList = array();
    if (!empty($productImagesEle)) {
      $productImageEles = $productImagesEle->find('img');
      foreach($productImageEles as $imageEle) {
        $productUrl = $imageEle->attr['data-original'];
        if (!empty($productUrl)) {
          $productImageList[] = $productUrl; 
        }
      }
    }
    $this->retMap['productImages'] = $productImageList;

    $detailImageList = array();
    if (!empty($detailImagesEle)) {
      $detailImageEles = $detailImagesEle->find('img');
      foreach($detailImageEles as $imageEle) {
        if ($imageEle->attr['width'] < 765) {
          continue;
        }
        $imageInfo = array();
        $imageInfo[] = $imageEle->attr['data-original'];
        $imageInfo[] = $imageEle->attr['height'];
        $detailImageList[] = $imageInfo;
        
      }
    }
    $this->retMap['detailImages'] = $detailImageList;
  }

}

class ParseMonteamor extends ParseLady {
  public function parse($url) {
    $dom = file_get_html($url);
    $this->parseCodeAndPrice($dom);    
    $this->parseSizeAndColor($dom);    
    $this->parseProps($dom);    
    $this->parseImg($dom);    
    $this->parseProductImg($dom);    
    $this->parseSizeTable($dom);    
    $this->parseTryTable($dom);    

    return $this->retMap; 
  }

  
  public function parseCodeAndPrice($dom) {
    // code
    $code = '';
    $infoEle = $dom->find('div.p_info', 0);
    $h2Ele = $infoEle->find('h2', 0);
    if (!empty($h2Ele)) {
      $codeSpanEle = $h2Ele->find('span', 0);
      if (!empty($codeSpanEle)) {
        $code = $codeSpanEle->plaintext;
      }
    }
    $codePos1 = strpos($code, '(');
    if ($codePos1 >= 0) {
      $codePos2 = strpos($code, ')');
      $code = substr($code, $codePos1+1, $codePos2-$codePos1-1);
    }
    $this->retMap['code'] = $code;

    // price
    $price = '';
    $priceEle = $infoEle->find('div.cankao', 0);
    if (empty($priceEle)) {
      $priceEle = $infoEle->find('div.price', 0);
    }
    if (!empty($priceEle)) {
      $price = $priceEle->plaintext;
      $pricePos = strpos($price, '参考价：');
      if ($pricePos !== false) {
        $price = substr($price, $pricePos + strlen('参考价：'));
      }
    }
    $price = trim($price);
    $this->retMap['price'] = $price;


  }

  public function parseProps($dom) {
    // props and desc
    $propList = array();
    $desc = '';
    $proPropEle = array();
    $foundDesc = false;

    $proInfoEles = $dom->find('div.pro_info');
    foreach($proInfoEles as $proInfoEle) {
      $infoStr = $proInfoEle->children[0]->plaintext;
      if (strpos($infoStr, '商品属性') !== false) {
        $proPropEle = $proInfoEle;
        $propEles = $proInfoEle->find('td.fenl');
        foreach($propEles as $propEle) {
          $propInfo = array();
          $propInfo[] = trim(str_replace('：', '', $propEle->children[0]->plaintext));
          $propInfo[] = trim($propEle->children[1]->plaintext);
          $propList[] = $propInfo;
        }
      } else if (strpos($infoStr, '商品描述') !== false) {
        $foundDesc = true;
        $descEles = $proInfoEle->find('p');
        foreach($descEles as $descEle) {
          $desc = $desc . $descEle->innertext . '<br>';
        }

      }
    }

    if (!$foundDesc && !empty($proPropEle)) {
      $infoStr = $proPropEle->nextSibling()->children[0]->plaintext;
      if (strpos($infoStr, '商品描述') !== false) {
        $descEle = $proInfoEle->nextSibling();
        while(true) {
          $desc = $desc . $descEle->innertext . '<br>';
          $descEle = $descEle->nextSibling();
          if (empty($descEle) || $descEle->tag != 'p') {
            break;
          }
        }
      }
    }
 
    $this->retMap['props'] = $propList;
    $this->retMap['desc'] = $desc;
  }


} 

class ParseMoon extends ParseLady {
  public function parse($url) {
    $dom = file_get_html($url);
    $this->parseCodeAndPrice($dom);    
    $this->parseSizeAndColor($dom);    
    $this->parseProps($dom);    
    $this->parseImg($dom);    
    $this->parseProductImg($dom);    
    $this->parseSizeTable($dom);    
    $this->parseTryTable($dom);    

    return $this->retMap; 
  }


  public function parseProps($dom) {
    // props and desc
    $propList = array();
    $desc = '';
    $proPropEle = array();
    $foundDesc = false;

    $proInfoEles = $dom->find('div.pro_info');
    foreach($proInfoEles as $proInfoEle) {
      $infoStr = $proInfoEle->children[0]->plaintext;
      if (strpos($infoStr, '商品属性') !== false) {
        $proPropEle = $proInfoEle;
        $propEles = $proInfoEle->find('td');
        foreach($propEles as $propEle) {
          $infoStr = $propEle->plaintext;
          if (strpos($infoStr, '：') !== false) {
            $propInfo = explode('：', $infoStr);
            $propList[] = $propInfo;
          }
        }
      } else if (strpos($infoStr, '商品描述') !== false) {
        $foundDesc = true;
        $descEles = $proInfoEle->find('p');
        foreach($descEles as $descEle) {
          $desc = $desc . $descEle->innertext . '<br>';
        }

      }
    }

    if (!$foundDesc && !empty($proPropEle)) {
      $infoStr = $proPropEle->nextSibling()->children[0]->plaintext;
      if (strpos($infoStr, '商品描述') !== false) {
        $descEle = $proInfoEle->nextSibling();
        while(true) {
          $desc = $desc . $descEle->innertext . '<br>';
          $descEle = $descEle->nextSibling();
          if (empty($descEle) || $descEle->tag != 'p') {
            break;
          }
        }
      }
    }
 
    $this->retMap['props'] = $propList;
    $this->retMap['desc'] = $desc;
  }

}

?>
