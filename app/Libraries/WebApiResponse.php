<?php

namespace App\Libraries;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class WebApiResponse
{
    /**
    * WebAPI Error Response
    * 
    * @param int $status_code HTP Status Code
    * @param array $error_details Array of Errors
    * @param string $message Error Message
     * @return Response
    */

    public static function error(int $status_code, array $error_details = [], string $message = '')
    {
        
        $responseData = [
            'status'    => app()->getLocale() == 'bn' ? 'ভুল হয়েছে' : 'error',
            'message'      => $message,
            'code'      => $status_code,
            'errors'    => $error_details
        
          ];
        return response()->json($responseData, $status_code, ['Content-Type' => 'text/json'], JSON_UNESCAPED_UNICODE);
    }

    /**
    * WebAPI Success Response
    * 
    * @param int $status_code HTP Status Code
    * @param array $items Array of Items
    * @param string $message Success Message
     * @return Response
    */

    public static function success(int $status_code = 200, array $items = [], string $message = '')
    {
        
        $responseData = [
            'status'        => app()->getLocale() == 'bn' ? 'সফল' : 'success',
            'message'       => $message,
            'code'          => $status_code,
            'data'          => $items
       
          ];
        return response()->json($responseData, $status_code, ['Content-Type' => 'text/json'], JSON_UNESCAPED_UNICODE);
    }
    /**
    * WebAPI Error Response
    * 
    * @param Validator $validator Validator Instance
    * @param Request $request Array of Request Data
    * @return Response
    */
    
    public static function validationError(Validator $validator,  Request $request){

        $items = [];
        
        $errors = $validator->errors()->toArray();

        foreach($errors as $index => $error){
            $items[] = [
            'field'   => $index, 
            'value'   => $request[$index], 
            'message' => $errors[$index],
            ];
        }

        return self::error(
            400,
            $items,
            'Invalid Data',
        );
    }
}