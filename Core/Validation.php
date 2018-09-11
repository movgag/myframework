<?php

namespace Core;

use PDO;

class Validation {

    public static function run($data,$rules)
    {
        $info = array();

        foreach ($data as $key=>$val){

            if(array_key_exists($key,$rules)){

                $current_rule = $rules[$key];

                $multi_rules = explode('|',$current_rule);

                if(count($multi_rules) > 1){
                    // multi rules case
                    foreach (array_reverse($multi_rules,true) as $j => $m_rule){
                        $rule_with_param = explode(':',$m_rule);

                        if(count($rule_with_param) > 1){
                            // rule with param case
                            $sec_arg = $rule_with_param[1];
                            $current_rule = $rule_with_param[0];
                        } else {
                            //just rule
                            $current_rule = $m_rule;
                            $sec_arg = 0;
                        }
                        $validation_result = self::$current_rule($val,$sec_arg);

                        if($validation_result){
                            $info[$key] = array(
                                'code' => config('settings.'.$current_rule),
                                'message' => $validation_result['message']
                            );
                        }
                    }
                } else {
                    // one rule case
                    $rule_with_param = explode(':',$current_rule);

                    if(count($rule_with_param) > 1){
                        // rule with param case
                        $sec_arg = $rule_with_param[1];
                        $current_rule = $rule_with_param[0];
                    } else {
                        // just rule
                        $sec_arg = 0;
                    }
                    $validation_result = self::$current_rule($val,$sec_arg);

                    if($validation_result){
                        $info[$key] = array(
                            'code' => config('settings.'.$current_rule),
                            'message' => $validation_result['message']
                        );
                    }
                }
            } // end array_key_exists
        } //endforeach

        return $info;
    }

    public static function getErrors($messages,$key)
    {
        $new_arr = array();
        foreach ($messages as $k => $item){
            $new_arr[$k] = $item[$key];
        }
        return $new_arr;
    }

    //001
    public static function isRequired($val){
        if((!(string)$val || !(array)$val)){
            return array(
                'message' => trans('en.validation.isRequired')
            );
        }
        return array();
    }

    //002
    public static function isEmail($val){

        if($val){
            $email_pattern = '/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/';
            preg_match($email_pattern, $val, $email_matches);
            if(!$email_matches[0]){
                return array(
                    'message' => trans('en.validation.isEmail')
                );
            }
            return array();
        }

        return array();
    }

    //003
    public static function isDate($val){

        $arr = explode('/',$val);

        if(is_array($arr) && count($arr) == 3){
            //
        } else {
            $arr = explode('.',$val);

            if(is_array($arr) && count($arr) == 3){
                //
            } else {
                $arr = explode('-',$val);

                if(is_array($arr) && count($arr) == 3){
                    //
                } else {
                    return array(
                        'message' => trans('en.validation.isDate')
                    );
                }
            }
        }

        $year = $arr[0];
        $month = $arr[1];
        $day = $arr[2];

        if(!checkdate($month, $day , $year)) {
            return array(
                'message' => trans('en.validation.isDate')
            );

        }
//        else {
//            $today = strtotime("now");
//            if(strtotime($val)<$today){
//                return array(
//                    'message' => trans('en.validation.isDate')
//                );
//            }
//        }

        return array();
    }

    //004
    public static function isAlpha($val){
        $text_pattern = '/^[a-zA-Z ]*$/';
        preg_match($text_pattern, $val, $name_matches);
        if(!$name_matches[0]){
            return array(
                'message' => trans('en.validation.isAlpha')
            );
        }
        return array();
    }

    //005
    public static function isNonNegInt($val){

        preg_match("@^([1-9][0-9]*)$@", $val, $persons_match);
        if(!$persons_match[0]){
            return array(
                'message' => trans('en.validation.isNonNegInt')
            );
        }
        return array();
    }

    //006
    public static function hasLength($val, $size){
        if(strlen($val) != (int)$size){
            return array(
                'message' => str_replace('$attr',$size,trans('en.validation.hasLength'))
            );
        }
        return array();
    }

    //007
    public static function hasMinLength($val, $size){
        if($val){
            if(strlen($val) < (int)$size){
                return array(
                    'message' => str_replace('$attr',$size,trans('en.validation.hasMinLength'))
                );
            }
        }

        return array();
    }

    //008
    public static function hasMaxLength($val, $size){
        if($val){
            if(strlen($val) > (int)$size){
                return array(
                    'message' => str_replace('$attr',$size,trans('en.validation.hasMaxLength'))
                );
            }
        }
        return array();
    }

    //009
    public static function isUrl($val) {
        $url_pattern = '/(http|ftp|https):\/\/[\w\-_]+(\.[\w\-_]+)*([\w\-\.,@?^=%&amp;:\/~\+#]*[\w\-\@?^=%&amp;\/~\+#])?/';
        preg_match($url_pattern, $val, $url_matches);
        if(!$url_matches[0]){
            return array(
                'message' => trans('en.validation.isUrl')
            );
        }
        return array();
    }

    //010
    public static function isSame($val,$sec_arg)
    {
        if((string)$val == (string)$sec_arg){
            return array();
        } else {
            return array(
                'message' => trans('en.validation.isSame')
            );
        }
    }

    //011
    public static function isIn($val,$sec_arg)
    {
        $arr = explode(',',$sec_arg);
        if(in_array($val,$arr)){
            return array();
        } else {
            return array(
                'message' => trans('en.validation.isIn')
            );
        }
    }

    //012
    public static function isUnique($val,$sec_arg)
    {
        $arr = explode(',',$sec_arg);

        $tbl_name = $arr[0];
        $field_name = $arr[1];

        $except_id = 0;
        // except via id
        if(count($arr) == 3){
            $except_id = (int)$arr[2];
        }

        $dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=".DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);

        $sql = "SELECT * FROM ".$tbl_name." WHERE ".$field_name." = :".$field_name;

        if($except_id){
            $sql .= ' AND id != :'.$except_id;
        }

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':'.$field_name,$val,PDO::PARAM_INT);
        if($except_id){
            $stmt->bindValue(':'.$except_id,$except_id,PDO::PARAM_INT);
        }
        $stmt->execute();
        $result = $stmt->fetch();

        if($result){
            return array(
                'message' => trans('en.validation.isUnique')
            );
        } else {
            return array();
        }
    }

    //013
    public static function isJson($val)
    {
        if(empty($val)){
            //
        } else {
            json_decode($val);
            if(json_last_error() == JSON_ERROR_NONE){
                return array();
            }
        }
        return array(
            'message' => trans('en.validation.isJson')
        );

    }

    //014
    public static function isIp($val)
    {
        if(filter_var($val, FILTER_VALIDATE_IP)){
            return array();
        } else {
            return array(
                'message' => trans('en.validation.isIp')
            );
        }
    }

    //015
    public static function isImage($file)
    {
        $tmp_file = $file['tmp_name'];

        if (file_exists($tmp_file)) {

            $imagesizedata = getimagesize($tmp_file);
            if ($imagesizedata === FALSE) {
                //not image
                return array(
                    'message' => trans('en.validation.isImage')
                );
            }
            else {
                //image
                //use $imagesizedata to get extra info
                return array();
            }
        } else {
//            return array(
//                'message' => trans('en.validation.isRequired')
//            );
            return array();
        }
    }

    //016
    public static function hasMaxSize($file,$sec_arg)
    {
        $tmp_file = $file['tmp_name'];

        if (file_exists($tmp_file)) {

            $file_size = $file['size'];
            if ($file_size > (int)$sec_arg*1000) {
                //not image
                return array(
                    'message' => str_replace('$attr',$sec_arg,trans('en.validation.hasMaxSize'))
                );
            }
            else {
                return array();
            }
        } else {
            return array(
                'message' => trans('en.validation.isFile')
            );
        }
    }

    //017
    public static function isFile($file)
    {
        $tmp_file = $file['tmp_name'];

        if (file_exists($tmp_file)) {

            return array();

        } else {
            return array(
                'message' => trans('en.validation.isFile')
            );
        }
    }

    //018
    public static function hasMimes($file,$sec_arg)
    {
        $tmp_file = $file['tmp_name'];

        if (file_exists($tmp_file)) {

            $file_extension = pathinfo($file["name"], PATHINFO_EXTENSION);

            $arr = explode(',',$sec_arg);

            if(in_array($file_extension,$arr)){

                return array();

            } else {
                return array(
                    'message' => str_replace('$attr',$sec_arg,trans('en.validation.hasMimes'))
                );
            }
        } else {
            return array(
                'message' => trans('en.validation.isFile')
            );
        }
    }

    //019
    public static function hasMaxDimensions($file,$sec_arg)
    {
        $tmp_file = $file['tmp_name'];

        if (file_exists($tmp_file)) {

            $imagesizedata = getimagesize($tmp_file);
            if ($imagesizedata === FALSE) {
                //not image
                return array(
                    'message' => trans('en.validation.isImage')
                );
            }
            else {
                $real_width = $imagesizedata[0];
                $real_height = $imagesizedata[1];

                $arr = explode(',',$sec_arg);

                if($real_width > $arr[0] || $real_height > $arr[1]){
                    return array(
                        //'message' => trans('en.validation.hasMaxDimension')
                        'message' => str_replace('$attr',str_replace(',','x',$sec_arg),trans('en.validation.hasMaxDimensions'))
                    );
                } else {
                    return array();
                }
            }
        } else {
            return array(
                'message' => trans('en.validation.isFile')
            );
        }
    }

    //020
    public static function isExists($val,$sec_arg)
    {
        $arr = explode(',',$sec_arg);

        $tbl_name = $arr[0];
        $field_name = $arr[1];

        $dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=".DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);

        $sql = "SELECT * FROM ".$tbl_name." WHERE ".$field_name." = :".$field_name;


        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':'.$field_name,$val,PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch();

        if($result){
            return array();
        } else {
            return array(
                'message' => trans('en.validation.isExists')
            );
        }
    }


    //021
    public static function isRequiredOrZero($val){
        if($val === '0' || $val === 0){
            //
        }elseif((!(string)$val || !(array)$val)){
            return array(
                'message' => trans('en.validation.isRequiredOrZero')
            );
        }
        return array();
    }

    //22
    public static function isNonNegIntOrZero($val){

        if($val){
            preg_match("@^([1-9][0-9]*)$@", $val, $persons_match);
            if(!$persons_match[0] && $val !== '0' && $val !== 0){
                return array(
                    'message' => trans('en.validation.isNonNegInt')
                );
            }
            return array();
        }
        return array();
    }

    //23
    public static function isFloat($val){

        if($val){
            // on progress
        }elseif ($val ==0){
            return array();
        }
        return array();
    }



    // cleaning $_POST
    public static function cleanData($arr){
        $new_arr = array();

        foreach ($arr as $k => $item){
            if(!is_array($item)){
                $item = trim($item);
                $item = strip_tags($item);
                $item = htmlspecialchars($item,ENT_QUOTES);
                $new_arr[$k] = $item;
            } else {
                return array();
            }
        }
        return $new_arr;
    }



}