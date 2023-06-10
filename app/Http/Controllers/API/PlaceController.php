<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;
use App\Traits\ApiResponse;

class PlaceController extends Controller
{
    use ApiResponse;

    public function getNearbyPlaces()
    {

        $validator = Validator::make(request()->all(), [
            'lat' => 'required|string',
            'long' => 'required|string'
        ]);
        if ($validator->fails()) {
            $response = array("status" => false, "errors" => $validator->errors(), "data" =>  request()->all());
            return $this->errors(422, $response);
        }
        //Retrieve the validated input...
        $data = $validator->validated();
        return response($data);

        $latitude = $data['lat'];
        $longitude = $data['long'];
        $radius = 5000; // Radius in meters (5 kilometers)

        $apiKey = env("GOOGLE_MAPS_API_KEY");
        $url = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=$latitude,$longitude&radius=$radius&key=$apiKey";

        try {
            $client = new Client();
            $response = $client->get($url);
            $places = json_decode($response->getBody()->getContents());

            // Process the response and extract relevant place details
            $results = [];
            foreach ($places->results as $place) {
                $result = [
                    'name' => $place->name,
                    'address' => $place->vicinity,
                    // Add more details as needed
                ];
                $results[] = $result;
            }
            return $this->success(200,'Success', true, $results);
        } catch (\Exception $e) {
            Log::error('Failed to create User form API :'.$e->getMessage());
            $response = array("status" => false, "errors" => 'Failed to fetch nearby places. :'.$e->getMessage(), "data" =>  request()->all());
            return $this->errors($e->getCode(), $response);
        }
    }
}
