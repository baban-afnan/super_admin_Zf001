<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Transaction;
use App\Models\BlockedIp;
use App\Models\Wallet;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Notifications\NewUserCreated;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $totalUsers = User::count();
        $activeUsers = User::where('status', 'active')->count();
        $inactiveUsers = User::where('status', 'inactive')->count();
        
        // Users with at least one transaction
        $usersWithTransactions = User::whereHas('transactions')->count();

        $agents = User::where('role', 'agent')->count();
        $businesses = User::where('role', 'business')->count();
        $personal = User::where('role', 'personal')->count();

        $query = User::query();

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone_no', 'like', "%{$search}%");
            });
        }

        // Role Filter
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Status Filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // State Filter
        if ($request->filled('state')) {
            $query->where('state', $request->state);
        }

        // LGA Filter
        if ($request->filled('lga')) {
            $query->where('lga', $request->lga);
        }

        $users = $query->latest()->paginate(10);
        $blockedIps = BlockedIp::with('blocker')->latest()->get();

        // Get unique states and LGAs for filter dropdowns
        $states = User::whereNotNull('state')->distinct()->pluck('state')->sort()->values();
        $lgas = User::whereNotNull('lga')
            ->when($request->filled('state'), function($q) use ($request) {
                return $q->where('state', $request->state);
            })
            ->distinct()
            ->pluck('lga')
            ->sort()
            ->values();

        return view('users.index', compact(
            'totalUsers', 'activeUsers', 'inactiveUsers', 'usersWithTransactions',
            'agents', 'businesses', 'personal', 'users', 'blockedIps', 'states', 'lgas'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_no' => 'required|string|max:20',
            'bvn' => 'required|string|max:11',
        ]);

        $password = Str::random(10);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->surname, // Mapping surname to last_name
            'email' => $request->email,
            'phone_no' => $request->phone_no,
            'bvn' => $request->bvn,
            'password' => Hash::make($password),
            'role' => 'personal', // Default role
            'status' => 'active',
        ]);

        // Create Wallet
        Wallet::create([
            'user_id' => $user->id,
            'balance' => 0,
            'hold_amount' => 0,
            'available_balance' => 0,
            'wallet_number' => mt_rand(1000000000, 9999999999),
            'currency' => 'NGN',
            'status' => 'active',
            'last_activity' => now(),
        ]);

        // Send notification with password
        $user->notify(new NewUserCreated($password));

        // Send verification link
        $user->sendEmailVerificationNotification();

        return back()->with('success', 'User created successfully. Password sent to email.');
    }

    public function show(User $user)
    {
        $user->load('wallet', 'virtualAccount');
        
        $query = $user->transactions()->latest();

        if ($reference = request('reference')) {
            $query->where('reference', 'like', "%{$reference}%");
        }

        if ($type = request('type')) {
            $query->where('type', $type);
        }

        // Calculate total amount for filtered results
        $totalAmount = (clone $query)->sum('amount');

        $transactions = $query->paginate(10)->withQueryString();

        return view('users.show', compact('user', 'transactions', 'totalAmount'));
    }

    public function updateStatus(Request $request, User $user)
    {
        $request->validate(['status' => 'required|in:active,inactive,suspended,pending,query']);
        $user->update(['status' => $request->status]);
        return back()->with('success', 'User status updated successfully.');
    }

    public function updateWalletStatus(Request $request, User $user)
    {
        $request->validate(['status' => 'required|in:active,inactive,suspended,closed']);
        
        if (!$user->wallet) {
            return back()->with('error', 'User does not have a wallet.');
        }

        $user->wallet->update(['status' => $request->status]);
        
        return back()->with('success', 'Wallet status updated successfully.');
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate(['role' => 'required|in:personal,agent,partner,business,staff,checker,super_admin,api']);
        $user->update(['role' => $request->role]);
        return back()->with('success', 'User role updated successfully.');
    }

    public function updateLimit(Request $request, User $user)
    {
        $request->validate(['limit' => 'required|numeric|min:0']);
        $user->update(['limit' => $request->limit]);
        return back()->with('success', 'Transaction limit updated successfully.');
    }

    public function verifyEmail(User $user)
    {
        $user->update(['email_verified_at' => now()]);
        return back()->with('success', 'User email marked as verified.');
    }

    public function blockIp(Request $request)
    {
        $request->validate([
            'ip_address' => 'required|ip|unique:blocked_ips,ip_address',
            'reason' => 'nullable|string|max:255',
        ]);

        BlockedIp::create([
            'ip_address' => $request->ip_address,
            'reason' => $request->reason,
            'blocked_by' => auth()->id(),
        ]);

        return back()->with('success', 'IP address blocked successfully.');
    }

    public function unblockIp(BlockedIp $blockedIp)
    {
        $blockedIp->delete();
        return back()->with('success', 'IP address unblocked successfully.');
    }

    public function downloadSample()
    {
        $csvHeader = ['First Name', 'Surname', 'Email', 'Phone Number', 'BVN'];
        $callback = function() use ($csvHeader) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $csvHeader);
            fclose($file);
        };

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=user_import_sample.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        return response()->stream($callback, 200, $headers);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx',
        ]);

        $file = $request->file('file');
        
        // Simple CSV parsing for now to avoid dependency issues if Maatwebsite is not fully configured
        // If it's an actual Excel file, we might need PhpSpreadsheet, but let's assume CSV for simplicity or try to parse
        
        $path = $file->getRealPath();
        $data = array_map('str_getcsv', file($path));
        $header = array_shift($data); // Remove header

        $count = 0;
        foreach ($data as $row) {
            if (count($row) < 5) continue;

            // Basic mapping assuming order: First Name, Surname, Email, Phone, BVN
            $firstName = $row[0];
            $surname = $row[1];
            $email = $row[2];
            $phone = $row[3];
            $bvn = $row[4];

            if (User::where('email', $email)->exists()) continue;

            $password = Str::random(10);
            
            $user = User::create([
                'first_name' => $firstName,
                'last_name' => $surname,
                'email' => $email,
                'phone_no' => $phone,
                'bvn' => $bvn,
                'password' => Hash::make($password),
                'role' => 'personal',
                'status' => 'active',
            ]);

            // Create Wallet
            Wallet::create([
                'user_id' => $user->id,
                'balance' => 0,
                'hold_amount' => 0,
                'available_balance' => 0,
                'wallet_number' => mt_rand(1000000000, 9999999999),
                'currency' => 'NGN',
                'status' => 'active',
                'last_activity' => now(),
            ]);

            try {
                $user->notify(new NewUserCreated($password));
            } catch (\Exception $e) {
                // Log error or continue
            }

            $count++;
        }

        return back()->with('success', "$count users imported successfully.");
    }
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone_no' => 'required|string|max:20|unique:users,phone_no,' . $user->id,
            'bvn' => 'nullable|string|max:11',
            'limit' => 'nullable|numeric',
        ], [
            'phone_no.unique' => 'Phone number already exist on another user'
        ]);

        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_no' => $request->phone_no,
            'bvn' => $request->bvn,
            'limit' => $request->limit,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User details updated successfully.');
    }

    public function destroy(User $user)
    {
        try {
            // Attempt to delete the user
            $user->delete();
            return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Check if it's a foreign key constraint violation
            if ($e->getCode() == 23000 || strpos($e->getMessage(), 'Integrity constraint violation') !== false) {
                return back()->with('error', 'This user cannot be deleted because they have related transactions or verifications in the system.');
            }
            
            // For other database errors
            return back()->with('error', 'An error occurred while deleting the user. Please try again.');
        } catch (\Exception $e) {
            // Catch any other exceptions
            return back()->with('error', 'An unexpected error occurred. Please contact support.');
        }
    }
}
