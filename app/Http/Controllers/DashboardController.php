<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\Ticket;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    public function getTickets(Request $request)
    {
        $userId = $request->get('user_id');
        if (!$userId) {
            return response()->json(['status' => false, 'message' => 'User not found'], 404);
        }

        $cacheKey = 'user_tickets_' . $userId;

        $tickets = Cache::get($cacheKey);
        if (!$tickets) {
            $tickets = DB::table('tickets')->where('user_id', $userId)->get();
            Cache::put($cacheKey, $tickets);
        }

        return response()->json(['status' => true, 'tickets' => $tickets]);
    }

    public function ticketDetails(Request $request, $id)
    {
        $userId = $request->get('user_id');
        if (!$userId) {
            return response()->json(['status' => false, 'message' => 'User not found'], 404);
        }
        
        $cacheKey = 'ticket_details_' . $userId . '_' . $id;

        $ticket = Cache::get($cacheKey);
        if (!$ticket) {
            $ticket = DB::table('tickets')
                ->where('id', $id)
                ->where('user_id', $userId)
                ->first();

            if (!$ticket) {
                return response()->json(['status' => false, 'message' => 'Ticket not found or access denied'], 404);
            }
            
            Cache::put($cacheKey, $ticket);
        }

        return response()->json(['status' => true, 'ticket' => $ticket]);
    }

    public function createTicket(Request $request)
    {
        $userId = $request->get('user_id');
        if (!$userId) {
            return response()->json(['status' => false, 'message' => 'User not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'ticket' => 'required|min:5',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()], 400);
        }

        DB::table('tickets')->insert([
            'ticket' => $request->ticket,
            'description' => $request->description,
            'status' => $request->status,
            'user_id' => $userId,
        ]);

        $this->refreshTicket($userId);

        return response()->json(['status' => true, 'message' => 'Ticket created successfully'], 201);
    }

    public function updateTicket(Request $request, $id)
    {
        $userId = $request->get('user_id');
        if (!$userId) {
            return response()->json(['status' => false, 'message' => 'User not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'ticket' => 'required|min:5',
            'status' => 'required|in:Open,In Progress,Closed',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()], 400);
        }

        $ticket = DB::table('tickets')
            ->where('id', $id)
            ->where('user_id', $userId)
            ->first();  

        if (!$ticket) {
            return response()->json(['status' => false, 'message' => 'Ticket not found or access denied'], 404);
        }

        DB::table('tickets')
        ->where('id', $id)
        ->where('user_id', $userId)
        ->update([
            'ticket' => $request->ticket,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        $this->refreshTicket($userId);

        return response()->json(['status' => true, 'message' => 'Ticket updated successfully']);
    }

    public function deleteTicket(Request $request, $id)
    {
        $userId = $request->get('user_id');

        $ticket = DB::table('tickets')
        ->where('id', $id)
        ->where('user_id', $userId)
        ->first();

        if (!$ticket) {
            return response()->json(['status' => false, 'message' => 'Ticket not found or access denied'], 404);
        }

        DB::table('tickets')
        ->where('id', $id)
        ->where('user_id', $userId)
        ->delete();

        $this->refreshTicket($userId);
        
        return response()->json(['status' => true, 'message' => 'Ticket deleted successfully']);
    }

    //cache refresh
    private function refreshTicket($userId)
    {
        $cacheKey = 'user_tickets_' . $userId;
        Cache::forget($cacheKey);

        $tickets = DB::table('tickets')->where('user_id', $userId)->get();
        Cache::put($cacheKey, $tickets);
    }

    public function getProfile(Request $request)
    {
        $userId = $request->get('user_id');
        $cacheKey = 'user_profile_' . $userId;

        $user = Cache::get($cacheKey);

        if (!$user) {
            $user = DB::table('users')
                ->where('user_id', $userId)
                ->first();

        if (!$user) {
            return response()->json(['status' => false, 'message' => 'User not found'], 404);
        }
        
        Cache::put($cacheKey, $user);
        }

        return response()->json(['status' => true, 'user' => $user]);
    }

    public function changePassword(Request $request)
    {
        $userId = $request->get('user_id');
        $user = DB::table('users')
            ->where('user_id', $userId)
            ->first();

        if (!$user) {
            return response()->json(['status' => false, 'message' => 'User not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'current_password' => 'required|min:6',
            'new_password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()], 400);
        }

        if (!password_verify($request->current_password, $user->password)) {
            return response()->json(['status' => false, 'message' => 'Current password is incorrect'], 403);
        }

        DB::table('users')
            ->where('user_id', $userId)
            ->update(['password' => bcrypt($request->new_password)]);

        return response()->json(['status' => true, 'message' => 'Password changed successfully']);
    }

    public function logout(Request $request)
    {
        return response()->json(['status' => true, 'message' => 'Logged out successfully']);
    }
}
