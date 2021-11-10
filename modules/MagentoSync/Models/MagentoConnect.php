<?php

namespace Modules\MagentoSync\Models;

use App\ObjectModel;

class MagentoConnect extends ObjectModel
{

    protected $fillable = ['user','pass','token'];
    
}
