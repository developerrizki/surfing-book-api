<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostCountryRequest;
use App\Http\Requests\PutCountryRequest;
use App\Models\Country;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $limit = $request->limit ?? 25;

        return response()->json([
            'message' => 'Success',
            'data' => Country::query()
                ->filterSearch($request)
                ->orderBy('name', 'ASC')
                ->paginate($limit)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PostCountryRequest $request
     * @return JsonResponse
     */
    public function store(PostCountryRequest $request): JsonResponse
    {
        $payload = $request->validated();
        $payload['uuid'] = Str::uuid();

        Country::query()->create($payload);

        return response()->json([
            'message' => 'Success',
            'data' => $request->validated()
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param string $uuid
     * @return JsonResponse
     */
    public function show(string $uuid): JsonResponse
    {
        $country = Country::query()->where('uuid', $uuid)->firstOrFail();

        return response()->json([
            'message' => 'Success',
            'data' => $country
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PutCountryRequest $request
     * @param string $uuid
     * @return JsonResponse
     */
    public function update(PutCountryRequest $request, string $uuid): JsonResponse
    {
        $country = Country::query()->where('uuid', $uuid)->firstOrFail();
        $country->update($request->validated());

        return response()->json([
            'message' => 'Success',
            'data' => $country
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $uuid
     * @return JsonResponse
     */
    public function destroy(string $uuid): JsonResponse
    {
        $country = Country::query()->where('uuid', $uuid)->firstOrFail();
        $country->delete();

        return response()->json([
            'message' => 'Success',
            'data' => null
        ]);
    }
}
