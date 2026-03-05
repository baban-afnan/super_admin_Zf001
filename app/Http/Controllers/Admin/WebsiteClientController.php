<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsiteClient;
use Illuminate\Http\Request;
use App\Mail\WebsiteRenewalMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class WebsiteClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = WebsiteClient::latest()->paginate(20);
        $active_count = WebsiteClient::where('status', 'active')->count();
        $expired_count = WebsiteClient::where('status', 'expired')->count();
        $expiring_count = WebsiteClient::where('status', 'active')
            ->whereDate('renew_date', '<=', now()->addDays(30))
            ->whereDate('renew_date', '>', now())
            ->count();
        $total_count = WebsiteClient::count();

        return view('admin.website-clients.index', compact('clients', 'active_count', 'expired_count', 'expiring_count', 'total_count'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:20',
            'website_name' => 'required|string|max:255',
            'website_link' => 'nullable|url|max:255',
            'issue_date' => 'required|date',
            'renew_date' => 'nullable|date',
            'status' => 'required|in:active,expired,pending',
            'notes' => 'nullable|string',
        ]);

        WebsiteClient::create($validated);

        return back()->with('status', 'Website client added successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $client = WebsiteClient::findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:20',
            'website_name' => 'required|string|max:255',
            'website_link' => 'nullable|url|max:255',
            'issue_date' => 'required|date',
            'renew_date' => 'required|date',
            'status' => 'required|in:active,expired,pending',
            'notes' => 'nullable|string',
        ]);

        $client->update($validated);

        return back()->with('status', 'Website client updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $client = WebsiteClient::findOrFail($id);
        $client->delete();

        return back()->with('status', 'Website client deleted successfully.');
    }

    /**
     * Notify a single client.
     */
    public function notify($id)
    {
        $client = WebsiteClient::findOrFail($id);
        $daysRemaining = now()->startOfDay()->diffInDays($client->renew_date->startOfDay(), false);

        Mail::to($client->email)->send(new WebsiteRenewalMail($client, $daysRemaining));

        return back()->with('status', "Notification sent to {$client->email}");
    }

    /**
     * Notify all active clients.
     */
    public function notifyAll()
    {
        $clients = WebsiteClient::where('status', 'active')->get();
        $count = 0;

        foreach ($clients as $client) {
            $daysRemaining = now()->startOfDay()->diffInDays($client->renew_date->startOfDay(), false);
            Mail::to($client->email)->send(new WebsiteRenewalMail($client, $daysRemaining));
            $count++;
        }

        return back()->with('status', "Notifications sent to {$count} clients.");
    }
}
