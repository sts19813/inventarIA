<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Http;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class InventoryAIController extends Controller
{

    public function scan(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:4096'
        ]);

        $manager = new ImageManager(new Driver());

        $image = $manager->read($request->file('image'));

        $image->orient();
        $image->scaleDown(width: 800);

        $encoded = $image->toJpeg(65);
        $base64 = base64_encode($encoded->toString());

        $response = Http::withToken(env('OPENAI_API_KEY'))
            ->post('https://api.openai.com/v1/responses', [
                "model" => "gpt-4o-mini",
                "input" => [
                    [
                        "role" => "user",
                        "content" => [
                            [
                                "type" => "input_text",
                                "text" => "Devuelve JSON con title, category y description."
                            ],
                            [
                                "type" => "input_image",
                                "image_url" => "data:image/jpeg;base64," . $base64
                            ]
                        ]
                    ]
                ],
                "text" => [
                    "format" => [
                        "type" => "json_object"
                    ]
                ]
            ]);

        if (!$response->successful()) {
            return response()->json([
                'error' => $response->body()
            ], 500);
        }

        $data = $response->json();

        $text = $data['output'][0]['content'][0]['text'] ?? '{}';
        $result = json_decode($text, true);

        return response()->json($result ?: []);
    }
}
