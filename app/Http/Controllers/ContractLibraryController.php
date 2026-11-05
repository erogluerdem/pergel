<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContractTemplate;
use App\Models\ContractClause;

class ContractLibraryController extends Controller
{
    public function index(Request $request)
    {
        $templatesQuery = ContractTemplate::query();
        $clausesQuery = ContractClause::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->input('search');
            $templatesQuery->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('body', 'like', "%{$search}%");
            });
            $clausesQuery->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Category filter for clauses
        if ($request->filled('category')) {
            $clausesQuery->where('category', $request->input('category'));
        }

        // Status filter
        if ($request->filled('status')) {
            $status = $request->input('status');
            $templatesQuery->where('status', $status);
            $clausesQuery->where('status', $status);
        }

        $templates = $templatesQuery->orderBy('title')->get();
        $clauses = $clausesQuery->orderBy('category')->orderBy('title')->get();

        return view('contracts.library.index', compact('templates','clauses'));
    }

    public function createTemplate()
    {
        // Redirect to main library page (modal preferred)
        return redirect()->route('contracts.library.index');
    }

    public function storeTemplate(Request $request)
    {
        $request->validate(['title'=>'required','body'=>'required']);
        ContractTemplate::create(array_merge($request->only('title','body'), ['created_by'=>auth()->id()]));
        return redirect()->route('contracts.library.index')->with('success','Şablon kaydedildi');
    }

    public function createClause()
    {
        // Redirect to main library page (modal preferred)
        return redirect()->route('contracts.library.index');
    }

    public function storeClause(Request $request)
    {
        $request->validate(['title'=>'required','content'=>'required']);
        ContractClause::create(array_merge($request->only('title','content','category'), ['created_by'=>auth()->id()]));
        return redirect()->route('contracts.library.index')->with('success','Madde kaydedildi');
    }

    // Template CRUD - Edit/Update/Delete
    public function editTemplate($id)
    {
        $template = ContractTemplate::findOrFail($id);
        return view('contracts.library.edit_template', compact('template'));
    }

    public function updateTemplate(Request $request, $id)
    {
        $template = ContractTemplate::findOrFail($id);
        $request->validate(['title'=>'required','body'=>'required']);
        $template->update($request->only('title','body','status'));
        return redirect()->route('contracts.library.index')->with('success','Şablon güncellendi');
    }

    public function destroyTemplate($id)
    {
        $template = ContractTemplate::findOrFail($id);
        $template->delete();
        return redirect()->route('contracts.library.index')->with('success','Şablon silindi');
    }

    // Clause CRUD - Edit/Update/Delete
    public function editClause($id)
    {
        $clause = ContractClause::findOrFail($id);
        return view('contracts.library.edit_clause', compact('clause'));
    }

    public function updateClause(Request $request, $id)
    {
        $clause = ContractClause::findOrFail($id);
        $request->validate(['title'=>'required','content'=>'required']);
        $clause->update($request->only('title','content','category','status'));
        return redirect()->route('contracts.library.index')->with('success','Madde güncellendi');
    }

    public function destroyClause($id)
    {
        $clause = ContractClause::findOrFail($id);
        $clause->delete();
        return redirect()->route('contracts.library.index')->with('success','Madde silindi');
    }

    // Template duplicate for customization
    public function duplicateTemplate($id)
    {
        $original = ContractTemplate::findOrFail($id);
        $duplicate = ContractTemplate::create([
            'title' => $original->title . ' (Kopya)',
            'body' => $original->body,
            'created_by' => auth()->id(),
            'status' => 'draft'
        ]);
        return redirect()->route('contracts.library.templates.edit', $duplicate->id)->with('success', 'Şablon kopyalandı - özelleştirmeye hazır');
    }
}
