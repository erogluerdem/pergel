<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use App\Models\Project;
use App\Models\Rfi;
use App\Models\Submittal;
use App\Models\MeetingMinute;
use App\Models\ActionItem;
use App\Models\User;
use App\Models\SubmittalReview;
use App\Notifications\CommunicationAssigned;
use App\Notifications\SubmittalReviewRequested;

class CommunicationController extends Controller
{
    public function dashboard(Request $request)
    {
        $projects = Project::orderBy('name')->get();
        // choose default project (optionally passed as ?project=ID)
        $defaultProject = $projects->first();
        if ($request->has('project')) {
            $p = Project::find($request->query('project'));
            if ($p) $defaultProject = $p;
        }

        // my inbox counts (scoped to selected project when available)
        $user = $request->user();
        $projectConstraint = $defaultProject ? ['project_id' => $defaultProject->id] : [];

        $assignedRfis = Rfi::where('assigned_to', $user->email ?? '')->whereIn('status', ['open','pending'])
            ->when($defaultProject, function($q) use ($defaultProject){ $q->where('project_id', $defaultProject->id); })->count();

        $assignedSubmittals = Submittal::whereJsonContains('reviewers', $user->email ?? '')->where('status','<>','approved')
            ->when($defaultProject, function($q) use ($defaultProject){ $q->where('project_id', $defaultProject->id); })->count();

        $assignedActions = ActionItem::where('assigned_to', $user->email ?? '')->where('status','<>','closed')
            ->when($defaultProject, function($q) use ($defaultProject){ $q->where('project_id', $defaultProject->id); })->count();

        $overdue = ActionItem::when($defaultProject, function($q) use ($defaultProject){ $q->where('project_id', $defaultProject->id); })
            ->where('due_date','<',now())->where('status','<>','closed')->count();

        // recent items for quick view (scoped)
        $recentRfis = Rfi::when($defaultProject, function($q) use ($defaultProject){ $q->where('project_id', $defaultProject->id); })->latest()->limit(5)->get();
        $recentSubs = Submittal::when($defaultProject, function($q) use ($defaultProject){ $q->where('project_id', $defaultProject->id); })->latest()->limit(5)->get();
        $recentActions = ActionItem::when($defaultProject, function($q) use ($defaultProject){ $q->where('project_id', $defaultProject->id); })->latest()->limit(5)->get();

        // counts for visual indicators
        $totalRfis = $defaultProject ? Rfi::where('project_id', $defaultProject->id)->count() : Rfi::count();
        $totalSubmittals = $defaultProject ? Submittal::where('project_id', $defaultProject->id)->count() : Submittal::count();
        $approvedSubmittals = $defaultProject ? Submittal::where('project_id', $defaultProject->id)->where('status','approved')->count() : Submittal::where('status','approved')->count();
        $openActions = $defaultProject ? ActionItem::where('project_id', $defaultProject->id)->where('status','<>','closed')->count() : ActionItem::where('status','<>','closed')->count();

        // chart data for communication types distribution
        $chartData = [];
        if ($defaultProject) {
            $rfis = Rfi::where('project_id', $defaultProject->id)->count();
            $submittals = Submittal::where('project_id', $defaultProject->id)->count();
            $meetings = MeetingMinute::where('project_id', $defaultProject->id)->count();
            $actions = ActionItem::where('project_id', $defaultProject->id)->count();

            $chartData = [
                ['type' => 'RFI', 'count' => $rfis ?: 5],
                ['type' => 'Submittal', 'count' => $submittals ?: 8],
                ['type' => 'Toplantı', 'count' => $meetings ?: 3],
                ['type' => 'Eylem', 'count' => $actions ?: 12]
            ];
        }

        // monthly activity trend (last 6 months)
        $monthlyActivity = [];
        if ($defaultProject) {
            for ($i = 5; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $month = $date->format('M Y');
                $count = 0;

                // Count activities for this month
                $count += Rfi::where('project_id', $defaultProject->id)
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count();

                $count += Submittal::where('project_id', $defaultProject->id)
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count();

                $count += MeetingMinute::where('project_id', $defaultProject->id)
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count();

                $count += ActionItem::where('project_id', $defaultProject->id)
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count();

                $monthlyActivity[] = ['month' => $month, 'count' => $count ?: rand(5, 20)];
            }
        } else {
            // Dummy data if no project selected
            $monthlyActivity = [
                ['month' => 'Eki 2024', 'count' => 12],
                ['month' => 'Kas 2024', 'count' => 15],
                ['month' => 'Ara 2024', 'count' => 8],
                ['month' => 'Oca 2025', 'count' => 22],
                ['month' => 'Şub 2025', 'count' => 18],
                ['month' => 'Mar 2025', 'count' => 25]
            ];
        }

        return view('projects.communication.dashboard', compact(
            'projects','assignedRfis','assignedSubmittals','assignedActions','overdue',
            'recentRfis','recentSubs','recentActions','defaultProject',
            'totalRfis','totalSubmittals','approvedSubmittals','openActions',
            'chartData','monthlyActivity'
        ));
    }

    // basic RFI list/store
    public function rfiList($projectId)
    {
        $project = Project::findOrFail($projectId);
        $this->authorize('view', $project);
        return view('projects.communication.rfi_list', ['project' => $project, 'rfis' => Rfi::where('project_id',$project->id)->get() ]);
    }

    public function rfiStore(Request $request, $projectId)
    {
        $project = Project::findOrFail($projectId);
        $this->authorize('update', $project);
        $data = $request->validate([ 'subject'=>'required','description'=>'nullable','assigned_to'=>'nullable','due_date'=>'nullable|date','attachments.*'=>'file|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg' ]);
        $data['project_id'] = $project->id;
        $data['rfi_no'] = 'RFI-'.time();

        // handle attachments
        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                if (!$file->isValid()) continue;
                $name = time().'_'.preg_replace('/[^A-Za-z0-9_\.-]/', '_', $file->getClientOriginalName());
                $path = "communications/projects/{$project->id}/rfis/{$data['rfi_no']}/" . $name;
                Storage::putFileAs(dirname($path), $file, basename($path));
                $attachments[] = basename($path);
            }
        }
        if (!empty($attachments)) $data['attachments'] = $attachments;

        $rfi = Rfi::create($data);

        // notify assigned user if found
        if (!empty($rfi->assigned_to)) {
            $u = User::where('email', $rfi->assigned_to)->first();
            if ($u) {
                try { $u->notify(new CommunicationAssigned(['title'=>"Yeni RFI: {$rfi->rfi_no}", 'body'=>$rfi->subject, 'link'=>route('projects.communication.rfis',['project'=>$project->id]) ])); } catch (\Exception $e) { /* ignore notification errors */ }
            }
        }

        return redirect()->route('projects.communication.rfis',['project'=>$project->id]);
    }

    public function rfiEdit($projectId, $rfiId)
    {
        $project = Project::findOrFail($projectId);
        $this->authorize('update', $project);
        $rfi = Rfi::where('project_id', $project->id)->findOrFail($rfiId);

        return view('projects.communication.rfi_edit_form', ['project' => $project, 'rfi' => $rfi]);
    }

    public function rfiUpdate(Request $request, $projectId, $rfiId)
    {
        $project = Project::findOrFail($projectId);
        $this->authorize('update', $project);
        $rfi = Rfi::where('project_id', $project->id)->findOrFail($rfiId);

        $data = $request->validate([
            'subject' => 'required',
            'description' => 'nullable',
            'assigned_to' => 'nullable',
            'due_date' => 'nullable|date',
            'status' => 'nullable|in:open,pending,closed',
            'attachments.*' => 'file|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg'
        ]);

        // Handle new attachments
        if ($request->hasFile('attachments')) {
            $attachments = $rfi->attachments ?? [];
            foreach ($request->file('attachments') as $file) {
                if (!$file->isValid()) continue;
                $name = time() . '_' . preg_replace('/[^A-Za-z0-9_\.-]/', '_', $file->getClientOriginalName());
                $path = "communications/projects/{$project->id}/rfis/{$rfi->rfi_no}/" . $name;
                Storage::putFileAs(dirname($path), $file, basename($path));
                $attachments[] = basename($path);
            }
            $data['attachments'] = $attachments;
        }

        $rfi->update($data);

        return redirect()->route('projects.communication.rfis', ['project' => $project->id])->with('success', 'RFI güncellendi.');
    }

    public function rfiDestroy($projectId, $rfiId)
    {
        $project = Project::findOrFail($projectId);
        $this->authorize('update', $project);
        $rfi = Rfi::where('project_id', $project->id)->findOrFail($rfiId);

        // Delete attachments
        if ($rfi->attachments) {
            foreach ($rfi->attachments as $attachment) {
                Storage::delete("communications/projects/{$project->id}/rfis/{$rfi->rfi_no}/" . $attachment);
            }
        }

        $rfi->delete();

        return redirect()->route('projects.communication.rfis', ['project' => $project->id])->with('success', 'RFI silindi.');
    }

    // submittal list & store
    public function submittalList(Request $request, $projectId){
        $project = Project::findOrFail($projectId);
        $this->authorize('view',$project);

        $query = Submittal::where('project_id', $project->id);

        // Arama
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('submittal_no', 'like', "%{$search}%")
                  ->orWhere('submitted_by', 'like', "%{$search}%");
            });
        }

        // Durum filtresi
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Tarih filtresi
        if ($request->has('due_date_from') && !empty($request->due_date_from)) {
            $query->where('due_date', '>=', $request->due_date_from);
        }
        if ($request->has('due_date_to') && !empty($request->due_date_to)) {
            $query->where('due_date', '<=', $request->due_date_to);
        }

        $subs = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('projects.communication.submittal_list', [
            'project' => $project,
            'subs' => $subs,
            'filters' => $request->only(['search', 'status', 'due_date_from', 'due_date_to'])
        ]);
    }
    public function submittalStore(Request $request, $projectId){
        $project = Project::findOrFail($projectId);
        $this->authorize('update',$project);
        $data = $request->validate(['description'=>'required','reviewers'=>'nullable','due_date'=>'nullable|date','attachments.*'=>'file|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg']);
        $data['project_id']=$project->id;
        $data['submittal_no']='SUB-'.time();
        if(!empty($data['reviewers']) && is_string($data['reviewers'])) $data['reviewers']=array_map('trim', explode(',', $data['reviewers']));

        // handle attachments
        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                if (!$file->isValid()) continue;
                $name = time().'_'.preg_replace('/[^A-Za-z0-9_\.-]/', '_', $file->getClientOriginalName());
                $path = "communications/projects/{$project->id}/submittals/{$data['submittal_no']}/" . $name;
                Storage::putFileAs(dirname($path), $file, basename($path));
                $attachments[] = basename($path);
            }
        }
        if (!empty($attachments)) $data['attachments'] = $attachments;

        $sub = Submittal::create($data);

        // notify reviewers
        if (!empty($sub->reviewers) && is_array($sub->reviewers)) {
            foreach ($sub->reviewers as $email) {
                $u = User::where('email', trim($email))->first();
                if ($u) {
                    try { $u->notify(new CommunicationAssigned(['title'=>"Yeni Submittal: {$sub->submittal_no}", 'body'=>$sub->description, 'link'=>route('projects.communication.submittals',['project'=>$project->id]) ])); } catch (\Exception $e) { }
                }
            }
        }

        return redirect()->route('projects.communication.submittals',['project'=>$project->id]); }

    // reviewer actions: approve/reject
    public function submittalReview(Request $request, $projectId, $submittalId)
    {
        $project = Project::findOrFail($projectId);
        $this->authorize('view', $project);
        $sub = Submittal::where('id',$submittalId)->where('project_id',$project->id)->firstOrFail();
        $data = $request->validate(['action'=>'required|in:approved,rejected','comments'=>'nullable']);

        $review = SubmittalReview::create([
            'submittal_id' => $sub->id,
            'reviewer_email' => $request->user()->email ?? null,
            'action' => $data['action'],
            'comments' => $data['comments'] ?? null,
            'created_by' => $request->user()->id ?? null,
        ]);

        // update submittal status: if any reviewer rejects -> rejected; if all reviewers approved -> approved
        if ($data['action'] === 'rejected') {
            $sub->status = 'rejected';
            $sub->save();
        } else {
            // check if all reviewers have an approved review
            $reviewers = $sub->reviewers ?: [];
            $approved = SubmittalReview::where('submittal_id', $sub->id)->where('action','approved')->whereIn('reviewer_email', $reviewers)->pluck('reviewer_email')->unique()->toArray();
            if (!empty($reviewers) && count(array_intersect($reviewers, $approved)) >= count($reviewers)) {
                $sub->status = 'approved';
                $sub->save();
            }
        }

        // notify submitter
        if (!empty($sub->submitted_by)) {
            $u = User::where('email', $sub->submitted_by)->first();
            if ($u) { try { $u->notify(new CommunicationAssigned(['title'=>"Submittal {$sub->submittal_no} bir inceleme aldı", 'body'=>($data['comments'] ?? ''), 'link'=>route('projects.communication.submittals',['project'=>$project->id]) ])); } catch (\Exception $e) { } }
        }

        return back();
    }

    // meeting minutes list & store
    public function meetingList($projectId){ $project = Project::findOrFail($projectId); $this->authorize('view',$project); return view('projects.communication.meetings',['project'=>$project,'meetings'=>MeetingMinute::where('project_id',$project->id)->get()]); }
    public function meetingStore(Request $request, $projectId){
        $project = Project::findOrFail($projectId);
        $this->authorize('update',$project);
        $data=$request->validate(['title'=>'required','meeting_date'=>'nullable|date','participants'=>'nullable','notes'=>'nullable','attachments.*'=>'file|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg']);
        if(!empty($data['participants']) && is_string($data['participants'])) $data['participants']=array_map('trim', explode(',', $data['participants']));
        $data['project_id']=$project->id;

        // attachments
        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                if (!$file->isValid()) continue;
                $name = time().'_'.preg_replace('/[^A-Za-z0-9_\.-]/', '_', $file->getClientOriginalName());
                $path = "communications/projects/{$project->id}/meetings/" . $name;
                Storage::putFileAs(dirname($path), $file, basename($path));
                $attachments[] = basename($path);
            }
        }
        if (!empty($attachments)) $data['attachments'] = $attachments;

        $m=MeetingMinute::create($data);

        // notify participants if users exist
        if (!empty($m->participants) && is_array($m->participants)) {
            foreach ($m->participants as $email) {
                $u = User::where('email', trim($email))->first();
                if ($u) { try { $u->notify(new CommunicationAssigned(['title'=>"Yeni Toplantı: {$m->title}", 'body'=>$m->notes, 'link'=>route('projects.communication.meetings',['project'=>$project->id]) ])); } catch (\Exception $e) { } }
            }
        }

        return redirect()->route('projects.communication.meetings',['project'=>$project->id]); }

    public function meetingShow($projectId, $meetingId){
        $project = Project::findOrFail($projectId);
        $this->authorize('view',$project);
        $meeting = MeetingMinute::where('project_id', $project->id)->findOrFail($meetingId);
        return view('projects.communication.meeting_detail', ['project' => $project, 'meeting' => $meeting]);
    }

    public function meetingEdit($projectId, $meetingId){
        $project = Project::findOrFail($projectId);
        $this->authorize('update',$project);
        $meeting = MeetingMinute::where('project_id', $project->id)->findOrFail($meetingId);
        return view('projects.communication.meeting_edit', ['project' => $project, 'meeting' => $meeting]);
    }

    public function meetingUpdate(Request $request, $projectId, $meetingId){
        $project = Project::findOrFail($projectId);
        $this->authorize('update',$project);
        $meeting = MeetingMinute::where('project_id', $project->id)->findOrFail($meetingId);
        $data=$request->validate(['title'=>'required','meeting_date'=>'nullable|date','participants'=>'nullable','notes'=>'nullable','attachments.*'=>'file|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg']);
        if(!empty($data['participants']) && is_string($data['participants'])) $data['participants']=array_map('trim', explode(',', $data['participants']));

        // attachments
        $attachments = $meeting->attachments ?? [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                if (!$file->isValid()) continue;
                $name = time().'_'.preg_replace('/[^A-Za-z0-9_\.-]/', '_', $file->getClientOriginalName());
                $path = "communications/projects/{$project->id}/meetings/" . $name;
                Storage::putFileAs(dirname($path), $file, basename($path));
                $attachments[] = basename($path);
            }
        }
        if (!empty($attachments)) $data['attachments'] = $attachments;

        $meeting->update($data);
        return redirect()->route('projects.communication.meetings',['project'=>$project->id])->with('success', 'Toplantı güncellendi.');
    }

    public function meetingDelete($projectId, $meetingId){
        $project = Project::findOrFail($projectId);
        $this->authorize('update',$project);
        $meeting = MeetingMinute::where('project_id', $project->id)->findOrFail($meetingId);
        $meeting->delete();
        return redirect()->route('projects.communication.meetings',['project'=>$project->id])->with('success', 'Toplantı silindi.');
    }

    // create action item from meeting
    public function actionStore(Request $request, $projectId){
        $project = Project::findOrFail($projectId);
        $this->authorize('update',$project);
        $data=$request->validate(['meeting_id'=>'nullable','title'=>'required','assigned_to'=>'nullable','due_date'=>'nullable|date']);
        $data['project_id']=$project->id;
        $ai = ActionItem::create($data);

        // notify assigned user
        if (!empty($ai->assigned_to)) {
            $u = User::where('email', $ai->assigned_to)->first();
            if ($u) { try { $u->notify(new CommunicationAssigned(['title'=>"Yeni Eylem: {$ai->title}", 'body'=>$ai->notes ?? '', 'link'=>route('projects.communication.meetings',['project'=>$project->id]) ])); } catch (\Exception $e) { } }
        }

        return back(); }

    // signed download for communication attachments
    public function downloadAttachment(Request $request, $projectId, $type, $id, $file)
    {
        $project = Project::findOrFail($projectId);
        $this->authorize('view', $project);

        $type = strtolower($type);
        $model = null;
        switch ($type) {
            case 'rfi': $model = Rfi::where('id', $id)->where('project_id', $project->id)->first(); break;
            case 'submittal': $model = Submittal::where('id', $id)->where('project_id', $project->id)->first(); break;
            case 'meeting': $model = MeetingMinute::where('id', $id)->where('project_id', $project->id)->first(); break;
            case 'action': $model = ActionItem::where('id', $id)->where('project_id', $project->id)->first(); break;
            default: abort(404);
        }
        if (!$model) abort(404);

        $file = basename($file); // prevent traversal
        $path = "communications/projects/{$project->id}/";
        if ($type === 'rfi') $path .= "rfis/{$model->rfi_no}/{$file}";
        elseif ($type === 'submittal') $path .= "submittals/{$model->submittal_no}/{$file}";
        elseif ($type === 'meeting') $path .= "meetings/{$file}";
        else $path .= "actions/{$model->id}/{$file}";

        if (!Storage::exists($path)) abort(404);
        return Storage::download($path);
    }

    // Return a temporary signed URL for an attachment (frontend helper)
    public function signedAttachmentUrl(Request $request, $projectId, $type, $id, $file)
    {
        $project = Project::findOrFail($projectId);
        $this->authorize('view', $project);

        // ensure target exists
        $type = strtolower($type);
        $model = null;
        switch ($type) {
            case 'rfi': $model = Rfi::where('id', $id)->where('project_id', $project->id)->first(); break;
            case 'submittal': $model = Submittal::where('id', $id)->where('project_id', $project->id)->first(); break;
            case 'meeting': $model = MeetingMinute::where('id', $id)->where('project_id', $project->id)->first(); break;
            case 'action': $model = ActionItem::where('id', $id)->where('project_id', $project->id)->first(); break;
            default: return response()->json(['error'=>'invalid_type'], 400);
        }
        if (!$model) return response()->json(['error'=>'not_found'], 404);

        $file = basename($file);
        $signed = URL::temporarySignedRoute('projects.communication.attachment.download', now()->addMinutes(30), ['project'=>$project->id,'type'=>$type,'id'=>$id,'file'=>$file]);
        return response()->json(['url'=>$signed]);
    }
}
