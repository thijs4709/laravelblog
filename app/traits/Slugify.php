<?php

namespace App\traits;

use Illuminate\Support\Str;

trait Slugify{
    public function Slugify($value){
        return str::slug($value,'-');
    }
}




?>
