<?php
    class Table{
        public function keys(){
            $ki = [];
            foreach(get_object_vars($this) as $key=>$value){
                $ki []= $key;
            }
            return $ki;
        }
        public function values(){
            $ki = [];
            foreach(get_object_vars($this) as $key=>$value){
                $ki []= $value;
            }
            return $ki;
        }
    }