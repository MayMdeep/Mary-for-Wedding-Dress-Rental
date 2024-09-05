<?php


namespace App\Traits;


use Illuminate\Http\Request;


trait Response 
{
    
    public function sendResponse($result, $message, $info = [])
    {
    	$response = [
            'success' => true,
            'data'    => $result,
            'info' => $info,
            'message' => $message,
            'status' => 200,
        ];


        return response()->json($response, 200);
    }
    /**
     * success response method for paginated data.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendPaginatedResponse($result, $message, $additioan_keys = [], $info = [])
    {
        $array = ['success' => true, 'message'=>$message, 'info' => $info, 'status'=>200];
        foreach($additioan_keys as $key => $value)
            $array[$key] = $value;
        $custom = collect($array);
        $response = $custom->merge($result->response()->getData(true));

        return response()->json($response, 200);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 400)
    {
    	$response = [
            'success' => false,
            'data' => [],
            'message' => $error,
            'status' => $code,
        ];


        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }


        return response()->json($response, $code);
    }
}