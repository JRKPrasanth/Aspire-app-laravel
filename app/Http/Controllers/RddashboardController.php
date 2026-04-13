<?php

public function index()
{
    $success = RdEntry::where('output_status','Success')->count();
    $failed = RdEntry::where('output_status','Failed')->count();
    $improve = RdEntry::where('output_status','Requires Improvement')->count();

    return view('rd.dashboard', compact('success','failed','improve'));
}