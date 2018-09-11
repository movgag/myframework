<?php

namespace Core;

class Notice {

    public static function create($msg, $type){
        $_SESSION['msgType'] = $type;
        if( $type == 'danger' || $type == 'warning'){
            $_SESSION['errorMsg'] = $msg;
        }
        else {
            $_SESSION['successMsg'] = $msg;
        }
    }

    public static function show(){
        if( isset($_SESSION['errorMsg']) && isset($_SESSION['msgType']) ) {
            $err_message = $_SESSION['errorMsg'];
            $message_type = $_SESSION['msgType'];
            unset($_SESSION['errorMsg']);
            unset($_SESSION['msgType']);

            return array(
                'message'=>$err_message,
                'type'=>$message_type,
            );
        }

        if( isset($_SESSION['successMsg']) && isset($_SESSION['msgType']) ) {

            $success_message = $_SESSION['successMsg'];
            $message_type = $_SESSION['msgType'];
            unset($_SESSION['successMsg']);
            unset($_SESSION['msgType']);

            return array(
                'message'=>$success_message,
                'type'=>$message_type,
            );
        }
        return array();
    }

    public static function flash($arr)
    {
        if(is_array($arr) && $arr){
            foreach ($arr as $key=>$item){
                $_SESSION['flash'][$key] = $item;
            }
        }
    }

    public static function old($arr)
    {
        if(is_array($arr) && $arr){
            foreach ($arr as $key=>$item){
                $_SESSION['old'][$key] = $item;
            }
        }
    }

    public static function getFlash()
    {
        if(isset($_SESSION['flash']) && is_array($_SESSION['flash']) && $_SESSION['flash']){
            $flash_messages = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $flash_messages;
        }
        return array();
    }

    public static function getOld()
    {
        if(isset($_SESSION['old']) && is_array($_SESSION['old']) && $_SESSION['old']){
            $old_messages = $_SESSION['old'];
            unset($_SESSION['old']);
            return $old_messages;
        }
        return array();
    }



}