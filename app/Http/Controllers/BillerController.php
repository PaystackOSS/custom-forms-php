<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BillerController extends Controller
{
    /**
     * Fetch form to display on Terminal
     */
    public function retrieveForm(Request $request)
    {
        // Get Terminal ID if you need some checks before building the form
        $terminal_id = $request->query('terminal_id');
        // $isRequestValid = validateRequest($request);
        
        // if(!$isRequestValid) {
        //     return response()->json([
        //         "status" =>  false,
        //         "message" => "Not authorized"
        //     ], 401);
        // }

        // create response JSON
        $response = [
	        "action" => "collect",
	        "title" => "User Information",
            "fields" => [
                [
                    "type" => "email",
                    "id" => "customer_email",
                    "title" => "Enter your email address",
                ],
                [
                    "type" => "alphanumeric",
                    "id" => "customer_name",
                    "title" => "Enter your name",
                    "validation" => [
                        "length" => [
                            "min" => "2",
                            "max" => "50"
                        ]
                    ]
                ],
                [
                    "type" => "date",
                    "id" => "date_of_birth",
                    "title" => "Enter your DOB",
                    "validation" => [
                        "date" => [
                            "before" => "06-10-2001",
                            "after" => "06-10-1951"
                        ]
                    ]
                ],
                [
                    "type" => "selector",
                    "id" => "origin_country",
                    "title" => "Select Country of Origin",
                    "options" => [
                        "selector_options" => [
                            [
                                "text" => "Nigeria",
                                "value" => "NG"
                            ],
                            [
                                "text" => "South Africa",
                                "value" => "SA"
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $responseHeader = createResponseHeader($response);

        return response($response)->withHeaders($responseHeader);
    }

    public function processForm(Request $request)
    {
        $isRequestValid = validateRequest($request);
        
        if(!$isRequestValid) {
            return response()->json([
                "status" =>  false,
                "message" => "Not authorized"
            ], 401);
        }

        // parse request here
        $body = $request->post();

        // create response JSON
        // $response

        return [
            "data" => $body
        ];
    }
}
