<?php

namespace App\Http\Controllers;

use App\Http\Resources\RecommendationResource;
use App\Jobs\AnalyzePlateCompatibility;
use App\Models\Plat;
use App\Models\Recommendation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class RecommendationController extends Controller
{
    public function analyze(int $plate_id): JsonResponse
    {
        $user = Auth::user();
        $plate = Plat::findOrFail($plate_id);

        // Create recommendation record
        $recommendation = Recommendation::create([
            'user_id' => $user->id,
            'plat_id' => $plate_id,
            'status' => 'processing'
        ]);

        // Start the analysis job
        AnalyzePlateCompatibility::dispatch($user, $plate, $recommendation);

        return response()->json([
            'status' => 'processing',
            'message' => 'Analysis started'
        ], Response::HTTP_ACCEPTED);
    }

    public function index(Request $request): JsonResponse
    {
        $user = Auth::user();
        
        $recommendations = Recommendation::where('user_id', $user->id)
            ->with('plat:id,name')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json([
            'data' => RecommendationResource::collection($recommendations)
        ]);
    }

    public function show(int $plate_id): JsonResponse
    {
        $user = Auth::user();
        
        $recommendation = Recommendation::where('user_id', $user->id)
            ->where('plat_id', $plate_id)
            ->with('plat:id,name')
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$recommendation) {
            return response()->json([
                'error' => 'Recommendation not found'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json(new RecommendationResource($recommendation));
    }
}
