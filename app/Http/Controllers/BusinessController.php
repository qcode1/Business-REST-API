<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Business;
use Illuminate\Support\Facades\Validator;

class BusinessController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        try {

            // Validate the request parameters to get 'page' & 'limit' values
            $validated = $request->validate(
                // Sometimes will only validate if value exists
                [
                    'page' => 'sometimes|integer|min:1',
                    'perPage' => 'sometimes|integer|min:1|max:200', // Enforce max limit
                ]
            );

            // Get the page and limit values from the request
            $page = (int)$request->input('page', 10); // Default: 10 items per page
            $limit = (int)$request->input('limit', 100);     // Default: max 100 items

            /** Create paginate query using page and limit values
             * page: current page number
             * limit: number of items to be displayed per page
             */
            $businesses = Business::paginate(
                perPage: $limit,
                columns: ['*'],
                page: $page
            );

            // Check if the businesses collection is empty
            if ($businesses->isEmpty()) {
                return response()->json([
                    'status' => 404,
                    'meta' => [
                        'page' => $page,
                        'limit' => $limit,
                    ],
                    'message' => 'No businesses found'
                ], 404);
            }

            // Return response paginated
            return response()->json([
                'status' => 200,
                'message' => 'Businesses retrieved successfully',
                'data' => $businesses
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while retrieving the businesses',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            // Validate the request data
            $validator = $this->validate($request);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            // Create a new business using the validated data
            $business = Business::create([
                'name' => $request->name,
                'description' => $request->description,
                'address' => $request->address,
                'phone_number' => $request->phone_number,
                'email' => $request->email,
                'website' => $request->website,
            ]);

            return response()->json([
                'status' => 201,
                'message' => 'Business created successfully',
                'business' => $business
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while creating the business',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function dummy()
    {
        // Create a dummy business using the Factory class
        $business = Business::factory()->create();

        return response()->json([
            'status' => 201,
            'message' => 'Dummy business created successfully',
            'business' => $business
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Find the business by ID
        $business = Business::find($id);

        // Check if the business exists
        if (!$business) {
            return response()->json([
                'status' => 404,
                'message' => 'Business not found'
            ], 404);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Business retrieved successfully',
            'data' => $business
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {

            // Find the business by ID
            $business = Business::find($id);

            if (!$business) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Business not found'
                ], 404);
            }

            // Validate the request data
            $validator = $this->validate($request);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            // Set updated data to variable and update the Model object
            $update_data = $validator->validated();
            $business->update($update_data);

            return response()->json([
                'status' => 201,
                'message' => 'Business updated successfully',
                'business' => $business
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while updating the business',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the business by ID
        $business = Business::find($id);

        // Check if the business exists
        if (!$business) {
            return response()->json([
                'status' => 404,
                'message' => 'Business not found'
            ], 404);
        }

        $business->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Business deleted successfully'
        ], 200);
    }

    private function validate(Request $request)
    {
        return Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
            'email' => 'required|email|unique:businesses,email',
            'website' => 'nullable|url'
        ]);
    }
}
