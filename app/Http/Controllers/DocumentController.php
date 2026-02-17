<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'document'          => ['required', 'file', 'max:10240', 'mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,txt,zip'],
            'documentable_type' => ['required', 'string', 'in:App\\Models\\Budget,App\\Models\\Opportunity'],
            'documentable_id'   => ['required', 'integer'],
        ]);

        $file = $request->file('document');
        $path = $file->store('documents', 'public');

        Document::create([
            'documentable_type' => $request->input('documentable_type'),
            'documentable_id'   => $request->input('documentable_id'),
            'user_id'           => Auth::id(),
            'filename'          => $file->getClientOriginalName(),
            'path'              => $path,
            'mime_type'         => $file->getClientMimeType(),
            'size'              => $file->getSize(),
        ]);

        return redirect()->back()->with('success', 'Document pujat correctament.');
    }

    public function destroy(Document $document)
    {
        Storage::disk('public')->delete($document->path);
        $document->delete();

        return redirect()->back()->with('success', 'Document eliminat correctament.');
    }

    public function download(Document $document)
    {
        return Storage::disk('public')->download($document->path, $document->filename);
    }
}
