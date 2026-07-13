<?php
namespace App\Http\Controllers;

use App\Models\KbCategory;
use App\Models\KbArticle;
use App\Models\KbAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class KnowledgeBaseController extends Controller
{
    private function ws(): int { return auth()->user()->current_workspace_id; }

    public function index(Request $request)
    {
        $q = $request->search;
        $categories = KbCategory::where('workspace_id', $this->ws())
            ->withCount(['articles','publishedArticles'])
            ->orderBy('sort_order')->get();

        $articles = KbArticle::where('workspace_id', $this->ws())
            ->with('category:id,name')
            ->when($q, fn($query) => $query->where('title','like',"%$q%")->orWhere('content','like',"%$q%"))
            ->latest()->get();

        return Inertia::render('knowledge-base/Index', compact('categories','articles') + ['search' => $q]);
    }

    public function storeCategory(Request $request)
    {
        $request->validate(['name'=>'required|string|max:255','description'=>'nullable|string|max:500','icon'=>'nullable|string|max:50']);
        KbCategory::create(['workspace_id'=>$this->ws(),'created_by'=>auth()->id(),...$request->only('name','description','icon')]);
        return back()->with('success','Category created.');
    }

    public function updateCategory(Request $request, KbCategory $category)
    {
        abort_if($category->workspace_id !== $this->ws(), 403);
        $request->validate(['name'=>'required|string|max:255','description'=>'nullable|string|max:500','icon'=>'nullable|string|max:50']);
        $category->update($request->only('name','description','icon'));
        return back()->with('success','Category updated.');
    }

    public function destroyCategory(KbCategory $category)
    {
        abort_if($category->workspace_id !== $this->ws(), 403);
        $category->delete();
        return back()->with('success','Category deleted.');
    }

    public function showArticle(KbArticle $article)
    {
        abort_if($article->workspace_id !== $this->ws(), 403);
        $article->increment('views');
        $article->load('category:id,name','creator:id,name,avatar','attachments');
        // Add download URL and size to each attachment
        $article->attachments->each(function ($a) {
            $a->download_url = route('kb.attachments.download', $a->id);
            $a->size_formatted = $a->size_formatted;
        });
        return Inertia::render('knowledge-base/Article', ['article' => $article]);
    }

    public function uploadAttachment(Request $request, KbArticle $article)
    {
        abort_if($article->workspace_id !== $this->ws(), 403);
        $request->validate([
            'file' => 'required|file|max:10240', // 10MB max
        ]);
        $file = $request->file('file');
        $path = $file->store('kb-attachments', 'public');

        KbAttachment::create([
            'kb_article_id' => $article->id,
            'name'          => $file->getClientOriginalName(),
            'path'          => $path,
            'mime_type'     => $file->getMimeType(),
            'size'          => $file->getSize(),
            'uploaded_by'   => auth()->id(),
        ]);

        return back()->with('success', 'File attached.');
    }

    public function downloadAttachment(KbAttachment $attachment)
    {
        abort_if($attachment->article->workspace_id !== $this->ws(), 403);
        return Storage::disk('public')->download($attachment->path, $attachment->name);
    }

    public function destroyAttachment(KbAttachment $attachment)
    {
        abort_if($attachment->article->workspace_id !== $this->ws(), 403);
        Storage::disk('public')->delete($attachment->path);
        $attachment->delete();
        return back()->with('success', 'Attachment removed.');
    }

    public function storeArticle(Request $request)
    {
        $request->validate(['title'=>'required|string|max:255','content'=>'required|string','kb_category_id'=>'required|exists:kb_categories,id','is_published'=>'boolean']);
        KbArticle::create(['workspace_id'=>$this->ws(),'created_by'=>auth()->id(),...$request->only('title','content','kb_category_id','is_published')]);
        return back()->with('success','Article created.');
    }

    public function updateArticle(Request $request, KbArticle $article)
    {
        abort_if($article->workspace_id !== $this->ws(), 403);
        $request->validate(['title'=>'required|string|max:255','content'=>'required|string','kb_category_id'=>'required|exists:kb_categories,id','is_published'=>'boolean']);
        $article->update($request->only('title','content','kb_category_id','is_published'));
        return back()->with('success','Article updated.');
    }

    public function destroyArticle(KbArticle $article)
    {
        abort_if($article->workspace_id !== $this->ws(), 403);
        $article->delete();
        return back()->with('success','Article deleted.');
    }
}
