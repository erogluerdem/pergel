<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use App\Models\Vendor;
use Illuminate\Http\Request;

class PurchasingController extends Controller
{
    public function quotations()
    {
        $quotations = Quotation::with(['vendor', 'items'])->orderBy('created_at', 'desc')->get();

        $totalQuotations = $quotations->count();
        $approvedCount = $quotations->where('status', 'approved')->count();
        $pendingCount = $quotations->where('status', 'pending')->count();
        $rejectedCount = $quotations->where('status', 'rejected')->count();
        $totalQuotationValue = $quotations->sum('total_amount');

        return view('purchasing.quotations.index', compact(
            'quotations',
            'totalQuotations',
            'approvedCount',
            'pendingCount',
            'rejectedCount',
            'totalQuotationValue'
        ));
    }

    public function createQuotation()
    {
        $vendors = Vendor::all();
        return view('purchasing.quotations.create', compact('vendors'));
    }

    public function showQuotation($id)
    {
        $quotation = Quotation::with(['vendor', 'items'])->find($id);
        if (!$quotation) {
            $quotation = Quotation::with(['vendor', 'items'])->first();
        }
        return view('purchasing.quotations.show', compact('quotation', 'id'));
    }

    public function editQuotation($id)
    {
        $quotation = Quotation::with(['vendor', 'items'])->find($id);
        if (!$quotation) {
            $quotation = Quotation::with(['vendor', 'items'])->first();
        }
        $vendors = Vendor::all();
        return view('purchasing.quotations.edit', compact('quotation', 'id', 'vendors'));
    }

    public function approveQuotation($id)
    {
        $quotation = Quotation::findOrFail($id);
        $quotation->update(['status' => 'approved']);

        return response()->json([
            'success' => true,
            'message' => 'Tedarikçi teklifi onaylandı.'
        ]);
    }

    public function rejectQuotation($id)
    {
        $quotation = Quotation::findOrFail($id);
        $quotation->update(['status' => 'rejected']);

        return response()->json([
            'success' => true,
            'message' => 'Tedarikçi teklifi reddedildi.'
        ]);
    }

    public function pendingApprovals()
    {
        $approvals = \App\Models\Approval::with(['requisition.project', 'approver'])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalApprovals = $approvals->count();
        $pendingCount = $approvals->where('status', 'pending')->count();
        $approvedCount = $approvals->where('status', 'approved')->count();
        $rejectedCount = $approvals->where('status', 'rejected')->count();

        return view('purchasing.approvals.pending', compact(
            'approvals',
            'totalApprovals',
            'pendingCount',
            'approvedCount',
            'rejectedCount'
        ));
    }

    public function createApprovalForm()
    {
        $requisitions = \App\Models\Requisition::all();
        $users = \App\Models\User::all();
        return view('purchasing.approvals.create', compact('requisitions', 'users'));
    }

    public function showApproval($id)
    {
        $approval = \App\Models\Approval::with(['requisition.project', 'approver'])->find($id);
        if (!$approval) {
            $approval = \App\Models\Approval::with(['requisition.project', 'approver'])->first();
        }
        return view('purchasing.approvals.show', compact('approval', 'id'));
    }

    public function editApproval($id)
    {
        $approval = \App\Models\Approval::with(['requisition.project', 'approver'])->find($id);
        if (!$approval) {
            $approval = \App\Models\Approval::with(['requisition.project', 'approver'])->first();
        }
        $users = \App\Models\User::all();
        return view('purchasing.approvals.edit', compact('approval', 'id', 'users'));
    }

    public function processApproval(Request $request, $id)
    {
        $approval = \App\Models\Approval::findOrFail($id);
        $status = $request->input('status', 'approved');
        
        if ($status === 'approved') {
            $approval->approve($request->input('comments'));
        } else {
            $approval->reject($request->input('rejection_reason', 'Gerekçe belirtilmedi.'));
        }

        return response()->json([
            'success' => true,
            'message' => 'Onay durumu güncellendi.'
        ]);
    }
}
