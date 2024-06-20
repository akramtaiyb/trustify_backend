<?php

namespace App\Http\Controllers;

use App\Models\MediaFile;
use App\Models\Publication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublicationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index()
    {
        $publications = Publication::with(['user', 'comments.user', 'mediaFiles'])
            ->withCount([
                'votes as upvotes_count' => function ($query) {
                    $query->where('vote', 'real');
                },
                'votes as downvotes_count' => function ($query) {
                    $query->where('vote', 'fake');
                },
                'comments'
            ])
            // retrieve the vote id of the user auth()->user() as user_vote_id if the vote exists else return false
            ->orderByDesc('id')
            ->paginate(4);

        return response()->json($publications);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'max:255',
            'content' => 'required',
            'user_id' => 'required|exists:users,id',
            'media_files.*' => 'file|mimes:jpg,jpeg,png,gif,mp4,pdf|max:10240', // Adjust as needed
        ]);

        DB::beginTransaction();

        try {
            $publication = Publication::create($request->only(['title', 'content', 'user_id']));

            if ($request->hasFile('media_files')) {
                foreach ($request->file('media_files') as $file) {
                    $path = $file->store('media_files', 'public');
                    $publication->mediaFiles()->create(['path' => $path, 'type' => $file->getClientMimeType()]);
                }
            }

            $publication = Publication::with(['user', 'comments.user', 'mediaFiles'])
                ->withCount([
                    'votes as upvotes_count' => function ($query) {
                        $query->where('vote', 'real');
                    },
                    'votes as downvotes_count' => function ($query) {
                        $query->where('vote', 'fake');
                    },
                    'comments'
                ])->find($publication->id);

            DB::commit();

            return response()->json($publication, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e], 500);
        }
    }


    public function show(Publication $publication)
    {
        return $publication->load('user');
    }

    public function update(Request $request, Publication $publication)
    {
        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'type' => 'sometimes|required|string',
            'media_files.*' => 'file|mimes:jpg,png,gif,mp4,pdf,doc,docx',
        ]);

        $publication->update($request->all());

        if ($request->hasFile('media_files')) {
            foreach ($request->file('media_files') as $file) {
                $path = $file->store('publications');
                MediaFile::create([
                    'publication_id' => $publication->id,
                    'type' => $file->getClientOriginalExtension(),
                    'path' => $path,
                ]);
            }
        }

        return response()->json($publication->load('mediaFiles'), 200);
    }

    public function destroy(Publication $publication)
    {
        $publication->delete();

        return response()->noContent();
    }
}
