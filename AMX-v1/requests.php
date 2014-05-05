<?php
class requestFilter extends requestVars{ // extend the common class
   function requestFilter($filter){
       $this->source = &$filter; //set the source to filter array
   }
}
class requestCookie extends requestVars{ // extend the common class
   function requestCookie(){
       $this->source = &$_COOKIE; //set the source to Cookie
   }
}
class requestGet extends requestVars{// extend the common class
   function requestGet(){
       $this->source = &$_GET;//set the source to Get
   }
}
class requestPost extends requestVars{// extend the common class
   function requestPost(){
       $this->source = &$_POST;//set the source to Post
   }
}

class requestVars{
   var $source = array();  // a common source container for filter(user defined), GET, POST, COOKIE or REQUEST (default in constructor)
   function requestVars(){
       $this->source = &$_REQUEST; // construct our source as _REQUEST, by default
   }
   function getVarCount($param){
       if (isset($this->source[$param])) {                        // check if parameter is set
           if (is_array($this->source[$param]))                // if its array count the elements
               return count($this->source[$param]);            // and return
           else
               return 0;                                        // return 0 if its just a variable
       }else
           return false;                                        // return false if param not set
   }
   function getVarInt($param, $default = 0, $sub=0){
       if (isset($this->source[$param])) {                        // check if parameter is set
           if (is_array($this->source[$param]))                //if its array get the required sub
               $sourceParam=$this->source[$param][$sub];
           else
               $sourceParam =$this->source[$param];            //otherwise just get the parameter
           return (int)$sourceParam;                            // return the integer of parameter
       }else
           return $default;                                    // return default value if param not set
   }
   function getVarFloat($param, $default = 0, $sub = 0){
       if (isset($this->source[$param])) {                        // check if parameter is set
           if (is_array($this->source[$param]))                //if its array get the required sub
               $sourceParam=$this->source[$param][$sub];
           else
               $sourceParam =$this->source[$param];            //otherwise just get the parameter
           return (float)$sourceParam;                        // return the float of parameter
       }else
           return $default;                                    // return default value if param not set
   }
   function getVarAlpha($param, $max = 0, $default = NULL, $sub = 0){
       if (isset($this->source[$param])) {                        // check if parameter is set
               if (is_array($this->source[$param]))                //if its array get the required sub
               $sourceParam=$this->source[$param][$sub];
           else
               $sourceParam =$this->source[$param];            //otherwise just get the parameter
           preg_match("/^[A-Za-z]+$/",$sourceParam,$arr);        //check strictly there is one alphabetic atleast
           if (!empty($arr))                                    //if you have caught something as alphabetic, return it
               return ($max>0)
               ? substr($arr[0],0,$max)                        //truncate the length
               : $arr[0];
       }
       return $default;                                        // return  default if param not set or its not alphabetic
   }
   function getVarFileName($param, $max = -1, $encode = 1, $default = NULL, $sub = 0){
       if (isset($this->source[$param])) {                        // check if parameter is set
           if (is_array($this->source[$param]))                //if its array get the required sub
               $sourceParam=$this->source[$param][$sub];
           else $sourceParam =$this->source[$param];            //otherwise just get the parameter
           preg_match("/^[\.\-\s#_a-zA-Z\d]+$/",$sourceParam,$arr);
           if (!empty($arr))                                    //if you have caught something as filename, return it
           {    if ($encode==1) $arr[0]=urlencode($arr[0]);    //Check is URL Encoding is required
               return ($max>0)
               ? substr($arr[0],0,$max)                        //truncate the length
               : $arr[0];
           }
       }
       return $default; // return  default if param not set or it contains invalid characters
   }
   function getVarPath($param, $max = -1, $encode = 1, $default = NULL, $sub = 0){
       if (isset($this->source[$param])) {                        // check if parameter is set
               if (is_array($this->source[$param]))                //if its array get the required sub
               $sourceParam=$this->source[$param][$sub];
           else
               $sourceParam =$this->source[$param];            //otherwise just get the parameter
           preg_match("/^[\.\-\s_a-zA-Z\d][\/\.\-\s_a-zA-Z\d]*$/",$sourceParam,$arr);
           if (!empty($arr))                                    //if you have caught something as path
           {                                                   
                   preg_match("/\/\/|\.\./",$arr[0],$arrCatch);
                   if (!empty($arrCatch))                                //caught with // or ..
                       return $default;                            // return  default
                   return ($max>0)                                //not caught, return the path
               ? substr($arr[0],0,$max)                        //truncate the length
               : $arr[0];
           }
       }
       return $default; // return  default if param not set or it contains invalid characters
   }
   function getVarAlphaSpace($param, $max = -1, $default = NULL, $sub = 0){
       if (isset($this->source[$param])) { // check if parameter is set
               if (is_array($this->source[$param]))                //if its array get the required sub
               $sourceParam=$this->source[$param][$sub];
           else $sourceParam =$this->source[$param];            //otherwise just get the parameter
           preg_match("/^[A-Za-z]([A-Za-z\s]*[A-Za-z])*$/",$sourceParam,$arr);
           if (!empty($arr)) return ($max>0) ? substr($arr[0],0,$max) : $arr[0];
       }
       return $default; // return  default if param not set or its not alphabetic with/without space
   }
   /////////////////////////////
   //  MP- Changed
   /////////////////////////////
   function getVarAlphaNum($param, $max = -1, $default = NULL, $sub = 0){
       if (isset($this->source[$param])) { // check if parameter is set
               if (is_array($this->source[$param]))                //if its array get the required sub
               $sourceParam=$this->source[$param][$sub];
           else $sourceParam =$this->source[$param];            //otherwise just get the parameter
           preg_match("/^[A-Za-z0-9]*$/",$sourceParam,$arr);
           if (!empty($arr)) return ($max>0) ? substr($arr[0],0,$max) : $arr[0];
       }
       return $default; // return  default if param not set or its not alphaNum
   }
   function getVarAlphaNumSpace($param, $max = -1, $default = NULL, $sub = 0){
       if (isset($this->source[$param])) { // check if parameter is set
               if (is_array($this->source[$param]))                //if its array get the required sub
               $sourceParam=$this->source[$param][$sub];
           else $sourceParam =$this->source[$param];            //otherwise just get the parameter
           preg_match("/^[A-Za-z]([A-Za-z0-9\s]*[A-Za-z0-9])*$/",$sourceParam,$arr);
           if (!empty($arr)) return ($max>0) ? substr($arr[0],0,$max) : $arr[0];
       }
       return $default; // return  default if param not set or its not alphaNum
   }
   function getVarAlpha_Num($param, $max = -1, $default = NULL, $sub = 0){
       if (isset($this->source[$param])) { // check if parameter is set
               if (is_array($this->source[$param]))                //if its array get the required sub
               $sourceParam=$this->source[$param][$sub];
           else $sourceParam =$this->source[$param];            //otherwise just get the parameter
           preg_match("/^[A-Za-z]([A-Za-z0-9_]*[A-Za-z0-9])*$/",$sourceParam,$arr);
           if (!empty($arr)) return ($max>0) ? substr($arr[0],0,$max) : $arr[0];
       }
       return $default; // return  default if param not set or its not alpha_Num
   }
   function getVarAlpha_NumSpace($param, $max = -1, $default = NULL, $sub = 0){
       if (isset($this->source[$param])) { // check if parameter is set
               if (is_array($this->source[$param]))                //if its array get the required sub
               $sourceParam=$this->source[$param][$sub];
           else $sourceParam =$this->source[$param];            //otherwise just get the parameter
           //check strictly there are two alpha/nums atleast
           //start with aplhabetic, may include space/numerics/underscores/alphabetics, end with alhabetic/numeric
           preg_match("/^[A-Za-z]([A-Za-z0-9\s_]*[A-Za-z0-9])*$/",$sourceParam,$arr);
           //if you have caught something as alpha_num Num, return it
           if (!empty($arr)) return ($max>0) ? substr($arr[0],0,$max) : $arr[0];
       }
       return $default; // return  default if param not set or its not alpha_num Num
   }
   function getVarAlphaOrNum($param, $max = -1, $default = NULL, $sub = 0){
       if (isset($this->source[$param])) { // check if parameter is set
               if (is_array($this->source[$param]))                //if its array get the required sub
               $sourceParam=$this->source[$param][$sub];
           else $sourceParam =$this->source[$param];            //otherwise just get the parameter
           preg_match("/^[A-Za-z0-9]([A-Za-z0-9\s]*[A-Za-z0-9])*$/",$sourceParam,$arr);
           if (!empty($arr)) return ($max>0) ? substr($arr[0],0,$max) : $arr[0];
       }
       return $default; // return  default if param not set or its not alpha or num
   }
   function getVarString($param, $max = -1, $default = NULL, $sub = 0){
       if (isset($this->source[$param])) { // check if parameter is set
               if (is_array($this->source[$param]))                //if its array get the required sub
               $sourceParam=$this->source[$param][$sub];
           else $sourceParam =$this->source[$param];            //otherwise just get the parameter
           //check strictly there is either an alpha or a num atleast
           //include alpha / num, may also include space, special characters
           preg_match("/^[\(\)\/\'\"\,\.\-\+\$\&\£\s@\?#_a-zA-Z\d]+$/",$sourceParam,$arr);
           //if you have caught something as alpha or num, return it
           if (!empty($arr)) return ($max>0) ? substr($arr[0],0,$max) : $arr[0];
       }
       return $default; // return  default if param not set or its not alpha or num
   }
   function getVar($param, $addslash = 1, $max = -1,  $default = NULL, $sub = 0){
       if (isset($this->source[$param])) { // check if parameter is set
               if (is_array($this->source[$param]))                //if its array get the required sub
               $sourceParam=$this->source[$param][$sub];
           else $sourceParam =$this->source[$param];            //otherwise just get the parameter
               if ($max>0) $textvar= substr($this->source[$param],0,$max);
           else $textvar=$this->source[$param];
           $textvar=htmlentities($this->source[$param]);
          
           return $textvar;
       }
       return $default; // return  default if param not set or its not alpha or num
   }
   function getVarHTML($param, $max = 0,  $default = NULL, $sub = 0){
       if (isset($this->source[$param])) { // check if parameter is set
               if (is_array($this->source[$param]))                //if its array get the required sub
               $sourceParam=$this->source[$param][$sub];
           else $sourceParam =$this->source[$param];            //otherwise just get the parameter
               if ($max>0) $textvar= substr($this->source[$param],0,$max);
           else $textvar=$this->source[$param];
          
           return $textvar;
       }
       return $default; // return  default if param not set or its not alpha or num
   }
}
?>