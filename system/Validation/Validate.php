<?php
declare(strict_types=1);

namespace Zero\System\Validation;

use Rakit\Validation\Validator;
use Zero\System\Http\Request;
use Zero\System\Session\Session;
use Zero\System\Url\Url;
use Zero\System\Validation\Rules\UniqueRule;

class Validate 
{
    //put your code here
    public static function make(array $rules, bool $json = false): mixed
    {
        $validator = new Validator;
        $validator->addValidator('unique', new UniqueRule());
        
        $validation = $validator->validate($_POST + $_FILES, $rules);
        
        $errors = $validation->errors();
        
        if ($validation->fails()) {
            
            if ($json) {
                return ['errors' => $errors->firstOfAll()];
            }
            
            Session::set('errors', $errors);
            Session::set('old', Request::all());
            return Url::redirect(Url::previous());
        }
    }
}
