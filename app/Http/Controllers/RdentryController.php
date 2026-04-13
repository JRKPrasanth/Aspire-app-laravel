<?php

namespace App\Http\Controllers;

use App\RdEntry;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class RdentryController extends Controller
{
    public function index()
    {
        return view('rd.index');
    }

    public function getData(Request $request)
    {
        $query = RdEntry::query();

        if ($request->status)
            $query->where('approval_status', $request->status);

        if ($request->stage)
            $query->where('current_stage', $request->stage);

        if ($request->project)
            $query->where('project_name', 'like', "%" . $request->project . "%");

        return datatables()->of($query)->make(true);
    }

    public function create()
    {
        return view('rd.create');
    }

    public function store(Request $request)
    {
        // 1️⃣ VALIDATION (RULES ONLY)
        $validated = $request->validate([
            'project_name'    => 'required|string|max:255',
            'batch_no'        => 'required|string|max:10',
            'product_name'    => 'required|string|max:255',
            'attachments.*'   => 'nullable|file|mimes:pdf,jpg,png,xlsx,docx|max:5120',
        ]);

        // 2️⃣ FILE UPLOAD
        $filesArr = [];

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $filesArr[] = $file->store('rd_files', 'public');
            }
        }

        // 3️⃣ PREPARE DATA FOR INSERT
        $data = [
            'project_name'    => $validated['project_name'],
            'batch_no'        => $validated['batch_no'],
            'product_name'    => $validated['product_name'],
            'attachments'     => json_encode($filesArr), // IMPORTANT
            'organization_id' => 1,
            'company_id'      => 1,
            'location_id'     => 1,
            'current_stage'   => 'R&D',
            'approval_status' => 'Pending',
            'created_at'      => 'date',
            'created_by'      => auth()->id(),
        ];
        // dd($data);
        // 4️⃣ INSERT
        RdEntry::create($data);

        return redirect()
            ->route('rd.index')
            ->with('success', 'R&D Entry Created Successfully');
    }

    public function edit($id)
    {
        $entry = RdEntry::findOrFail($id);

        return view('rd.edit', compact('entry'));
    }

    public function update(Request $request, $id)
    {
        $entry = RdEntry::findOrFail($id);


        $validated = $request->validate([
            'project_name'      => 'required',
            'test_parameters'   => 'required',
            'test_results'      => 'required',
            'test_observations' => 'required',
            'process_steps'     => 'required',
            'process_remarks'   => 'required',
            'output_summary'    => 'required',
            'output_status'     => 'required',
            'attachments.*'     => 'nullable|file|mimes:pdf,jpg,png,xlsx,docx'
        ]);

        $filesArr = $entry->attachments ?? [];

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $filesArr[] = $file->store('rd_files', 'public');
            }
        }

        $validated['attachments'] = $filesArr;

        $entry->update($validated);


        return response()->json([
            'status' => true,
            'message' => 'Updated successfully'
        ]);
    }

    public function approve(Request $request, $id)
    {
        $entry = RdEntry::findOrFail($id);

        $entry->approval_status = 'Approved';
        $entry->review_comments = $request->review_comments;
        $entry->save();

        return back()->with('success', 'Approved!');
    }

    public function reject(Request $request, $id)
    {
        $entry = RdEntry::findOrFail($id);

        $entry->approval_status = 'Rejected';
        $entry->review_comments = $request->review_comments;
        $entry->save();

        return back()->with('error', 'Rejected!');
    }

    /*  public function pdf($id)
    {
    $entry = RdEntry::findOrFail($id);

    return Pdf::loadView('rd.pdf', compact('entry'))
              ->stream('rd_'.$id.'.pdf');
    }
*/

    public function pdf($id)
    {
        $entry = RdEntry::findOrFail($id);

        if ($entry->approval_status !== 'Approved') {
            return back()->with('error', 'Approved entries only can be downloaded as PDF');
        }

        // Load the PDF view using DomPDF
        $pdf = Pdf::loadView('rd.pdf', compact('entry'));

        // Optional: Set paper size and orientation
        $pdf->setPaper('A4', 'portrait');

        // Download the PDF
        return $pdf->download('rd-entry-' . $entry->id . '.pdf');

        // Alternative: Stream (view in browser instead of download)
        // return $pdf->stream('rd-entry-'.$entry->id.'.pdf');
    }
}