<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class YoutubeController extends Controller
{
    public function index(Request $request)
    {
        // request to link with parameters
        $http = Http::get('https://www.googleapis.com/youtube/v3/search', [
            'key' => 'AIzaSyAawTDnI6QDRlOTULGb7pogZUcZOj57LUU',
            'part' => 'id,snippet',
            'order' => 'relevance',
            'q' => $request->search,
            'type' => 'video',
            'maxResults' => 10,
        ]);

        // error handling
        if ($http->status() != 200) {
            if ($http->body()) {
                return response()->json(['status' => $http->status(), 'message' => 'Error: ' . json_decode($http->body())->error->message]);
            } else {
                return response()->json(['status' => $http->status(), 'message' => 'Error: No API connection']);
            }
        } else if ($http['pageInfo']['totalResults'] == 0 || !$request->search) { //if there are no results or nothing was searched
            return response()->json(['status' => $http->status(), 'message' => 'Error: No Search Results']);
        }


        $videos = $http['items'];
        $response = [];

        // response creation
        for ($x = 0; $x < count($videos); $x++) {
            $response[] = array(
                'published_at' => $videos[$x]['snippet']['publishedAt'],
                'id' => $videos[$x]['id']['videoId'],
                'title' => $videos[$x]['snippet']['title'],
                'description' => $videos[$x]['snippet']['description'],
                'thumbnail' => $videos[$x]['snippet']['thumbnails'],
                'extra' => array('channel' => $videos[$x]['snippet']['channelTitle'], 'publishTime' => $videos[$x]['snippet']['publishTime']),
            );
        }

        return response()->json(['status' => $http->status(), 'data' => $response, 'totalResults' => $http['pageInfo']['totalResults'], 'regionCode' => $http['regionCode']]);
    }
}
