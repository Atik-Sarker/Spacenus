<?php
namespace App\Traits;

trait ApiResponse
{
    /*
     * Core of response
     *
     * @param   string          $message
     * @param   array|object    $data
     * @param   integer         $statusCode
     * @param   boolean         $isSuccess
     */
    public function CoreResponse($statusCode, $message, $isSuccess, $data)
    {
        // Check the params
        if(!$message) return response()->json(['message' => 'Message is required'], 203);
        // Send the response
        if($isSuccess) {
            return response()->json([
                'status' => $statusCode,
                'message' => $message,
                'data' => $data
            ], $statusCode);
        } else {
            return response()->json([
                'message' => $message,
                'status' => $statusCode
            ], $statusCode);
        }
    }

    /*
     * Send any success response
     *
     * @param   string          $message
     * @param   array|object    $data
     * @param   integer         $statusCode
     */
    public function success($statusCode, $message, $isSuccess = true,  $data = array())
    {
        return $this->CoreResponse($statusCode, $message, $isSuccess,  $data);
    }

    /*
     * Send any error response
     *
     * @param   string          $message
     * @param   integer         $statusCode
     */
    public function errors($statusCode = 500, $message)
    {
        return $this->CoreResponse($statusCode, $message, false, array());
    }
}
