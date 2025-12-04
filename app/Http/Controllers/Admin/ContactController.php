<?php



namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display all contact messages
     */
    public function index(Request $request)
    {
        $query = Contact::query();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by subject
        if ($request->filled('subject')) {
            $query->where('subject', $request->subject);
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('message', 'like', '%' . $request->search . '%');
            });
        }

        $contacts = $query->latest()->paginate(15);

        // Statistics
        $stats = [
            'total' => Contact::count(),
            'unread' => Contact::unread()->count(),
            'read' => Contact::read()->count(),
            'replied' => Contact::replied()->count(),
        ];

        return view('admin.contacts.index', compact('contacts', 'stats'));
    }

    /**
     * Show single contact message
     */
    public function show($id)
    {
        $contact = Contact::findOrFail($id);
        
        // Mark as read if unread
        if ($contact->status === 'unread') {
            $contact->markAsRead();
        }

        return view('admin.contacts.show', compact('contact'));
    }

    /**
     * Mark as replied
     */
    public function markReplied($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->markAsReplied();

        return back()->with('success', 'Contact marked as replied!');
    }

    /**
     * Update admin notes
     */
    public function updateNotes(Request $request, $id)
    {
        $contact = Contact::findOrFail($id);
        
        $validated = $request->validate([
            'admin_notes' => 'nullable|string',
        ]);

        $contact->update($validated);

        return back()->with('success', 'Notes updated successfully!');
    }

    /**
     * Delete contact message
     */
    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();

        return redirect()
            ->route('admin.contacts.index')
            ->with('success', 'Contact message deleted successfully!');
    }
}