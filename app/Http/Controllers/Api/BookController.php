<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookRequest;
use App\Models\Book;
use App\Models\Country;
use App\Models\PaymentRequestApproval;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BookController extends Controller
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
            'data' => Book::query()
                ->filterSearch($request)
                ->orderBy('name', 'ASC')
                ->paginate($limit)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param BookRequest $request
     * @return JsonResponse
     */
    public function store(BookRequest $request): JsonResponse
    {
        $payload = $request->validated();
        $country = Country::query()->where('uuid', $request->input('country_id'))->first();

        if ($request->hasFile('file_id_verification')) {
            $filePath = $request->file('file_id_verification')?->store('public/uploads/verification');
            $payload['file_id_verification'] = $filePath;
        }

        $payload['country_id'] = $country?->id;
        $payload['uuid'] = Str::uuid();
        $payload['visit_date'] = Carbon::parse($request->input('visit_date'))->format('Y-m-d');

        return response()->json([
            'message' => 'Success',
            'data' => Book::query()->create($payload)->load('country')
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
        $book = Book::query()->where('uuid', $uuid)->firstOrFail();

        return response()->json([
            'message' => 'Success',
            'data' => $book->load('country')
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param BookRequest $request
     * @param string $uuid
     * @return JsonResponse
     */
    public function update(BookRequest $request, string $uuid): JsonResponse
    {
        $country = Country::query()->where('uuid', $request->input('country_id'))->first();

        $payload = $request->validated();

        if ($request->hasFile('file_id_verification')) {
            $filePath = $request->file('file_id_verification')?->store('public/uploads/verification');
            $payload['file_id_verification'] = $filePath;
        }

        $payload['country_id'] = $country?->id;
        $payload['visit_date'] = Carbon::parse($request->input('visit_date'))->format('Y-m-d');

        $book = Book::query()->where('uuid', $uuid)->firstOrFail();
        $book->update($payload);

        return response()->json([
            'message' => 'Success',
            'data' => $book
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
        $book = Book::query()->where('uuid', $uuid)->firstOrFail();
        $book->delete();

        return response()->json([
            'message' => 'Success',
            'data' => null
        ]);
    }
}
