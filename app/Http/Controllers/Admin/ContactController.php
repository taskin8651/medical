<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    // 🟢 Admin list
    public function index()
    {
        $contacts = Contact::latest()->get();
        return view('admin.contact.index', compact('contacts'));
    }

    // 🟢 Store from frontend
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required',
        ]);

        Contact::create($request->all());

        return back()->with('success','Message Sent Successfully');
    }

    // 🟢 View single message
    public function show($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->update(['is_read' => 1]);

        return view('admin.contact.show', compact('contact'));
    }

    // 🟢 Delete
    public function destroy($id)
    {
        Contact::findOrFail($id)->delete();
        return back()->with('success','Deleted');
    }
}